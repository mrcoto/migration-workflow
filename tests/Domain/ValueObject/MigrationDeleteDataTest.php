<?php 

namespace MrCoto\MigrationWorkflow\Test\Domain\ValueObject;

use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationDeleteData;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class MigrationDeleteDataTest extends TestCase
{

    public function test_should_throw_exception_if_table_name_is_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        new MigrationDeleteData(
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
        new MigrationDeleteData(
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
        new MigrationDeleteData(
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
        new MigrationDeleteData(
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
        new MigrationDeleteData(
            'table_name',
            'detail_table_name',
            [2, true],
            'SeedDummyWorkflow',
            ''
        );
    }

    public function test_should_generate_deploy_data()
    {
        $deleteData = new MigrationDeleteData(
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