# Migration handoff

This candidate contains runtime implementation and package-owned tests. Publication and App adoption remain separate, attested tasks.

```yaml
{
  "schema": "kumwe-migration-handoff/v2",
  "artifact_kind": "framework_php",
  "migration_id": "KUMWE-MIG-2026-030",
  "change_set": "KUMWE-CS-2026-030",
  "state": "draft_pr_open",
  "source": {
    "app": {
      "repository": "https://github.com/kumwe/app",
      "baseline_commit": "24ecf956423c18933e824b43cea1bfb9127a79a9",
      "examined_paths": [
        "src/BusinessSchema/Domain/InvalidBusinessSchema.php",
        "src/BusinessSchema/Domain/PhysicalColumnBlueprint.php",
        "src/BusinessSchema/Domain/PhysicalForeignKeyBlueprint.php",
        "src/BusinessSchema/Domain/PhysicalIndexBlueprint.php",
        "src/BusinessSchema/Domain/PhysicalNameCompiler.php",
        "src/BusinessSchema/Domain/PhysicalSchemaBlueprint.php",
        "src/BusinessSchema/Domain/PhysicalTableBlueprint.php",
        "src/BusinessSchema/Domain/PhysicalTableKind.php",
        "src/BusinessSchema/Domain/SchemaDocument.php",
        "src/BusinessSchema/Domain/SchemaEvolutionHints.php",
        "src/BusinessSchema/Domain/SchemaInstallation.php",
        "src/BusinessSchema/Domain/SchemaInstallationStatus.php",
        "src/BusinessSchema/Domain/SchemaOperation.php",
        "src/BusinessSchema/Domain/SchemaOperationKind.php",
        "src/BusinessSchema/Domain/SchemaPlan.php",
        "src/BusinessSchema/Domain/SchemaPlanApproval.php",
        "src/BusinessSchema/Domain/SchemaPlanStatus.php",
        "src/BusinessSchema/Domain/SchemaPlanStep.php",
        "src/BusinessSchema/Domain/SchemaRecoveryEvidence.php",
        "src/BusinessSchema/Domain/SchemaRisk.php",
        "src/BusinessSchema/Domain/SchemaStepStatus.php",
        "src/BusinessSchema/Infrastructure/Schema/CanonicalDefinitionPhysicalSchemaCompiler.php"
      ],
      "old_namespace_roots": [
        "Kumwe\\App\\BusinessRecord",
        "Kumwe\\App\\BusinessSchema",
        "Kumwe\\App\\BusinessReporting"
      ],
      "capability_index_sha256": null
    },
    "semantic_inputs": [
      {
        "owner": "kumwe/extension-sdk",
        "version_or_commit": "e8ec23f155c5836c6bd083f154a8efb6e50aec66",
        "manifest_or_corpus": "resources/extraction/v1.json",
        "sha256": "3767932a89b33f6d856b734bb150c4622726aa01df2d0fa8c23b4ef8507bd146"
      }
    ],
    "examined_dependencies": [
      {
        "package": "kumwe/business-definition",
        "constraint": "dev-main",
        "independently_verified": false,
        "attestation": null
      },
      {
        "package": "kumwe/sequence",
        "constraint": "dev-main",
        "independently_verified": false,
        "attestation": null
      }
    ],
    "active_related_pull_requests": [
      "https://github.com/kumwe/record-query/pull/2",
      "https://github.com/kumwe/record-model/pull/2",
      "https://github.com/kumwe/reporting/pull/2"
    ]
  },
  "target": {
    "repository": "https://github.com/kumwe/business-schema",
    "artifact_identity": "kumwe/business-schema",
    "canonical_namespace_or_abi": "Kumwe\\BusinessSchema\\",
    "branch": "agent/extraction-v2-business-data",
    "pull_request": "https://github.com/kumwe/business-schema/pull/1"
  },
  "ownership": {
    "responsibility": "Portable physical schema blueprints, deterministic change plans and recovery values.",
    "non_responsibilities": [
      "authorization",
      "trusted generation selection",
      "persistence",
      "SQL execution",
      "transactions",
      "delivery",
      "native execution"
    ],
    "allowed_dependency_ceiling": [
      "php",
      "php-64bit",
      "ext-json",
      "kumwe/business-definition",
      "ramsey/uuid",
      "kumwe/sequence",
      "ext-mbstring",
      "psr/container"
    ],
    "implementation_owner": "kumwe/business-schema",
    "next_consumer": "kumwe/app",
    "public_manifests": [
      {
        "path": "resources/public-api/v1.json",
        "sha256": "a776d43bc0f6ba19f527be07b95b9ad242c9f7f859baff3488fe140adeb0bb24"
      },
      {
        "path": "resources/capabilities/v1.json",
        "sha256": "595fcc31ef1ecf3158dc8c14a26e066824f526bbbd4c861c2b21f9cf54974d23"
      },
      {
        "path": "resources/service-map/v1.json",
        "sha256": "fcdfd72befbc995bc8b308dfde72b8fae8097d8c4e75c39cb75f3b62f57b067b"
      },
      {
        "path": "resources/test-ownership/v1.json",
        "sha256": "6c0b0d082223e579929e01531c358265972324bb47810ec87390aa22876f7296"
      }
    ],
    "intentionally_excluded": [
      "App repositories, policy gates and lifecycle orchestration",
      "production PHP native executor fallback"
    ]
  },
  "framework_php": {
    "composer_package": "kumwe/business-schema",
    "canonical_namespace": "Kumwe\\BusinessSchema\\",
    "public_api_manifest": "resources/public-api/v1.json",
    "capability_manifest": "resources/capabilities/v1.json",
    "service_map": "resources/service-map/v1.json",
    "extracted_symbols": [
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/InvalidBusinessSchema.php",
        "target_path": "src/Domain/InvalidBusinessSchema.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/PhysicalColumnBlueprint.php",
        "target_path": "src/Domain/PhysicalColumnBlueprint.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/PhysicalForeignKeyBlueprint.php",
        "target_path": "src/Domain/PhysicalForeignKeyBlueprint.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/PhysicalIndexBlueprint.php",
        "target_path": "src/Domain/PhysicalIndexBlueprint.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/PhysicalNameCompiler.php",
        "target_path": "src/Domain/PhysicalNameCompiler.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/PhysicalSchemaBlueprint.php",
        "target_path": "src/Domain/PhysicalSchemaBlueprint.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/PhysicalTableBlueprint.php",
        "target_path": "src/Domain/PhysicalTableBlueprint.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/PhysicalTableKind.php",
        "target_path": "src/Domain/PhysicalTableKind.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaDocument.php",
        "target_path": "src/Domain/SchemaDocument.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaEvolutionHints.php",
        "target_path": "src/Domain/SchemaEvolutionHints.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaInstallation.php",
        "target_path": "src/Domain/SchemaInstallation.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaInstallationStatus.php",
        "target_path": "src/Domain/SchemaInstallationStatus.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaOperation.php",
        "target_path": "src/Domain/SchemaOperation.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaOperationKind.php",
        "target_path": "src/Domain/SchemaOperationKind.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaPlan.php",
        "target_path": "src/Domain/SchemaPlan.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaPlanApproval.php",
        "target_path": "src/Domain/SchemaPlanApproval.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaPlanStatus.php",
        "target_path": "src/Domain/SchemaPlanStatus.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaPlanStep.php",
        "target_path": "src/Domain/SchemaPlanStep.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaRecoveryEvidence.php",
        "target_path": "src/Domain/SchemaRecoveryEvidence.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaRisk.php",
        "target_path": "src/Domain/SchemaRisk.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaStepStatus.php",
        "target_path": "src/Domain/SchemaStepStatus.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Infrastructure/Schema/CanonicalDefinitionPhysicalSchemaCompiler.php",
        "target_path": "src/Compiler/CanonicalDefinitionPhysicalSchemaCompiler.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Application/BusinessSchemaPlanner.php",
        "target_path": "src/Planner/SchemaChangePlanner.php",
        "extraction_kind": "partial_methods",
        "methods": [
          "operations",
          "tableOperations",
          "number",
          "withoutForeignKeys",
          "containsPinnedRowBreakingChange",
          "hasRecordRepin",
          "additiveColumnRelaxation",
          "dependencyHandles",
          "spec",
          "tablesByLogical",
          "columnsByLogical",
          "indexesByLogical",
          "foreignKeysByLogical",
          "backfillValue",
          "backfillValueOrDefault",
          "backfillState",
          "transformShadowColumn",
          "validateEvolutionHints"
        ]
      }
    ],
    "consumers": {
      "app_code": [
        "src/BusinessSchema/Domain/InvalidBusinessSchema.php",
        "src/BusinessSchema/Domain/PhysicalColumnBlueprint.php",
        "src/BusinessSchema/Domain/PhysicalForeignKeyBlueprint.php",
        "src/BusinessSchema/Domain/PhysicalIndexBlueprint.php",
        "src/BusinessSchema/Domain/PhysicalNameCompiler.php",
        "src/BusinessSchema/Domain/PhysicalSchemaBlueprint.php",
        "src/BusinessSchema/Domain/PhysicalTableBlueprint.php",
        "src/BusinessSchema/Domain/PhysicalTableKind.php",
        "src/BusinessSchema/Domain/SchemaDocument.php",
        "src/BusinessSchema/Domain/SchemaEvolutionHints.php",
        "src/BusinessSchema/Domain/SchemaInstallation.php",
        "src/BusinessSchema/Domain/SchemaInstallationStatus.php",
        "src/BusinessSchema/Domain/SchemaOperation.php",
        "src/BusinessSchema/Domain/SchemaOperationKind.php",
        "src/BusinessSchema/Domain/SchemaPlan.php",
        "src/BusinessSchema/Domain/SchemaPlanApproval.php",
        "src/BusinessSchema/Domain/SchemaPlanStatus.php",
        "src/BusinessSchema/Domain/SchemaPlanStep.php",
        "src/BusinessSchema/Domain/SchemaRecoveryEvidence.php",
        "src/BusinessSchema/Domain/SchemaRisk.php",
        "src/BusinessSchema/Domain/SchemaStepStatus.php",
        "src/BusinessSchema/Infrastructure/Schema/CanonicalDefinitionPhysicalSchemaCompiler.php"
      ],
      "configuration_and_di": [],
      "reflection_and_string_references": [
        "Recompute using source/import closure at adoption head."
      ],
      "fixtures_and_examples": [],
      "external": [
        "kumwe/extension-sdk coordinated successor"
      ]
    },
    "dependency_injection": {
      "mode": "config-provider",
      "provider": "Kumwe\\BusinessSchema\\ConfigProvider",
      "factories": [
        "Kumwe\\BusinessSchema\\Container\\PhysicalSchemaCompilerFactory",
        "Kumwe\\BusinessSchema\\Container\\SchemaChangePlannerFactory"
      ],
      "aliases": [],
      "service_lifetimes": {
        "Kumwe\\BusinessSchema\\Compiler\\CanonicalDefinitionPhysicalSchemaCompiler": "shared",
        "Kumwe\\BusinessSchema\\Planner\\SchemaChangePlanner": "shared"
      },
      "configuration_keys": [
        "Kumwe\\BusinessSchema\\Contract\\DefinitionSchemaLookup",
        "Kumwe\\BusinessDefinition\\Application\\FieldTypeDefinitionResolver",
        "Kumwe\\BusinessSchema\\Domain\\PhysicalNameCompiler"
      ],
      "provider_absence_reason": null
    }
  },
  "native_cpp": null,
  "php_extension": null,
  "tests": {
    "moved_or_added": [
      {
        "path": "tests/ContainerTest.php",
        "methods": [
          "testRealContainerResolvesSharedCompilerWithExplicitHostPorts",
          "testMissingHostAuthorityBindingDoesNotReceiveAnImplicitDefault"
        ],
        "implementation_owner": "kumwe/business-schema",
        "source_ownership": "new_package_tests",
        "source_tests": []
      },
      {
        "path": "tests/PhysicalNameCompilerTest.php",
        "methods": [
          "testNamesAreDeterministicBoundedAndDefinitionScoped",
          "testRejectsNonCanonicalPrefixesThatCouldCollapseOrProduceInvalidNames",
          "testDistinctCanonicalPrefixesCannotCompileTheSamePhysicalName"
        ],
        "implementation_owner": "kumwe/business-schema",
        "source_ownership": "moved_or_adapted",
        "source_tests": [
          {
            "owner": "app",
            "baseline_commit": "24ecf956423c18933e824b43cea1bfb9127a79a9",
            "path": "tests/Unit/BusinessSchema/Domain/PhysicalNameCompilerTest.php",
            "methods": [
              "testNamesAreDeterministicBoundedAndDefinitionScoped",
              "testRejectsNonCanonicalPrefixesThatCouldCollapseOrProduceInvalidNames",
              "testDistinctCanonicalPrefixesCannotCompileTheSamePhysicalName"
            ],
            "retained_methods": [],
            "remove_whole_file": true
          }
        ]
      },
      {
        "path": "tests/PhysicalSchemaCompilerTest.php",
        "methods": [
          "testReferenceIdentityUsesGuidPrimaryKeyAndScopedAlternateUniqueIndex",
          "testConstraintNamesCannotCollideAcrossDefinitions",
          "testVirtualFormulaIsOmittedAndStoredFormulaUsesItsExactResultType",
          "testStructuredRuntimeDefaultIsNotEmittedAsANonPortableJsonDatabaseDefault",
          "testPortableTextLengthBoundaryIsPreservedWithoutSilentCapping",
          "testForeignKeySupportIndexIsAlwaysExplicitInThePortableBlueprint",
          "testAReversalCompilesToARestrictedSelfTargetColumn"
        ],
        "implementation_owner": "kumwe/business-schema",
        "source_ownership": "moved_or_adapted",
        "source_tests": [
          {
            "owner": "app",
            "baseline_commit": "24ecf956423c18933e824b43cea1bfb9127a79a9",
            "path": "tests/Unit/BusinessSchema/Infrastructure/CanonicalDefinitionPhysicalSchemaCompilerTest.php",
            "methods": [
              "testReferenceIdentityUsesGuidPrimaryKeyAndScopedAlternateUniqueIndex",
              "testConstraintNamesCannotCollideAcrossDefinitions",
              "testVirtualFormulaIsOmittedAndStoredFormulaUsesItsExactResultType",
              "testStructuredRuntimeDefaultIsNotEmittedAsANonPortableJsonDatabaseDefault",
              "testPortableTextLengthBoundaryIsPreservedWithoutSilentCapping",
              "testForeignKeySupportIndexIsAlwaysExplicitInThePortableBlueprint",
              "testAReversalCompilesToARestrictedSelfTargetColumn"
            ],
            "retained_methods": [],
            "remove_whole_file": false,
            "retained_assertions": [
              "BusinessDefinitionValidator assertion belongs to the definition owner and was not extracted."
            ]
          }
        ]
      },
      {
        "path": "tests/SchemaEvolutionHintsTest.php",
        "methods": [
          "testLiteralAndExpressionBackfillsRoundTripCanonically",
          "testAmbiguousRenameAndEvolutionKeyTyposFailClosed"
        ],
        "implementation_owner": "kumwe/business-schema",
        "source_ownership": "moved_or_adapted",
        "source_tests": [
          {
            "owner": "app",
            "baseline_commit": "24ecf956423c18933e824b43cea1bfb9127a79a9",
            "path": "tests/Unit/BusinessSchema/Domain/SchemaEvolutionHintsTest.php",
            "methods": [
              "testLiteralAndExpressionBackfillsRoundTripCanonically",
              "testAmbiguousRenameAndEvolutionKeyTyposFailClosed"
            ],
            "retained_methods": [],
            "remove_whole_file": true
          }
        ]
      },
      {
        "path": "tests/SchemaInstallationTest.php",
        "methods": [
          "testExtensionPackageOwnerIdentityIsPreserved"
        ],
        "implementation_owner": "kumwe/business-schema",
        "source_ownership": "moved_or_adapted",
        "source_tests": [
          {
            "owner": "app",
            "baseline_commit": "24ecf956423c18933e824b43cea1bfb9127a79a9",
            "path": "tests/Unit/BusinessSchema/Domain/SchemaInstallationTest.php",
            "methods": [
              "testExtensionPackageOwnerIdentityIsPreserved"
            ],
            "retained_methods": [],
            "remove_whole_file": true
          }
        ]
      },
      {
        "path": "tests/SchemaPlanTest.php",
        "methods": [
          "testApprovalIsChecksumBoundAndExecutionIsFenceBound",
          "testHighImpactPlanRequiresExactConfirmationAndRecoveryEvidence"
        ],
        "implementation_owner": "kumwe/business-schema",
        "source_ownership": "moved_or_adapted",
        "source_tests": [
          {
            "owner": "app",
            "baseline_commit": "24ecf956423c18933e824b43cea1bfb9127a79a9",
            "path": "tests/Unit/BusinessSchema/Domain/SchemaPlanTest.php",
            "methods": [
              "testApprovalIsChecksumBoundAndExecutionIsFenceBound",
              "testHighImpactPlanRequiresExactConfirmationAndRecoveryEvidence"
            ],
            "retained_methods": [],
            "remove_whole_file": true
          }
        ]
      },
      {
        "path": "tests/SchemaRecoveryContractTest.php",
        "methods": [
          "testCanonicalPlanOrderAndChecksumSurviveEveryRecoveryTransition",
          "testPersistedPlanRejectsCanonicalAndApprovalChecksumDrift",
          "testLockingApprovalRequiresConfirmationAndSourceBoundEvidence",
          "testRecoveryEvidenceQualifiesOnlyForTheExactFreshEnvironmentAndSource",
          "testInterruptedStepAdvancesAttemptAndFenceBeforeCompletion"
        ],
        "implementation_owner": "kumwe/business-schema",
        "source_ownership": "moved_or_adapted",
        "source_tests": [
          {
            "owner": "app",
            "baseline_commit": "24ecf956423c18933e824b43cea1bfb9127a79a9",
            "path": "tests/Unit/BusinessSchema/Domain/SchemaRecoveryContractTest.php",
            "methods": [
              "testCanonicalPlanOrderAndChecksumSurviveEveryRecoveryTransition",
              "testPersistedPlanRejectsCanonicalAndApprovalChecksumDrift",
              "testLockingApprovalRequiresConfirmationAndSourceBoundEvidence",
              "testRecoveryEvidenceQualifiesOnlyForTheExactFreshEnvironmentAndSource",
              "testInterruptedStepAdvancesAttemptAndFenceBeforeCompletion"
            ],
            "retained_methods": [],
            "remove_whole_file": true
          }
        ]
      }
    ],
    "remain_in_app_or_consumer": [
      "SQL/database matrix",
      "policy-before-query",
      "authorization and generation fences",
      "cryptographic envelope authenticity and key lifecycle",
      "transaction/concurrency and recovery",
      "export/delivery/adapters"
    ],
    "split_tests": [],
    "prohibited_duplicates": [
      "Do not retain moved implementation tests in App/SDK after the separate verified adoption."
    ],
    "corpora": []
  },
  "documentation": {
    "charter": "CHARTER.md",
    "readme": "README.md",
    "public_api": "docs/public-api.md",
    "architecture": "docs/architecture.md",
    "integration_or_consumer": "docs/integration.md",
    "examples": [
      "examples/consumer.php"
    ],
    "changelog_record": "CHANGELOG.md / Unreleased"
  },
  "release_expectations": {
    "version_policy": "SemVer; determine release version after review. Replace development dependency constraints with exact independently verified pre-1.0 releases.",
    "expected_artifact_types": [
      "Composer source zip"
    ],
    "required_checks": [
      "composer check",
      "composer security:audit",
      "composer clean-consumer",
      "review dependency ceiling",
      "immutable release and source/artifact manifests independently attested"
    ],
    "required_registry_or_installer": "Composer",
    "required_external_attestation": true
  },
  "next_task": {
    "phase_name": "Independent release verification, followed by separate App adoption",
    "permitted_only_when": [
      "Human merges package PR",
      "Immutable upstream dependency releases and target release are independently verified",
      "External RELEASE-ATTESTATION.yaml exists and matches all source/artifact identities"
    ],
    "consumer_repository": "https://github.com/kumwe/app",
    "dependency_or_native_change": "Exact-pin the reviewed immutable package release and remove the former implementation. Never use this development branch as a released dependency.",
    "namespace_or_api_replacements": [
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/InvalidBusinessSchema.php",
        "target_path": "src/Domain/InvalidBusinessSchema.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/PhysicalColumnBlueprint.php",
        "target_path": "src/Domain/PhysicalColumnBlueprint.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/PhysicalForeignKeyBlueprint.php",
        "target_path": "src/Domain/PhysicalForeignKeyBlueprint.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/PhysicalIndexBlueprint.php",
        "target_path": "src/Domain/PhysicalIndexBlueprint.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/PhysicalNameCompiler.php",
        "target_path": "src/Domain/PhysicalNameCompiler.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/PhysicalSchemaBlueprint.php",
        "target_path": "src/Domain/PhysicalSchemaBlueprint.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/PhysicalTableBlueprint.php",
        "target_path": "src/Domain/PhysicalTableBlueprint.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/PhysicalTableKind.php",
        "target_path": "src/Domain/PhysicalTableKind.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaDocument.php",
        "target_path": "src/Domain/SchemaDocument.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaEvolutionHints.php",
        "target_path": "src/Domain/SchemaEvolutionHints.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaInstallation.php",
        "target_path": "src/Domain/SchemaInstallation.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaInstallationStatus.php",
        "target_path": "src/Domain/SchemaInstallationStatus.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaOperation.php",
        "target_path": "src/Domain/SchemaOperation.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaOperationKind.php",
        "target_path": "src/Domain/SchemaOperationKind.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaPlan.php",
        "target_path": "src/Domain/SchemaPlan.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaPlanApproval.php",
        "target_path": "src/Domain/SchemaPlanApproval.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaPlanStatus.php",
        "target_path": "src/Domain/SchemaPlanStatus.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaPlanStep.php",
        "target_path": "src/Domain/SchemaPlanStep.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaRecoveryEvidence.php",
        "target_path": "src/Domain/SchemaRecoveryEvidence.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaRisk.php",
        "target_path": "src/Domain/SchemaRisk.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Domain/SchemaStepStatus.php",
        "target_path": "src/Domain/SchemaStepStatus.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Infrastructure/Schema/CanonicalDefinitionPhysicalSchemaCompiler.php",
        "target_path": "src/Compiler/CanonicalDefinitionPhysicalSchemaCompiler.php",
        "extraction_kind": "whole_file"
      },
      {
        "old_owner": "app",
        "source_path": "src/BusinessSchema/Application/BusinessSchemaPlanner.php",
        "target_path": "src/Planner/SchemaChangePlanner.php",
        "extraction_kind": "partial_methods",
        "methods": [
          "operations",
          "tableOperations",
          "number",
          "withoutForeignKeys",
          "containsPinnedRowBreakingChange",
          "hasRecordRepin",
          "additiveColumnRelaxation",
          "dependencyHandles",
          "spec",
          "tablesByLogical",
          "columnsByLogical",
          "indexesByLogical",
          "foreignKeysByLogical",
          "backfillValue",
          "backfillValueOrDefault",
          "backfillState",
          "transformShadowColumn",
          "validateEvolutionHints"
        ]
      }
    ],
    "files_to_update": [
      "composer.json",
      "composer.lock",
      "container configuration",
      "capability index",
      "migration ledger",
      "CHANGELOG.md"
    ],
    "files_to_remove": [
      "src/BusinessSchema/Domain/InvalidBusinessSchema.php",
      "src/BusinessSchema/Domain/PhysicalColumnBlueprint.php",
      "src/BusinessSchema/Domain/PhysicalForeignKeyBlueprint.php",
      "src/BusinessSchema/Domain/PhysicalIndexBlueprint.php",
      "src/BusinessSchema/Domain/PhysicalNameCompiler.php",
      "src/BusinessSchema/Domain/PhysicalSchemaBlueprint.php",
      "src/BusinessSchema/Domain/PhysicalTableBlueprint.php",
      "src/BusinessSchema/Domain/PhysicalTableKind.php",
      "src/BusinessSchema/Domain/SchemaDocument.php",
      "src/BusinessSchema/Domain/SchemaEvolutionHints.php",
      "src/BusinessSchema/Domain/SchemaInstallation.php",
      "src/BusinessSchema/Domain/SchemaInstallationStatus.php",
      "src/BusinessSchema/Domain/SchemaOperation.php",
      "src/BusinessSchema/Domain/SchemaOperationKind.php",
      "src/BusinessSchema/Domain/SchemaPlan.php",
      "src/BusinessSchema/Domain/SchemaPlanApproval.php",
      "src/BusinessSchema/Domain/SchemaPlanStatus.php",
      "src/BusinessSchema/Domain/SchemaPlanStep.php",
      "src/BusinessSchema/Domain/SchemaRecoveryEvidence.php",
      "src/BusinessSchema/Domain/SchemaRisk.php",
      "src/BusinessSchema/Domain/SchemaStepStatus.php",
      "src/BusinessSchema/Infrastructure/Schema/CanonicalDefinitionPhysicalSchemaCompiler.php"
    ],
    "tests_to_remove": [
      {
        "owner": "app",
        "baseline_commit": "24ecf956423c18933e824b43cea1bfb9127a79a9",
        "path": "tests/Unit/BusinessSchema/Domain/PhysicalNameCompilerTest.php",
        "methods": [
          "testNamesAreDeterministicBoundedAndDefinitionScoped",
          "testRejectsNonCanonicalPrefixesThatCouldCollapseOrProduceInvalidNames",
          "testDistinctCanonicalPrefixesCannotCompileTheSamePhysicalName"
        ],
        "retained_methods": [],
        "remove_whole_file": true
      },
      {
        "owner": "app",
        "baseline_commit": "24ecf956423c18933e824b43cea1bfb9127a79a9",
        "path": "tests/Unit/BusinessSchema/Infrastructure/CanonicalDefinitionPhysicalSchemaCompilerTest.php",
        "methods": [
          "testReferenceIdentityUsesGuidPrimaryKeyAndScopedAlternateUniqueIndex",
          "testConstraintNamesCannotCollideAcrossDefinitions",
          "testVirtualFormulaIsOmittedAndStoredFormulaUsesItsExactResultType",
          "testStructuredRuntimeDefaultIsNotEmittedAsANonPortableJsonDatabaseDefault",
          "testPortableTextLengthBoundaryIsPreservedWithoutSilentCapping",
          "testForeignKeySupportIndexIsAlwaysExplicitInThePortableBlueprint",
          "testAReversalCompilesToARestrictedSelfTargetColumn"
        ],
        "retained_methods": [],
        "remove_whole_file": false,
        "retained_assertions": [
          "BusinessDefinitionValidator assertion belongs to the definition owner and was not extracted."
        ]
      },
      {
        "owner": "app",
        "baseline_commit": "24ecf956423c18933e824b43cea1bfb9127a79a9",
        "path": "tests/Unit/BusinessSchema/Domain/SchemaEvolutionHintsTest.php",
        "methods": [
          "testLiteralAndExpressionBackfillsRoundTripCanonically",
          "testAmbiguousRenameAndEvolutionKeyTyposFailClosed"
        ],
        "retained_methods": [],
        "remove_whole_file": true
      },
      {
        "owner": "app",
        "baseline_commit": "24ecf956423c18933e824b43cea1bfb9127a79a9",
        "path": "tests/Unit/BusinessSchema/Domain/SchemaInstallationTest.php",
        "methods": [
          "testExtensionPackageOwnerIdentityIsPreserved"
        ],
        "retained_methods": [],
        "remove_whole_file": true
      },
      {
        "owner": "app",
        "baseline_commit": "24ecf956423c18933e824b43cea1bfb9127a79a9",
        "path": "tests/Unit/BusinessSchema/Domain/SchemaPlanTest.php",
        "methods": [
          "testApprovalIsChecksumBoundAndExecutionIsFenceBound",
          "testHighImpactPlanRequiresExactConfirmationAndRecoveryEvidence"
        ],
        "retained_methods": [],
        "remove_whole_file": true
      },
      {
        "owner": "app",
        "baseline_commit": "24ecf956423c18933e824b43cea1bfb9127a79a9",
        "path": "tests/Unit/BusinessSchema/Domain/SchemaRecoveryContractTest.php",
        "methods": [
          "testCanonicalPlanOrderAndChecksumSurviveEveryRecoveryTransition",
          "testPersistedPlanRejectsCanonicalAndApprovalChecksumDrift",
          "testLockingApprovalRequiresConfirmationAndSourceBoundEvidence",
          "testRecoveryEvidenceQualifiesOnlyForTheExactFreshEnvironmentAndSource",
          "testInterruptedStepAdvancesAttemptAndFenceBeforeCompletion"
        ],
        "retained_methods": [],
        "remove_whole_file": true
      }
    ],
    "tests_to_retain_or_add": [
      "Host responsibility cases listed above",
      "Native parity against committed semantic corpus where applicable"
    ],
    "di_or_provisioning_changes": [
      "Kumwe\\BusinessSchema\\Contract\\DefinitionSchemaLookup",
      "Kumwe\\BusinessDefinition\\Application\\FieldTypeDefinitionResolver",
      "Kumwe\\BusinessSchema\\Domain\\PhysicalNameCompiler"
    ],
    "capability_index_changes": [
      "Record actual release and package responsibility without declaring composed roadmap completion."
    ],
    "changelog_and_evidence_changes": [
      "Record immutable artifact, attestation and remaining host acceptance gates."
    ],
    "verification_commands": [
      "composer check",
      "composer clean-consumer",
      "App affected integration train and platform matrix"
    ]
  },
  "concurrency": {
    "likely_conflict_files": [
      "App composer.json",
      "App composer.lock",
      "App capability and migration registries"
    ],
    "related_migrations": [
      "KUMWE-MIG-2026-029",
      "KUMWE-MIG-2026-031",
      "KUMWE-MIG-2026-032",
      "KUMWE-MIG-2026-033"
    ],
    "ownership_conflicts": [],
    "integration_train": "Framework 4 Business Data",
    "resolution_rule": "semantic-preservation"
  },
  "governance": {
    "roadmap_source_sha256": "a202155ef1a65f5ab293d4f8397ebf4ac430db7f1e877c776bbe7851e6fe18d8",
    "roadmap_refs": [],
    "non_roadmap_refs": [
      "NRM-2026-030"
    ],
    "completion_claim": false
  },
  "decisions": [
    "Canonical namespace and approved value behavior retained.",
    "No host authority or persistence moves into the package.",
    "See CHARTER.md for explicit dependency amendments; no release approval is inferred."
  ],
  "blockers": [
    "Immutable upstream releases and external attestations are not available for the entire dependency closure. No publication or App adoption is authorized by this candidate.",
    "Package candidate source checks do not substitute for clean immutable release verification.",
    "Review the explicit dependency-ceiling amendment in CHARTER.md before publication."
  ]
}
```
