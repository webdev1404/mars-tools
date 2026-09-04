<?php

namespace Mars\Bin\Handlers;

use Mars\Bin\Base;

class Cache extends Base
{
    public protected(set) string $title = 'Clean Cache';

    public protected(set) string $root = 'cache';

    public protected(set) array $commands = [
        'clean'           => 'cleanAll',
        'clean:all'       => 'cleanAll',
        'clean:config'    => 'cleanConfig',
        'clean:css'       => 'cleanCss',
        'clean:js'        => 'cleanJs',
        'clean:data'      => 'cleanData',
        'clean:languages' => 'cleanLanguages',
        'clean:modules'   => 'cleanModules',
        'clean:html'     => 'cleanHtml',
        'clean:plugins'   => 'cleanPlugins',
        'clean:routes'    => 'cleanRoutes',
        'clean:storage'   => 'cleanStorage',
        'clean:storage:all'   => 'cleanStorageAll',
        'clean:templates' => 'cleanTemplates',
        'clean:themes'    => 'cleanThemes',
    ];
    
    public protected(set) array $command_descriptions = [
        'clean'           => 'Cleans all caches',
        'clean:all'       => 'Cleans all caches',
        'clean:config'    => 'Cleans the config cache',
        'clean:css'       => 'Cleans the CSS cache',
        'clean:js'        => 'Cleans the JavaScript cache',
        'clean:data'      => 'Cleans the data cache',
        'clean:languages' => 'Cleans the languages cache',
        'clean:modules'   => 'Cleans the modules cache',
        'clean:html'     => 'Cleans the html cache',
        'clean:plugins'   => 'Cleans the plugins cache',
        'clean:routes'    => 'Cleans the route cache',
        'clean:storage'   => 'Cleans the expired storage cache',
        'clean:storage:all'   => 'Cleans all storage caches, expired or not',
        'clean:templates' => 'Cleans the template cache',
        'clean:themes'    => 'Cleans the themes cache',
    ];

    public protected(set) array $command_help = [
        'clean'   => 'Usage: clean [--no-rebuild]',
        'clean:all'   => 'Usage: clean:all [--no-rebuild]',
        'clean:modules'   => 'Usage: clean:modules [--no-rebuild]',
        'clean:plugins'   => 'Usage: clean:plugins [--no-rebuild]',
        'clean:routes'   => 'Usage: clean:routes [--no-rebuild]',
        'clean:themes'   => 'Usage: clean:themes [--no-rebuild]',
    ];

    /**
     * @internal
     */
    protected bool $show_done = true;

    /**
     * Cleans all the caches
     */
    public function cleanAll()
    {
        $this->show_done = false;

        $this->cleanConfig();
        $this->cleanCss();
        $this->cleanJs();
        $this->cleanData();
        $this->cleanLanguages();
        $this->cleanModules();
        $this->cleanHtml();
        $this->cleanPlugins();
        $this->cleanRoutes();
        $this->cleanStorage();
        $this->cleanTemplates();
        $this->cleanThemes();
        $this->done();
    }

    /**
     * Cleans the Config cache
     */
    public function cleanConfig()
    {
        $this->doing('Cleaning the Config cache...');
        $this->app->cache->config->clean();
    }

    /**
     * Cleans the Css cache
     */
    public function cleanCss()
    {
        $this->doing('Cleaning the CSS cache...');
        $this->app->cache->css_list->clean();
        $this->app->cache->css->clean();
    }

    /**
     * Cleans the Javascript cache
     */
    public function cleanJs()
    {
        $this->doing('Cleaning the JavaScript cache...');
        $this->app->cache->js_list->clean();
        $this->app->cache->js->clean();
    }

    /**
     * Cleans the Data cache
     */
    public function cleanData()
    {
        $this->doing('Cleaning the Data cache...');
        $this->app->cache->data->clean();
    }

    /**
     * Cleans the Modules cache
     */
    public function cleanModules()
    {
        $this->doing('Cleaning the Modules cache...');
        $this->app->cache->modules->clean();

        if (!$this->app->cli->has('no-rebuild')) {
            $this->app->cache->modules->cache();
        }
    }

    /**
     * Cleans the Languages cache
     */
    public function cleanLanguages()
    {
        $this->doing('Cleaning the Languages cache...');
        $this->app->cache->languages->clean();

        if (!$this->app->cli->has('no-rebuild')) {
            $this->app->cache->languages->cache();
        }
    }

    /**
     * Cleans the HTML cache
     */
    public function cleanHtml()
    {
        $this->doing('Cleaning the HTML cache...');
        $this->app->cache->html->clean();
    }

    /**
     * Cleans the Plugins cache
     */
    public function cleanPlugins()
    {
        $this->doing('Cleaning the Plugins cache...');
        $this->app->cache->plugins->clean();

        if (!$this->app->cli->has('no-rebuild')) {
            $this->app->cache->plugins->cache();
        }
    }

    /**
     * Cleans the Routes cache
     */
    public function cleanRoutes()
    {
        $this->doing('Cleaning the Routes cache...');
        $this->app->cache->routes->clean();

        if (!$this->app->cli->has('no-rebuild')) {
            $this->app->cache->routes->cache();
        }
    }

    /**
     * Cleans the Storage cache
     */
    public function cleanStorage()
    {
        $this->doing('Cleaning the Storage cache...');
        $this->app->cache->storage->clean();
    }

    public function cleanStorageAll()
    {
        $this->doing('Cleaning Storage caches (all)...');
        $this->app->cache->storage->cleanAll();
    }

    /**
     * Cleans the Templates cache
     */
    public function cleanTemplates()
    {
        $this->doing('Cleaning the Templates cache...');
        $this->app->cache->templates->clean();
    }

    /**
     * Cleans the Themes cache
     */
    public function cleanThemes()
    {
        $this->doing('Cleaning the Themes cache...');
        $this->app->cache->themes->clean();
        
        if (!$this->app->cli->has('no-rebuild')) {
            $this->app->cache->themes->cache();
        }
    }
}
