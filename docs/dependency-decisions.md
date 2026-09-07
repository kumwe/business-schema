# Dependency decision BS-001

Status: existing released source dependency, proposed catalogue reconciliation for maintainer review in PR #3.

The Version 2 catalogue names Business Definition as Business Schema's dependency ceiling. The extracted
`CanonicalDefinitionPhysicalSchemaCompiler` also imports `Kumwe\Sequence\Value\NumberSequenceFormat` and reads
`MAXIMUM_LENGTH` to size allocated business identity columns. Sequence is the canonical owner of that format bound.

Keep the explicit, exact stable `kumwe/sequence` dependency. Removing it would leave an undeclared transitive
runtime dependency; copying its constant would permit schema storage and numbering semantics to diverge. This
edge grants no sequence allocation, persistence, reservation, transaction or numbering authority to Business Schema.
The package still compiles portable blueprints and plans only.

The existing published Business Definition 0.1.0 requires Sequence 0.2.0, so this release train retains Sequence
0.2.0 in Business Schema. After Business Definition's maintenance release is published and independently verified,
advance both pins together and run the compiler, schema planning and archive consumer suites. A moving latest
constraint cannot satisfy conflicting exact transitive requirements.

The maintainer must reconcile the catalogue ceiling with this explicit source-derived edge before declaring full
catalogue alignment or starting App adoption. Publication of earlier packages does not itself approve that change.
