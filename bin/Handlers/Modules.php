<?php

namespace Mars\Bin\Handlers;

use Mars\Bin\Extensions;
use Mars\Extensions\Extensions as BaseExtensions;

class Modules extends Extensions
{
    public protected(set) string $title = 'Manage Modules';

    public protected(set) array $roots = ['modules', 'module'];

    public protected(set) array $commands = [
        'list'           => 'listEnabled',
        'list:all'       => 'listAll',
        'list:available' => 'listAvailable',
        'install'        => 'install',
        'enable'         => 'enable',
        'upgrade'        => 'upgrade',
        'disable'        => 'disable',
        'uninstall'      => 'uninstall',
    ];

    public protected(set) array $command_descriptions = [
        'modules:list'           => 'List all enabled modules',
        'modules:list:all'       => 'List all modules',
        'modules:list:available' => 'List all available modules',
        'module:install'         => 'Installs a module',
        'module:enable'          => 'Enables a module',
        'module:upgrade'         => 'Upgrades a module',
        'module:disable'         => 'Disables a module',
        'module:uninstall'       => 'Uninstalls a module'
    ];

    public protected(set) array $command_help = [
        'module:install' => 'Usage: module:install <module_name> [<module_name> ...]',
        'module:enable'  => 'Usage: module:enable <module_name> [<module_name> ...]',
        'module:upgrade' => 'Usage: module:upgrade <module_name> [<module_name> ...]',
        'module:disable' => 'Usage: module:disable <module_name> [<module_name> ...]',
        'module:uninstall'  => 'Usage: module:uninstall <module_name> [<module_name> ...]'
    ];

    protected array $names = [
        'extension' => 'Module',
        'extension_lower' => 'module',
        'extensions' => 'Modules',
        'extensions_lower' => 'modules'
    ];

    protected array $usage = [
        'extension' => 'module',
    ];

    protected BaseExtensions $manager {
        get => $this->app->modules;
    }

    /**
     * @see Extensions::install()
     * {@inheritDoc}
     */
    public function install()
    {
        parent::install();

        $this->app->cache->routes->clean();
    }

    /**
     * @see Extensions::enable()
     * {@inheritDoc}
     */
    public function enable()
    {
        parent::enable();

        $this->app->cache->routes->clean();
    }

    /**
     * @see Extensions::upgrade()
     * {@inheritDoc}
     */
    public function upgrade()
    {
        parent::upgrade();

        $this->app->cache->routes->clean();
    }

    /**
     * @see Extensions::disable()
     * {@inheritDoc}
     */
    public function disable()
    {
        parent::disable();

        $this->app->cache->routes->clean();
    }

    /**
     * @see Extensions::uninstall()
     * {@inheritDoc}
     */
    public function uninstall()
    {
        parent::uninstall();

        $this->app->cache->routes->clean();
    }
}
