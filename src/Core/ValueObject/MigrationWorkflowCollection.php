<?php 

namespace MrCoto\MigrationWorkflow\Core\ValueObject;

use MrCoto\MigrationWorkflow\Core\Exceptions\MigrationWorkflowEmptyFilesException;
use MrCoto\MigrationWorkflow\Core\Exceptions\MigrationWorkflowTypeExpectedException;

class MigrationWorkflowCollection
{
    /** @var MigrationWorkflowStep[] $steps */
    private $steps;

    /**
     * Generate inmutable collection from migration workflow steps
     *
     * @param MigrationWorkflowStep[] $steps
     * @throws MigrationWorkflowEmptyFilesException
     * @throws MigrationWorkflowTypeExpectedException
     */
    public function __construct(array $steps)
    {
        $this->steps = [];
        /** @var MigrationWorkflowStep $step */
        foreach($steps as $index => $step)
        {
            $this->steps[] = new MigrationWorkflowStep(
                $step->type(),
                $step->files()
            );
        }
    }

    /**
     * Get MigrationWorkflow collection's steps
     *
     * @return MigrationWorkflowStep[] $steps
     */
    public function steps() : array
    {
        return $this->steps;
    }

}