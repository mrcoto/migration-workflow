<?php 

namespace MrCoto\MigrationWorkflow\Action\Migrate\Exceptions;

use Exception;

class ClassFileIsNotSeederException extends Exception
{

    public function __construct(string $seedClass)
    {
        parent::__construct("Seed class '$seedClass' doesn't extends 'Illuminate\Database\Seeder'");
    }

}