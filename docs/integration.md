# Integration

Consume only an independently verified immutable release; exact pins are mandatory before 1.0. App supplies already authorized values and trusted definitions. Database adapters, scope authority, policy enforcement, signing secrets, persistence and delivery remain in App. No change to historical owners happens in this Phase 1 branch.

## Source and archive verification


`ConfigProvider` advertises shared stateless compiler/planner services. The host supplies typed `DefinitionSchemaLookup`, `FieldTypeDefinitionResolver` and `PhysicalNameCompiler` bindings; missing inputs fail construction. No ambient site, registry or prefix is inferred. The host retains transaction, lease/fence, DDL and schema journal persistence.

Run `composer install` and `composer check`. Source CI installs exact published Kumwe dependencies and builds an isolated no-dev classmap-authoritative archive consumer. The complete gate includes package-owned behavior, boundary and conformance tests, static analysis, API and ownership checks, release automation fixtures and clean-consumer verification.

Exact pre-1.0 dependency pins change through reviewed update PRs. A moving `latest` coordinate would make the verified dependency closure irreproducible.
