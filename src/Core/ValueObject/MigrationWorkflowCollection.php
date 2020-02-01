<?php 

namespace MrCoto\MigrationWorkflow\Core\ValueObject;

class MigrationWorkflowCollection
{
    /** @var MigrationWorkflowStep[] $steps */
    private $steps;

    /**
     * Generate inmutable collection from migration workflow steps
     *
     * @param MigrationWorkflowStep[] $steps
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