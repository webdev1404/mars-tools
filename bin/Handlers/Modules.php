<?php

namespace Mars\Bin\Handlers;

use Mars\Bin\Extensions;
use Mars\Extensions\Extensions as BaseExtensions;

class Modules extends Extensions
{
    public protected(set) string $title = 'Manage Modules';

    public protected(set) string $root = 'modules';

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
        'list'           => 'List all enabled modules',
        'list:all'       => 'List all modules',
        'list:available' => 'List all available modules',
        'install'         => 'Installs a module',
        'enable'          => 'Enables a module',
        'upgrade'         => 'Upgrades a module',
        'disable'         => 'Disables a module',
        'uninstall'       => 'Uninstalls a module'
    ];

    public protected(set) array $command_help = [
        'install' => 'Usage: install [--force] <module_name> [<module_name> ...]',
        'enable'  => 'Usage: enable [--force] <module_name> [<module_name> ...]',
        'upgrade' => 'Usage: upgrade [--force] <module_name> [<module_name> ...]',
        'disable' => 'Usage: disable [--force] <module_name> [<module_name> ...]',
        'uninstall'  => 'Usage: uninstall [--force] <module_name> [<module_name> ...]'
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

    protected array $actions_caches = ['routes'];

    protected BaseExtensions $manager {
        get => $this->app->modules;
    }
}
