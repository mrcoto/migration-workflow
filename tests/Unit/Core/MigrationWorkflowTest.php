<?php 

namespace MrCoto\MigrationWorkflow\Test\Unit\Core;

use MrCoto\MigrationWorkflow\Core\MigrationWorkflow;
use PHPUnit\Framework\TestCase;

class MigrationWorkflowTest extends TestCase
{

    public function test_should_generate_workflow_collection()
    {
        $collection = MigrationWorkflow::workflow(
            [
                MigrationWorkflow::step('migration', [
                    'MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep'
                ]),
                MigrationWorkflow::step('seed', [
                    'MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep'
                ]),
            ]
        );
        $steps = $collection->steps();
        $this->assertEquals(2, count($steps));
        $this->assertEquals('migration', $steps[0]->type());
        $this->assertEquals(1, count($steps[0]->files()));
        $this->assertEquals('MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep', $steps[0]->files()[0]);
        $this->assertEquals('seed', $steps[1]->type());
        $this->assertEquals(1, count($steps[1]->files()));
        $this->assertEquals('MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep', $steps[1]->files()[0]);
    }

}