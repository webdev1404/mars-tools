<?php
/**
* The Bin Class
* @package Mars
*/

namespace Mars\Bin;

use Mars\App\Kernel;
use Mars\Extensions\Modules\Module;

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

            $this->command = $this->app->cli->params[0] ?? '';

            return $this->command;
        }
    }

    /**
     * @var array $command_parts The parts of the command
     */
    public protected(set) array $command_parts {
        get {
            if (isset($this->command_parts)) {
                return $this->command_parts;
            }

            $this->command_parts = [];
            if ($this->command) {
                $this->command_parts = explode(':', $this->command);
            }

            return $this->command_parts;
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
            if (!$this->app->cli->has('help') && $this->command_parts) {
                $this->root = current($this->command_parts);
            }

            return $this->root;
        }
    }

    /**
     * @var string $action The action to be executed
     */
    public protected(set) string $action {
        get {
            if (isset($this->action)) {
                return $this->action;
            }

            $this->action = '';
            if (!$this->app->cli->has('help') && count($this->command_parts) > 1) {
                $this->action = implode(':', array_slice($this->command_parts, 1));
            }

            return $this->action;
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

            $this->handlers = $this->getHandlers(__DIR__ . '/Handlers', '\\Mars\\Bin\\Handlers');

            //Load the files from the app's bin directory, if it exists
            $app_bin_dir = $this->app->app_path . '/bin';
            if (is_dir($app_bin_dir)) {
                $app_handlers = $this->getHandlers($app_bin_dir, '\\App\\Bin');

                if ($app_handlers) {
                    $this->handlers = [...$this->handlers, ...$app_handlers];
                }
            }

            //Load the files from the bin directories of the modules
            foreach ($this->app->modules->getEnabled() as $module_name => $module_path) {
                $module_bin_path = $module_path . '/' . Module::DIRS['bin'];
                if (!is_dir($module_bin_path)) {
                    continue;
                }

                $base_namespace = $this->app->modules->getBaseNamespace($module_name, Module::DIRS['bin']);
                $module_handlers = $this->getHandlers($module_bin_path, $base_namespace);

                if ($module_handlers) {
                    $this->handlers = [...$this->handlers, ...$module_handlers];
                }
            }

            //Sort the handlers by their order property
            uasort($this->handlers, fn ($a, $b) => ($a->order ?? 100) <=> ($b->order ?? 100));

            return $this->handlers;
        }
    }

    /**
     * Returns the action object for the current action
     * @return BinInterface The action object
     */
    public function get() : BinInterface
    {
        return $this->handlers[$this->root];
    }

    /**
     * Checks if the current action exists
     * @return bool True if the action exists, false otherwise
     */
    public function exists() : bool
    {
        return isset($this->handlers[$this->root]);
    }

    /**
     * Loads the classes from the given directory and returns the handlers they provide
     * @param string $dir The directory to load the classes from
     * @param string $base_namespace The base namespace of the classes
     * @return array The handlers found in the directory
     */
    protected function getHandlers(string $dir, string $base_namespace) : array
    {
        $handlers = [];
        
        $classes = $this->app->dir->getFilesSorted($dir, true, true, [], ['php']);
        foreach ($classes as $filename) {
            $name = str_ireplace([$dir, '.php'], '', $filename);
            $name = str_replace('/', '\\', $name);
    
            $namespace = $base_namespace . $name;

            include($filename);

            $obj = new $namespace($this->app);
            if (!$obj instanceof BinInterface) {
                throw new \Exception("Class {$namespace} does not implement BinInterface");
            }

            $roots_list = $obj->roots ?? [];
            foreach ($roots_list as $root) {
                $handlers[$root] = $obj;
            }
        }

        return $handlers;
    }
}
