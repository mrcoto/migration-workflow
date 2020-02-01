<?php 

namespace MrCoto\MigrationWorkflow\Test\Unit\Core\ValueObject;

use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowCollection;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep;
use PHPUnit\Framework\TestCase;

class MigrationWorkflowCollectionTest extends TestCase
{

    public function test_should_generate_workflow_collection()
    {
        $collection = new MigrationWorkflowCollection(
            [
                new MigrationWorkflowStep('migration', [
                    'MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep'
                ]),
                new MigrationWorkflowStep('seed', [
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