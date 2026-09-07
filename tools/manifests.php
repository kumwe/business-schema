<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';
$root = dirname(__DIR__);
$composer = json_decode(file_get_contents($root . '/composer.json'), true, 512, JSON_THROW_ON_ERROR);
$prefix = array_key_first($composer['autoload']['psr-4']);
$symbols = [];
$docs = "# Public API\n\n"
    . "Constructor invariants, serialization, exceptions and method contracts follow. "
    . "Values perform no I/O; host inputs must remain stable through each operation.\n\n";
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
            $params[] = ['name' => $param->getName(),
                 'type' => (string) $param->getType(),
                 'optional' => $param->isOptional(),
                 'variadic' => $param->isVariadic(),
                 'by_reference' => $param->isPassedByReference()];
        }
        $members[$method->getName()] = ['visibility' => 'public',
             'static' => $method->isStatic(),
             'parameters' => $params,
             'return' => (string) $method->getReturnType()];
        $docs .= '### ' . $method->getName() . "\n\n"
            . ($method->getDocComment() ?: 'Generated enum/runtime member.') . "\n\n";
    }
    $properties = [];
    foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
        $properties[$property->getName()] = ['static' => $property->isStatic(),
             'type' => (string) $property->getType(),
             'readonly' => $property->isReadOnly()];
    }
    $constants = [];
    foreach ($class->getReflectionConstants() as $constant) {
        if ($constant->isPublic()) {
            $constants[$constant->getName()] = ['type' => $constant->hasType() ? (string) $constant->getType() : null];
        }
    }
    $symbols[$name] = ['stability' => 'stable',
         'file' => 'src/' . $relative . '.php',
         'abstract' => $class->isAbstract(),
         'final' => $class->isFinal(),
         'readonly' => $class->isReadOnly(),
         'parent' => ($class->getParentClass() ?: null)?->getName(),
         'interfaces' => $class->getInterfaceNames(),
         'deprecated' => null,
         'kind' => $class->isEnum() ? 'enum' : ($class->isInterface() ? 'interface' : 'class'),
         'methods' => (object) $members,
         'properties' => (object) $properties,
         'constants' => (object) $constants];
}
ksort($symbols, SORT_STRING);
preg_match('/^##\s+([0-9]+\.[0-9]+\.[0-9]+)\b/m', file_get_contents($root . '/CHANGELOG.md'), $release);
$manifest = ['schema' => 'kumwe-package-public-api/v1',
     'package' => $composer['name'],
     'release' => $release[1] ?? null,
     'namespace' => $prefix,
     'symbols' => (object) $symbols,
     'extension_points' => array_keys(array_filter(
         $symbols,
         static fn (array $symbol): bool => $symbol['kind'] === 'interface',
     )),
     'digest_of' => 'src'];
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
