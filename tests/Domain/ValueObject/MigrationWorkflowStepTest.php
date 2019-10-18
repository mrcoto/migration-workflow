<?php 

namespace MrCoto\MigrationWorkflow\Test\Domain\ValueObject;

use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\Exceptions\MigrationWorkflowEmptyFilesException;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\Exceptions\MigrationWorkflowTypeExpectedException;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\MigrationWorkflowToken;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\ValueObject\MigrationWorkflowStep;
use PHPUnit\Framework\TestCase;

class MigrationWorkflowStepTest extends TestCase
{

    public function test_should_throw_exception_if_type_is_not_available()
    {
        $this->expectException(MigrationWorkflowTypeExpectedException::class);
        new MigrationWorkflowStep('random_type', [
            'MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\ValueObject\MigrationWorkflowStep'
        ]);
    }

    public function test_should_throw_exception_if_not_files_are_sent()
    {
        $this->expectException(MigrationWorkflowEmptyFilesException::class);
        new MigrationWorkflowStep(MigrationWorkflowToken::MIGRATION, [

        ]);
    }

    public function test_should_create_migration_workflow_step()
    {
        $step = new MigrationWorkflowStep('migration', [
            'MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\ValueObject\MigrationWorkflowStep'
        ]);
        $this->assertEquals('migration', $step->type());
        $this->assertEquals(1, count($step->files()));
        $this->assertEquals('MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\ValueObject\MigrationWorkflowStep', $step->files()[0]);
    }

}