<?php 

namespace MrCoto\MigrationWorkflow\Test\Unit\Action\Delete\ValueObject;

use MrCoto\MigrationWorkflow\Action\Delete\ValueObject\DeleteData;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class DeleteDataTest extends TestCase
{

    public function test_should_throw_exception_if_table_name_is_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        new DeleteData(
            '',
            'detail_table_name',
            ['path1', 'path2', 2, true],
            'SeedDummyWorkflow',
            'dev'
        );
    }

    public function test_should_throw_exception_if_detail_table_name_is_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        new DeleteData(
            'table_name',
            '',
            ['path1', 'path2', 2, true],
            'SeedDummyWorkflow',
            'dev'
        );
    }

    public function test_should_throw_exception_if_workflow_path_after_filter_is_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        new DeleteData(
            'table_name',
            'detail_table_name',
            [2, true],
            'SeedDummyWorkflow',
            'dev'
        );
    }

    public function test_should_throw_exception_if_workflow_to_remove_is_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        new DeleteData(
            'table_name',
            'detail_table_name',
            [2, true],
            '',
            'dev'
        );
    }

    public function test_should_throw_exception_if_version_to_remove_is_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        new DeleteData(
            'table_name',
            'detail_table_name',
            [2, true],
            'SeedDummyWorkflow',
            ''
        );
    }

    public function test_should_generate_deploy_data()
    {
        $deleteData = new DeleteData(
            'table_name',
            'detail_table_name',
            ['MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data2', 'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data1', 2, true],
            'SeedDummyWorkflow',
            'dev'
        );
        $this->assertEquals('table_name', $deleteData->tableName());
        $this->assertEquals('detail_table_name', $deleteData->detailTableName());
        $workflowData = $deleteData->workflowData();
        $workflow = $workflowData->workflow();
        $reflection = new ReflectionClass($workflow);
        $this->assertEquals('SeedDummyWorkflow_dev_2019_10_21_101700', $reflection->getShortName());
    }

}