<?php 

namespace MrCoto\MigrationWorkflow\Domain\MigrationWorkflow;

class MigrationWorkflowToken
{

    public const MIGRATION = 'migration';
    public const SEED = 'seed';

    /**
     * Available token types to us
     */
    public const TYPES = [
        self::MIGRATION,
        self::SEED,
    ];

}