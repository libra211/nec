<?php

namespace App\Console\Commands;

use App\Services\NecMirrorService;
use Illuminate\Console\Command;

class MirrorNecContent extends Command
{
    protected $signature = 'nec:mirror
        {--force : Overwrite existing records and re-download files}
        {--pages : Mirror WordPress pages into CMS pages}
        {--posts : Mirror WordPress posts into news and events}
        {--media : Mirror the WordPress media library into the gallery}
        {--downloads : Mirror WPDM documents into the downloads library}
        {--rewrite : Re-rewrite stored HTML asset URLs to local mirrors}';

    protected $description = 'Mirror all content (pages, news, media, documents) from nec.gov.ss into this system';

    public function handle(NecMirrorService $mirror): int
    {
        $force = (bool) $this->option('force');

        $toggles = [
            'pages' => (bool) $this->option('pages'),
            'posts' => (bool) $this->option('posts'),
            'media' => (bool) $this->option('media'),
            'downloads' => (bool) $this->option('downloads'),
        ];

        $anyChosen = collect($toggles)->contains(true) || $this->option('rewrite');

        if (!$anyChosen) {
            $toggles = array_map(fn() => true, $toggles);
        }

        if ($toggles['pages']) {
            $this->info("Mirroring pages…");
            $count = $mirror->mirrorPages($force);
            $this->line("  <info>{$count} pages processed</info>");
        }

        if ($toggles['media']) {
            $this->info("Mirroring media library…");
            $result = $mirror->mirrorMedia($force);
            $this->line("  <info>{$result['retrieved']} media items, {$result['saved']} saved, {$result['failed']} failed</info>");
        }

        if ($toggles['posts']) {
            $this->info("Mirroring posts…");
            $result = $mirror->mirrorPosts($force);
            $this->line("  <info>{$result['news']} news items, {$result['events']} events</info>");
        }

        if ($toggles['downloads']) {
            $this->info("Mirroring downloads…");
            $result = $mirror->mirrorDownloads($force);
            $this->line("  <info>{$result['packages']} packages processed, {$result['saved']} saved</info>");
        }

        $this->info("Rewriting stored HTML asset URLs…");
        $result = $mirror->rewriteStoredContent();
        $this->line("  <info>{$result['touched']} records updated</info>");

        $this->info("Content mirror complete.");

        return self::SUCCESS;
    }
}