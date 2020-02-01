<?php

namespace MrCoto\MigrationWorkflow\Action\Migrate\Handler;

use Exception;
use MrCoto\MigrationWorkflow\Action\Migrate\Contract\MigrateHookContract;
use MrCoto\MigrationWorkflow\Action\Migrate\Contract\MigrateStepContract;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflowContract;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep;
use MrCoto\MigrationWorkflow\Logger\LoggerFactory;

class MigrateHandler
{
    
    private $logger;

    private $migrateStepHandler;
    private $hookHandler;

    public function __construct(
        MigrateStepContract $migrateStepHandler,
        MigrateHookContract $hookHandler
    )
    {
        $this->logger = LoggerFactory::getLogger();       
        $this->migrateStepHandler = $migrateStepHandler;
        $this->hookHandler = $hookHandler;
    }

    /**
     * Handle migration workflow
     *
     * @param MigrationWorkflowContract $migrationWorkflow
     * @return void
     */
    public function handle(MigrationWorkflowContract $migrationWorkflow)
    {
        // try-catch useful to define transaction context's or another error processing
        try {
            $workflow = $migrationWorkflow->getWorkFlow();
            $this->hookHandler->beforeAll($workflow);
            $steps = $workflow->steps();
            $this->logger->info("Workflow steps: ".count($steps));
            foreach($steps as $index => $step) {
                $stepNumber = $index + 1;
                $this->handleStep($stepNumber, $step);
            }
            $this->hookHandler->afterAll($workflow);
        } catch(Exception $e) {
            $this->hookHandler->onError($workflow, $step, $stepNumber);
            $this->logger->error("Unexpected error on step #$stepNumber: ". $e->getMessage());
            throw $e;
        }
        
    }

    /**
     * Handle single step
     *
     * @param integer $stepNumber
     * @param MigrationWorkflowStep $step
     * @return void
     */
    private function handleStep(int $stepNumber, MigrationWorkflowStep $step)
    {
        $this->logger->info($this->getStepDescription('Executing', $stepNumber, $step));
        if ($step->isMigrationStep()) {
            $this->migrateStepHandler->handleMigration($stepNumber, $step);
        } else {
            $this->migrateStepHandler->handleSeed($stepNumber, $step);
        }
        $this->logger->debug($this->getStepDescription('Executed', $stepNumber, $step));
    }

    /**
     * Get Particular step description
     *
     * @param string $action
     * @param MigrationWorkflowStep $step
     * @return string
     */
    private function getStepDescription(string $action, int $stepNumber, MigrationWorkflowStep $step) : string
    {
        $type = $step->type();
        $filesLen = count($step->files());
        return "$action step: $stepNumber --> type: $type | files found: $filesLen";
    }

}