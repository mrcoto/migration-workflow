# Migration Workflow Package for Laravel

One little problem with Laravel when running migrations and seed is the execution order on those files.
First, migration files are executed, and then seeder classes are executed.

This package attempt to define a way to specify the sequential order of those files.
(For example, running two migration clases, one seed, and two migration).

## Changelog

|Version|Descripción|
|-|-|
|V1.0.1|You can run seeds from database/seeders (In V1.0.0 you can only un seeds inside ```app/```)|
|V1.0.0|Command ```migrate:workflow``` is provided to run a specific migration workflow|

## Migration Workflow

A Migration Workflow is a **collection of steps**, where each **step** can be a **migration** or a **seed**, and may contains at least one file (migration or seed class respectively).

## Installation

Requires PHP 7.2 or greater

```bash
composer require mrcoto/migration-workflow
```

Now, you need to publish service provider:

```bash
php artisan vendor:publish --provider="MrCoto\MigrationWorkflow\Application\LaravelMigrationWorkflowServiceProvider"
```

This package provide a ```migrate:workflow``` comand.

## Migrate Workflow Command

The single usage is:

```bash
php artisan migrate:workflow --class=Path\\To\\MigrateWorkflowClass
```

(The backslashes may vary from Windows or Linux, for example in Linux you have to use two backslashes)

This command executes in **database transaction mode** the workflow defined in your **MigrateWorkflowClass** class.

## How to define a Workflow?

Your workflow class **MUST** implements ```MrCoto\MigrationWorkflow\Domain\MigrationWorkflowContract``` interface.

Then, you need to implement ```public function getWorkFlow(): MigrationWorkflowCollection``` function defined in the previous contract.

Example workflow:

```php
<?php 

namespace Path\To;

use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowCollection;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflowContract;

class MigrateWorkflowClass implements MigrationWorkflowContract
{
    /**
     * Return migration workflow,
     * each element in the array is a workflow's step.
     *
     * @return MigrationWorkflowCollection
     */
    public function getWorkFlow(): MigrationWorkflowCollection
    {
        // MigrationWorkflow::workflow return an inmutable MigrationWorkflowCollection object
        // This workflow has three steps
        // When running this workflow:
        // 1. Table 'dummy' will be generated
        // 2. Table 'another' will be generated
        // 3. Table 'dummy' will be seed with some data
        // 4. Table 'dummy' will be dropped
        return MigrationWorkflow::workflow(
            array(
                // MigrationWorkflow::step return a MigrationWorkflowStep object
                MigrationWorkflow::step('migration', [
                    'database/migrations/2019_10_19_120000_create_dummy_table',
                    'database/migrations/2019_10_20_120000_create_another_table',
                ]),
                MigrationWorkflow::step('seed', [
                    'Path\To\DummyTableSeeder',
                ]),
                MigrationWorkflow::step('migration', [
                    'database/migrations/2019_10_19_120000_drop_dummy_table',
                ]),
            )
        );
    }

}
```

----------------------------

All rights reserved Innlab@2019 (Package developed by José Espinoza)