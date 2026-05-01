<?php

namespace Mars\Bin;

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
        $this->show_done = true;

        $extensions = $this->getExtensions();
        foreach ($extensions as $name) {
            $this->doing("Installing {$this->names['extension_lower']} '{$name}'");

            $this->manager->install($name);
        }

        $this->app->cache->data->clean();
    }

    /**
     * Enables one or more extensions
     */
    public function enable()
    {
        $this->show_done = true;

        $extensions = $this->getExtensions();
        foreach ($extensions as $name) {
            $this->doing("Enabling {$this->names['extension_lower']} '{$name}'");

            $this->manager->enable($name);
        }

        $this->app->cache->data->clean();
    }

    /**
     * Upgrades one or more extensions
     */
    public function upgrade()
    {
        $this->show_done = true;

        $extensions = $this->getExtensions();
        foreach ($extensions as $name) {
            $this->doing("Upgrading {$this->names['extension_lower']} '{$name}'");

            $this->manager->upgrade($name);
        }

        $this->app->cache->data->clean();
    }

    /**
     * Disables one or more extensions
     */
    public function disable()
    {
        $this->show_done = true;

        $extensions = $this->getExtensions();
        foreach ($extensions as $name) {
            $this->doing("Disabling {$this->names['extension_lower']} '{$name}'");

            $this->manager->disable($name);
        }

        $this->app->cache->data->clean();
    }

    /**
     * Uninstalls one or more extensions
     */
    public function uninstall()
    {
        if ($this->askImportant("Are you sure you want to uninstall the selected {$this->names['extensions_lower']}?\nThe data it might have created will be lost!\nThis action cannot be undone. (yes/no)") !== 'yes') {
            return;
        }

        $this->printLn();

        $this->show_done = true;

        $extensions = $this->getExtensions();
        foreach ($extensions as $name) {
            $this->doing("Uninstalling {$this->names['extension_lower']} '{$name}'");

            $this->manager->uninstall($name);
        }

        $this->app->cache->data->clean();
    }

    /**
     * Returns the extension names from the command line arguments
     * @return array The extension names
     */
    protected function getExtensions(): array
    {
        $extensions_list =  array_slice($this->app->cli->params, 1);
        if (!$extensions_list) {
            throw new \Exception("{$this->names['extension']} name not specified. " . $this->command_help["{$this->usage['extension']}:install"]);
        }

        //check if the extensions exist
        $extensions = $this->manager->getAll(false);
        foreach ($extensions_list as $name) {
            if (!isset($extensions[$name])) {
                throw new \Exception("{$this->names['extension']} {$name} not found.");
            }
        }

        return $extensions_list;
    }
}
