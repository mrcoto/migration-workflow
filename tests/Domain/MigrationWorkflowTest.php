<?php 

namespace MrCoto\MigrationWorkflow\Test\Domain;

use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowCollection;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep;
use PHPUnit\Framework\TestCase;

class MigrationWorkflowTest extends TestCase
{

    public function test_should_generate_workflow_collection()
    {
        $collection = MigrationWorkflow::workflow(
            [
                MigrationWorkflow::step('migration', [
                    'MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep'
                ]),
                MigrationWorkflow::step('seed', [
                    'MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep'
                ]),
            ]
        );
        $steps = $collection->steps();
        $this->assertEquals(2, count($steps));
        $this->assertEquals('migration', $steps[0]->type());
        $this->assertEquals(1, count($steps[0]->files()));
        $this->assertEquals('MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep', $steps[0]->files()[0]);
        $this->assertEquals('seed', $steps[1]->type());
        $this->assertEquals(1, count($steps[1]->files()));
        $this->assertEquals('MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep', $steps[1]->files()[0]);
    }

}