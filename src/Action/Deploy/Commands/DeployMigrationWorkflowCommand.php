<?php 

namespace MrCoto\MigrationWorkflow\Action\Deploy\Commands;

use Illuminate\Console\Command;
use MrCoto\MigrationWorkflow\Action\Deploy\Handler\DeployHandler;
use MrCoto\MigrationWorkflow\Action\Deploy\Handler\DeployRepository;
use MrCoto\MigrationWorkflow\Action\Deploy\ValueObject\DeployData;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\MigrateHandler;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\MigrateHookHandler;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\MigrateStepHandler;

class DeployMigrationWorkflowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:deploy {--versions=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy migration workflows (all if no versions are specified, Ej: --versions=v1,v2)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $migrateHandler = $this->getMigrateHandler();
        $deployData = new DeployData(
            config('migration_workflow.table_name'),
            config('migration_workflow.table_name_detail'),
            config('migration_workflow.workflows'),
            $this->getVersions()
        );
        $deployHandler = $this->getDeployHandler($deployData, $migrateHandler);
        $deployHandler->deploy();
    }

    /**
     * Get versions
     *
     * @return array
     */
    private function getVersions() : array
    {
        $versions = $this->option('versions');
        if (!$versions || empty($versions)) {
            return [];
        }
        return array_map(function(string $version) {
            return trim($version);
        }, explode(',', $versions));
    }

    /**
     * Get Migration Workflow migrate handler
     *
     * @return MigrateHandler
     */
    private function getMigrateHandler() : MigrateHandler
    {
        return new MigrateHandler(
            new MigrateStepHandler,
            new MigrateHookHandler
        );
    }

    /**
     * Get Migration Workflow deploy handler
     *
     * @param DeployData $deployData
     * @param MigrateHandler $migrateHandler
     * @return DeployHandler
     */
    private function getDeployHandler(DeployData $deployData, MigrateHandler $migrateHandler) : DeployHandler
    {
        return new DeployHandler(
            $deployData,
            new DeployRepository,
            $migrateHandler
        );
    }

}