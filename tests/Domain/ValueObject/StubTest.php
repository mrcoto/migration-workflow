<?php 

namespace MrCoto\MigrationWorkflow\Test\Domain\ValueObject;

use MrCoto\MigrationWorkflow\Domain\ValueObject\Stub;
use PHPUnit\Framework\TestCase;

class StubTest extends TestCase
{

    public function test_should_throw_exception_if_namespace_is_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Stub(
            '',
            'MyClass',
            'migration_workflow'
        );
    }

    public function test_should_throw_exception_if_classname_is_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Stub(
            'App\MigrationWorkflows',
            '',
            'migration_workflow'
        );
    }

    public function test_should_throw_exception_if_stub_doesnt_exists()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Stub(
            'App\MigrationWorkflows',
            'MyMigrationClass_dev_2019_10_21_160230',
            'not_existing_stub'
        );
    }

    public function test_should_render_stub()
    {
        $stub = new Stub(
            'App\MigrationWorkflows',
            'MyMigrationClass_dev_2019_10_21_160230',
            'migration_workflow'
        );
        $expectedRender = <<<PHP
<?php 

namespace App\MigrationWorkflows;

use MrCoto\MigrationWorkflow\Domain\MigrationWorkflowContract;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowCollection;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow;

class MyMigrationClass_dev_2019_10_21_160230 implements MigrationWorkflowContract
{

    /**
     * Return migration workflow's step
     *
     * @return MigrationWorkflowCollection
     */
    public function getWorkFlow(): MigrationWorkflowCollection
    {
        return MigrationWorkflow::workflow(
            array(
                MigrationWorkflow::step('migration', [
                    '',
                ]),
            )
        );
    }

}
PHP;
        $this->assertEquals($expectedRender, $stub->render());
    }

    public function test_should_generate_stub()
    {
        $stub = new Stub(
            'App\MigrationWorkflows',
            'MyMigrationClass_dev_2019_10_21_160230',
            'migration_workflow'
        );
        $stub->generate();
        $this->assertTrue(file_exists('app/MigrationWorkflows/MyMigrationClass_dev_2019_10_21_160230.php'));
        unlink('app/MigrationWorkflows/MyMigrationClass_dev_2019_10_21_160230.php');
        rmdir('app/MigrationWorkflows');
        rmdir('app');
    }

}