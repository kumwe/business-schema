<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';
$root = dirname(__DIR__);
$composer = json_decode(file_get_contents($root . '/composer.json'), true, 512, JSON_THROW_ON_ERROR);
$prefix = array_key_first($composer['autoload']['psr-4']);
$symbols = [];
$docs = "# Public API\n\nAll values enforce the documented constructor invariants. Domain methods perform no I/O, own no transaction and make no authorization decisions. Immutable values are safe to share; host inputs and lookup ports must remain generation-stable for the duration of an operation. Exceptions and parameter detail appear below verbatim from the source contract.\n\n";
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/src'));
foreach ($files as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }
    $bytes = file_get_contents($file->getPathname());
    if (preg_match('/(?:use|extends|implements) +Kumwe\\\\(?:App|Extension)\\\\/', $bytes)) {
        throw new RuntimeException('Historical owner dependency: ' . $file->getPathname());
    }
    $relative = substr($file->getPathname(), strlen($root . '/src/'), -4);
    $name = $prefix . str_replace('/', '\\', $relative);
    $class = new ReflectionClass($name);
    if (str_contains($class->getDocComment() ?: '', '@internal')) {
        continue;
    }
    $members = [];
    $docs .= '## ' . $name . "\n\n" . ($class->getDocComment() ?: '') . "\n\n";
    foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
        if ($method->getDeclaringClass()->getName() !== $name) {
            continue;
        }
        $params = [];
        foreach ($method->getParameters() as $param) {
            $params[] = ['name' => $param->getName(), 'type' => (string) $param->getType(), 'optional' => $param->isOptional(), 'variadic' => $param->isVariadic(), 'reference' => $param->isPassedByReference()];
        }
        $members[] = ['name' => $method->getName(), 'static' => $method->isStatic(), 'parameters' => $params, 'return' => (string) $method->getReturnType()];
        $docs .= '### ' . $method->getName() . "\n\n" . ($method->getDocComment() ?: 'Generated enum/runtime member.') . "\n\n";
    }
    $properties = [];
    foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
        $properties[] = ['name' => $property->getName(), 'type' => (string) $property->getType(), 'readonly' => $property->isReadOnly()];
    }
    $constants = [];
    foreach ($class->getReflectionConstants() as $constant) {
        if ($constant->isPublic()) {
            $constants[] = $constant->getName();
        }
    }
    $symbols[] = ['name' => $name, 'kind' => $class->isEnum() ? 'enum' : ($class->isInterface() ? 'interface' : 'class'), 'methods' => $members, 'properties' => $properties, 'constants' => $constants];
}
usort($symbols, static fn ($a, $b) => strcmp($a['name'], $b['name']));
$manifest = ['schema' => 'kumwe-public-api/v1', 'package' => $composer['name'], 'release' => null, 'namespace' => $prefix, 'symbols' => $symbols];
$encoded = json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n";
$file = $root . '/resources/public-api/v1.json';
if (in_array('--write', $argv, true)) {
    if (!is_dir(dirname($file))) {
        mkdir(dirname($file), 0777, true);
    }
    file_put_contents($file, $encoded);
    file_put_contents($root . '/docs/public-api.md', $docs);
} elseif (!is_file($file) || file_get_contents($file) !== $encoded) {
    throw new RuntimeException('Public API drift: review and regenerate with --write.');
}
echo count($symbols) . " public symbols verified.\n";
