<?php

namespace Mars\Bin\Handlers;

use Mars\Bin\Extensions;
use Mars\Extensions\Extensions as BaseExtensions;

class Themes extends Extensions
{
    public protected(set) string $title = 'Manage Themes';

    public protected(set) array $roots = ['themes', 'theme'];

    public protected(set) array $commands = [
        'themes:list'           => 'listAvailable',
        'themes:install'        => 'install',
        'themes:upgrade'        => 'upgrade',
        'themes:uninstall'      => 'uninstall',
    ];

    public protected(set) array $command_descriptions = [
        'themes:list'         => 'List all existing themes',
        'theme:install'       => 'Installs a theme',
        'theme:upgrade'       => 'Upgrades a theme',
        'theme:uninstall'     => 'Uninstalls a theme'
    ];
    
    public protected(set) array $command_help = [
        'theme:install' => 'Usage: theme:install <theme_name> [<theme_name> ...]',
        'theme:upgrade' => 'Usage: theme:upgrade <theme_name> [<theme_name> ...]',
        'theme:uninstall'  => 'Usage: theme:uninstall <theme_name> [<theme_name> ...]'
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

    protected BaseExtensions $manager {
        get => $this->app->theme->manager;
    }
}
