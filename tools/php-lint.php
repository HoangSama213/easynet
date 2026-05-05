<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$excludedDirectories = [
    $root . DIRECTORY_SEPARATOR . 'vendor',
    $root . DIRECTORY_SEPARATOR . '.git',
    $root . DIRECTORY_SEPARATOR . '.phpunit.cache',
];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
);

$files = [];
foreach ($iterator as $file) {
    if (!$file->isFile() || strtolower($file->getExtension()) !== 'php') {
        continue;
    }

    $path = $file->getPathname();
    $skip = false;
    foreach ($excludedDirectories as $excludedDirectory) {
        if (str_starts_with($path, $excludedDirectory)) {
            $skip = true;
            break;
        }
    }

    if ($skip) {
        continue;
    }

    $files[] = $path;
}

sort($files);

$failures = [];
foreach ($files as $file) {
    $command = escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($file) . ' 2>&1';
    exec($command, $output, $exitCode);

    if ($exitCode !== 0) {
        $failures[] = [
            'file' => $file,
            'output' => implode(PHP_EOL, $output),
        ];
    }
}

if ($failures === []) {
    fwrite(STDOUT, 'OK: ' . count($files) . ' file PHP hợp lệ.' . PHP_EOL);
    exit(0);
}

foreach ($failures as $failure) {
    fwrite(STDERR, '[LINT ERROR] ' . $failure['file'] . PHP_EOL);
    fwrite(STDERR, $failure['output'] . PHP_EOL . PHP_EOL);
}

exit(1);
