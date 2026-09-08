# Dependency decision BS-001

Status: source-derived dependency amendment included in the integration-readiness review.

The Version 2 catalogue names Business Definition as Business Schema's dependency ceiling. The extracted
`CanonicalDefinitionPhysicalSchemaCompiler` also imports `Kumwe\Sequence\Value\NumberSequenceFormat` and reads
`MAXIMUM_LENGTH` to size allocated business identity columns. Sequence is the canonical owner of that format bound.

Keep the explicit, exact stable `kumwe/sequence` dependency. Removing it would leave an undeclared transitive
runtime dependency; copying its constant would permit schema storage and numbering semantics to diverge. This
edge grants no sequence allocation, persistence, reservation, transaction or numbering authority to Business Schema.
The package still compiles portable blueprints and plans only.

The published Business Definition 0.1.2 requires Sequence 0.2.1. The 0.1.2 Business Schema candidate advances
both exact pins together, so the compiler, schema planning and archive consumer suites exercise that coherent
published tuple. Independent release verification remains a separate downstream adoption requirement.
A moving latest constraint cannot satisfy conflicting exact transitive requirements.

The package dependency ceiling is therefore Business Definition plus Sequence, with the boundaries above.
This source-derived reconciliation is part of the maintainer's review of this candidate. The central catalogue
must incorporate BS-001 during the later integration planning step; no App checkout is changed here.
