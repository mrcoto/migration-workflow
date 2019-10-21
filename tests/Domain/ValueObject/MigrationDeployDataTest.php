<?php 

namespace MrCoto\MigrationWorkflow\Test\Domain\ValueObject;

use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationDeployData;
use PHPUnit\Framework\TestCase;

class MigrationDeployDataTest extends TestCase
{

    public function test_should_throw_exception_if_table_name_is_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        new MigrationDeployData(
            '',
            'detail_table_name',
            ['path1', 'path2', 2, true],
            ['all', 'dev', 'prod', 2, true]
        );
    }

    public function test_should_throw_exception_if_detail_table_name_is_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        new MigrationDeployData(
            'table_name',
            '',
            ['path1', 'path2', 2, true],
            ['all', 'dev', 'prod', 2, true]
        );
    }

    public function test_should_throw_exception_if_workflow_path_after_filter_is_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        new MigrationDeployData(
            'table_name',
            'detail_table_name',
            [2, true],
            ['all', 'dev', 'prod', 2, true]
        );
    }

    public function test_should_generate_deploy_data()
    {
        $deployData = new MigrationDeployData(
            'table_name',
            'detail_table_name',
            ['path1', 'path2', 2, true],
            ['all', 'dev', 'prod', 2, true]
        );
        $this->assertEquals('table_name', $deployData->tableName());
        $this->assertEquals('detail_table_name', $deployData->detailTableName());
        $workflowPaths = $deployData->workflowPaths();
        $this->assertEquals(2, count($workflowPaths));
        $this->assertEquals('path1', $workflowPaths[0]);
        $this->assertEquals('path2', $workflowPaths[1]);
        $versions = $deployData->versions();
        $this->assertEquals(3, count($deployData->versions()));
        $this->assertEquals('all', $versions[0]);
        $this->assertEquals('dev', $versions[1]);
        $this->assertEquals('prod', $versions[2]);
    }

}