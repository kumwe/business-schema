# Releasing

The callable Package CI gate and shared release-on-record helpers are copied from
Canonical JSON main 584f965e65a22e098e2ca6edff10047b9f9e3041. The dependency-aware
publication workflow and verifier use the same Business Definition release gate.
Unreleased-only changelogs do not select a version. No release is recorded here.

Before recording a stable release, select exact stable dependency versions with
independent immutable release attestations. The live dependency verifier checks
the complete resolved Kumwe closure and fails on development versions, mutable
releases or missing evidence. Source CI runs its isolated regression fixtures.
The shared helper verifies the tested default-branch identity and existing tags
before any publication. Maintainers retain merge and publication authority.
