# business-schema

Portable physical schema blueprints, deterministic change plans and recovery values.

The package owns value invariants, canonical serialization and its behavior/boundary/conformance tests. The host owns authorization, trusted generation resolution, persistence, SQL, transactions, lifecycle, delivery and operational recovery. Native arithmetic belongs exclusively to Engine; this package contains no native fallback.

## Proposed dependency amendment

The existing compiler consumes NumberSequenceFormat::MAXIMUM_LENGTH, now canonically owned by `kumwe/sequence`. This direct source dependency is proposed explicitly in addition to Business Definition. Publication is blocked until the catalog amendment and immutable Sequence release attestation are reviewed. The compiler consumes the canonical constant and does not duplicate sequence behavior.
