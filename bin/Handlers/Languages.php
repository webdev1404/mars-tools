<?php

namespace Mars\Bin\Handlers;

use Mars\Bin\Extensions;
use Mars\Extensions\Extensions as BaseExtensions;

class Languages extends Extensions
{
    public protected(set) string $title = 'Manage Languages';

    public protected(set) array $roots = ['languages', 'language'];

    public protected(set) array $commands = [
        'list'           => 'listEnabled',
        'list:all'       => 'listAll',
        'list:available' => 'listAvailable',
        'install'        => 'install',
        'upgrade'        => 'upgrade',
        'uninstall'      => 'uninstall',
    ];

    public protected(set) array $command_descriptions = [
        'languages:list'           => 'List all enabled languages',
        'languages:list:all'       => 'List all languages',
        'languages:list:available' => 'List all available languages',
        'language:install'         => 'Installs a language',
        'language:upgrade'         => 'Upgrades a language',
        'language:uninstall'       => 'Uninstalls a language'
    ];

    public protected(set) array $command_help = [
        'language:install' => 'Usage: language:install <language_name> [<language_name> ...]',
        'language:upgrade' => 'Usage: language:upgrade <language_name> [<language_name> ...]',
        'language:uninstall'  => 'Usage: language:uninstall <language_name> [<language_name> ...]'
    ];

    protected array $names = [
        'extension' => 'Language',
        'extension_lower' => 'language',
        'extensions' => 'Languages',
        'extensions_lower' => 'languages'
    ];

    protected array $usage = [
        'extension' => 'language',
    ];

    protected BaseExtensions $manager {
        get => $this->app->lang->manager;
    }
}
