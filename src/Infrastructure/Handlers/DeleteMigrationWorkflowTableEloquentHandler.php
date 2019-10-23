<?php 

namespace MrCoto\MigrationWorkflow\Infrastructure\Handlers;

use Illuminate\Support\Facades\DB;
use MrCoto\MigrationWorkflow\Domain\Handlers\DeleteMigrationWorkflowTableHandler;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowData;

class DeleteMigrationWorkflowTableEloquentHandler implements DeleteMigrationWorkflowTableHandler
{

    /**
     * Delete migration workflow from database
     *
     * @param string $tableName
     * @param string $detailTableName
     * @param MigrationWorkflowData $workflowData
     * @return void
     */
    public function deleteMigrationWorkflow(string $tableName, string $detailTableName, MigrationWorkflowData $workflowData)
    {
        $workflow = $workflowData->workflow();
        $workflowClass = get_class($workflow);
        $tableIds = DB::table($tableName)->where('workflow_class', $workflowClass)->get()->pluck('id')->toArray();
        DB::table($detailTableName)->whereIn($tableName.'_id', $tableIds)->delete();
        DB::table($tableName)->whereIn('id', $tableIds)->delete();
    }

}