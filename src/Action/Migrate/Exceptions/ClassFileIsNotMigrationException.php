<?php 

namespace MrCoto\MigrationWorkflow\Action\Migrate\Exceptions;

use Exception;

class ClassFileIsNotMigrationException extends Exception
{

    public function __construct(string $migrationClass)
    {
        parent::__construct("Migration class '$migrationClass' doesn't extends 'Illuminate\Database\Migrations\Migration'");
    }

}