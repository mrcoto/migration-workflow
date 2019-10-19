<?php 

namespace MrCoto\MigrationWorkflow\Infrastructure\Exceptions;

use Exception;

class ClassFileIsNotSeederException extends Exception
{

    public function __construct(string $seedClass)
    {
        parent::__construct("Seed class '$seedClass' doesn't extends 'Illuminate\Database\Seeder'");
    }

}