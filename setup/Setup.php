<?php
/**
* The Setup Class
* @package Mars
*/

namespace Mars\Setup;

use Mars\App\Kernel;

/**
 * The Setup Class
 */
class Setup
{
    use Kernel;

    public function run()
    {

    }

    protected function welcome()
    {
        $this->app->cli->printLn();
        $this->app->cli->print('*********************************', 'important');
        $this->app->cli->print('Welcome to the Mars setup script!', 'important');
        $this->app->cli->print('*********************************', 'important');
        $this->app->cli->printLn();

        $this->app->cli->notice('This script will guide you through the initial setup of your Mars application.');
        $this->app->cli->notice('Please provide the following information (leave blank to skip an option):');
        $this->app->cli->printLn();
    }
}