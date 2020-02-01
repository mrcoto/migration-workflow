<?php 

namespace MrCoto\MigrationWorkflow\Test\Unit\Action\Deploy\ValueObject;

use MrCoto\MigrationWorkflow\Core\Exceptions\MigrationWorkflowBadClassNameException;
use MrCoto\MigrationWorkflow\Core\ValueObject\PathInfo;
use MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data1\CreateDummyWorkflow_dev_2019_10_21_101600;
use MrCoto\MigrationWorkflow\Test\Stub\Workflow\DummyWorkflow;
use PHPUnit\Framework\TestCase;

class PathInfoTest extends TestCase
{

    public function test_should_throw_exception_if_class_name_is_not_workflow_regex()
    {
        $this->expectException(MigrationWorkflowBadClassNameException::class);
        new PathInfo(
            new DummyWorkflow
        );
    }

    public function test_should_generate_workflow_data()
    {
        $workflowData = new PathInfo(
            new CreateDummyWorkflow_dev_2019_10_21_101600
        );
        $this->assertEquals('dev', $workflowData->version());
        $this->assertEquals(date('2019-10-21 10:16:00'), $workflowData->date());
        $this->assertEquals(strtotime(date('2019-10-21 10:16:00')), $workflowData->timestamp());
    }

}