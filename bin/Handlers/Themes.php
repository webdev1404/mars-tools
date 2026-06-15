<?php

namespace Mars\Bin\Handlers;

use Mars\Bin\Extensions;
use Mars\Extensions\Extensions as BaseExtensions;

class Themes extends Extensions
{
    public protected(set) string $title = 'Manage Themes';

    public protected(set) string $root = 'themes';

    public protected(set) array $commands = [
        'list'           => 'listAvailable',
        'install'        => 'install',
        'upgrade'        => 'upgrade',
        'uninstall'      => 'uninstall',
    ];

    public protected(set) array $command_descriptions = [
        'list'         => 'List all existing themes',
        'install'       => 'Installs a theme',
        'upgrade'       => 'Upgrades a theme',
        'uninstall'     => 'Uninstalls a theme'
    ];
    
    public protected(set) array $command_help = [
        'install' => 'Usage: install [--force] <theme_name> [<theme_name> ...]',
        'upgrade' => 'Usage: upgrade [--force] <theme_name> [<theme_name> ...]',
        'uninstall'  => 'Usage: uninstall [--force] <theme_name> [<theme_name> ...]'
    ];

    protected array $names = [
        'extension' => 'Theme',
        'extension_lower' => 'theme',
        'extensions' => 'Themes',
        'extensions_lower' => 'themes'
    ];

    protected array $usage = [
        'extension' => 'theme',
    ];

    protected array $actions_list = [
        'install' => 'available',
        'upgrade' => 'available',
        'uninstall' => 'available',
    ];

    protected BaseExtensions $manager {
        get => $this->app->theme->manager;
    }
}
