<?php

/** Validate the package's released governance contract without loading an App checkout. */

declare(strict_types=1);

$root = dirname(__DIR__);
$read = static function (string $path) use ($root): array {
    return json_decode(file_get_contents($root . '/' . $path), true, 512, JSON_THROW_ON_ERROR);
};
$composer = $read('composer.json');
$api = $read('resources/public-api/v1.json');
$capabilities = $read('resources/capabilities/v1.json');
$services = $read('resources/service-map/v1.json');
$require = static function (bool $condition, string $message): void {
    if (!$condition) {
        throw new RuntimeException('Governance contract: ' . $message);
    }
};
$keys = static function (array $value, array $expected) use ($require): void {
    $actual = array_keys($value);
    sort($actual);
    sort($expected);
    $require($actual === $expected, 'unexpected or missing keys: ' . implode(', ', $actual));
};
$keys($api, ['schema', 'package', 'release', 'namespace', 'symbols', 'extension_points', 'digest_of']);
$keys($capabilities, [
    'schema', 'package', 'release', 'namespace', 'responsibility', 'non_responsibilities',
    'capabilities', 'native_requirements', 'deprecations',
]);
$keys($services, [
    'schema', 'package', 'release', 'config_provider', 'provider_absence_reason', 'factories',
    'aliases', 'delegators', 'configuration_keys',
]);
foreach (['public-api' => $api, 'capabilities' => $capabilities, 'service-map' => $services] as $kind => $data) {
    $require($data['schema'] === 'kumwe-package-' . $kind . '/v1', $kind . ' schema identity');
    $require($data['package'] === $composer['name'], $kind . ' package identity');
    $require($data['release'] === $api['release'], $kind . ' release identity');
}
$require(preg_match('/^[0-9]+\.[0-9]+\.[0-9]+$/D', $api['release']) === 1, 'stable release required');
$namespace = array_key_first($composer['autoload']['psr-4']);
$require($api['namespace'] === $namespace && $capabilities['namespace'] === $namespace, 'canonical namespace');
$require(!array_is_list($api['symbols']), 'public symbols must be keyed by canonical FQCN');
foreach ($api['symbols'] as $name => $entry) {
    $keys($entry, [
        'kind', 'stability', 'file', 'abstract', 'final', 'readonly', 'parent', 'interfaces',
        'constants', 'properties', 'methods', 'deprecated',
    ]);
    $require(str_starts_with($name, $namespace), 'public symbol outside canonical namespace');
    $require(is_file($root . '/' . $entry['file']), 'public source path missing');
    foreach ($entry['methods'] as $method) {
        $keys($method, ['visibility', 'static', 'parameters', 'return']);
        foreach ($method['parameters'] as $parameter) {
            $keys($parameter, ['name', 'type', 'optional', 'variadic', 'by_reference']);
        }
    }
}
$covered = [];
$ids = [];
foreach ($capabilities['capabilities'] as $capability) {
    $keys($capability, ['id', 'title', 'description', 'symbols', 'documentation']);
    $require(!isset($ids[$capability['id']]), 'duplicate capability');
    $ids[$capability['id']] = true;
    foreach ($capability['symbols'] as $symbol) {
        $require(isset($api['symbols'][$symbol]), 'capability names an unexported symbol');
        $covered[$symbol] = true;
    }
    foreach ($capability['documentation'] as $path) {
        $require(is_file($root . '/' . $path), 'capability documentation missing');
    }
}
$require(array_diff_key($api['symbols'], $covered) === [], 'public symbols lack semantic ownership');
if ($services['config_provider'] === null) {
    $require(is_string($services['provider_absence_reason']), 'absent provider needs a reason');
    $require($services['factories'] === [], 'absent provider cannot register factories');
} else {
    $require(isset($api['symbols'][$services['config_provider']]), 'provider is not exported');
}
foreach ($services['factories'] as $factory) {
    $keys($factory, ['service', 'factory', 'lifetime']);
    $require(isset($api['symbols'][$factory['factory']]), 'factory is not exported');
    $require(in_array($factory['lifetime'], ['shared', 'non-shared', 'request-supplied'], true), 'service lifetime');
}
$record = file_get_contents($root . '/docs/release-record.md');
$require(str_starts_with($record, "---\n"), 'release record must start with YAML front matter');
$parts = explode("\n---\n", $record, 2);
$require(count($parts) === 2, 'release record front matter closing fence');
$require(str_contains($parts[0], 'schema: "kumwe-package-release-record/v1"'), 'release record schema');
$require(str_contains($parts[0], 'composer_package: "' . $composer['name'] . '"'), 'release record identity');
foreach (
    [
    'Package contract', 'Public API and responsibility', 'Dependencies and semantic inputs',
    'Consumer contract', 'Test ownership', 'Consumer verification', 'Compatibility and drift',
    'Validation',
    ] as $section
) {
    $require(preg_match('/^##\s+(?:[0-9]+\.\s+)?' . preg_quote($section, '/') . '\s*$/m', $parts[1]) === 1, $section);
}
preg_match_all('/path: "([^"]+)"\n\s+sha256: "([a-f0-9]{64})"/', $parts[0], $digests, PREG_SET_ORDER);
$observed = [];
foreach ($digests as $digest) {
    $require(is_file($root . '/' . $digest[1]), 'release record manifest path missing');
    $require(hash_file('sha256', $root . '/' . $digest[1]) === $digest[2], 'Record digest drift: ' . $digest[1]);
    $observed[$digest[1]] = true;
}
foreach (['public-api', 'capabilities', 'service-map'] as $kind) {
    $require(isset($observed['resources/' . $kind . '/v1.json']), 'release record missing manifest digest');
}
echo "Governance identities, API shape, semantic ownership, service declarations and record digests verified.\n";
