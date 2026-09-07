<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$composer = json_decode(file_get_contents($root . '/composer.json'), true, 512, JSON_THROW_ON_ERROR);
$namespace = array_key_first($composer['autoload']['psr-4']);
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/src'));
foreach ($files as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }
    $bytes = file_get_contents($file->getPathname());
    foreach (token_get_all($bytes) as $token) {
        if (!is_array($token)) {
            continue;
        }
        if (in_array($token[0], [T_NAME_QUALIFIED, T_NAME_FULLY_QUALIFIED], true)) {
            $name = ltrim($token[1], '\\');
            foreach (['Kumwe\\App\\', 'Kumwe\\Extension\\', 'Doctrine\\', 'Symfony\\'] as $forbidden) {
                if (str_starts_with($name, $forbidden)) {
                    throw new RuntimeException('Forbidden historical/host dependency: ' . $name);
                }
            }
        }
        if ($token[0] === T_EVAL || ($token[0] === T_STRING && strtolower($token[1]) === 'class_alias')) {
            throw new RuntimeException('Runtime aliases or executable fallback are forbidden.');
        }
    }
}
$expected = json_decode(
    file_get_contents($root . '/resources/test-ownership/v1.json'),
    true,
    512,
    JSON_THROW_ON_ERROR,
);
$actual = [];
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/tests'));
foreach ($files as $file) {
    if (!$file->isFile() || !str_ends_with($file->getFilename(), 'Test.php')) {
        continue;
    }
    preg_match_all('/public function (test\w+)\(/', file_get_contents($file->getPathname()), $methods);
    $actual[] = [
        'path' => substr($file->getPathname(), strlen($root) + 1),
        'methods' => $methods[1],
        'implementation_owner' => $composer['name'],
    ];
}
usort($actual, static fn (array $left, array $right): int => strcmp($left['path'], $right['path']));
$declared = [];
foreach ($expected['tests'] as $test) {
    $declared[] = [
        'path' => $test['path'],
        'methods' => $test['methods'],
        'implementation_owner' => $test['implementation_owner'],
    ];
}
if ($actual === [] || $actual !== $declared) {
    throw new RuntimeException('Test ownership drift: review the exact discovered methods and ownership manifest.');
}
echo count($actual) . " package test files and runtime ownership verified.\n";
