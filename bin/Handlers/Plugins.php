<?php

namespace Mars\Bin\Handlers;

use Mars\Bin\Extensions;
use Mars\Extensions\Extensions as BaseExtensions;

class Plugins extends Extensions
{
    public protected(set) string $title = 'Manage Plugins';

    public protected(set) string $root = 'plugins';

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
        'list'           => 'List all enabled plugins',
        'list:all'       => 'List all plugins',
        'list:available' => 'List all available plugins',
        'install'         => 'Installs a plugin',
        'enable'          => 'Enables a plugin',
        'upgrade'         => 'Upgrades a plugin',
        'disable'         => 'Disables a plugin',
        'uninstall'       => 'Uninstalls a plugin'
    ];

    public protected(set) array $command_help = [
        'install' => 'Usage: install [--force] <plugin_name> [<plugin_name> ...]',
        'enable'  => 'Usage: enable [--force] <plugin_name> [<plugin_name> ...]',
        'upgrade' => 'Usage: upgrade [--force] <plugin_name> [<plugin_name> ...]',
        'disable' => 'Usage: disable [--force] <plugin_name> [<plugin_name> ...]',
        'uninstall'  => 'Usage: uninstall [--force] <plugin_name> [<plugin_name> ...]'
    ];

    protected array $names = [
        'extension' => 'Plugin',
        'extension_lower' => 'plugin',
        'extensions' => 'Plugins',
        'extensions_lower' => 'plugins'
    ];

    protected array $usage = [
        'extension' => 'plugin',
    ];

    protected array $actions_caches = ['routes'];

    protected BaseExtensions $manager {
        get => $this->app->plugins;
    }
}
