<?php 

namespace MrCoto\MigrationWorkflow\Action\Migrate\Handler;

use MrCoto\MigrationWorkflow\Action\Migrate\Contract\MigrateStepContract;
use MrCoto\MigrationWorkflow\Action\Migrate\Exceptions\ClassFileIsNotMigrationException;
use MrCoto\MigrationWorkflow\Action\Migrate\Exceptions\ClassFileIsNotSeederException;
use MrCoto\MigrationWorkflow\Action\Migrate\Exceptions\MigrationFileNotFoundException;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\Step\MigrationFileHandler;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\Step\SeedFileHandler;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep;
use ReflectionException;

class MigrateStepHandler implements MigrateStepContract
{

    /**
     * Handle migration workflow's migration step
     *
     * @param int $stepNumber
     * @param MigrationWorkflowStep $step
     * @return void
     * @throws ClassFileIsNotMigrationException
     * @throws MigrationFileNotFoundException
     */
    public function handleMigration(int $stepNumber, MigrationWorkflowStep $step)
    {
        (new MigrationFileHandler)->handle($stepNumber, $step);
    }

    /**
     * Handle migration workflow's seed step
     *
     * @param int $stepNumber
     * @param MigrationWorkflowStep $step
     * @return void
     * @throws ClassFileIsNotSeederException
     * @throws ReflectionException
     */
    public function handleSeed(int $stepNumber, MigrationWorkflowStep $step)
    {
        (new SeedFileHandler)->handle($stepNumber, $step);
    }

}