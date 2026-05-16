<?php

$base_path = dirname(__DIR__, 2) . '/mars-framework/src/';

//a SIGSEGV - core dumped is triggered, for some reason, if the App/Kernel.php file is preloaded, so we exclude it from the preload list.
$exclude_files = [
    'App/Kernel.php',
];

$files = [];
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base_path));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() == 'php') {
        $rel_path = str_replace($base_path, '', $file->getPathname());
        if (in_array($rel_path, $exclude_files)) {
            continue;
        }

        $files[] = $file->getPathname();
    }
}

foreach ($files as $file) {
    opcache_compile_file($file);
}
