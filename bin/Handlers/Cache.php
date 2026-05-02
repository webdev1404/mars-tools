<?php

namespace Mars\Bin\Handlers;

use Mars\Bin\Base;

class Cache extends Base
{
    public protected(set) string $title = 'Clean Cache';

    public protected(set) array $roots = ['cache'];

    public protected(set) array $commands = [
        'cache:clean'           => 'cleanAll',
        'cache:clean:all'       => 'cleanAll',
        'cache:clean:config'    => 'cleanConfig',
        'cache:clean:css'       => 'cleanCss',
        'cache:clean:js'        => 'cleanJs',
        'cache:clean:data'      => 'cleanData',
        'cache:clean:pages'     => 'cleanPages',
        'cache:clean:routes'    => 'cleanRoutes',
        'cache:clean:storage'   => 'cleanStorage',
        'cache:clean:storage:all'   => 'cleanStorageAll',
        'cache:clean:templates' => 'cleanTemplates',
    ];
    
    public protected(set) array $command_descriptions = [
        'cache:clean'           => 'Cleans all caches',
        'cache:clean:all'       => 'Cleans all caches',
        'cache:clean:config'    => 'Cleans the config cache',
        'cache:clean:css'       => 'Cleans the CSS cache',
        'cache:clean:js'        => 'Cleans the JavaScript cache',
        'cache:clean:data'      => 'Cleans the data cache',
        'cache:clean:pages'     => 'Cleans the page cache',
        'cache:clean:routes'    => 'Cleans the route cache',
        'cache:clean:storage'   => 'Cleans the expired storage cache',
        'cache:clean:storage:all'   => 'Cleans all storage caches, expired or not',
        'cache:clean:templates' => 'Cleans the template cache',
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
        $this->cleanPages();
        $this->cleanRoutes();
        $this->cleanTemplates();
        $this->cleanStorage();
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
     * Cleans the Pages cache
     */
    public function cleanPages()
    {
        $this->doing('Cleaning the Pages cache...');
        $this->app->cache->pages->clean();
    }

    /**
     * Cleans the Routes cache
     */
    public function cleanRoutes()
    {
        $this->doing('Cleaning the Routes cache...');
        $this->app->cache->routes->clean();
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
}
