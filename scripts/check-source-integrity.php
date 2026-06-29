<?php

declare(strict_types=1);

/**
 * Source integrity checker for intisari-cms.
 *
 * Verifies that every PHP file outside vendor/:
 *  1. Starts with <?php
 *  2. Declares strict_types=1
 *  3. Does NOT use the Intisari\ namespace (reserved for core framework)
 */

$root      = dirname(__DIR__);
$exitCode  = 0;
$scanned   = 0;
$errors    = [];

$excludeDirs = [
    $root . DIRECTORY_SEPARATOR . 'vendor',
];

/**
 * @return Generator<string>
 */
function phpFiles(string $dir): Generator
{
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));

    foreach ($it as $file) {
        /** @var SplFileInfo $file */
        if ($file->isFile() && $file->getExtension() === 'php') {
            yield $file->getRealPath();
        }
    }
}

foreach (phpFiles($root) as $path) {
    // Skip vendor
    $skip = false;
    foreach ($excludeDirs as $excluded) {
        if (str_starts_with($path, $excluded)) {
            $skip = true;
            break;
        }
    }

    if ($skip) {
        continue;
    }

    $scanned++;
    $content = file_get_contents($path);

    if ($content === false) {
        $errors[] = "[UNREADABLE] {$path}";
        continue;
    }

    // Rule 1: must start with <?php
    if (!str_starts_with(ltrim($content), '<?php')) {
        $errors[] = "[NO_OPEN_TAG] {$path}";
    }

    // Rule 2: must declare strict_types=1
    if (!str_contains($content, 'declare(strict_types=1)')) {
        $errors[] = "[NO_STRICT] {$path}";
    }

    // Rule 3: must not use Intisari\ namespace (reserved for framework core)
    if (preg_match('/^namespace\s+Intisari\\\\/m', $content)) {
        $errors[] = "[INTISARI_NS] {$path}";
    }
}

if ($errors === []) {
    echo "✓ Source integrity OK ({$scanned} files checked).\n";
    exit(0);
}

echo "✗ Source integrity FAILED ({$scanned} files checked, " . count($errors) . " error(s)):\n";
foreach ($errors as $err) {
    echo "  {$err}\n";
}

exit(1);
