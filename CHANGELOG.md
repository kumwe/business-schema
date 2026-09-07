# Changelog

## 0.1.1 - 2026-09-07

- Detach schema operations, plans, blueprints and recovery metadata from caller references so approved checksums and recovery input cannot change after admission.
- Add package-owned regression tests and refresh extraction handoff, dependency and release documentation.
- Keep exact stable dependency requirements; grouped weekly update PRs re-run the package gate.

## 0.1.0 - 2026-09-07

### Added

- Portable physical schema blueprints, deterministic change plans and recovery values.
- NRM-2026-030: package extraction enabling the Version 2 migration. Roadmap impact: enables; no completion claim.

Normal publication verifies exact stable dependency tag, source and dist identity.
