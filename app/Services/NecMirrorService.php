<?php

namespace App\Services;

use App\Models\CmsPage;
use App\Models\Download;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\GalleryAlbum;
use App\Models\News;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NecMirrorService
{
    public const BASE = 'https://nec.gov.ss';
    public const API = 'https://nec.gov.ss/wp-json/wp/v2';
    public const UA = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120 Safari/537.36';
    public const MANIFEST = 'nec-mirror/manifest.json';

    protected string $mediaDir = 'nec-mirror/media';
    protected string $downloadsDir = 'nec-mirror/downloads';
    protected string $orphansDir = 'nec-mirror/orphans';

    protected array $manifest = [];

    public function __construct()
    {
        $this->loadManifest();
    }

    /* ─────────────────────────── Manifest ─────────────────────────── */

    protected function loadManifest(): void
    {
        $this->manifest = Storage::disk('public')->exists(self::MANIFEST)
            ? (json_decode(Storage::disk('public')->get(self::MANIFEST), true) ?: [])
            : [];
    }

    protected function saveManifest(): void
    {
        Storage::disk('public')->put(self::MANIFEST, json_encode($this->manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function manifest(): array
    {
        return $this->manifest;
    }

    protected function manifestEntry(string $sourceUrl): ?array
    {
        foreach ($this->manifest as $entry) {
            if (($entry['source_url'] ?? '') === $sourceUrl) {
                return $entry;
            }
        }
        return null;
    }

    protected function rememberEntry(array $entry): void
    {
        $this->manifest[] = $entry;
        $this->saveManifest();
    }

    /* ─────────────────────────── HTTP helpers ─────────────────────────── */

    protected function fetchJson(string $url): array
    {
        $response = Http::withHeaders(['User-Agent' => self::UA])
            ->timeout(90)
            ->get($url);

        if ($response->failed()) {
            throw new \RuntimeException("WP request failed ({$response->status()}): {$url}");
        }

        return $response->json() ?: [];
    }

    protected function firstCategoryName(array $categories): string
    {
        foreach ($categories as $cat) {
            if (!empty($cat['name'])) {
                return $cat['name'];
            }
        }
        return 'News';
    }

    protected function categoryFromSlug(string $name): string
    {
        $map = [
            'latest-news' => 'latest',
            'field-office-news' => 'field-news',
            'capacity-building' => 'capacity-building',
            'events' => 'events',
            'operation' => 'operations',
            'voter-registration' => 'voter-registration',
            'press-release' => 'press-release',
            'news' => 'news',
            'uncategorized' => 'news',
        ];
        return $map[Str::slug($name)] ?? Str::slug($name) ?: 'news';
    }

    /* ─────────────────────────── Pagination ─────────────────────────── */

    protected function fetchAll(string $endpoint, bool $embed = false): array
    {
        $items = [];
        $page = 1;
        do {
            $sep = str_contains($endpoint, '?') ? '&' : '?';
            $embedQuery = $embed ? '&_embed' : '';
            $url = self::API . $endpoint . $sep . 'per_page=100&page=' . $page . $embedQuery;
            $batch = $this->fetchJson($url);
            if (empty($batch)) {
                break;
            }
            array_push($items, ...$batch);
            if (count($batch) < 100) {
                break;
            }
            $page++;
        } while ($page <= 50);

        return $items;
    }

    /* ─────────────────────────── Downloading ─────────────────────────── */

    protected function curlDownload(string $url, string $dest, int $timeout = 180): bool
    {
        $abs = Storage::disk('public')->path($dest);
        Storage::disk('public')->makeDirectory(dirname($dest));

        if (is_file($abs) && filesize($abs) > 0) {
            return true;
        }

        $cmd = [
            'curl', '-fsSL', '--max-time', (string) $timeout,
            '-A', self::UA,
            '-e', self::BASE . '/',
            '-o', $abs,
            $url,
        ];

        $proc = proc_open($cmd, [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ], $pipes);

        if (!is_resource($proc)) {
            return false;
        }

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $code = proc_close($proc);

        if ($code !== 0) {
            @unlink($abs);
            return false;
        }

        return is_file($abs) && filesize($abs) > 0;
    }

    /**
     * Download many files with curl in parallel (batches of 8).
     */
    public function downloadMany(array $items, int $parallel = 8): array
    {
        $failed = [];
        $chunks = array_chunk($items, $parallel, true);
        foreach ($chunks as $chunk) {
            $jobs = [];
            foreach ($chunk as $key => $item) {
                $url = $item['url'];
                $dest = $item['path'];
                $abs = Storage::disk('public')->path($dest);
                Storage::disk('public')->makeDirectory(dirname($dest));
                if (is_file($abs) && filesize($abs) > 0) {
                    continue;
                }
                $jobs[] = [
                    'url' => escapeshellarg($url),
                    'out' => escapeshellarg($abs),
                ];
            }

            if (empty($jobs)) {
                continue;
            }

            $tmp = tempnam(sys_get_temp_dir(), 'necmirror') . '.sh';
            file_put_contents($tmp, "#!/bin/sh\n");
            foreach (array_chunk($jobs, $parallel) as $batch) {
                $lines = array_map(function ($j) {
                    return 'curl -fsSL --max-time 300 -A ' . escapeshellarg(self::UA) . " -o {$j['out']} {$j['url']} >/dev/null 2>&1 || true";
                }, $batch);
                file_put_contents($tmp, implode("\n", $lines) . "\nwait\n", FILE_APPEND);
            }

            $proc = proc_open('sh ' . escapeshellarg($tmp), [
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ], $pipes);

            if (is_resource($proc)) {
                stream_get_contents($pipes[1]);
                stream_get_contents($pipes[2]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($proc);
            }
            @unlink($tmp);
        }

        foreach ($items as $key => $item) {
            $path = $item['path'];
            if (!Storage::disk('public')->exists($path) || Storage::disk('public')->size($path) <= 0) {
                $failed[$key] = $item['url'];
            }
        }

        return $failed;
    }

    /* ─────────────────────────── HTML rewriting ─────────────────────────── */

    /**
     * Rewrite nec.gov.ss asset URLs in content to locally mirrored files.
     */
    public function rewriteHtml(string $html): string
    {
        if (empty($html)) {
            return $html;
        }

        $self = $this;

        $html = preg_replace_callback('#(https?:)?//nec\.gov\.ss/(?:wp-content/uploads/[^"\'\s)]+|wp-content/[^"\'\s)]+\.(?:pdf|docx?|xlsx?|pptx?)[^"\'\s)]*|download/[a-z0-9\-]+/?)#i', function ($m) use ($self) {
            $url = $m[0];
            if (!preg_match('#^https?://#i', $url)) {
                $url = 'https:' . $url;
            }
            $entry = $self->manifestEntry($url);
            if ($entry && !empty($entry['path'])) {
                return '/storage/' . $entry['path'];
            }
            return $url;
        }, $html);

        return $html;
    }

    public function localizeMediaUrl(string $url): ?string
    {
        $entry = $this->manifestEntry($url);
        return $entry ? '/storage/' . $entry['path'] : null;
    }

    /* ─────────────────────────── Pages ─────────────────────────── */

    public function mirrorPages(bool $force = false): int
    {
        $count = 0;
        $pages = $this->fetchAll('/pages');
        foreach ($pages as $page) {
            $slug = $page['slug'] ?? null;
            if (!$slug) {
                continue;
            }

            $data = [
                'title' => $this->cleanTitle($page['title']['rendered'] ?? ''),
                'slug' => Str::slug($slug),
                'content' => $this->rewriteHtml($this->cleanHtml($page['content']['rendered'] ?? '')),
                'meta_description' => Str::limit(strip_tags($page['excerpt']['rendered'] ?? ''), 490),
                'status' => ($page['status'] ?? 'publish') === 'publish' ? 'published' : 'draft',
            ];

            $existing = CmsPage::where('slug', $data['slug'])->first();
            if ($existing && !$force) {
                $this->info("  [skip] page {$data['slug']}");
                $count++;
                continue;
            }

            if ($existing) {
                $existing->update($data);
            } else {
                CmsPage::create($data);
            }
            $this->info("  [ok] page {$data['slug']}");
            $count++;
        }
        return $count;
    }

    /* ─────────────────────────── Posts / News ─────────────────────────── */

    public function mirrorPosts(bool $force = false): array
    {
        $newsCount = 0;
        $eventIds = [];

        $posts = $this->fetchAll('/posts', true);
        foreach ($posts as $post) {
            $slug = $post['slug'] ?? null;
            if (!$slug) {
                continue;
            }

            $categories = $post['_embedded']['wp:term'][0] ?? [];
            $terms = collect($categories)->pluck('name')->filter()->implode(', ');
            $category = $this->firstCategoryName($categories);
            $isEvent = Str::contains(strtolower($category), 'event');

            $featured = null;
            if (!empty($post['featured_media'])) {
                $featured = $this->mediaUrlFromManifest((int) $post['featured_media']);
            }

            $content = $this->rewriteHtml($this->cleanHtml($post['content']['rendered'] ?? ''));
            $excerpt = strip_tags($post['excerpt']['rendered'] ?? '');
            $author = $this->firstTermName($post['_embedded']['author'] ?? []);
            $publishedAt = !empty($post['date_gmt']) ? $post['date_gmt'] : now();

            $newsData = [
                'title' => $this->cleanTitle($post['title']['rendered'] ?? ''),
                'slug' => Str::slug($slug),
                'content' => $content,
                'excerpt' => Str::limit($excerpt, 490),
                'category' => $this->categoryFromSlug($category),
                'author' => $author,
                'image' => $featured,
                'featured_image' => $featured,
                'tags' => $terms ?: null,
                'meta_description' => Str::limit($excerpt, 490),
                'status' => ($post['status'] ?? 'publish') === 'publish' ? 'published' : 'draft',
                'published_at' => $publishedAt,
            ];

            $existing = News::where('slug', $newsData['slug'])->first();
            if ($existing && !$force) {
                $this->info("  [skip] post {$newsData['slug']}");
                continue;
            }
            if ($existing) {
                $existing->update($newsData);
            } else {
                News::create($newsData);
            }
            $newsCount++;

            if ($isEvent && !empty($post['date_gmt'])) {
                $eventIds[] = $this->storeEvent($post, $featured, $force);
            }
        }

        return ['news' => $newsCount, 'events' => count($eventIds)];
    }

    protected function storeEvent(array $post, ?string $featured, bool $force): ?int
    {
        $slug = Str::slug($post['slug'] ?? '');
        if (!$slug) {
            return null;
        }
        $start = !empty($post['date_gmt']) ? $post['date_gmt'] : now();
        $data = [
            'title' => $this->cleanTitle($post['title']['rendered'] ?? ''),
            'slug' => $slug,
            'description' => $this->rewriteHtml($this->cleanHtml($post['content']['rendered'] ?? '')),
            'start_date' => $start,
            'organizer' => 'NEC South Sudan',
            'event_type' => 'public',
            'featured_image' => $featured,
            'meta_description' => Str::limit(strip_tags($post['excerpt']['rendered'] ?? ''), 490),
            'status' => 'published',
        ];

        $existing = Event::where('slug', $slug)->first();
        if ($existing && !$force) {
            return $existing->id;
        }
        if ($existing) {
            $existing->update($data);
            return $existing->id;
        }
        return Event::create($data)->id;
    }

    /* ─────────────────────────── Media / Gallery ─────────────────────────── */

    public function mirrorMedia(bool $force = false): array
    {
        $downloaded = 0;
        $retrieved = 0;

        $album = GalleryAlbum::firstOrCreate(
            ['slug' => 'nec-gallery'],
            ['title' => 'NEC South Sudan Gallery', 'description' => 'Photo library mirrored from nec.gov.ss', 'status' => 'published']
        );

        $mediaItems = $this->fetchAll('/media', true);
        $jobs = [];
        $entries = [];

        foreach ($mediaItems as $m) {
            $wpId = (int) ($m['id'] ?? 0);
            $sourceUrl = $m['source_url'] ?? null;
            if (!$sourceUrl) {
                continue;
            }

            $ext = strtolower(pathinfo(parse_url($sourceUrl, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION));
            $ext = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'avif']) ? $ext : 'jpg';
            $path = $this->mediaDir . '/' . $wpId . '.' . $ext;

            $existing = Gallery::where('image_path', $path)->first();
            if ($existing && !$force) {
                $this->ensureManifest($sourceUrl, $path);
                $retrieved++;
                continue;
            }

            $jobs[] = ['url' => $sourceUrl, 'path' => $path];
            $entries[$sourceUrl] = $path;
            $retrieved++;
        }

        $failed = !empty($jobs) ? $this->downloadMany($jobs) : [];

        $alt = '';
        foreach ($mediaItems as $m) {
            $sourceUrl = $m['source_url'] ?? null;
            if (!$sourceUrl) {
                continue;
            }
            foreach (($m['_embedded']['wp:featuredmedia'] ?? []) as $f) {
                $alt = $f['alt_text'] ?? '';
            }
            $path = $entries[$sourceUrl] ?? null;
            if (!$path) {
                continue;
            }
            if (in_array($sourceUrl, $failed, true)) {
                $this->warn("  [fail] media " . ($m['id'] ?? '?'));
                continue;
            }
            if (!Storage::disk('public')->exists($path)) {
                continue;
            }

            $this->upsertMediaRecord($m, $path, $album->id, $alt, $force);
            $this->ensureManifest($sourceUrl, $path);
            $downloaded++;
        }

        return ['retrieved' => $retrieved, 'saved' => $downloaded, 'failed' => count($failed)];
    }

    protected function upsertMediaRecord(array $m, string $path, int $albumId, string $alt, bool $force): void
    {
        $wpId = (int) ($m['id'] ?? 0);
        $title = $this->cleanTitle($m['title']['rendered'] ?? '');
        $description = Str::limit(strip_tags($m['description']['rendered'] ?? $m['caption']['rendered'] ?? ''), 490);

        $existing = Gallery::where('image_path', $path)->first();
        $data = [
            'title' => $title ?: 'Photo ' . $wpId,
            'description' => $description,
            'image_path' => $path,
            'gallery_album_id' => $albumId,
            'alt_text' => $alt ?: $title,
            'status' => 'published',
            'published_at' => !empty($m['date_gmt']) ? $m['date_gmt'] : now(),
        ];

        if ($existing) {
            $existing->update($data);
        } else {
            Gallery::create($data);
        }
    }

    protected function ensureManifest(string $sourceUrl, string $path): void
    {
        if ($this->manifestEntry($sourceUrl)) {
            return;
        }
        $this->rememberEntry([
            'source_url' => $sourceUrl,
            'path' => $path,
            'type' => 'mirror',
        ]);
    }

    protected function mediaUrlFromManifest(int $wpId): ?string
    {
        foreach ($this->manifest as $entry) {
            if (preg_match('#/(\d+)\.(?:jpg|jpeg|png|gif|webp|svg|avif)$#i', $entry['path'] ?? '', $mm) && (int) $mm[1] === $wpId) {
                return '/storage/' . $entry['path'];
            }
        }
        return null;
    }

    /* ─────────────────────────── Downloads (WPDM) ─────────────────────────── */

    public function mirrorDownloads(bool $force = false): array
    {
        $mirrored = 0;
        $saved = 0;
        $jobs = [];
        $seen = [];

        $sources = collect($this->fetchAll('/pages'))
            ->pluck('content.rendered')
            ->push(...CmsPage::pluck('content'))
            ->implode("\n");

        $links = preg_match_all('#(https?://(?:www\.)?nec\.gov\.ss/(?:download/[a-z0-9\-]+/?|[^"\']*?wpdmdl=\d+))#i', $sources, $matches);

        if ($links) {
            foreach ($matches[1] as $rawHref) {
                $href = str_replace(['&amp;', '&#038;'], '&', $rawHref);

                $slug = null;
                $wpdmdl = null;

                if (preg_match('#/download/([a-z0-9\-]+)/?$#i', $href, $sm)) {
                    $slug = $sm[1];
                }
                if (preg_match('#wpdmdl=(\d+)#i', $href, $wm)) {
                    $wpdmdl = $wm[1];
                }

                if (!$slug && !$wpdmdl) {
                    continue;
                }
                if ($slug && isset($seen[$slug])) {
                    continue;
                }
                if ($slug) {
                    $seen[$slug] = true;
                }

                $item = $slug
                    ? $this->resolveWpdmPackage($slug)
                    : $this->packageFromWpdmdl((int) $wpdmdl);

                if (empty($item) || !empty($item['error'])) {
                    $this->warn("  [fail] package " . ($slug ?: "?wpdmdl=$wpdmdl"));
                    continue;
                }

                $fres = $this->upsertDownloadItem($item, $force, count($jobs));
                if (is_array($fres)) {
                    $jobs[] = $fres['job'];
                } else {
                    $mirrored++;
                    $saved += ($fres === 1 ? 1 : 0);
                }
            }
        }

        // Complement with the full WPDM package search index.
        foreach ($this->fetchWpdmSearch() as $package) {
            $slug = Str::slug($package['slug'] ?? '');
            if (!$slug || isset($seen[$slug])) {
                continue;
            }
            $seen[$slug] = true;
            $item = $this->resolveWpdmPackage($slug);
            if (empty($item) || !empty($item['error'])) {
                continue;
            }
            $fres = $this->upsertDownloadItem($item, $force, count($jobs));
            if (is_array($fres)) {
                $jobs[] = $fres['job'];
            } else {
                $mirrored++;
                $saved += ($fres === 1 ? 1 : 0);
            }
        }

        if (!empty($jobs)) {
            $failed = $this->downloadMany($jobs);
            foreach ($jobs as $job) {
                if (in_array($job['url'], $failed, true)) {
                    $this->warn("  [fail] pdf {$job['url']}");
                    continue;
                }
                if (!Storage::disk('public')->exists($job['path'])) {
                    continue;
                }
                $this->markDownloadReady($job);
                $saved++;
            }
            $mirrored += count($jobs);
        }

        return ['packages' => $mirrored, 'saved' => $saved];
    }

    /**
     * Resolve a /download/{slug}/ page to a package (title + wpdmdl url).
     */
    protected function resolveWpdmPackage(string $slug): array
    {
        $cached = cache()->get('nec_wpdm_' . $slug);
        if ($cached) {
            return $cached;
        }

        $url = self::BASE . '/download/' . $slug . '/';
        try {
            $html = Http::withHeaders(['User-Agent' => self::UA])->timeout(60)->get($url)->body();
        } catch (\Throwable $e) {
            return ['error' => true];
        }

        if (preg_match('#wpdmdl=(\d+)#i', $html, $mm)) {
            $title = '';
            if (preg_match('#<h[12][^>]*>(.*?)</h[12]>#is', $html, $tm) && !empty($tm[1])) {
                $title = $this->cleanTitle($tm[1]);
            }
            if (!$title) {
                $title = $this->cleanTitle(strip_tags($html));
            }
            if (mb_strlen($title) > 180) {
                $title = Str::headline($slug);
            }
            $item = [
                'slug' => $slug,
                'title' => $title ?: Str::headline($slug),
                'url' => self::BASE . '/?wpdmdl=' . $mm[1],
                'file_type' => 'pdf',
            ];
            cache()->put('nec_wpdm_' . $slug, $item, now()->addDay());
            return $item;
        }

        return ['error' => true];
    }

    /**
     * Create the download DB record (before the file exists) and return a curl job.
     */
    protected function upsertDownloadItem(array $item, bool $force, int $index): array|int
    {
        $ext = 'pdf';
        $path = $this->downloadsDir . '/' . $item['slug'] . '.' . $ext;

        $existing = Download::where('file_path', $path)->orWhere('title', $item['title'])->first();
        if ($existing && !$force && !Storage::disk('public')->exists($path)) {
            return 0;
        }
        if ($existing && !$force && Storage::disk('public')->exists($path)) {
            return 1;
        }

        $data = [
            'title' => $item['title'],
            'description' => null,
            'file_path' => $path,
            'file_type' => $item['file_type'] ?? 'pdf',
            'category' => $this->downloadCategory($item['slug']),
            'status' => 'published',
        ];

        $download = $existing ?: new Download();
        $download->fill($data);
        $download->save();

        return [
            'job' => [
                'url' => $item['url'],
                'path' => $path,
                'download_id' => $download->id,
            ],
        ];
    }

    protected function markDownloadReady(array $job): void
    {
        $download = Download::find($job['download_id'] ?? null);
        if (!$download) {
            return;
        }
        $abs = Storage::disk('public')->path($job['path']);
        $download->update([
            'file_size' => is_file($abs) ? (string) filesize($abs) : null,
            'status' => 'published',
        ]);
        $this->ensureManifest($job['url'], $job['path']);
    }

    protected function downloadCategory(string $slug): string
    {
        if (preg_match('/constitution|amendment/i', $slug)) {
            return 'constitution';
        }
        if (preg_match('/act|legislation|law/i', $slug)) {
            return 'legislation';
        }
        if (preg_match('/regulation/i', $slug)) {
            return 'regulations';
        }
        if (preg_match('/civic|education|manual|booklet|curriculum|baseline|survey|strategy/i', $slug)) {
            return 'education';
        }
        if (preg_match('/form/i', $slug)) {
            return 'forms';
        }
        if (preg_match('/communique|press|statement/i', $slug)) {
            return 'press-release';
        }
        return 'publications';
    }

    protected function packageFromWpdmdl(int $wpdmdl): array
    {
        $cached = cache()->get('nec_wpdm_id_' . $wpdmdl);
        if ($cached) {
            return $cached;
        }

        $item = [
            'slug' => 'package-' . $wpdmdl,
            'title' => 'NEC Document ' . $wpdmdl,
            'url' => self::BASE . '/?wpdmdl=' . $wpdmdl,
            'file_type' => 'pdf',
        ];

        try {
            $html = Http::withHeaders(['User-Agent' => self::UA])->timeout(60)->get($item['url'])->body();
            if (preg_match('#content-disposition:\s*attachment;\s*filename="([^"]+)"#i', $html, $fm)) {
                $item['title'] = $this->cleanTitle(pathinfo($fm[1], PATHINFO_FILENAME));
                $item['slug'] = Str::slug(pathinfo($fm[1], PATHINFO_FILENAME));
            }
        } catch (\Throwable $e) {
            // Keep fallback name
        }

        cache()->put('nec_wpdm_id_' . $wpdmdl, $item, now()->addDay());
        return $item;
    }

    protected function fetchWpdmSearch(): array
    {
        try {
            $url = self::API . '/../wpdm/search';
            $response = Http::withHeaders(['User-Agent' => self::UA])->timeout(60)->get($url);
            if ($response->failed()) {
                return [];
            }
            $data = $response->json();
            if (!is_array($data)) {
                return [];
            }
            return collect($data)
                ->filter(fn($d) => is_array($d) && !empty($d['post_title']))
                ->map(function ($d) {
                    $title = strip_tags($d['post_title'] ?? '');
                    return [
                        'slug' => \Illuminate\Support\Str::slug($title),
                        'title' => $this->cleanTitle($title),
                    ];
                })
                ->unique('slug')
                ->values()
                ->all();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /* ─────────────────────────── Re-rewrite stored content ─────────────────────────── */

    public function rewriteStoredContent(): array
    {
        $touched = 0;

        foreach (CmsPage::all() as $page) {
            $rewritten = $this->rewriteHtml($page->content ?? '');
            if ($rewritten !== $page->content) {
                $page->update(['content' => $rewritten]);
                $touched++;
            }
        }

        foreach (News::all() as $news) {
            $rewritten = $this->rewriteHtml($news->content ?? '');
            if ($rewritten !== $news->content) {
                $news->update(['content' => $rewritten]);
                $touched++;
            }
        }

        foreach (Event::all() as $event) {
            $rewritten = $this->rewriteHtml($event->description ?? '');
            if ($rewritten !== $event->description) {
                $event->update(['description' => $rewritten]);
                $touched++;
            }
        }

        return ['touched' => $touched];
    }

    /* ─────────────────────────── Sanitizers ─────────────────────────── */

    protected function cleanHtml(string $html): string
    {
        // Remove inline Elementor/editor comments and JS that leaks builder markup
        $html = preg_replace('#<!-- (?:wp:|elementor|e-|comment)[^>]*-->#i', '', $html) ?? $html;
        return (string) $html;
    }

    protected function cleanTitle(string $title): string
    {
        return trim(preg_replace('/\s+/', ' ', strip_tags($title)) ?? '');
    }

    protected function firstTermName(array $items): ?string
    {
        foreach ($items as $item) {
            foreach ($item as $term) {
                if (!empty($term['name'])) {
                    return $term['name'];
                }
            }
        }
        return null;
    }

    protected function info(string $msg): void
    {
        if (app()->runningInConsole()) {
            $this->out($msg);
        }
    }

    protected function warn(string $msg): void
    {
        if (app()->runningInConsole()) {
            $this->out($msg, true);
        }
    }

    protected function out(string $msg, bool $isWarn = false): void
    {
        $prefix = $isWarn ? "\033[33m" : "\033[32m";
        echo $prefix . $msg . "\033[0m\n";
    }
}