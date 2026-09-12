# Dependency contract BS-001

Business Schema depends on Business Definition and Sequence. The physical schema
compiler imports `Kumwe\Sequence\Value\NumberSequenceFormat` and reads `MAXIMUM_LENGTH`
to size allocated business identity columns. Sequence owns that format bound.

Keep the explicit exact `kumwe/sequence` requirement. Relying on an undeclared
transitive dependency or copying the constant would allow storage and numbering
semantics to diverge. This dependency grants no sequence allocation, reservation,
persistence, transaction or numbering authority to Business Schema.

Business Definition 0.1.2 and Sequence 0.2.1 form the declared published dependency
tuple. Compiler, planner and archive-consumer suites verify their composition.
Core's dependency catalogue must preserve both dependency edges. Independently
verify exact release identities before adoption; a moving constraint does not
resolve incompatible exact pre-1.0 requirements.
