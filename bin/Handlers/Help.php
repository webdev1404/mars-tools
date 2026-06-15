<?php

namespace Mars\Bin\Handlers;

use Mars\Bin\Base;
use Mars\Bin\BinInterface;

class Help extends Base
{
    public protected(set) string $root = 'help';

    /**
     * Executes the command
     */
    public function execute(string $command)
    {
        $this->printLn();

        if ($command) {
            $this->showActionHelp($command);
        } else {
            $this->showVersion();
            $this->showAvailableCommands();
        }

        $this->printLn();
    }

    /**
     * Shows the version of the framework and PHP
     */
    protected function showVersion()
    {
        $php = phpversion();

        $this->print("Mars Framework {$this->app->version}");
        $this->print("PHP {$php}");
        $this->printLn(2);
    }

    /**
     * Shows the available commands
     */
    protected function showAvailableCommands()
    {
        $data = [];
        foreach ($this->app->bin->handlers as $root => $obj) {
            if ($root == 'help') {
                continue;
            }

            $title = $obj->title ?? get_class($obj);
            $data[$title] = $this->getCommands($obj);
        }

        $this->printListMulti($data);
    }

    /**
     * Returns the available commands for the given object
     * @param BinInterface $obj The object
     * @return array The available commands
     */
    protected function getCommands(BinInterface $obj): array
    {
        $data_array = [];
        foreach ($obj->command_descriptions ?? [] as $cmd => $desc) {
            $data_array[] = [$obj->root . ':' . $cmd, $desc];
        }

        return $data_array;
    }

    /**
     * Shows help for the given action
     * @param string $command The command to show help for
     */
    public function showActionHelp(string $command)
    {
        $parts = explode(':', $command);
        $root = array_shift($parts);
        $action = implode(':', $parts);

        if (!isset($this->app->bin->handlers[$root])) {
            throw new \Exception("Command {$command} not found");
        }

        $obj = $this->app->bin->handlers[$root];

        $text = $obj->command_help[$action] ?? ($obj->command_descriptions[$action] ?? null);
        if ($text === null) {
            throw new \Exception("Command {$command} not found");
        }

        $this->printList([[$command, $text]]);
    }
}
