<?php 

namespace MrCoto\MigrationWorkflow\Action\Deploy\Handler;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use MrCoto\MigrationWorkflow\Action\Deploy\Contract\DeployRepositoryContract;
use MrCoto\MigrationWorkflow\Core\ValueObject\PathInfo;

class DeployRepository implements DeployRepositoryContract
{

    /**
     * Create migration workflow table if not exists
     *
     * @param string $tableName
     * @return void
     */
    public function createTableIfNotExists(string $tableName)
    {
        if (Schema::hasTable($tableName)) {
            return;
        }
        Schema::create($tableName, function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('workflow_class');
            $table->string('version');
            $table->string('date');
            $table->bigInteger('timestamp');
        });
    }

    /**
     * Create migration workflow detail table if not exists
     *
     * @param string $tableName
     * @param string $detailTableName
     * @return void
     */
    public function createDetailTableIfNotExists(string $tableName, string $detailTableName)
    {
        if (Schema::hasTable($detailTableName)) {
            return;
        }
        Schema::create($detailTableName, function(Blueprint $table) use ($tableName){
            $table->bigIncrements('id');
            $table->integer('step_number');
            $table->string('type');
            $table->string('file');
            $table->bigInteger($tableName.'_id');

            $table->foreign($tableName.'_id')->references('id')->on($tableName);
        });
    }

    /**
     * Return true if migration workflow is present in database
     *
     * @param string $tableName
     * @param PathInfo $workflowData
     * @return bool
     */
    public function exists(string $tableName, PathInfo $workflowData) : bool
    {
        return DB::table($tableName)
                 ->where('workflow_class', get_class($workflowData->workflow()))
                 ->exists();
    }

    /**
     * Save a specific migration workflow contract
     *
     * @param string $tableName
     * @param string $detailTableName
     * @param PathInfo $workflowData
     * @return void
     */
    public function save(string $tableName, string $detailTableName, PathInfo $workflowData)
    {
        DB::table($tableName)->insert(array(
            'workflow_class' => get_class($workflowData->workflow()),
            'version' => $workflowData->version(),
            'date' => $workflowData->date(),
            'timestamp' => $workflowData->timestamp()
        ));
        $lastId = DB::table($tableName)->max('id') ?? 1;
        $details = [];
        foreach($workflowData->workflow()->getWorkFlow()->steps() as $index => $step) {
            foreach($step->files() as $file) {
                $details[] = array(
                    'step_number' => $index + 1,
                    'type' => $step->type(),
                    'file' => $file,
                    $tableName.'_id' => $lastId
                );
            }
        }
            
        DB::table($detailTableName)->insert($details);
    }

}