<?php

namespace Mars\Bin\Handlers;

use Mars\Bin\Base;

class Sitemap extends Base
{
    public protected(set) string $title = 'Sitemap';

    public protected(set) string $root = 'sitemap';

    public protected(set) array $commands = [
        'generate'       => 'generate',
        'delete'         => 'delete',
    ];
    
    public protected(set) array $command_descriptions = [
        'generate' => 'Generates the sitemap. Should be run periodically to keep it up to date.',
        'delete'   => 'Deletes the sitemap file.',
    ];

    /**
     * Generates the sitemap
     */
    public function generate()
    {
        $sitemap = new \Mars\Seo\Sitemap($this->app);
        $sitemap->generate();
        
        $this->done('Sitemap generated successfully.');
    }
    
    /**
     * Deletes the sitemap file
     */
    public function delete()
    {
        $filename = $this->app->public_path . '/sitemap_index.xml';
        if (is_file($filename)) {
            unlink($filename);
        }

        $this->app->cache->sitemap->clean();

        $this->done('Sitemap deleted successfully.');
    }
}

