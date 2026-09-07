<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$composer = json_decode(file_get_contents($root . '/composer.json'), true, 512, JSON_THROW_ON_ERROR);
$temporary = sys_get_temp_dir() . '/kumwe-consumer-' . bin2hex(random_bytes(8));
mkdir($temporary, 0700, true);
$run = static function (array $arguments, string $directory): void {
    $command = implode(' ', array_map(escapeshellarg(...), $arguments));
    $previous = getcwd();
    chdir($directory);
    passthru($command, $status);
    chdir($previous);
    if ($status !== 0) {
        throw new RuntimeException('Clean consumer command failed: ' . $command);
    }
};
$run(['composer', 'archive', '--format=zip', '--dir=' . $temporary, '--file=package'], $root);
$zip = new ZipArchive();
if ($zip->open($temporary . '/package.zip') !== true) {
    throw new RuntimeException('Package archive is unavailable.');
}
foreach (range(0, $zip->numFiles - 1) as $index) {
    $name = $zip->getNameIndex($index);
    if (preg_match('~^(vendor/|tests/|tools/|\.github/|composer\.local\.)~', $name) === 1) {
        throw new RuntimeException('Development file leaked into package archive: ' . $name);
    }
}
foreach (['composer.json', 'resources/public-api/v1.json', 'MIGRATION-HANDOFF.md'] as $required) {
    if ($zip->locateName($required) === false) {
        throw new RuntimeException('Missing archive contract: ' . $required);
    }
}
$zip->close();
$package = $composer;
unset($package['require-dev'], $package['autoload-dev'], $package['scripts'], $package['repositories']);
$package['version'] = 'dev-extraction';
$package['dist'] = ['type' => 'zip', 'url' => 'file://' . $temporary . '/package.zip'];
$repositories = array_merge([['type' => 'package', 'package' => $package]], $composer['repositories'] ?? []);
$overlay = getenv('KUMWE_CANDIDATE_REPOSITORIES');
if (is_string($overlay) && $overlay !== '') {
    $configuration = json_decode(file_get_contents($overlay), true, 512, JSON_THROW_ON_ERROR);
    $repositories = array_merge([$repositories[0]], $configuration['repositories']);
}
$consumer = [
    'name' => 'kumwe-verification/consumer',
    'require' => array_merge($configuration['require'] ?? [], [$composer['name'] => 'dev-extraction']),
    'repositories' => $repositories,
    'minimum-stability' => 'dev',
    'prefer-stable' => true,
    'config' => ['allow-plugins' => false],
];
file_put_contents(
    $temporary . '/composer.json',
    json_encode($consumer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n",
);
$run(['composer', 'update', '--no-dev', '--classmap-authoritative', '--no-interaction', '--prefer-dist'], $temporary);
$run([PHP_BINARY, $root . '/examples/consumer.php', $temporary . '/vendor/autoload.php'], $temporary);
$installed = $temporary . '/vendor/' . $composer['name'];
$manifest = json_decode(
    file_get_contents($installed . '/resources/public-api/v1.json'),
    true,
    512,
    JSON_THROW_ON_ERROR
);
$check = <<<'CHECK'
<?php
$loader = require $argv[1];
$manifest = json_decode(file_get_contents($argv[2]), true, 512, JSON_THROW_ON_ERROR);
foreach ($manifest['symbols'] as $symbol) {
    $name = $symbol['name'];
    if (!class_exists($name) && !interface_exists($name) && !enum_exists($name)) {
        throw new RuntimeException('Unresolvable archive API: ' . $name);
    }
    $source = (new ReflectionClass($name))->getFileName();
    if (!str_starts_with($source, dirname($argv[2], 3) . '/src/')) {
        throw new RuntimeException('API resolved outside package archive: ' . $name);
    }
}
if (!$loader->isClassMapAuthoritative()) {
    throw new RuntimeException('Consumer autoload must be classmap authoritative.');
}
echo count($manifest['symbols']) . " archive symbols resolved without dev dependencies.\n";
CHECK;
file_put_contents($temporary . '/verify.php', $check);
$run([PHP_BINARY, $temporary . '/verify.php', $temporary . '/vendor/autoload.php',
    $installed . '/resources/public-api/v1.json'], $temporary);
echo 'Archive SHA-256: ' . hash_file('sha256', $temporary . '/package.zip') . "\n";
echo $overlay ? "Candidate dependency consumer passed; no release verification claimed.\n"
    : "Registry dependency consumer passed; immutable release attestation remains separate.\n";
