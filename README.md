# business-schema

Portable physical schema blueprints, deterministic change plans and recovery values.

Canonical namespace: `Kumwe\BusinessSchema`. Requires PHP 8.5, 64-bit. Version 0.1.0 is recorded for automatic publication after human rebase merge and the complete package gate. App adoption is a separate integration change.

Values are constructed directly. Register Kumwe\BusinessSchema\ConfigProvider explicitly with your ConfigAggregator. Its factories provide shared compiler and planner services. The host binds DefinitionSchemaLookup, FieldTypeDefinitionResolver and PhysicalNameCompiler; there are no implicit authority or naming defaults. See resources/service-map/v1.json and the runnable examples/consumer.php. The example consumer declares Laminas ServiceManager as its host container dependency.

See [public API](docs/public-api.md), [architecture](docs/architecture.md), [integration](docs/integration.md) and [test ownership](docs/test-ownership.md).

For standalone verification, run `composer install` and `composer check`; see `docs/integration.md`. `composer clean-consumer` verifies the archive in a fresh no-dev classmap-authoritative consumer. License: Apache-2.0.

Maintenance release: Detach schema operations, plans, blueprints and recovery metadata from caller references so approved checksums and recovery input cannot change after admission.

Direct Kumwe dependencies use exact stable versions. Dependabot proposes grouped weekly Composer updates; review and merge only after the complete package gate passes. The downstream App consumes a verified exact release, never an unreviewed moving `latest` constraint.
