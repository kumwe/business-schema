# business-schema

Portable physical schema blueprints, deterministic change plans and recovery values.

The package owns value invariants, canonical serialization and its behavior/boundary/conformance tests. The host owns authorization, trusted generation resolution, persistence, SQL, transactions, lifecycle, delivery and operational recovery. Native arithmetic belongs exclusively to Engine; this package contains no native fallback.

## Explicit dependency decision

The current released source closure includes one narrow dependency omitted by the original Version 2 catalogue.
The package preserves that source-derived edge and records its ownership, rationale, exact version train and
required maintainer catalogue reconciliation in [dependency-decisions.md](docs/dependency-decisions.md).
This is an explicit review item; full catalogue alignment is not inferred from publication.
