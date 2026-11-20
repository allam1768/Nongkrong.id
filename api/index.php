<?php

/**
 * Entry point for Vercel Serverless PHP Runtime.
 *
 * Prepares writable /tmp directory for cache, views, logs, sessions, and bootstrap cache
 * because the root filesystem on Vercel is strictly read-only.
 */

$storagePath = '/tmp/storage';

$dirs = [
    $storagePath,
    $storagePath . '/app',
    $storagePath . '/app/public',
    $storagePath . '/framework',
    $storagePath . '/framework/cache',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
    $storagePath . '/bootstrap',
    $storagePath . '/bootstrap/cache',
];

foreach ($dirs as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Copy pre-existing bootstrap cache files if any
$originCache = __DIR__ . '/../bootstrap/cache';
if (is_dir($originCache)) {
    foreach (scandir($originCache) as $file) {
        if ($file !== '.' && $file !== '..' && $file !== '.gitignore') {
            @copy($originCache . '/' . $file, $storagePath . '/bootstrap/cache/' . $file);
        }
    }
}

putenv("LARAVEL_STORAGE_PATH={$storagePath}");
$_ENV['LARAVEL_STORAGE_PATH'] = $storagePath;
$_SERVER['LARAVEL_STORAGE_PATH'] = $storagePath;

putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
$_ENV['VIEW_COMPILED_PATH'] = "{$storagePath}/framework/views";
$_SERVER['VIEW_COMPILED_PATH'] = "{$storagePath}/framework/views";

require __DIR__ . '/../public/index.php';
