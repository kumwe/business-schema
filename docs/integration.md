# Integration

Consume only an independently verified immutable release; exact pins are mandatory before 1.0. App supplies already authorized values and trusted definitions. Database adapters, scope authority, policy enforcement, signing secrets, persistence and delivery remain in App. No change to historical owners happens in this Phase 1 branch.

## Candidate source verification


`ConfigProvider` advertises shared stateless compiler/planner services. The host supplies typed `DefinitionSchemaLookup`, `FieldTypeDefinitionResolver` and `PhysicalNameCompiler` bindings; missing inputs fail construction. No ambient site, registry or prefix is inferred. The host retains transaction, lease/fence, DDL and schema journal persistence.

Source CI installs the original Composer metadata with actual development branch
constraints and builds an isolated no-dev archive consumer. Candidate inputs are
mutable and do not establish publication readiness. No development alias is used
to satisfy a fictional stable release.
