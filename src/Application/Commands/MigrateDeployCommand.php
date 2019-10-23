<?php 

namespace MrCoto\MigrationWorkflow\Application\Commands;

use Illuminate\Console\Command;
use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationDeployHandler;
use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationWorkflowHandler;
use MrCoto\MigrationWorkflow\Domain\Logger\Logger;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationDeployData;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\HookEloquentHandler;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\MigrationDeployTableEloquentHandler;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\MigrationStepEloquentHandler;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\SeedStepEloquentHandler;
use MrCoto\MigrationWorkflow\Infrastructure\Logger\ConsoleMonologLogger;

class MigrateDeployCommand extends Command
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

    /** @var Logger $logger */
    private $logger;

    /** @var MigrationWorkflowHandler $migrationWorkflowHandler */
    private $migrationWorkflowHandler;

    /** @var MigrationDeployHandler $migrationDeployHandler */
    private $migrationDeployHandler;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $loggerClass = config('migration_workflow.logger', ConsoleMonologLogger::class);
        $this->logger = new $loggerClass;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->migrationWorkflowHandler = new MigrationWorkflowHandler(
            $this->logger,
            new MigrationStepEloquentHandler,
            new SeedStepEloquentHandler,
            new HookEloquentHandler
        );

        $this->migrationDeployHandler = new MigrationDeployHandler(
            new MigrationDeployData(
                config('migration_workflow.table_name'),
                config('migration_workflow.table_name_detail'),
                config('migration_workflow.workflows'),
                $this->getVersions()
            ),
            new MigrationDeployTableEloquentHandler,
            $this->migrationWorkflowHandler,
            $this->logger
        );

        $this->migrationDeployHandler->deploy();
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

}