# Releasing

Version 0.1.0 is recorded in CHANGELOG.md and the public API manifest. Direct Kumwe
requirements name published stable versions. PRs and human rebase merges run the
same Package gate, including package-owned behavior tests and a stable, no-dev
archive consumer installation. A successful default-branch run publishes the recorded
version from the actual tested commit. Existing tags and releases are preserved.

Normal publication verifies dependency tag, source and installed dist identity.
Branch protection, GitHub's immutable-release setting and external attestations are
optional and do not block this publication path. The separate strict evidence audit
remains available for downstream adoption; App integration is a subsequent change.
