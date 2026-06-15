<?php
/**
* The Bin Class
* @package Mars
*/

namespace Mars\Bin;

use Mars\App\Kernel;
use Mars\Extensions\Module;

/**
 * The Bin Class
 */
class Bin
{
    use Kernel;

    /**
     * @var string $command The command to be executed
     */
    public protected(set) string $command {
        get {
            if (isset($this->command)) {
                return $this->command;
            }

            $this->command = $this->app->cli->commands[0] ?? '';

            return $this->command;
        }
    }

    /**
     * @var string $root The command's root
     */
    public protected(set) string $root {
        get {
            if (isset($this->root)) {
                return $this->root;
            }

            $this->root = 'help';

            if ($this->command) {
                $parts = explode(':', $this->command);

                $this->root = array_first($parts);
            }

            return $this->root;
        }
    }

    /**
     * @var array $handlers The list of handlers
     */
    public protected(set) array $handlers {
        get {
            if (isset($this->handlers)) {
                return $this->handlers;
            }

            $this->handlers = [];

            //Load the files from the bin directory of the framework
            $this->addHandlers($this->handlers, __DIR__ . '/Handlers', '\\Mars\\Bin\\Handlers');

            //Load the files from the app's bin directory, if it exists
            $bin_path = $this->app->app_path . '/bin';
            if (is_dir($bin_path)) {
                $this->addHandlers($this->handlers, $bin_path, '\\App\\Bin');
            }

            //Load the files from the bin directories of the extensions
            foreach ($this->app->extensions as $type => $manager) {
                if (!$manager->supports('bin')) {
                    continue;
                }

                $instance = $manager->getInstanceClass();

                //Load the files from the bin directories of the extensions
                foreach ($manager->getEnabled() as $extension_name => $extension_path) {
                    $bin_path = $extension_path . '/' . $instance::DIRS['bin'];
                    if (!is_dir($bin_path)) {
                        continue;
                    }

                    $this->addHandlers($this->handlers, $bin_path, $manager->getBaseNamespace($extension_name, 'bin'));
                }
            }

            //Sort the handlers by their order property
            uasort($this->handlers, fn ($a, $b) => ($a->order ?? 100) <=> ($b->order ?? 100));

            return $this->handlers;
        }
    }

    /**
     * Runs the command
     */
    public function run()
    {
        if (!$this->exists()) {
            throw new \Exception("Command {$this->command} not found");
        }

        $this->get()->execute($this->command);
    }

    /**
     * Returns the action object for the current action
     * @return BinInterface The action object
     */
    protected function get() : BinInterface
    {
        $root = $this->root;
        if ($this->app->cli->has('help')) {
            $root = 'help';
        }

        return $this->handlers[$root];
    }

    /**
     * Checks if the current action exists
     * @return bool True if the action exists, false otherwise
     */
    protected function exists() : bool
    {
        return isset($this->handlers[$this->root]);
    }

    /**
     * Loads the classes from the given path and adds them to the handlers array
     * @param array $handlers The array to add the handlers to
     * @param string $path The path to load the classes from
     * @param string $base_namespace The base namespace of the classes
     */
    protected function addHandlers(array &$handlers, string $path, string $base_namespace)
    {
        $classes = $this->app->dir->getFilesSorted($path, true, true, [], ['php']);
        foreach ($classes as $filename) {
            $name = str_ireplace([$path, '.php'], '', $filename);
            $name = str_replace('/', '\\', $name);
    
            $namespace = $base_namespace . $name;

            include($filename);

            $obj = new $namespace($this->app);
            if (!$obj instanceof BinInterface) {
                throw new \Exception("Class {$namespace} does not implement BinInterface");
            }

            $handlers[$obj->root] = $obj;
        }
    }
}
