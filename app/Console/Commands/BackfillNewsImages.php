<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Services\NecMirrorService;
use Illuminate\Console\Command;

class BackfillNewsImages extends Command
{
    protected $signature = 'nec:backfill-news-images';

    protected $description = 'Backfill news featured images from embedded article images (local mirror preferred, nec.gov.ss fallback)';

    public function handle(NecMirrorService $mirror): int
    {
        $items = News::where(fn ($q) => $q->whereNull('image')->orWhere('image', ''))->get();

        if ($items->isEmpty()) {
            $this->info('No news items missing an image.');

            return self::SUCCESS;
        }

        $updated = 0;
        $skipped = 0;
        $remoteOnly = 0;

        foreach ($items as $item) {
            $url = $this->firstImageFromContent($item->content);
            if (!$url) {
                $skipped++;
                continue;
            }

            $image = $this->resolveImage($url, $mirror);
            if ($image === null) {
                $skipped++;
                continue;
            }

            if (str_starts_with($image, 'http')) {
                $remoteOnly++;
            }

            $item->forceFill([
                'image' => $image,
                'featured_image' => $image,
            ])->save();
            $updated++;

            $this->line("  [ok] {$item->slug} -> {$image}");
        }

        $this->info("Backfill complete: {$updated} updated, {$skipped} no image found" . ($remoteOnly ? ", {$remoteOnly} using remote nec.gov.ss URL" : ''));

        return self::SUCCESS;
    }

    protected function firstImageFromContent(?string $content): ?string
    {
        if (!$content) {
            return null;
        }

        if (preg_match('/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $content, $m)) {
            return html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5);
        }

        if (preg_match('#url\([\'"]?([^)\'"]+)[\'"]?\)#i', $content, $m)) {
            return $m[1];
        }

        return null;
    }

    protected function resolveImage(string $url, NecMirrorService $mirror): ?string
    {
        $url = trim($url);

        if (str_starts_with($url, '/storage/')) {
            return $url;
        }

        if (!str_contains($url, 'nec.gov.ss') && !str_contains($url, 'wp-content/uploads')) {
            return null;
        }

        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https:' . $url;
        }

        $local = $mirror->localizeMediaUrl($url);
        if ($local) {
            return $local;
        }

        $base = preg_replace('#-\d+x\d+(?=\.[a-z0-9]+$)#i', '', $url);
        if ($base !== $url) {
            $local = $mirror->localizeMediaUrl($base);
            if ($local) {
                return $local;
            }
        }

        return $url;
    }
}