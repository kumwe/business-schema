# business-schema

Portable physical schema blueprints, deterministic change plans and recovery values.

Canonical namespace: `Kumwe\BusinessSchema`. Requires PHP 8.5, 64-bit. This is a development extraction candidate; do not publish or adopt until the migration handoff and exact upstream release attestations are reviewed.

Pure values and stateless normalization are constructed directly. No empty container provider is registered. Services with real collaborators receive explicit factories when introduced.

See [public API](docs/public-api.md), [architecture](docs/architecture.md), [integration](docs/integration.md) and [test ownership](docs/test-ownership.md).

Run `composer install` followed by `composer check`. License: Apache-2.0.
