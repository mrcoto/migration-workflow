<?php 

namespace MrCoto\MigrationWorkflow\Infrastructure\Exceptions;

use Exception;

class MigrationFileNotFoundException extends Exception
{

    public function __construct(string $migrationFile)
    {
        parent::__construct("Migration file '$migrationFile' doesn't exists");
    }

}