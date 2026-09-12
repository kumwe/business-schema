# Kumwe Business Schema

[![Packagist version][version-badge]][package]
[![CI][ci-badge]][ci]
[![PHP requirement][php-badge]](composer.json)
[![License][license-badge]](LICENSE)

Portable physical schema blueprints, deterministic change plans and recovery values
under `Kumwe\BusinessSchema`.

## Installation

Requires 64-bit PHP 8.5 with JSON and mbstring. Pin an exact pre-1.0 release:

```sh
composer require kumwe/business-schema:0.1.3
```

Composer declares exact Business Definition and Sequence requirements. The version
badge links published packages; default-branch CI reports package quality checks.
Consumers separately verify exact releases and their Core integration.

## Usage and Core contract

Construct values directly. Register `Kumwe\BusinessSchema\ConfigProvider` with your
ConfigAggregator for shared compiler and planner services. Core must bind
`DefinitionSchemaLookup`, `FieldTypeDefinitionResolver` and `PhysicalNameCompiler`;
there are no implicit authority or naming defaults. See the [service map](resources/service-map/v1.json)
and [runnable consumer](examples/consumer.php), which uses Laminas ServiceManager.

Use `SchemaChangePlanner::dependencyHandles($definition)` to discover outgoing
targets before resolving and compiling their blueprints under host authority.
Core retains database execution, authorization, transactions, fencing, DDL journals,
persistence and recovery. Package blueprints and approved plan checksums are detached
from caller references; constructing a plan does not grant permission to execute it.

## Documentation

- [Public API](docs/public-api.md), [architecture](docs/architecture.md) and [integration](docs/integration.md)
- [Dependency contract](docs/dependency-decisions.md) and [test ownership](docs/test-ownership.md)
- [Release record](docs/release-record.md), [release process](docs/releasing.md) and [changelog](CHANGELOG.md)

## Development

```sh
composer install
composer check
```

The gate covers dependency evidence, syntax, architecture, behavior/conformance,
static analysis, coding standards, public manifests, governance, audit, release
automation and a fresh no-dev classmap-authoritative archive consumer. CI runs
PHP 8.5 on Linux. Dependabot proposes grouped weekly dependency updates.

Existing releases remain fixed; source documentation updates ship in a subsequent
release. Licensed under [Apache-2.0](LICENSE).

[version-badge]: https://img.shields.io/packagist/v/kumwe/business-schema
[package]: https://packagist.org/packages/kumwe/business-schema
[ci-badge]: https://img.shields.io/github/actions/workflow/status/kumwe/business-schema/ci.yml?branch=main
[ci]: https://github.com/kumwe/business-schema/actions/workflows/ci.yml
[php-badge]: https://img.shields.io/packagist/php-v/kumwe/business-schema
[license-badge]: https://img.shields.io/packagist/l/kumwe/business-schema
