---
schema: "kumwe-package-release-record/v1"
artifact_kind: "framework_php"
migration_id: "KUMWE-MIG-2026-030"
change_set: "KUMWE-CS-2026-030"
source:
  app:
    repository: "https://github.com/kumwe/app"
    baseline_commit: "24ecf956423c18933e824b43cea1bfb9127a79a9"
    examined_paths:
      - "docs/architecture/governance/core-growth-baseline.json"
      - "src/BusinessRecord/Application/BusinessRecordMutationGeneration.php"
      - "src/BusinessRecord/Application/InstalledBusinessRecordDefinitionResolver.php"
      - "src/BusinessRecord/Application/RecordValueCodec.php"
      - "src/BusinessRecord/Application/ResolvedBusinessDefinition.php"
      - "src/BusinessRecord/Infrastructure/Persistence/DoctrineBusinessRecordMutationFence.php"
      - "src/BusinessRecord/Infrastructure/Persistence/DoctrineBusinessRecordQueryCompiler.php"
      - "src/BusinessRecord/Infrastructure/Persistence/DoctrineBusinessRecordReadRepository.php"
      - "src/BusinessRecord/Infrastructure/Persistence/DoctrineBusinessRecordWriteRepository.php"
      - "src/BusinessRecord/Infrastructure/Persistence/DoctrineBusinessSchemaRecordRepinGateway.php"
      - "src/BusinessRecord/Infrastructure/Persistence/DoctrineRecordSecretRotation.php"
      - "src/BusinessRecord/Infrastructure/Persistence/OwnedLineWritePlan.php"
      - "src/BusinessSchema/Application/BusinessSchemaExecutionStateGuard.php"
      - "src/BusinessSchema/Application/BusinessSchemaExecutor.php"
      - "src/BusinessSchema/Application/BusinessSchemaInstallationRepository.php"
      - "src/BusinessSchema/Application/BusinessSchemaLifecycleManager.php"
      - "src/BusinessSchema/Application/BusinessSchemaPlanRepository.php"
      - "src/BusinessSchema/Application/BusinessSchemaPlanner.php"
      - "src/BusinessSchema/Application/BusinessSchemaRecordRepinGateway.php"
      - "src/BusinessSchema/Application/BusinessSchemaRecoveryEvidenceRepository.php"
      - "src/BusinessSchema/Application/BusinessSchemaService.php"
      - "src/BusinessSchema/Application/DefinitionPhysicalSchemaCompiler.php"
      - "src/BusinessSchema/Application/PhysicalSchemaGateway.php"
      - "src/BusinessSchema/Application/PublishedDefinitionSchemaObserver.php"
      - "src/BusinessSchema/Delivery/Administrator/BusinessSchemaPlansHandler.php"
      - "src/BusinessSchema/Delivery/Administrator/RecordBusinessSchemaRecoveryEvidenceHandler.php"
      - "src/BusinessSchema/Delivery/Api/BusinessSchemaApiHandler.php"
      - "src/BusinessSchema/Delivery/Api/BusinessSchemaApiPresenter.php"
      - "src/BusinessSchema/Domain/InvalidBusinessSchema.php"
      - "src/BusinessSchema/Domain/PhysicalColumnBlueprint.php"
      - "src/BusinessSchema/Domain/PhysicalForeignKeyBlueprint.php"
      - "src/BusinessSchema/Domain/PhysicalIndexBlueprint.php"
      - "src/BusinessSchema/Domain/PhysicalNameCompiler.php"
      - "src/BusinessSchema/Domain/PhysicalSchemaBlueprint.php"
      - "src/BusinessSchema/Domain/PhysicalTableBlueprint.php"
      - "src/BusinessSchema/Domain/PhysicalTableKind.php"
      - "src/BusinessSchema/Domain/SchemaDocument.php"
      - "src/BusinessSchema/Domain/SchemaEvolutionHints.php"
      - "src/BusinessSchema/Domain/SchemaInstallation.php"
      - "src/BusinessSchema/Domain/SchemaInstallationStatus.php"
      - "src/BusinessSchema/Domain/SchemaOperation.php"
      - "src/BusinessSchema/Domain/SchemaOperationKind.php"
      - "src/BusinessSchema/Domain/SchemaPlan.php"
      - "src/BusinessSchema/Domain/SchemaPlanApproval.php"
      - "src/BusinessSchema/Domain/SchemaPlanStatus.php"
      - "src/BusinessSchema/Domain/SchemaPlanStep.php"
      - "src/BusinessSchema/Domain/SchemaRecoveryEvidence.php"
      - "src/BusinessSchema/Domain/SchemaRisk.php"
      - "src/BusinessSchema/Domain/SchemaStepStatus.php"
      - "src/BusinessSchema/Infrastructure/Execution/DoctrineBusinessSchemaExecutionStateGuard.php"
      - "src/BusinessSchema/Infrastructure/Persistence/DoctrineBusinessSchemaInstallationRepository.php"
      - "src/BusinessSchema/Infrastructure/Persistence/DoctrineBusinessSchemaPlanRepository.php"
      - "src/BusinessSchema/Infrastructure/Persistence/DoctrineBusinessSchemaRecoveryEvidenceRepository.php"
      - "src/BusinessSchema/Infrastructure/Schema/CanonicalDefinitionPhysicalSchemaCompiler.php"
      - "src/BusinessSchema/Infrastructure/Schema/DoctrinePhysicalSchemaGateway.php"
      - "src/BusinessSecurity/Infrastructure/Persistence/DoctrineBusinessRecordAccessController.php"
      - "src/Delivery/Console/Command/ManageBusinessSchemaCommand.php"
      - "src/Demo/Infrastructure/VdmBusinessDemoInstaller.php"
      - "src/Infrastructure/Mcp/KumweMcpHandlers.php"
      - "src/Infrastructure/Persistence/Migration/BusinessSecurityPortalMigration.php"
      - "src/Kernel/ContainerFactory.php"
      - "src/Kernel/DeferredBusinessSchemaObserver.php"
      - "tests/Integration/BusinessRecord/BusinessRecordEvolutionIntegrationTest.php"
      - "tests/Integration/BusinessRecord/BusinessRecordInverseRelationshipIntegrationTest.php"
      - "tests/Integration/BusinessRecord/BusinessRecordMutationGenerationIntegrationTest.php"
      - "tests/Integration/BusinessRecord/BusinessRecordRelationshipIntegrationTest.php"
      - "tests/Integration/BusinessRecord/BusinessRecordRuntimeIntegrationTest.php"
      - "tests/Integration/BusinessRecord/ImmutableRecordReversalIntegrationTest.php"
      - "tests/Integration/BusinessSchema/BusinessSchemaColumnRelaxationIntegrationTest.php"
      - "tests/Integration/BusinessSchema/BusinessSchemaExecutionStateGuardIntegrationTest.php"
      - "tests/Integration/BusinessSchema/BusinessSchemaRecoveryIntegrationTest.php"
      - "tests/Integration/BusinessSchema/BusinessSchemaRuntimeIntegrationTest.php"
      - "tests/Integration/BusinessSchema/BusinessSchemaSourceBindingRecoveryIntegrationTest.php"
      - "tests/Integration/Performance/HotPlanRegressionIntegrationTest.php"
      - "tests/Support/AssetInspectionDeploymentAcceptance.php"
      - "tests/Support/BusinessRuntimeBackupAcceptance.php"
      - "tests/Support/NeutralBusinessFixture.php"
      - "tests/Support/TransientBusinessDefinitionFixtureScope.php"
      - "tests/Unit/BusinessRecord/Application/BusinessRecordRelationshipCoordinatorTest.php"
      - "tests/Unit/BusinessRecord/Domain/ExactValueCodecTest.php"
      - "tests/Unit/BusinessRecord/NeutralBusinessFixtureTest.php"
      - "tests/Unit/BusinessReporting/RecordExportPipelineTest.php"
      - "tests/Unit/BusinessReporting/RecordExportReportProviderTest.php"
      - "tests/Unit/BusinessSchema/Domain/PhysicalNameCompilerTest.php"
      - "tests/Unit/BusinessSchema/Domain/SchemaEvolutionHintsTest.php"
      - "tests/Unit/BusinessSchema/Domain/SchemaInstallationTest.php"
      - "tests/Unit/BusinessSchema/Domain/SchemaPlanTest.php"
      - "tests/Unit/BusinessSchema/Domain/SchemaRecoveryContractTest.php"
      - "tests/Unit/BusinessSchema/Infrastructure/CanonicalDefinitionPhysicalSchemaCompilerTest.php"
      - "tests/Unit/BusinessSchema/Infrastructure/DoctrinePhysicalSchemaGatewayDefaultTest.php"
      - "tests/Unit/BusinessSurface/Application/Custom/CustomBusinessActionExecutorTest.php"
      - "tests/Unit/Demo/Infrastructure/VdmBusinessDefinitionSchemaTest.php"
      - "tests/Unit/Support/TransientBusinessDefinitionFixtureScopeTest.php"
    old_namespace_roots:
      - "Kumwe\\App\\BusinessRecord\\"
      - "Kumwe\\App\\BusinessSchema\\"
      - "Kumwe\\App\\BusinessReporting\\"
    capability_index_sha256: null
  semantic_inputs:
    -
      owner: "kumwe/extension-sdk"
      version_or_commit: "e8ec23f155c5836c6bd083f154a8efb6e50aec66"
      manifest_or_corpus: "resources/extraction/v1.json"
      sha256: "3767932a89b33f6d856b734bb150c4622726aa01df2d0fa8c23b4ef8507bd146"
  examined_dependencies:
    - "kumwe/business-definition 0.1.2; independent release attestation not asserted"
    - "kumwe/sequence 0.2.1; independent release attestation not asserted"
target:
  repository: "https://github.com/kumwe/business-schema"
  artifact_identity: "kumwe/business-schema"
  canonical_namespace_or_abi: "Kumwe\\BusinessSchema\\"
ownership:
  responsibility: "Portable physical schema blueprints, deterministic change plans and recovery values."
  non_responsibilities:
    - "authorization"
    - "trusted generation selection"
    - "persistence"
    - "SQL execution"
    - "transactions"
    - "delivery"
    - "native execution"
  allowed_dependency_ceiling:
    - "php"
    - "php-64bit"
    - "ext-json"
    - "kumwe/business-definition"
    - "ramsey/uuid"
    - "kumwe/sequence"
    - "ext-mbstring"
    - "psr/container"
  implementation_owner: "kumwe/business-schema"
  next_consumer: "kumwe/app"
  public_manifests:
    -
      path: "resources/public-api/v1.json"
      sha256: "d3dd01caf6d4b6162c54f87797aadc38457754866f1abeb5bfb31d16c6b54178"
    -
      path: "resources/capabilities/v1.json"
      sha256: "9df001a9b1b911e9fa012d2a6de75ae34f9ab8e1c647a59ddb9af529f4b60bd7"
    -
      path: "resources/service-map/v1.json"
      sha256: "d7a0f0d59aa94f0f251c598c75a4b97072cbd213e1957e93d321200f268c607b"
    -
      path: "resources/test-ownership/v1.json"
      sha256: "98d475d96b24535ee998005ef5fb256cdcd74105b1b2fa5a0b21dd15bedcfffe"
  intentionally_excluded:
    - "App repositories, policy gates and lifecycle orchestration"
    - "production PHP native executor fallback"
framework_php:
  composer_package: "kumwe/business-schema"
  canonical_namespace: "Kumwe\\BusinessSchema\\"
  public_api_manifest: "resources/public-api/v1.json"
  capability_manifest: "resources/capabilities/v1.json"
  service_map: "resources/service-map/v1.json"
  extracted_symbols:
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\InvalidBusinessSchema"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\InvalidBusinessSchema"
      source_path: "src/BusinessSchema/Domain/InvalidBusinessSchema.php"
      target_path: "src/Domain/InvalidBusinessSchema.php"
      kind: "class"
      public_methods: []
      public_properties: []
      public_constants: []
      exceptions: []
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\PhysicalColumnBlueprint"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\PhysicalColumnBlueprint"
      source_path: "src/BusinessSchema/Domain/PhysicalColumnBlueprint.php"
      target_path: "src/Domain/PhysicalColumnBlueprint.php"
      kind: "class"
      public_methods:
        - "__construct"
        - "fromArray"
        - "toArray"
      public_properties:
        - "options"
        - "logicalName"
        - "physicalName"
        - "doctrineType"
        - "nullable"
      public_constants: []
      exceptions:
        - "InvalidBusinessSchema"
        - "\\Kumwe\\BusinessDefinition\\Domain\\InvalidBusinessDefinition"
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\PhysicalForeignKeyBlueprint"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\PhysicalForeignKeyBlueprint"
      source_path: "src/BusinessSchema/Domain/PhysicalForeignKeyBlueprint.php"
      target_path: "src/Domain/PhysicalForeignKeyBlueprint.php"
      kind: "class"
      public_methods:
        - "__construct"
        - "fromArray"
        - "toArray"
      public_properties:
        - "localColumns"
        - "foreignColumns"
        - "logicalName"
        - "physicalName"
        - "foreignTable"
        - "onDelete"
        - "onUpdate"
      public_constants: []
      exceptions:
        - "InvalidBusinessSchema"
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\PhysicalIndexBlueprint"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\PhysicalIndexBlueprint"
      source_path: "src/BusinessSchema/Domain/PhysicalIndexBlueprint.php"
      target_path: "src/Domain/PhysicalIndexBlueprint.php"
      kind: "class"
      public_methods:
        - "__construct"
        - "fromArray"
        - "toArray"
      public_properties:
        - "columns"
        - "options"
        - "logicalName"
        - "physicalName"
        - "unique"
      public_constants: []
      exceptions:
        - "InvalidBusinessSchema"
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\PhysicalNameCompiler"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\PhysicalNameCompiler"
      source_path: "src/BusinessSchema/Domain/PhysicalNameCompiler.php"
      target_path: "src/Domain/PhysicalNameCompiler.php"
      kind: "class"
      public_methods:
        - "__construct"
        - "entityTable"
        - "relationTable"
        - "lineTable"
        - "column"
        - "index"
        - "foreignKey"
        - "compile"
      public_properties: []
      public_constants: []
      exceptions:
        - "InvalidBusinessSchema"
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\PhysicalSchemaBlueprint"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\PhysicalSchemaBlueprint"
      source_path: "src/BusinessSchema/Domain/PhysicalSchemaBlueprint.php"
      target_path: "src/Domain/PhysicalSchemaBlueprint.php"
      kind: "class"
      public_methods:
        - "__construct"
        - "fromArray"
        - "tables"
        - "table"
        - "toArray"
        - "checksum"
      public_properties:
        - "definitionId"
        - "definitionVersion"
        - "definitionChecksum"
      public_constants: []
      exceptions:
        - "InvalidBusinessSchema"
        - "\\Kumwe\\BusinessDefinition\\Domain\\InvalidBusinessDefinition"
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\PhysicalTableBlueprint"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\PhysicalTableBlueprint"
      source_path: "src/BusinessSchema/Domain/PhysicalTableBlueprint.php"
      target_path: "src/Domain/PhysicalTableBlueprint.php"
      kind: "class"
      public_methods:
        - "__construct"
        - "fromArray"
        - "columns"
        - "column"
        - "physicalColumn"
        - "indexes"
        - "foreignKeys"
        - "toArray"
      public_properties:
        - "primaryKey"
        - "options"
        - "logicalName"
        - "physicalName"
        - "kind"
      public_constants: []
      exceptions:
        - "InvalidBusinessSchema"
        - "\\Kumwe\\BusinessDefinition\\Domain\\InvalidBusinessDefinition"
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\PhysicalTableKind"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\PhysicalTableKind"
      source_path: "src/BusinessSchema/Domain/PhysicalTableKind.php"
      target_path: "src/Domain/PhysicalTableKind.php"
      kind: "enum"
      public_methods:
        - "cases"
        - "from"
        - "tryFrom"
      public_properties:
        - "name"
        - "value"
      public_constants:
        - "Entity"
        - "Relation"
        - "Junction"
        - "OwnedLine"
      exceptions: []
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\SchemaDocument"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\SchemaDocument"
      source_path: "src/BusinessSchema/Domain/SchemaDocument.php"
      target_path: "src/Domain/SchemaDocument.php"
      kind: "class"
      public_methods:
        - "assertOnly"
        - "string"
        - "nullableString"
        - "integer"
        - "nullableInteger"
        - "boolean"
        - "object"
        - "objects"
        - "strings"
        - "assertUuid"
        - "assertChecksum"
        - "assertIdentifier"
        - "assertBoundedText"
        - "assertObjectValue"
        - "assertPhysicalIdentifier"
        - "date"
        - "formatDate"
      public_properties: []
      public_constants: []
      exceptions:
        - "InvalidBusinessSchema"
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\SchemaEvolutionHints"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\SchemaEvolutionHints"
      source_path: "src/BusinessSchema/Domain/SchemaEvolutionHints.php"
      target_path: "src/Domain/SchemaEvolutionHints.php"
      kind: "class"
      public_methods:
        - "fromDefinition"
        - "fromArray"
        - "renameForTable"
        - "hasBackfill"
        - "backfill"
        - "transform"
        - "transforms"
        - "renames"
        - "backfills"
        - "repin"
        - "repins"
        - "toArray"
        - "checksum"
      public_properties: []
      public_constants: []
      exceptions:
        - "InvalidBusinessSchema"
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\SchemaInstallation"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\SchemaInstallation"
      source_path: "src/BusinessSchema/Domain/SchemaInstallation.php"
      target_path: "src/Domain/SchemaInstallation.php"
      kind: "class"
      public_methods:
        - "__construct"
        - "fromArray"
        - "disable"
        - "reactivate"
        - "preserve"
        - "toArray"
      public_properties:
        - "definitionId"
        - "siteIdentifier"
        - "ownerIdentifier"
        - "definitionVersion"
        - "definitionChecksum"
        - "schemaChecksum"
        - "blueprint"
        - "status"
        - "installedAt"
        - "updatedAt"
      public_constants: []
      exceptions:
        - "InvalidBusinessSchema"
        - "\\Kumwe\\BusinessDefinition\\Domain\\InvalidBusinessDefinition"
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\SchemaInstallationStatus"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\SchemaInstallationStatus"
      source_path: "src/BusinessSchema/Domain/SchemaInstallationStatus.php"
      target_path: "src/Domain/SchemaInstallationStatus.php"
      kind: "enum"
      public_methods:
        - "cases"
        - "from"
        - "tryFrom"
      public_properties:
        - "name"
        - "value"
      public_constants:
        - "Installing"
        - "Active"
        - "Disabled"
        - "Preserved"
        - "Failed"
      exceptions: []
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\SchemaOperation"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\SchemaOperation"
      source_path: "src/BusinessSchema/Domain/SchemaOperation.php"
      target_path: "src/Domain/SchemaOperation.php"
      kind: "class"
      public_methods:
        - "__construct"
        - "fromArray"
        - "toArray"
        - "persistedArray"
        - "checksum"
      public_properties:
        - "before"
        - "after"
        - "ordinal"
        - "kind"
        - "risk"
        - "table"
        - "subject"
        - "requiresBackfill"
        - "recoveryImplication"
      public_constants: []
      exceptions:
        - "InvalidBusinessSchema"
        - "\\Kumwe\\BusinessDefinition\\Domain\\InvalidBusinessDefinition"
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\SchemaOperationKind"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\SchemaOperationKind"
      source_path: "src/BusinessSchema/Domain/SchemaOperationKind.php"
      target_path: "src/Domain/SchemaOperationKind.php"
      kind: "enum"
      public_methods:
        - "cases"
        - "from"
        - "tryFrom"
      public_properties:
        - "name"
        - "value"
      public_constants:
        - "CreateTable"
        - "RenameTable"
        - "DropTable"
        - "AddColumn"
        - "AlterColumn"
        - "RenameColumn"
        - "DropColumn"
        - "AddPrimaryKey"
        - "DropPrimaryKey"
        - "AddIndex"
        - "DropIndex"
        - "AddForeignKey"
        - "DropForeignKey"
        - "Backfill"
        - "Transform"
        - "RepinRecords"
        - "ValidateConstraint"
      exceptions: []
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\SchemaPlan"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\SchemaPlan"
      source_path: "src/BusinessSchema/Domain/SchemaPlan.php"
      target_path: "src/Domain/SchemaPlan.php"
      kind: "class"
      public_methods:
        - "__construct"
        - "fromArray"
        - "operations"
        - "approve"
        - "begin"
        - "resume"
        - "complete"
        - "fail"
        - "recoveryRequired"
        - "compensate"
        - "canonicalPlan"
        - "checksum"
        - "toArray"
      public_properties:
        - "outcome"
        - "updatedAt"
        - "id"
        - "definitionId"
        - "siteIdentifier"
        - "fromDefinitionVersion"
        - "toDefinitionVersion"
        - "fromDefinitionChecksum"
        - "toDefinitionChecksum"
        - "fromSchemaChecksum"
        - "targetSchemaChecksum"
        - "risk"
        - "status"
        - "revision"
        - "createdBy"
        - "createdAt"
        - "approval"
        - "recoveryEvidenceId"
        - "executionFence"
      public_constants: []
      exceptions:
        - "InvalidBusinessSchema"
        - "\\Kumwe\\BusinessDefinition\\Domain\\InvalidBusinessDefinition"
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\SchemaPlanApproval"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\SchemaPlanApproval"
      source_path: "src/BusinessSchema/Domain/SchemaPlanApproval.php"
      target_path: "src/Domain/SchemaPlanApproval.php"
      kind: "class"
      public_methods:
        - "__construct"
        - "fromArray"
        - "toArray"
      public_properties:
        - "actorIdentifier"
        - "approvedAt"
        - "approvedChecksum"
        - "confirmationDigest"
      public_constants: []
      exceptions:
        - "InvalidBusinessSchema"
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\SchemaPlanStatus"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\SchemaPlanStatus"
      source_path: "src/BusinessSchema/Domain/SchemaPlanStatus.php"
      target_path: "src/Domain/SchemaPlanStatus.php"
      kind: "enum"
      public_methods:
        - "terminal"
        - "cases"
        - "from"
        - "tryFrom"
      public_properties:
        - "name"
        - "value"
      public_constants:
        - "PendingApproval"
        - "Approved"
        - "Executing"
        - "Completed"
        - "Failed"
        - "RecoveryRequired"
        - "Compensated"
        - "Cancelled"
      exceptions: []
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\SchemaPlanStep"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\SchemaPlanStep"
      source_path: "src/BusinessSchema/Domain/SchemaPlanStep.php"
      target_path: "src/Domain/SchemaPlanStep.php"
      kind: "class"
      public_methods:
        - "__construct"
        - "fromArray"
        - "pending"
        - "start"
        - "resume"
        - "checkpoint"
        - "complete"
        - "fail"
        - "toArray"
      public_properties:
        - "cursor"
        - "outcome"
        - "planId"
        - "ordinal"
        - "operationChecksum"
        - "operationKind"
        - "risk"
        - "state"
        - "attempt"
        - "executionFence"
        - "beforeSchemaChecksum"
        - "afterSchemaChecksum"
        - "errorCode"
        - "startedAt"
        - "completedAt"
        - "updatedAt"
      public_constants: []
      exceptions:
        - "InvalidBusinessSchema"
        - "\\Kumwe\\BusinessDefinition\\Domain\\InvalidBusinessDefinition"
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\SchemaRecoveryEvidence"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\SchemaRecoveryEvidence"
      source_path: "src/BusinessSchema/Domain/SchemaRecoveryEvidence.php"
      target_path: "src/Domain/SchemaRecoveryEvidence.php"
      kind: "class"
      public_methods:
        - "__construct"
        - "fromArray"
        - "qualifies"
        - "toArray"
        - "checksum"
      public_properties:
        - "details"
        - "id"
        - "siteIdentifier"
        - "databaseDriver"
        - "databaseServerVersion"
        - "applicationRelease"
        - "sourceSchemaChecksum"
        - "backupManifestChecksum"
        - "restoreTested"
        - "backupCreatedAt"
        - "verifiedAt"
        - "verifiedBy"
        - "drillReference"
      public_constants: []
      exceptions:
        - "InvalidBusinessSchema"
        - "\\Kumwe\\BusinessDefinition\\Domain\\InvalidBusinessDefinition"
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\SchemaRisk"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\SchemaRisk"
      source_path: "src/BusinessSchema/Domain/SchemaRisk.php"
      target_path: "src/Domain/SchemaRisk.php"
      kind: "enum"
      public_methods:
        - "severity"
        - "requiresHighImpactAuthorization"
        - "requiresRecoveryEvidence"
        - "highest"
        - "cases"
        - "from"
        - "tryFrom"
      public_properties:
        - "name"
        - "value"
      public_constants:
        - "OnlineSafeAdditive"
        - "BackfillRequired"
        - "RebuildOrLocking"
        - "BehaviorChanging"
        - "Destructive"
      exceptions: []
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Domain\\SchemaStepStatus"
      new_fqcn: "Kumwe\\BusinessSchema\\Domain\\SchemaStepStatus"
      source_path: "src/BusinessSchema/Domain/SchemaStepStatus.php"
      target_path: "src/Domain/SchemaStepStatus.php"
      kind: "enum"
      public_methods:
        - "terminal"
        - "cases"
        - "from"
        - "tryFrom"
      public_properties:
        - "name"
        - "value"
      public_constants:
        - "Pending"
        - "Running"
        - "Completed"
        - "Failed"
        - "Compensated"
        - "Skipped"
      exceptions: []
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Infrastructure\\Schema\\CanonicalDefinitionPhysicalSchemaCompiler"
      new_fqcn: "Kumwe\\BusinessSchema\\Compiler\\CanonicalDefinitionPhysicalSchemaCompiler"
      source_path: "src/BusinessSchema/Infrastructure/Schema/CanonicalDefinitionPhysicalSchemaCompiler.php"
      target_path: "src/Compiler/CanonicalDefinitionPhysicalSchemaCompiler.php"
      kind: "class"
      public_methods:
        - "__construct"
        - "compile"
      public_properties: []
      public_constants: []
      exceptions:
        - "InvalidBusinessSchema"
        - "\\Kumwe\\BusinessDefinition\\Domain\\InvalidBusinessDefinition"
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
    -
      old_fqcn: "Kumwe\\App\\BusinessSchema\\Application\\BusinessSchemaPlanner"
      new_fqcn: "Kumwe\\BusinessSchema\\Planner\\SchemaChangePlanner"
      source_path: "src/BusinessSchema/Application/BusinessSchemaPlanner.php"
      target_path: "src/Planner/SchemaChangePlanner.php"
      kind: "class"
      public_methods:
        - "operations"
        - "containsPinnedRowBreakingChange"
        - "hasRecordRepin"
        - "dependencyHandles"
      public_properties: []
      public_constants: []
      exceptions:
        - "InvalidBusinessSchema"
        - "BusinessSchemaNotFound"
      serialization_contract: "See docs/public-api.md and package conformance corpus for exact serialization."
      compatibility: "Portable source closure; host authority stays with the consumer. See resources/extraction/v1.json for source ownership and extraction granularity."
  consumers:
    app_code:
      - "src/BusinessRecord/Application/BusinessRecordMutationGeneration.php"
      - "src/BusinessRecord/Application/InstalledBusinessRecordDefinitionResolver.php"
      - "src/BusinessRecord/Application/RecordValueCodec.php"
      - "src/BusinessRecord/Application/ResolvedBusinessDefinition.php"
      - "src/BusinessRecord/Infrastructure/Persistence/DoctrineBusinessRecordMutationFence.php"
      - "src/BusinessRecord/Infrastructure/Persistence/DoctrineBusinessRecordQueryCompiler.php"
      - "src/BusinessRecord/Infrastructure/Persistence/DoctrineBusinessRecordReadRepository.php"
      - "src/BusinessRecord/Infrastructure/Persistence/DoctrineBusinessRecordWriteRepository.php"
      - "src/BusinessRecord/Infrastructure/Persistence/DoctrineBusinessSchemaRecordRepinGateway.php"
      - "src/BusinessRecord/Infrastructure/Persistence/DoctrineRecordSecretRotation.php"
      - "src/BusinessRecord/Infrastructure/Persistence/OwnedLineWritePlan.php"
      - "src/BusinessSchema/Application/BusinessSchemaExecutionStateGuard.php"
      - "src/BusinessSchema/Application/BusinessSchemaExecutor.php"
      - "src/BusinessSchema/Application/BusinessSchemaInstallationRepository.php"
      - "src/BusinessSchema/Application/BusinessSchemaLifecycleManager.php"
      - "src/BusinessSchema/Application/BusinessSchemaPlanRepository.php"
      - "src/BusinessSchema/Application/BusinessSchemaPlanner.php"
      - "src/BusinessSchema/Application/BusinessSchemaRecordRepinGateway.php"
      - "src/BusinessSchema/Application/BusinessSchemaRecoveryEvidenceRepository.php"
      - "src/BusinessSchema/Application/BusinessSchemaService.php"
      - "src/BusinessSchema/Application/DefinitionPhysicalSchemaCompiler.php"
      - "src/BusinessSchema/Application/PhysicalSchemaGateway.php"
      - "src/BusinessSchema/Application/PublishedDefinitionSchemaObserver.php"
      - "src/BusinessSchema/Delivery/Administrator/BusinessSchemaPlansHandler.php"
      - "src/BusinessSchema/Delivery/Administrator/RecordBusinessSchemaRecoveryEvidenceHandler.php"
      - "src/BusinessSchema/Delivery/Api/BusinessSchemaApiHandler.php"
      - "src/BusinessSchema/Delivery/Api/BusinessSchemaApiPresenter.php"
      - "src/BusinessSchema/Infrastructure/Execution/DoctrineBusinessSchemaExecutionStateGuard.php"
      - "src/BusinessSchema/Infrastructure/Persistence/DoctrineBusinessSchemaInstallationRepository.php"
      - "src/BusinessSchema/Infrastructure/Persistence/DoctrineBusinessSchemaPlanRepository.php"
      - "src/BusinessSchema/Infrastructure/Persistence/DoctrineBusinessSchemaRecoveryEvidenceRepository.php"
      - "src/BusinessSchema/Infrastructure/Schema/CanonicalDefinitionPhysicalSchemaCompiler.php"
      - "src/BusinessSchema/Infrastructure/Schema/DoctrinePhysicalSchemaGateway.php"
      - "src/BusinessSecurity/Infrastructure/Persistence/DoctrineBusinessRecordAccessController.php"
      - "src/Delivery/Console/Command/ManageBusinessSchemaCommand.php"
      - "src/Demo/Infrastructure/VdmBusinessDemoInstaller.php"
      - "src/Infrastructure/Mcp/KumweMcpHandlers.php"
      - "src/Infrastructure/Persistence/Migration/BusinessSecurityPortalMigration.php"
      - "src/Kernel/ContainerFactory.php"
      - "src/Kernel/DeferredBusinessSchemaObserver.php"
    configuration_and_di:
      - "docs/architecture/governance/core-growth-baseline.json"
    reflection_and_string_references: []
    fixtures_and_examples:
      - "tests/Integration/BusinessRecord/BusinessRecordEvolutionIntegrationTest.php"
      - "tests/Integration/BusinessRecord/BusinessRecordInverseRelationshipIntegrationTest.php"
      - "tests/Integration/BusinessRecord/BusinessRecordMutationGenerationIntegrationTest.php"
      - "tests/Integration/BusinessRecord/BusinessRecordRelationshipIntegrationTest.php"
      - "tests/Integration/BusinessRecord/BusinessRecordRuntimeIntegrationTest.php"
      - "tests/Integration/BusinessRecord/ImmutableRecordReversalIntegrationTest.php"
      - "tests/Integration/BusinessSchema/BusinessSchemaColumnRelaxationIntegrationTest.php"
      - "tests/Integration/BusinessSchema/BusinessSchemaExecutionStateGuardIntegrationTest.php"
      - "tests/Integration/BusinessSchema/BusinessSchemaRecoveryIntegrationTest.php"
      - "tests/Integration/BusinessSchema/BusinessSchemaRuntimeIntegrationTest.php"
      - "tests/Integration/BusinessSchema/BusinessSchemaSourceBindingRecoveryIntegrationTest.php"
      - "tests/Integration/Performance/HotPlanRegressionIntegrationTest.php"
      - "tests/Support/AssetInspectionDeploymentAcceptance.php"
      - "tests/Support/BusinessRuntimeBackupAcceptance.php"
      - "tests/Support/NeutralBusinessFixture.php"
      - "tests/Support/TransientBusinessDefinitionFixtureScope.php"
      - "tests/Unit/BusinessRecord/Application/BusinessRecordRelationshipCoordinatorTest.php"
      - "tests/Unit/BusinessRecord/Domain/ExactValueCodecTest.php"
      - "tests/Unit/BusinessRecord/NeutralBusinessFixtureTest.php"
      - "tests/Unit/BusinessReporting/RecordExportPipelineTest.php"
      - "tests/Unit/BusinessReporting/RecordExportReportProviderTest.php"
      - "tests/Unit/BusinessSchema/Domain/PhysicalNameCompilerTest.php"
      - "tests/Unit/BusinessSchema/Domain/SchemaEvolutionHintsTest.php"
      - "tests/Unit/BusinessSchema/Domain/SchemaInstallationTest.php"
      - "tests/Unit/BusinessSchema/Domain/SchemaPlanTest.php"
      - "tests/Unit/BusinessSchema/Domain/SchemaRecoveryContractTest.php"
      - "tests/Unit/BusinessSchema/Infrastructure/CanonicalDefinitionPhysicalSchemaCompilerTest.php"
      - "tests/Unit/BusinessSchema/Infrastructure/DoctrinePhysicalSchemaGatewayDefaultTest.php"
      - "tests/Unit/BusinessSurface/Application/Custom/CustomBusinessActionExecutorTest.php"
      - "tests/Unit/Demo/Infrastructure/VdmBusinessDefinitionSchemaTest.php"
      - "tests/Unit/Support/TransientBusinessDefinitionFixtureScopeTest.php"
    external:
      - "kumwe/extension-sdk coordinated successor"
  dependency_injection:
    mode: "config-provider"
    provider: "Kumwe\\BusinessSchema\\ConfigProvider"
    factories:
      - "Kumwe\\BusinessSchema\\Container\\PhysicalSchemaCompilerFactory"
      - "Kumwe\\BusinessSchema\\Container\\SchemaChangePlannerFactory"
    aliases: []
    service_lifetimes:
      - "Kumwe\\BusinessSchema\\Compiler\\CanonicalDefinitionPhysicalSchemaCompiler: shared"
      - "Kumwe\\BusinessSchema\\Planner\\SchemaChangePlanner: shared"
    configuration_keys:
      - "Kumwe\\BusinessSchema\\Contract\\DefinitionSchemaLookup"
      - "Kumwe\\BusinessDefinition\\Application\\FieldTypeDefinitionResolver"
      - "Kumwe\\BusinessSchema\\Domain\\PhysicalNameCompiler"
    provider_absence_reason: null
native_cpp: null
php_extension: null
tests:
  moved_or_added:
    - "tests/SchemaChangePlannerTest.php (testDependencyHandlesIncludeAllReferenceFormsInStableUniqueOrder, testDependencyHandlesIgnoreMissingAndNonStringTargets); provenance: resources/test-ownership/v1.json"
    - "tests/ContainerTest.php (testRealContainerResolvesSharedCompilerWithExplicitHostPorts, testMissingHostAuthorityBindingDoesNotReceiveAnImplicitDefault); provenance: resources/test-ownership/v1.json"
    - "tests/PhysicalNameCompilerTest.php (testNamesAreDeterministicBoundedAndDefinitionScoped, testRejectsNonCanonicalPrefixesThatCouldCollapseOrProduceInvalidNames, testDistinctCanonicalPrefixesCannotCompileTheSamePhysicalName); provenance: resources/test-ownership/v1.json"
    - "tests/PhysicalSchemaCompilerTest.php (testReferenceIdentityUsesGuidPrimaryKeyAndScopedAlternateUniqueIndex, testConstraintNamesCannotCollideAcrossDefinitions, testVirtualFormulaIsOmittedAndStoredFormulaUsesItsExactResultType, testStructuredRuntimeDefaultIsNotEmittedAsANonPortableJsonDatabaseDefault, testPortableTextLengthBoundaryIsPreservedWithoutSilentCapping, testForeignKeySupportIndexIsAlwaysExplicitInThePortableBlueprint, testAReversalCompilesToARestrictedSelfTargetColumn); provenance: resources/test-ownership/v1.json"
    - "tests/SchemaEvolutionHintsTest.php (testLiteralAndExpressionBackfillsRoundTripCanonically, testAmbiguousRenameAndEvolutionKeyTyposFailClosed); provenance: resources/test-ownership/v1.json"
    - "tests/SchemaInstallationTest.php (testExtensionPackageOwnerIdentityIsPreserved); provenance: resources/test-ownership/v1.json"
    - "tests/SchemaPlanTest.php (testApprovalIsChecksumBoundAndExecutionIsFenceBound, testHighImpactPlanRequiresExactConfirmationAndRecoveryEvidence); provenance: resources/test-ownership/v1.json"
    - "tests/SchemaRecoveryContractTest.php (testCanonicalPlanOrderAndChecksumSurviveEveryRecoveryTransition, testPersistedPlanRejectsCanonicalAndApprovalChecksumDrift, testLockingApprovalRequiresConfirmationAndSourceBoundEvidence, testRecoveryEvidenceQualifiesOnlyForTheExactFreshEnvironmentAndSource, testInterruptedStepAdvancesAttemptAndFenceBeforeCompletion); provenance: resources/test-ownership/v1.json"
    - "tests/ValueImmutabilityTest.php (testSchemaOperationDetachesApprovedBeforeAndAfterStates); provenance: resources/test-ownership/v1.json"
  remain_in_app_or_consumer:
    - "SQL/database matrix"
    - "policy-before-query"
    - "authorization and generation fences"
    - "cryptographic envelope authenticity and key lifecycle"
    - "transaction/concurrency and recovery"
    - "export/delivery/adapters"
  split_tests: []
  prohibited_duplicates:
    - "Do not retain moved implementation tests in App/SDK after the separate verified adoption."
  corpora: []
documentation:
  charter: "CHARTER.md"
  readme: "README.md"
  public_api: "docs/public-api.md"
  architecture: "docs/architecture.md"
  integration_or_consumer: "docs/integration.md"
  examples:
    - "examples/consumer.php"
  changelog_record: "CHANGELOG.md / 0.1.3"
release_expectations:
  version_policy: "SemVer; direct Kumwe dependencies use coherent exact published stable versions. Independent release verification precedes Core adoption."
  expected_artifact_types:
    - "Composer source zip"
  required_checks:
    - "composer check"
    - "composer security:audit"
    - "composer clean-consumer"
    - "review dependency ceiling"
    - "immutable release and source/artifact manifests independently attested"
  required_registry_or_installer: "Composer"
  required_external_attestation: true
consumer_contract:
  permitted_only_when:
    - "Human merges package PR"
    - "Immutable upstream dependency releases and target release are independently verified"
    - "External RELEASE-ATTESTATION.yaml exists and matches all source/artifact identities"
  consumer_repository: "https://github.com/kumwe/app"
  dependency_or_native_change: "Exact-pin the reviewed immutable package release and remove the former implementation. Never use this development branch as a released dependency."
  namespace_or_api_replacements:
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/InvalidBusinessSchema.php\",\"target_path\":\"src/Domain/InvalidBusinessSchema.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/PhysicalColumnBlueprint.php\",\"target_path\":\"src/Domain/PhysicalColumnBlueprint.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/PhysicalForeignKeyBlueprint.php\",\"target_path\":\"src/Domain/PhysicalForeignKeyBlueprint.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/PhysicalIndexBlueprint.php\",\"target_path\":\"src/Domain/PhysicalIndexBlueprint.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/PhysicalNameCompiler.php\",\"target_path\":\"src/Domain/PhysicalNameCompiler.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/PhysicalSchemaBlueprint.php\",\"target_path\":\"src/Domain/PhysicalSchemaBlueprint.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/PhysicalTableBlueprint.php\",\"target_path\":\"src/Domain/PhysicalTableBlueprint.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/PhysicalTableKind.php\",\"target_path\":\"src/Domain/PhysicalTableKind.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/SchemaDocument.php\",\"target_path\":\"src/Domain/SchemaDocument.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/SchemaEvolutionHints.php\",\"target_path\":\"src/Domain/SchemaEvolutionHints.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/SchemaInstallation.php\",\"target_path\":\"src/Domain/SchemaInstallation.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/SchemaInstallationStatus.php\",\"target_path\":\"src/Domain/SchemaInstallationStatus.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/SchemaOperation.php\",\"target_path\":\"src/Domain/SchemaOperation.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/SchemaOperationKind.php\",\"target_path\":\"src/Domain/SchemaOperationKind.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/SchemaPlan.php\",\"target_path\":\"src/Domain/SchemaPlan.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/SchemaPlanApproval.php\",\"target_path\":\"src/Domain/SchemaPlanApproval.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/SchemaPlanStatus.php\",\"target_path\":\"src/Domain/SchemaPlanStatus.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/SchemaPlanStep.php\",\"target_path\":\"src/Domain/SchemaPlanStep.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/SchemaRecoveryEvidence.php\",\"target_path\":\"src/Domain/SchemaRecoveryEvidence.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/SchemaRisk.php\",\"target_path\":\"src/Domain/SchemaRisk.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Domain/SchemaStepStatus.php\",\"target_path\":\"src/Domain/SchemaStepStatus.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Infrastructure/Schema/CanonicalDefinitionPhysicalSchemaCompiler.php\",\"target_path\":\"src/Compiler/CanonicalDefinitionPhysicalSchemaCompiler.php\",\"extraction_kind\":\"whole_file\"}"
    - "{\"old_owner\":\"app\",\"source_path\":\"src/BusinessSchema/Application/BusinessSchemaPlanner.php\",\"target_path\":\"src/Planner/SchemaChangePlanner.php\",\"extraction_kind\":\"partial_methods\",\"methods\":[\"operations\",\"tableOperations\",\"number\",\"withoutForeignKeys\",\"containsPinnedRowBreakingChange\",\"hasRecordRepin\",\"additiveColumnRelaxation\",\"dependencyHandles\",\"spec\",\"tablesByLogical\",\"columnsByLogical\",\"indexesByLogical\",\"foreignKeysByLogical\",\"backfillValue\",\"backfillValueOrDefault\",\"backfillState\",\"transformShadowColumn\",\"validateEvolutionHints\"]}"
  files_to_update:
    - "composer.json"
    - "composer.lock"
    - "container configuration"
    - "capability index"
    - "migration ledger"
    - "CHANGELOG.md"
  files_to_remove:
    - "src/BusinessSchema/Domain/InvalidBusinessSchema.php"
    - "src/BusinessSchema/Domain/PhysicalColumnBlueprint.php"
    - "src/BusinessSchema/Domain/PhysicalForeignKeyBlueprint.php"
    - "src/BusinessSchema/Domain/PhysicalIndexBlueprint.php"
    - "src/BusinessSchema/Domain/PhysicalNameCompiler.php"
    - "src/BusinessSchema/Domain/PhysicalSchemaBlueprint.php"
    - "src/BusinessSchema/Domain/PhysicalTableBlueprint.php"
    - "src/BusinessSchema/Domain/PhysicalTableKind.php"
    - "src/BusinessSchema/Domain/SchemaDocument.php"
    - "src/BusinessSchema/Domain/SchemaEvolutionHints.php"
    - "src/BusinessSchema/Domain/SchemaInstallation.php"
    - "src/BusinessSchema/Domain/SchemaInstallationStatus.php"
    - "src/BusinessSchema/Domain/SchemaOperation.php"
    - "src/BusinessSchema/Domain/SchemaOperationKind.php"
    - "src/BusinessSchema/Domain/SchemaPlan.php"
    - "src/BusinessSchema/Domain/SchemaPlanApproval.php"
    - "src/BusinessSchema/Domain/SchemaPlanStatus.php"
    - "src/BusinessSchema/Domain/SchemaPlanStep.php"
    - "src/BusinessSchema/Domain/SchemaRecoveryEvidence.php"
    - "src/BusinessSchema/Domain/SchemaRisk.php"
    - "src/BusinessSchema/Domain/SchemaStepStatus.php"
    - "src/BusinessSchema/Infrastructure/Schema/CanonicalDefinitionPhysicalSchemaCompiler.php"
  tests_to_remove:
    - "{\"owner\":\"app\",\"baseline_commit\":\"24ecf956423c18933e824b43cea1bfb9127a79a9\",\"path\":\"tests/Unit/BusinessSchema/Domain/PhysicalNameCompilerTest.php\",\"methods\":[\"testNamesAreDeterministicBoundedAndDefinitionScoped\",\"testRejectsNonCanonicalPrefixesThatCouldCollapseOrProduceInvalidNames\",\"testDistinctCanonicalPrefixesCannotCompileTheSamePhysicalName\"],\"retained_methods\":[],\"remove_whole_file\":true}"
    - "{\"owner\":\"app\",\"baseline_commit\":\"24ecf956423c18933e824b43cea1bfb9127a79a9\",\"path\":\"tests/Unit/BusinessSchema/Infrastructure/CanonicalDefinitionPhysicalSchemaCompilerTest.php\",\"methods\":[\"testReferenceIdentityUsesGuidPrimaryKeyAndScopedAlternateUniqueIndex\",\"testConstraintNamesCannotCollideAcrossDefinitions\",\"testVirtualFormulaIsOmittedAndStoredFormulaUsesItsExactResultType\",\"testStructuredRuntimeDefaultIsNotEmittedAsANonPortableJsonDatabaseDefault\",\"testPortableTextLengthBoundaryIsPreservedWithoutSilentCapping\",\"testForeignKeySupportIndexIsAlwaysExplicitInThePortableBlueprint\",\"testAReversalCompilesToARestrictedSelfTargetColumn\"],\"retained_methods\":[],\"remove_whole_file\":false,\"retained_assertions\":[\"BusinessDefinitionValidator assertion belongs to the definition owner and was not extracted.\"]}"
    - "{\"owner\":\"app\",\"baseline_commit\":\"24ecf956423c18933e824b43cea1bfb9127a79a9\",\"path\":\"tests/Unit/BusinessSchema/Domain/SchemaEvolutionHintsTest.php\",\"methods\":[\"testLiteralAndExpressionBackfillsRoundTripCanonically\",\"testAmbiguousRenameAndEvolutionKeyTyposFailClosed\"],\"retained_methods\":[],\"remove_whole_file\":true}"
    - "{\"owner\":\"app\",\"baseline_commit\":\"24ecf956423c18933e824b43cea1bfb9127a79a9\",\"path\":\"tests/Unit/BusinessSchema/Domain/SchemaInstallationTest.php\",\"methods\":[\"testExtensionPackageOwnerIdentityIsPreserved\"],\"retained_methods\":[],\"remove_whole_file\":true}"
    - "{\"owner\":\"app\",\"baseline_commit\":\"24ecf956423c18933e824b43cea1bfb9127a79a9\",\"path\":\"tests/Unit/BusinessSchema/Domain/SchemaPlanTest.php\",\"methods\":[\"testApprovalIsChecksumBoundAndExecutionIsFenceBound\",\"testHighImpactPlanRequiresExactConfirmationAndRecoveryEvidence\"],\"retained_methods\":[],\"remove_whole_file\":true}"
    - "{\"owner\":\"app\",\"baseline_commit\":\"24ecf956423c18933e824b43cea1bfb9127a79a9\",\"path\":\"tests/Unit/BusinessSchema/Domain/SchemaRecoveryContractTest.php\",\"methods\":[\"testCanonicalPlanOrderAndChecksumSurviveEveryRecoveryTransition\",\"testPersistedPlanRejectsCanonicalAndApprovalChecksumDrift\",\"testLockingApprovalRequiresConfirmationAndSourceBoundEvidence\",\"testRecoveryEvidenceQualifiesOnlyForTheExactFreshEnvironmentAndSource\",\"testInterruptedStepAdvancesAttemptAndFenceBeforeCompletion\"],\"retained_methods\":[],\"remove_whole_file\":true}"
  tests_to_retain_or_add:
    - "Host responsibility cases listed above"
    - "Native parity against committed semantic corpus where applicable"
  di_or_provisioning_changes:
    - "Kumwe\\BusinessSchema\\Contract\\DefinitionSchemaLookup"
    - "Kumwe\\BusinessDefinition\\Application\\FieldTypeDefinitionResolver"
    - "Kumwe\\BusinessSchema\\Domain\\PhysicalNameCompiler"
  capability_index_changes:
    - "Record actual release and package responsibility without declaring composed roadmap completion."
  changelog_and_evidence_changes:
    - "Record immutable artifact, attestation and remaining host acceptance gates."
  verification_commands:
    - "composer check"
    - "composer clean-consumer"
    - "App affected integration train and platform matrix"
governance:
  completion_claim: false
decisions:
  - "Canonical namespace and approved value behavior retained."
  - "No host authority or persistence moves into the package."
  - "See CHARTER.md for explicit dependency amendments; no release approval is inferred."
blockers:
  - "Independent verification of the final maintenance release and its complete dependency closure remains a separate task before App adoption."
---

# Business Schema release record

This record binds public manifests, baseline source ownership, DI requirements and
consumer test responsibilities. Baseline paths describe compatibility evidence;
consumers reconcile them against current Core before changing implementations.

## Package contract

Portable physical schema blueprints, deterministic change plans and recovery values
belong to Business Schema. Core owns database adapters, scope authority, policy,
signing secrets, transactions, leases/fences, DDL, journals, persistence and delivery.

## Public API and responsibility

See [public API](public-api.md), [architecture](architecture.md) and the canonical
API, capability and service-map manifests. Compiler and planner services are shared
and stateless. Approved plan checksums and recovery inputs remain detached from
caller references after admission.

## Dependencies and semantic inputs

Business Definition owns trusted definition semantics and Sequence owns number-format
bounds. Both are exact runtime dependencies. [Dependency decisions](dependency-decisions.md)
explain this boundary. The source ownership manifest and conformance corpus retain
baseline provenance; they do not prove host database or native integration.

## Consumer contract

Core supplies typed DefinitionSchemaLookup, FieldTypeDefinitionResolver and
PhysicalNameCompiler bindings. Missing inputs fail construction. Use dependencyHandles()
to discover outgoing dependencies, resolve versions under host authority, compile
blueprints and then plan operations. See [integration](integration.md).

## Test ownership

Package tests own portable behavior, immutability, boundary refusal and conformance.
The [test ownership contract](test-ownership.md) and resource manifest retain exact
source provenance and host acceptance responsibilities. Runtime database migration,
recovery, authorization and fencing tests remain with the host.

## Consumer verification

Independently verify exact package and dependency releases, source identities, archive
and manifest digests. Run the host integration and database acceptance suites after
composition. Pre-1.0 requirements remain exact; moving constraints cannot resolve an
incompatible transitive version graph.

## Compatibility and drift

Reconcile baseline source and consumer inventories, including configuration, fixtures
and dynamically composed names, before removing duplicate implementations. Public
signatures, canonical plan bytes and source provenance are compatibility contracts.

## Validation

Run `composer check` and `composer clean-consumer`. Required checks cover dependency
identity, behavior/conformance, architecture, static analysis, API/governance, audit,
release automation and no-dev authoritative archive consumption. Published releases
and independent attestations retain their own exact source and artifact identities.
