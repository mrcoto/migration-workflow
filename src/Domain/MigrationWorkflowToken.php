<?php 

namespace MrCoto\MigrationWorkflow\Domain;

class MigrationWorkflowToken
{

    public const MIGRATION = 'migration';
    public const SEED = 'seed';

    /**
     * Available token types to use
     */
    public const TYPES = [
        self::MIGRATION,
        self::SEED,
    ];

}