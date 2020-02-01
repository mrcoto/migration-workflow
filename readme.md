# Migration Workflow Package for Laravel

One little problem with Laravel when running migrations and seed is the execution order on those files.
First, migration files are executed, and then seeder classes are executed.

This package attempt to define a way to specify the sequential order of those files.
(For example, running two migration clases, one seed, and two migration).

## Changelog

|Version|Descripción|
|-|-|
|V1.4.1|Commands ```make:workflow``` and ```module:make-workflow```  will generate correct folders and class with this case inputs: ```FolderOne\FolderTwo\MyClass```, ```FolderOne\\FolderTwo\\MyClass```, ```FolderOne/FolderTwo/MyClass```|
|V1.4.0|Added ```delete:workflow``` command|
|V1.3.0|Added ```migration_workflow.logger``` config value|
|V1.2.0|Added ```make:workflow``` and ```module:make-workflow``` command|
|V1.1.0|You can deploy all your workflows defined in ```migration_workflow``` config file with ```migrate:deploy``` command |
|V1.0.1|You can run seeds from database/seeders (In V1.0.0 you can only un seeds inside ```app/```)|
|V1.0.0|Command ```migrate:workflow``` is provided to run a specific migration workflow|

## Migration Workflow

A Migration Workflow is a **collection of steps**, where each **step** can be a **migration** or a **seed**, and may contains at least one file (migration or seed class respectively).

## Menú 

- [Installation](#Installation)
- [Config file](#Config)
- [Migrate Workflow Command](#Migrate-Workflow-Command)
- [Migrate Deploy Command](#Migration-Deploy-Command)
- [Helper Commands](#Helper-Commands)

## Installation

Requires PHP 7.2 or greater

```bash
composer require mrcoto/migration-workflow
```

Now, you need to publish service provider:

```bash
php artisan vendor:publish --provider="MrCoto\MigrationWorkflow\Config\LaravelMigrationWorkflowServiceProvider"
```

This package provide a ```migrate:workflow``` and a ```migrate:deploy``` comand, also a ```migration_workflow``` config file.

## Config

The first two configs ```table_name``` and ```table_name_detail``` are the names of the tables
where all your workflows are tracked by ```migrate:deploy``` command (not by ```migrate:workflow``` command).

In the ```workflows``` config, you have to specify all namespaces where your workflows are placed.
By default, you should place your workflows in `App\MigrationWorkflows`.

The last config, is ```logger```, where you can specify your logger class where migration workflows are logged.

## Migrate Workflow Command

The single usage is:

```bash
php artisan migrate:workflow --class=Path\\To\\MigrateWorkflowClass
```

(The backslashes may vary from Windows or Linux, for example in Linux you have to use two backslashes)

This command executes in **database transaction mode** the workflow defined in your **MigrateWorkflowClass** class.

## How to define a Workflow?

Your workflow class **MUST** implements ```MrCoto\MigrationWorkflow\Core\Contract\MigrationWorkflowContract``` interface.

Then, you need to implement ```public function getWorkFlow(): MigrationWorkflowCollection``` function defined in the previous contract.

Example workflow:

```php
<?php 

namespace Path\To;

use MrCoto\MigrationWorkflow\Core\MigrationWorkflow;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowCollection;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflowContract;

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

## Migration Deploy Command

To use this command, this requisites needs to be satisfied:
- Your workflows must be places in your ```migration_workflow.workflows``` config values.
- The class name of workflow (and file) must follow this structure:

```bash
YourClassName_<Version>_<Year>_<Month>_<Day>_<TimePart>
```

Example: 

```bash
MyWorkflow_dev_2019_10_21_152842 # Version: dev, Date: 2019-10-21 15:26:42
```

**Note:** If your workflow is "MyWorkflow" without version and date, the version is assumed as "v1", and
date current timestamp.

The previous workflow run a specific workflows many times as you wish, but if you want to track what workflows you run, you need to use ```migrate:deploy``` command.

Example usage:

```bash
php artisan migrate:deploy # Run all versions
php artisan migrate:deploy --versions=dev,prod # Run versions dev and prod
php artisan migrate:deploy --versions=dev # Run only dev
```

This command **FILTER WORKFLOWS BY VERSIONS** and deploy them in **ASCENDING ORDER**.

All your workflows deployed are stored in ```migrate_workflow.table_name``` and ```migrate_workflow.table_name_detail``` tables (defined in config file).

**Note:** You can't run a workflow if it's already stored in database, but if your workflow is named "MyWorkflow" you can execute it as many times as you want.

## Helper Commands

1. The first helper command is:

```bash
php artisan make:workflow MyWorkflow dev
```

This command generate the following file ```app/MigrationWorkflows/MyWorkflow_dev_2019_10_21_164230.php```.

The timestamp in file name is the current timestamp.

Version argument is optional (default is "v1")

2. The second helper command is:

```bash
php artisan module:make-workflow MyWorkflow dev
```

This command generate the following file ```Modules/General/MigrationWorkflows/MyWorkflow_dev_2019_10_21_164230.php```.

Version argument is optional (default is "v1")

**Note:** This command is useful is you're using the [laravel modules package](https://github.com/nWidart/laravel-modules).

3. The third helper command is:

```bash
php artisan delete:workflow MyWorkflow dev
```

This will find your **LAST** ```MyWorkflow_dev_*``` file and then will remove records from database.
If you add a ```--file``` flag, the file from fylesystem will be removed.

**NOTE:** This command only works if your file is found.


----------------------------

All rights reserved Innlab@2019 (Package developed by José Espinoza)