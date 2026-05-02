<?php
/**
* The Language Setup Class
* @package Mars
*/

namespace Mars\Setup\Steps;

/**
* @package Mars
*/
class Language extends Base
{
    /**
     * Run the language setup step
     */
    public function run()
    {
        $languages = new \Mars\Extensions\Languages\Languages($this->app);
        $languages->install($this->app->config->language->name);

        $this->app->cli->success('Language installed successfully');
    }
}