<?php

use Mars\App;

$app = App::obj();
if (!$app->is_cli) {
    die("The setup script must be run as a CLI application\n");
}

$app->cli->printLn();
$app->cli->print('*********************************', 'important');
$app->cli->print('Welcome to the Mars setup script!', 'important');
$app->cli->print('*********************************', 'important');
$app->cli->printLn();

$app->cli->notice('This script will guide you through the initial setup of your Mars application.');
$app->cli->notice('Please provide the following information (leave blank to skip an option):');
$app->cli->printLn();


//setup_config($app);
//setup_simlinks($app);
//setup_theme($app);
setup_language($app);

$app->cli->printLn();
$app->cli->success('Setup complete!');
$app->cli->printLn();


/**
 * Set up the config file with user input
 */
function setup_config(App $app) 
{
    $config_file = $app->base_path . '/config/config.php';

    if (!is_readable($config_file)) {
        $app->cli->error("The config file is not readable: $config_file");
    }
    if (!is_writeable($config_file)) {
        $app->cli->error("The config file is not writable: $config_file");
    }

    $config_array = [
        'url.base' => 'Please enter the base URL',
        'site.name' => 'Please enter the site name',
        'site.emails' => ['Please enter the site email(s) (comma separated)', true]
    ];

    $process_array = [
        'url.base' => fn($value) => rtrim($value, '/'),
        'site.emails' => fn($value) => array_map('trim', explode(',', $value))
    ];

    $config_data = [];
    foreach ($config_array as $key => $prompt) {
        $is_array = false;
        if (is_array($prompt)) {
            $is_array = true;
            $prompt = $prompt[0];
        }

        $value = $app->cli->ask($prompt . ': ');
        if (!$value) {
            continue;
        }

        if (isset($process_array[$key])) {
            $value = $process_array[$key]($value);
        }

        $config_data[] = [$key, get_config_preg($key, $is_array), $value];
    }

    write_config($config_file,$config_data);

    $app->cache->config->clean();
}

/**
 * Write the config data to the config file
 * @param string $config_file The path to the config file
 * @param array $config_data An array of config data to write, where each item is
 */
function write_config(string $config_file, array $config_data) 
{
    $config_contents = file_get_contents($config_file);

    foreach ($config_data as [$key, $preg, $value]) {
        $config_contents = preg_replace_callback($preg, function($matches) use ($key, $value) {
            $start = '';
            $end = '';
            if (is_array($value)) {
                $start = '[';
                $end = ']';
            }

            return "'" . $key . "' => " . $start . get_config_value($value) . $end . ",";
        }, $config_contents);
    }

    file_put_contents($config_file, $config_contents);
}

/**
 * Get the regex pattern for a config key
 * @param string $key The config key (e.g. 'url.base')
 * @param bool $is_array Whether the config value is an array
 * @return string The regex pattern to match the config key and value
 */
function get_config_preg(string $key, bool $is_array) : string
{
    $key = preg_quote($key);
    $preg = '';

    if ($is_array) {
        return "/(['\"]){$key}\\1\s*=>\s*(\[)(.*)(\])\s*,/sU";
    } else {
        return "/(['\"]){$key}\\1\s*=>\s*(['\"])(.*)(\\2)\s*,/sU";
    }
}

/**
 * Get the config value as a string or array
 * @param string|array $value The config value
 * @return string|array The config value as a string or array
 */
function get_config_value(string|array $value) : string|array
{
    if (is_array($value)) {
        $values = array_map('add_config_slashes', $value);
        $values = array_map(function($value) { return "'{$value}'"; }, $values);

        return implode(', ', $values);

    } else {
        return "'" . add_config_slashes($value) . "'";
    }
}

/**
 * Add slashes to a config value
 * @param string $value The config value
 * @return string The config value with slashes added
 */
function add_config_slashes(string $value) : string
{
    return str_replace("'", "\\'", $value);
}

/**
 * Setup symbolic links for the required directories
 * @param App $app The application instance
 */
function setup_simlinks(App $app) 
{
    $app->cli->message('Setting up symbolic links...');

    $symlinks = [    
        'vendor/webdev1404/mars-framework/assets' => 'public/assets/framework',
        'app/assets' => 'public/assets/app',
        'data/cache/css' => 'public/assets/cache/css',
        'data/cache/js' => 'public/assets/cache/js'
    ];
    foreach ($symlinks as $target => $link) {
        $target = $app->base_path . '/' . $target;
        $link = $app->base_path . '/' . $link;

        if (is_link($link)) {
            continue;
        }

        if (!is_dir($link)) {
            symlink($target, $link);
        }
    } 
}

/**
 * Setup the theme
 * @param App $app The application instance
 */
function setup_theme(App $app) 
{
    $app->cli->message('Setting up theme...');

    $themes = new \Mars\Extensions\Themes\Themes($app);
    $themes->install($app->config->theme->name);
}

/**
 * Setup the language
 * @param App $app The application instance
 */
function setup_language(App $app) 
{
    $app->cli->message('Setting up language...');

    $languages = new \Mars\Extensions\Languages\Languages($app);
    $languages->install($app->config->language->name);
}