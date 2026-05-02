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

    protected array $steps = [
        \Mars\Setup\Steps\Config::class,
        \Mars\Setup\Steps\Symlinks::class,
        \Mars\Setup\Steps\Language::class,
        \Mars\Setup\Steps\Theme::class
    ];

    /**
     * Run the setup process
     */
    public function run()
    {
        $this->welcome();

        foreach ($this->steps as $step_class) {
            $step = new $step_class($this->app);
            $step->run();
        }

        $this->finish();
    }

    /**
     * Welcome message and instructions
     */
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

    /**
     * Finish the setup process with a success message
     */
    protected function finish()
    {
        $this->app->cli->printLn();
        $this->app->cli->success('Setup complete!');
        $this->app->cli->printLn();
    }
}