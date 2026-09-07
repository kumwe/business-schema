# business-schema

Portable physical schema blueprints, deterministic change plans and recovery values.

Canonical namespace: `Kumwe\BusinessSchema`. Requires PHP 8.5, 64-bit. This is a development extraction candidate; do not publish or adopt until the migration handoff and exact upstream release attestations are reviewed.

Pure values and stateless normalization are constructed directly. No empty container provider is registered. Services with real collaborators receive explicit factories when introduced.

See [public API](docs/public-api.md), [architecture](docs/architecture.md), [integration](docs/integration.md) and [test ownership](docs/test-ownership.md).

For candidate source verification, follow `docs/integration.md`. Run `composer check` after installing the explicit candidate toolchain. `composer clean-consumer` verifies the archive in a fresh no-dev classmap-authoritative consumer. License: Apache-2.0.
