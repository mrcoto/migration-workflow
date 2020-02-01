<?php 

namespace MrCoto\MigrationWorkflow\Test\Unit\Action\Delete\ValueObject;

use MrCoto\MigrationWorkflow\Action\Delete\ValueObject\DeletePathInfo;
use MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data1\CreateDummyWorkflow_dev_2019_10_21_101600;
use MrCoto\MigrationWorkflow\Test\Stub\Workflow\DummyWorkflow;
use PHPUnit\Framework\TestCase;

class DeletePathInfoTest extends TestCase
{

    public function test_should_generate_workflow_data_with_default_version_and_timestamp()
    {
        $workflowData = new DeletePathInfo(
            new DummyWorkflow
        );
        $this->assertEquals('v1', $workflowData->version());
        $this->assertEquals(date('Y-m-d 00:00:00'), $workflowData->date());
        $this->assertEquals(strtotime(date('Y-m-d 00:00:00')), $workflowData->timestamp());
    }

    public function test_should_generate_workflow_data()
    {
        $workflowData = new DeletePathInfo(
            new CreateDummyWorkflow_dev_2019_10_21_101600
        );
        $this->assertEquals('dev', $workflowData->version());
        $this->assertEquals(date('2019-10-21 10:16:00'), $workflowData->date());
        $this->assertEquals(strtotime(date('2019-10-21 10:16:00')), $workflowData->timestamp());
    }

}