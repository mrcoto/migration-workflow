<?php 

namespace MrCoto\MigrationWorkflow\Test\Integration\Action\Make\Handler;

use MrCoto\MigrationWorkflow\Action\Make\Handler\Stub;
use MrCoto\MigrationWorkflow\Test\Helper\FileHandler;
use PHPUnit\Framework\TestCase;

class StubTest extends TestCase
{

    protected function tearDown(): void
    {
        (new FileHandler)->delete('app');
    }

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
        $expectedRender = $this->get_simple_rendered_stub();
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
    }

    public function test_should_render_complex_stub_case_one()
    {
        $stub = new Stub(
            'App\MigrationWorkflows',
            'FolderOne\FolderTwo\MyMigrationClass_dev_2019_10_21_160230',
            'migration_workflow'
        );
        $expectedRender = $this->get_complex_rendered_stub();
        $this->assertEquals($expectedRender, $stub->render());
    }

    public function test_should_generate_complex_stub_case_one()
    {
        $stub = new Stub(
            'App\MigrationWorkflows',
            'FolderOne\FolderTwo\MyMigrationClass_dev_2019_10_21_160230',
            'migration_workflow'
        );
        $stub->generate();
        $this->assertTrue(file_exists('app/MigrationWorkflows/FolderOne/FolderTwo/MyMigrationClass_dev_2019_10_21_160230.php'));
    }

    public function test_should_render_complex_stub_case_two()
    {
        $stub = new Stub(
            'App\\MigrationWorkflows',
            'FolderOne\\FolderTwo\\MyMigrationClass_dev_2019_10_21_160230',
            'migration_workflow'
        );
        $expectedRender = $this->get_complex_rendered_stub();
        $this->assertEquals($expectedRender, $stub->render());
    }

    public function test_should_generate_complex_stub_case_two()
    {
        $stub = new Stub(
            'App\\MigrationWorkflows',
            'FolderOne\\FolderTwo\\MyMigrationClass_dev_2019_10_21_160230',
            'migration_workflow'
        );
        $stub->generate();
        $this->assertTrue(file_exists('app/MigrationWorkflows/FolderOne/FolderTwo/MyMigrationClass_dev_2019_10_21_160230.php'));
    }

    public function test_should_render_complex_stub_case_three()
    {
        $stub = new Stub(
            'App/MigrationWorkflows',
            'FolderOne/FolderTwo/MyMigrationClass_dev_2019_10_21_160230',
            'migration_workflow'
        );
        $expectedRender = $this->get_complex_rendered_stub();
        $this->assertEquals($expectedRender, $stub->render());
    }

    public function test_should_generate_complex_stub_case_three()
    {
        $stub = new Stub(
            'App/MigrationWorkflows',
            'FolderOne/FolderTwo/MyMigrationClass_dev_2019_10_21_160230',
            'migration_workflow'
        );
        $stub->generate();
        $this->assertTrue(file_exists('app/MigrationWorkflows/FolderOne/FolderTwo/MyMigrationClass_dev_2019_10_21_160230.php'));
    }

    private function get_simple_rendered_stub()
    {
        return <<<PHP
<?php 

namespace App\MigrationWorkflows;

use MrCoto\MigrationWorkflow\Core\MigrationWorkflowContract;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowCollection;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflow;

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
    }

    private function get_complex_rendered_stub()
    {
        return <<<PHP
<?php 

namespace App\MigrationWorkflows\FolderOne\FolderTwo;

use MrCoto\MigrationWorkflow\Core\MigrationWorkflowContract;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowCollection;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflow;

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
    }



}