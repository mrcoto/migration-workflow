<?php

namespace MrCoto\MigrationWorkflow\Domain\Handlers;

use MrCoto\MigrationWorkflow\Domain\Logger\Logger;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflowContract;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflowToken;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep;

class MigrationWorkflowHandler
{
    
    private $logger;
    private $migrationHandler;
    private $seedHandler;

    public function __construct(
        Logger $logger,
        MigrationWorkflowStepHandler $migrationHandler,
        MigrationWorkflowStepHandler $seedHandler
    )
    {
        $this->logger = $logger;       
        $this->migrationHandler = $migrationHandler;
        $this->seedHandler = $seedHandler; 
    }

    /**
     * Handle migration workflow
     *
     * @param MigrationWorkflowContract $migrationWorkflow
     * @return void
     */
    public function handle(MigrationWorkflowContract $migrationWorkflow)
    {
        $workflow = $migrationWorkflow->getWorkFlow();
        $steps = $workflow->steps();
        $this->logger->info("Workflow steps: ".count($steps));
        /** @var MigrationWorkflowStep $step */
        foreach($steps as $index => $step)
        {
            $stepNumber = $index + 1;
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