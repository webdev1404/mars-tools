<?php
// Utility which cleans the cache directory

$cache_dir = $base_path . '/data/cache';

$dirs_list = [
    'assets/css',
    'assets/js',
    'config',
    'data',
    'extensions/languages',
    'extensions/modules',
    'extensions/plugins',
    'extensions/themes',
    'pages',
    'routes',
    'storage',
    'templates',
];

foreach ($dirs_list as $dir) {
    $path = $cache_dir . '/' . $dir;

    if (is_dir($path)) {
        clean_dir($path);
    }
}


function clean_dir(string $path)
{
    echo "Cleaning directory: {$path}\n";

    $directory_iterator = new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS | \RecursiveDirectoryIterator::CURRENT_AS_SELF);
    $iterator = new \RecursiveIteratorIterator($directory_iterator, \RecursiveIteratorIterator::CHILD_FIRST);

    foreach ($iterator as $file) {
        if ($file->isDir()) {
            if (!rmdir($file->getPathname())) {
                die("Failed to delete directory: " . $file->getPathname());
            }
        } else {
            if (!unlink($file->getPathname())) {
                die("Failed to delete file: " . $file->getPathname());
            }
        }
    }
}
