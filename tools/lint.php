<?php

declare(strict_types=1);

$root = dirname(__DIR__);
foreach (['src', 'tests', 'tools', 'examples'] as $folder) {
    if (!is_dir($root . '/' . $folder)) {
        continue;
    }
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/' . $folder));
    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            passthru(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($file->getPathname()), $status);
            if ($status !== 0) {
                exit($status);
            }
        }
    }
}
