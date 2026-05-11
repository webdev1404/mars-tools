<?php
/**
* The Upgrade Class
* @package Mars
*/

namespace Mars\Upgrade;

use Mars\App\Kernel;

/**
 * The Upgrade Class
 */
class Upgrade
{
    use Kernel;

    /**
     * Run the setup process
     */
    public function run()
    {
        $this->welcome();
    }

    /**
     * Welcome message and instructions
     */
    protected function welcome()
    {
        $this->app->cli->printLn();
        $this->app->cli->important('*********************************');
        $this->app->cli->important('Welcome to the Mars upgrade script!');
        $this->app->cli->important('*********************************');
        $this->app->cli->printLn();

        $this->app->cli->success("The script does nothing yet, but in the future it will guide you through the upgrade process.");
    }
}
