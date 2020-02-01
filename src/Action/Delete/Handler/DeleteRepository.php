<?php 

namespace MrCoto\MigrationWorkflow\Action\Delete\Handler;

use Illuminate\Support\Facades\DB;
use MrCoto\MigrationWorkflow\Action\Delete\Contract\DeleteRepositoryContract;
use MrCoto\MigrationWorkflow\Core\ValueObject\PathInfo;

class DeleteRepository implements DeleteRepositoryContract
{

    /**
     * Delete migration workflow from database
     *
     * @param string $tableName
     * @param string $detailTableName
     * @param PathInfo $workflowData
     * @return void
     */
    public function delete(string $tableName, string $detailTableName, PathInfo $workflowData)
    {
        $workflow = $workflowData->workflow();
        $workflowClass = get_class($workflow);
        $tableIds = DB::table($tableName)->where('workflow_class', $workflowClass)->get()->pluck('id')->toArray();
        DB::table($detailTableName)->whereIn($tableName.'_id', $tableIds)->delete();
        DB::table($tableName)->whereIn('id', $tableIds)->delete();
    }

}