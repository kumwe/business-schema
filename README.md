# business-schema

Portable physical schema blueprints, deterministic change plans and recovery values.

Canonical namespace: `Kumwe\BusinessSchema`. Requires PHP 8.5, 64-bit. Version 0.1.0 is recorded for automatic publication after human rebase merge and the complete package gate. App adoption is a separate integration change.

Values are constructed directly. Register Kumwe\BusinessSchema\ConfigProvider explicitly with your ConfigAggregator. Its factories provide shared compiler and planner services. The host binds DefinitionSchemaLookup, FieldTypeDefinitionResolver and PhysicalNameCompiler; there are no implicit authority or naming defaults. See resources/service-map/v1.json and the runnable examples/consumer.php. The example consumer declares Laminas ServiceManager as its host container dependency.

See [public API](docs/public-api.md), [architecture](docs/architecture.md), [integration](docs/integration.md) and [test ownership](docs/test-ownership.md).

For candidate source verification, follow `docs/integration.md`. Run `composer check` after installing the explicit candidate toolchain. `composer clean-consumer` verifies the archive in a fresh no-dev classmap-authoritative consumer. License: Apache-2.0.
