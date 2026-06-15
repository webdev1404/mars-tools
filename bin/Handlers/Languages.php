<?php

namespace Mars\Bin\Handlers;

use Mars\Bin\Extensions;
use Mars\Extensions\Extensions as BaseExtensions;

class Languages extends Extensions
{
    public protected(set) string $title = 'Manage Languages';

    public protected(set) string $root = 'languages';

    public protected(set) array $commands = [
        'list'           => 'listEnabled',
        'list:all'       => 'listAll',
        'list:available' => 'listAvailable',
        'install'         => 'install',
        'upgrade'         => 'upgrade',
        'uninstall'       => 'uninstall',
    ];

    public protected(set) array $command_descriptions = [
        'list'           => 'List all enabled languages',
        'list:all'       => 'List all languages',
        'list:available' => 'List all available languages',
        'install'         => 'Installs a language',
        'upgrade'         => 'Upgrades a language',
        'uninstall'       => 'Uninstalls a language'
    ];

    public protected(set) array $command_help = [
        'install' => 'Usage: install [--force] <language_name> [<language_name> ...]',
        'upgrade' => 'Usage: upgrade [--force] <language_name> [<language_name> ...]',
        'uninstall'  => 'Usage: uninstall [--force] <language_name> [<language_name> ...]'
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

    protected array $actions_list = [
        'install' => 'all',
        'upgrade' => 'all',
        'uninstall' => 'all',
    ];

    protected BaseExtensions $manager {
        get => $this->app->lang->manager;
    }
}
