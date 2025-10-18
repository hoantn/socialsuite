<?php
/**
 * Scan the repository for [SOCIALSUITE][GPT] tags and print a simple report.
 * Usage:
 *   php scripts/verify_gpt_tags.php [--root=/path/to/repo] [--ext=php,js,ts,vue] [--ignore=vendor,node_modules,storage,.git]
 */

$argvOpts = [
    'root:'   => '/',
    'ext:'    => 'php,js,ts,vue,blade.php,css,scss,md',
    'ignore:' => 'vendor,node_modules,storage,.git,patches',
];

foreach ($argv as $arg) {
    if (strpos($arg, '--root=') === 0)   $argvOpts['root']   = substr($arg, 7);
    if (strpos($arg, '--ext=') === 0)    $argvOpts['ext']    = substr($arg, 6);
    if (strpos($arg, '--ignore=') === 0) $argvOpts['ignore'] = substr($arg, 9);
}

$root   = realpath($argvOpts['root']) ?: getcwd();
$exts   = array_map('trim', explode(',', $argvOpts['ext']));
$ignore = array_map('trim', explode(',', $argvOpts['ignore']));

$pattern = '/\[SOCIALSUITE\]\[GPT\]/';

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
$count = 0;

foreach ($rii as $file) {
    if (!$file->isFile()) continue;

    $path = $file->getPathname();
    $rel  = ltrim(str_replace($root, '', $path), DIRECTORY_SEPARATOR);

    // ignore directories
    foreach ($ignore as $ig) {
        if (strpos($rel, $ig . DIRECTORY_SEPARATOR) === 0 || $rel === $ig) {
            continue 2;
        }
    }

    // filter by extension
    $ok = false;
    foreach ($exts as $e) {
        if ($e === '') continue;
        if (str_ends_with($path, $e)) { $ok = true; break; }
    }
    if (!$ok) continue;

    $content = @file($path);
    if ($content === false) continue;

    foreach ($content as $i => $line) {
        if (preg_match($pattern, $line)) {
            $count++;
            echo $rel . ':' . ($i+1) . '  ' . trim($line) . PHP_EOL;
        }
    }
}

echo PHP_EOL . "Total tag hits: " . $count . PHP_EOL;