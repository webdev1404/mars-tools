<?php
/**
* The Bin Base Class
* @package Mars
*/

namespace Mars\Bin;

use Mars\App\Kernel;
use Mars\Alerts\Alerts;
use Mars\Validation\ValidateTrait;

/**
 * The Bin Base Class
 * Base class for all bin classes
 */
abstract class Base implements BinInterface
{
    use Kernel;
    use ValidateTrait {
        ValidateTrait::validate as validateData;
    }

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
     * Validates the data
     * @param array|object $data The data to validate. If empty, the current CLI options are used.
     * @return bool True if the validation passed all tests, false otherwise
     */
    public function validate(array|object $data = []) : bool
    {
        if (!$data) {
            $data = $this->app->cli->options;
        }

        return $this->validateData($data);
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
    public function error(string $message, bool $die = true)
    {
        $this->app->cli->error($message, $die);
    }

    /**
     * Prints the errors and exits
     * @see \Mars\Cli::errors()
     */
    public function errors(array|Alerts $alerts, bool $die = true)
    {
        $this->app->cli->errors($alerts, $die);
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
     * Prints the warnings
     * @see \Mars\Cli::warnings()
     */
    public function warnings(array|Alerts $alerts)
    {
        $this->app->cli->warnings($alerts);
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
     * Prints the notices
     * @see \Mars\Cli::notices()
     */
    public function notices(array|Alerts $alerts)
    {
        $this->app->cli->notices($alerts);
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
     * Prints a table
     * @see \Mars\Cli::printTable()
     */
    public function printTable(array $headers, array $data, array $colors = [], array $align = [], array $paddings_left = [], array $paddings_right = [])
    {
        $this->app->cli->printTable($headers, $data, $colors, $align, $paddings_left, $paddings_right);
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
        return strtolower($this->app->cli->ask($question, 'important'));
    }
}
