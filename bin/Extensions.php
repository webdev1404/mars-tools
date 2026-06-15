<?php

namespace Mars\Bin;

use Mars\Exception;
use Mars\Extensions\Extensions as BaseExtensions;

abstract class Extensions extends Base
{
    /**
     * @var array $names The names used in messages
     */
    protected array $names = [
        'extension' => 'Extension',
        'extension_lower' => 'extension',
        'extensions' => 'Extensions',
        'extensions_lower' => 'extensions'
    ];

    protected array $usage = [
        'extension' => 'extension',
    ];

    /**
     * @var array $actions_list The list of actions and the type of extensions they work with
     */
    protected array $actions_list = [
        'install' => 'available',
        'enable' => 'available',
        'disable' => 'enabled',
        'upgrade' => 'enabled',
        'uninstall' => 'enabled',
    ];

    /**
     * @var array $actions_caches The list of caches to clean after performing an action
     */
    protected array $actions_caches = [];

    /**
     * @var BaseExtensions $manager The extensions manager instance
     */
    protected BaseExtensions $manager;

    /**
     * Default action
     */
    public function default()
    {
        $this->listEnabled();
    }

    /**
     * Lists all enabled extensions
     */
    public function listEnabled()
    {
        $data = [
            "Enabled {$this->names['extensions']}" => $this->getList($this->manager->getEnabled(false))
        ];

        $this->printListMulti($data);
    }

    /**
     * Lists all extensions
     */
    public function listAll()
    {
        $data = [
            "Enabled {$this->names['extensions']}" => $this->getList($this->manager->getEnabled(false)),
            "Available {$this->names['extensions']}" => $this->getList($this->manager->getAvailable(false))
        ];

        $this->printListMulti($data);
    }

    /**
     * Lists all available extensions
     */
    public function listAvailable()
    {
        $data = [
            "Available {$this->names['extensions']}" => $this->getList($this->manager->getAvailable(false))
        ];

        $this->printListMulti($data);
    }

    /**
     * Returns the extensions list in a format suitable for printing
     * @param array $extensions_list The list of  extensions
     * @return array The available extensions
     */
    protected function getList(array $extensions_list): array
    {
        $extensions = [];

        foreach ($extensions_list as $name => $path) {
            $info = $this->manager->getInfo($name);

            $title = $info['title'] ?? $name;
            $description = $info['description'] ?? '';

            $extensions[] = [$title, $name, $description];
        }

        return $extensions;
    }

    /**
     * Installs one or more extensions
     */
    public function install()
    {
        try {
            $this->show_done = true;

            $extensions = $this->getExtensions($this->actions_list['install']);
            foreach ($extensions as $name) {
                $this->doing("Installing {$this->names['extension_lower']} '{$name}'");

                $this->manager->install($name);
            }

            $this->cleanCaches();
        } catch (Exception $e) {
            throw new \Exception("Error installing {$this->names['extension_lower']} '{$e->getMessage()}'. It it already installed or does not exist. Use the --force option to force install it.");
        }
    }

    /**
     * Enables one or more extensions
     */
    public function enable()
    {
        try {
            $this->show_done = true;

            $extensions = $this->getExtensions($this->actions_list['enable']);
            foreach ($extensions as $name) {
                $this->doing("Enabling {$this->names['extension_lower']} '{$name}'");

                $this->manager->enable($name);
            }

            $this->cleanCaches();
        } catch (Exception $e) {
            throw new \Exception("Error enabling {$this->names['extension_lower']} '{$e->getMessage()}'. It it already enabled or does not exist. Use the --force option to force enable it.");
        }
    }

    /**
     * Disables one or more extensions
     */
    public function disable()
    {
        try {
            $this->show_done = true;

            $extensions = $this->getExtensions($this->actions_list['disable']);
            foreach ($extensions as $name) {
                $this->doing("Disabling {$this->names['extension_lower']} '{$name}'");

                $this->manager->disable($name);
            }

            $this->cleanCaches();
        } catch (Exception $e) {
            throw new \Exception("Error disabling {$this->names['extension_lower']} '{$e->getMessage()}'. It it already disabled or does not exist. Use the --force option to force disable it.");
        }
    }

    /**
     * Upgrades one or more extensions
     */
    public function upgrade()
    {
        try {
            $this->show_done = true;

            $extensions = $this->getExtensions($this->actions_list['upgrade']);
            foreach ($extensions as $name) {
                $this->doing("Upgrading {$this->names['extension_lower']} '{$name}'");

                $this->manager->upgrade($name);
            }

            $this->cleanCaches();
        } catch (Exception $e) {
            throw new \Exception("Error upgrading {$this->names['extension_lower']} '{$e->getMessage()}'. It it not enabled or does not exist. Use the --force option to force upgrade it.");
        }
    }

    /**
     * Uninstalls one or more extensions
     */
    public function uninstall()
    {
        try {
            $extensions = $this->getExtensions($this->actions_list['uninstall']);

            if ($this->askImportant("Are you sure you want to uninstall the selected {$this->names['extensions_lower']}?\nThe data it might have created will be lost!\nThis action cannot be undone. (yes/no)") !== 'yes') {
                return;
            }

            $this->printLn();

            $this->show_done = true;
            
            foreach ($extensions as $name) {
                $this->doing("Uninstalling {$this->names['extension_lower']} '{$name}'");

                $this->manager->uninstall($name);
            }

            $this->cleanCaches();
        } catch (Exception $e) {
            throw new \Exception("Error uninstalling {$this->names['extension_lower']} '{$e->getMessage()}'. It it not enabled or does not exist. Use the --force option to force uninstall it.");
        }
    }

    /**
     * Returns the extension names from the command line arguments
     * @param string $type The type of extensions to return: 'enabled', 'available', 'all'
     * @return array The extension names
     */
    protected function getExtensions(string $type): array
    {
        $extensions_list =  array_slice($this->app->cli->commands, 1);
        if (!$extensions_list) {
            throw new \Exception("{$this->names['extension']} name not specified. " . $this->command_help['install']);
        }

        if ($this->app->cli->has('force')) {
            $type = 'all';
        }

        //check if the extensions exist
        $extensions = [];

        switch ($type) {
            case 'enabled':
                $extensions = $this->manager->getEnabled(false);
                break;
            case 'available':
                $extensions = $this->manager->getAvailable(false);
                break;
            default:
                $extensions = $this->manager->getAll(false);
                break;
        }

        foreach ($extensions_list as $name) {
            if (!isset($extensions[$name])) {
                throw new Exception($name, 'not-found');
            }
        }

        return $extensions_list;
    }

    /**
     * Cleans the caches after performing an action
     */
    protected function cleanCaches()
    {
        $this->manager->cache->clean();

        foreach ($this->actions_caches as $cache) {
            $this->app->cache->{$cache}->clean();
        }
    }
}
