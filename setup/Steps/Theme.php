<?php
/**
* The Theme Setup Class
* @package Mars
*/

namespace Mars\Setup\Steps;

/**
 * @package Mars
 */
class Theme extends Base
{
    /**
     * Run the theme setup step
     */
    public function run()
    {
        $themes = new \Mars\Extensions\Themes($this->app);
        $themes->install($this->app->config->theme->name);

        $this->app->cli->success('Theme installed successfully');
    }
}
