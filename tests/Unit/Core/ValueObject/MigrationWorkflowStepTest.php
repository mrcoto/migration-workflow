<?php 

namespace MrCoto\MigrationWorkflow\Test\Unit\Core\ValueObject;

use MrCoto\MigrationWorkflow\Core\Exceptions\MigrationWorkflowEmptyFilesException;
use MrCoto\MigrationWorkflow\Core\Exceptions\MigrationWorkflowTypeExpectedException;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflowConstant;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep;
use PHPUnit\Framework\TestCase;

class MigrationWorkflowStepTest extends TestCase
{

    public function test_should_throw_exception_if_type_is_not_available()
    {
        $this->expectException(MigrationWorkflowTypeExpectedException::class);
        new MigrationWorkflowStep('random_type', [
            'MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep'
        ]);
    }

    public function test_should_throw_exception_if_not_files_are_sent()
    {
        $this->expectException(MigrationWorkflowEmptyFilesException::class);
        new MigrationWorkflowStep(MigrationWorkflowConstant::MIGRATION, [

        ]);
    }

    public function test_should_create_migration_workflow_step()
    {
        $step = new MigrationWorkflowStep('migration', [
            'MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep'
        ]);
        $this->assertEquals('migration', $step->type());
        $this->assertEquals(1, count($step->files()));
        $this->assertEquals('MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep', $step->files()[0]);
    }

}