<?php
/**
* The Config Setup Class
* @package Mars
*/

namespace Mars\Setup\Steps;

/**
* @package Mars
*/
class Config extends Base
{
    /**
     * The filename of the config file
     */
    protected string $filename;

    /**
     * The config keys and their prompts
     */
    protected array $options = [
        'url.base' => 'Please enter the base URL',
        'site.name' => 'Please enter the site name',
        'site.emails' => ['Please enter the site email(s) (comma separated)', true]
    ];

    /**
     * Returns the processing functions for specific config keys
     * @return array The processing functions
     */
    protected function getProcessArray(): array
    {
        return [
            'url.base' => fn($value) => rtrim($value, '/'),
            'site.emails' => fn($value) => array_map('trim', explode(',', $value))
        ];
    }

    /**
     * Run the config setup step
     */
    public function run()
    {
        $this->filename = $this->app->base_path . '/config/config.php';
        $this->check();

        $data = [];
        $process_array = $this->getProcessArray();

        foreach ($this->options as $key => $prompt) {
            $is_array = false;
            if (is_array($prompt)) {
                $is_array = true;
                $prompt = $prompt[0];
            }

            $value = $this->app->cli->ask($prompt);
            if (!$value) {
                continue;
            }

            if (isset($process_array[$key])) {
                $value = $process_array[$key]($value);
            }

            $data[] = [$key, $this->getPreg($key, $is_array), $value];
        }

        $this->write($data);

        $this->app->cache->config->clean();

        $this->app->cli->printLn();
        $this->app->cli->success("Config updated successfully");
    }

    /**
     * Check if the config file is readable and writable
     */
    protected function check()
    {
        if (!is_readable($this->filename)) {
            $this->app->cli->error("The config file is not readable: $this->filename");
        }
        if (!is_writeable($this->filename)) {
            $this->app->cli->error("The config file is not writable: $this->filename");
        }
    }

    /**
     * Write the config data to the config file
     * @param array $data The config data
     */
    protected function write(array $data) 
    {
        $contents = file_get_contents($this->filename);

        foreach ($data as [$key, $preg, $value]) {
            $contents = preg_replace_callback($preg, function($matches) use ($key, $value) {
                $start = '';
                $end = '';
                if (is_array($value)) {
                    $start = '[';
                    $end = ']';
                }

                return "'" . $key . "' => " . $start . $this->getValue($value) . $end . ",";
            }, $contents);
        }

        file_put_contents($this->filename, $contents);
    }

    /**
     * Get the regex pattern for a config key
     * @param string $key The config key (e.g. 'url.base')
     * @param bool $is_array Whether the config value is an array
     * @return string The regex pattern to match the config key and value
     */
    protected function getPreg(string $key, bool $is_array) : string
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
    protected function getValue(string|array $value) : string|array
    {
        if (is_array($value)) {
            $values = array_map([$this, 'addSlashes'], $value);
            $values = array_map(function($value) { return "'{$value}'"; }, $values);

            return implode(', ', $values);

        } else {
            return "'" . $this->addSlashes($value) . "'";
        }
    }

    /**
     * Add slashes to a config value
     * @param string $value The config value
     * @return string The config value with slashes added
     */
    protected function addSlashes(string $value) : string
    {
        return str_replace("'", "\\'", $value);
    }
}
