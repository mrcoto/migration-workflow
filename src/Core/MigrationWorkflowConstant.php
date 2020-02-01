<?php 

namespace MrCoto\MigrationWorkflow\Core;

class MigrationWorkflowConstant
{

    public const MIGRATION = 'migration';
    public const SEED = 'seed';

    public const MIGRATION_WORKFLOW_CLASS_REGEX = '/([A-Za-z0-9])+_([A-Za-z0-9])+_(\d){4}_(\d){2}_(\d){2}_(\d){6}$/';
    public const MIGRATION_WORKFLOW_FILE_REGEX = '/([A-Za-z0-9])+_([A-Za-z0-9])+_(\d){4}_(\d){2}_(\d){2}_(\d){6}\.php$/';

    /**
     * Available token types to use
     */
    public const TYPES = [
        self::MIGRATION,
        self::SEED,
    ];

}