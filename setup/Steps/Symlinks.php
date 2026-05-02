<?php
/**
* The Symlinks Setup Class
* @package Mars
*/

namespace Mars\Setup\Steps;

/**
* @package Mars
*/
class Symlinks extends Base
{
    /**
     * The symbolic links to be created
     */
    protected array $symlinks = [
        'vendor/webdev1404/mars-framework/assets' => 'public/assets/framework',
        'app/assets' => 'public/assets/app',
        'data/cache/css' => 'public/assets/cache/css',
        'data/cache/js' => 'public/assets/cache/js'
    ];

    /**
     * Run the symlinks setup step
     */
    public function run()
    {
        foreach ($this->symlinks as $target => $link) {
            $target = $this->app->base_path . '/' . $target;
            $link = $this->app->base_path . '/' . $link;

            if (is_link($link)) {
                continue;
            }

            if (!is_dir($link)) {
                symlink($target, $link);
            }
        }

        $this->app->cli->success('Symbolic links created successfully');
    }
}
