<?php

namespace MrCoto\MigrationWorkflow\Domain\Handlers;

use Exception;
use MrCoto\MigrationWorkflow\Domain\Contracts\MigrationWorkflowHookHandlerContract;
use MrCoto\MigrationWorkflow\Domain\Contracts\MigrationWorkflowStepHandlerContract;
use MrCoto\MigrationWorkflow\Domain\Logger\LoggerFactory;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflowContract;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflowToken;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep;

class MigrationWorkflowHandler
{
    
    private $logger;
    private $migrationHandler;
    private $seedHandler;
    private $hookHandler;

    public function __construct(
        MigrationWorkflowStepHandlerContract $migrationHandler,
        MigrationWorkflowStepHandlerContract $seedHandler,
        MigrationWorkflowHookHandlerContract $hookHandler
    )
    {
        $this->logger = LoggerFactory::getLogger();       
        $this->migrationHandler = $migrationHandler;
        $this->seedHandler = $seedHandler; 
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
            foreach($steps as $index => $step)
            {
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
        $type = $step->type();
        $filesLen = count($step->files());
        $this->logger->info("Executing step: $stepNumber --> type: $type | files found: $filesLen");
        if ($step->type() == MigrationWorkflowToken::MIGRATION) {
            $this->handleMigration($stepNumber, $step);
        } else {
            $this->handleSeed($stepNumber, $step);
        }
        $this->logger->debug("Executed step: $stepNumber --> type: $type | files found: $filesLen");
    }

    /**
     * Handle migration type step
     *
     * @param integer $stepNumber
     * @param MigrationWorkflowStep $step
     * @return void
     */
    private function handleMigration(int $stepNumber, MigrationWorkflowStep $step)
    {
        $this->migrationHandler->handle($stepNumber, $step);
    }

    /**
     * Handle seed type step
     *
     * @param integer $stepNumber
     * @param MigrationWorkflowStep $step
     * @return void
     */
    private function handleSeed(int $stepNumber, MigrationWorkflowStep $step)
    {
        $this->seedHandler->handle($stepNumber, $step);
    }

}