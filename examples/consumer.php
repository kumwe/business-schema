<?php

declare(strict_types=1);

require $argv[1] ?? dirname(__DIR__) . '/vendor/autoload.php';
$names = new \Kumwe\BusinessSchema\Domain\PhysicalNameCompiler('kb_');
$name = $names->entityTable('018f4f24-98d8-7ad4-8f3f-38c909178b6b', 'acme.invoice');
if (strlen($name) > 63) {
    throw new RuntimeException('Physical name overflow.');
}
$provider = (new \Kumwe\BusinessSchema\ConfigProvider())();
if (count($provider['dependencies']['factories']) !== 2) {
    throw new RuntimeException('Service map changed.');
}
echo 'Package consumer behavior passed.' . PHP_EOL;
