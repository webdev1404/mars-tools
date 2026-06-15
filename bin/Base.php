<?php
/**
* The Bin Base Class
* @package Mars
*/

namespace Mars\Bin;

use Mars\App\Kernel;

/**
 * The Bin Base Class
 * Base class for all bin classes
 */
abstract class Base implements BinInterface
{
    use Kernel;

    /**
     * @var string $root The root the class is responsible for
     */
    public protected(set) string $root = '';

    /**
     * @var array $commands The commands the class can handle
     */
    public protected(set) array $commands = [];

    /**
     * @var array $command_descriptions The command_descriptions The command descriptions
     */
    public protected(set) array $command_descriptions = [];

    /**
     * @var array $command_help The command_help The command help
     */
    public protected(set) array $command_help = [];

    /**
     * @var int $order The order in which the bin classes are executed
     */
    public int $order = 100;

    /**
     * @var bool $show_done Whether to show the done message after executing a command
     */
    protected bool $show_done = false;

    /**
     * Executes the command
     * @param string $command The command to execute
     */
    public function execute(string $command)
    {
        $this->printLn();

        $parts = explode(':', $command);
        $action = implode(':', array_slice($parts, 1));

        $method = 'default';
        if ($action) {
            if (!isset($this->commands[$action])) {
                throw new \Exception("Command {$command} not found");
            }

            $method = $this->commands[$action];
        }

        if (!method_exists($this, $method)) {
            throw new \Exception("Method {$method} not found in class " . get_class($this));
        }

        call_user_func([$this, $method]);

        if ($this->show_done) {
            $this->done();
        }

        $this->printLn();
    }

    /**
     * Default action
     */
    public function default()
    {
    }

    /**
     * Prints the start message
     * @param string $message The message to print
     * @param string $color The color of the message
     */
    public function doing(string $message, string $color = 'blue')
    {
        $this->app->cli->print($message, $color);
    }

    /**
     * Prints the done message
     * @param string $message The message to print
     * @param string $color The color of the message
     */
    public function done(string $message = 'Done!', $color = 'green')
    {
        $this->app->cli->print($message, $color);
    }

    /**
     * Prints a message
     * @see \Mars\Cli::print()
     */
    public function print(string $message, string $color = 'blue')
    {
        $this->app->cli->print($message, $color);
    }

    /**
     * Prints a newline
     * @see \Mars\Cli::printLn()
     */
    public function printLn(int $count = 1)
    {
        $this->app->cli->printLn($count);
    }

    /**
     * Prints an error message and exits
     * @see \Mars\Cli::error()
     */
    public function error(string $message, bool $exit = true)
    {
        $this->app->cli->error($message, $exit);
    }

    /**
     * Prints a warning message
     * @see \Mars\Cli::warning()
     */
    public function warning(string $message)
    {
        $this->app->cli->warning($message);
    }

    /**
     * Prints a notice message
     * @see \Mars\Cli::notice()
     */
    public function notice(string $message)
    {
        $this->app->cli->notice($message);
    }

    /**
     * Prints a list
     * @see \Mars\Cli::printList()
     */
    public function printList(array $data, array $colors = ['green'], array $paddings_right = [], array $paddings_left = [])
    {
        $this->app->cli->printList($data, $colors, $paddings_right, $paddings_left);
    }

    /**
     * Prints a list with multiple columns
     * @see \Mars\Cli::printListMulti()
     */
    public function printListMulti(array $data, array $colors = ['green'], array $paddings_right = [], array $paddings_left = [])
    {
        $this->app->cli->printListMulti($data, $colors, $paddings_right, $paddings_left);
    }

    /**
     * Asks a question and returns the answer
     * @see \Mars\Cli::ask()
     */
    public function ask(string $question) : string
    {
        return $this->app->cli->ask($question);
    }

    /**
     * Asks an important question and returns the answer
     * @see \Mars\Cli::askImportant()
     */
    public function askImportant(string $question) : string
    {
        return $this->app->cli->ask($question, 'important');
    }
}
