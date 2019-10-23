<?php 

namespace MrCoto\MigrationWorkflow\Application\Commands;

use Illuminate\Console\Command;
use MrCoto\MigrationWorkflow\Domain\Handlers\DeleteMigrationWorkflowHandler;
use MrCoto\MigrationWorkflow\Domain\Logger\Logger;
use MrCoto\MigrationWorkflow\Domain\Logger\LoggerFactory;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationDeleteData;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\DeleteMigrationWorkflowTableEloquentHandler;
use MrCoto\MigrationWorkflow\Infrastructure\Logger\ConsoleMonologLogger;

class DeleteMigrationWorkflowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:workflow {className} {version=v1} {--file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete workflow database record and/or migration workflow file';

    /** @var Logger $logger */
    private $logger;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->configureLogger();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $className = $this->argument('className');
        $version = $this->argument('version');

        $deleteMigrationWorkflowHandler = new DeleteMigrationWorkflowHandler(
            new MigrationDeleteData(
                config('migration_workflow.table_name'),
                config('migration_workflow.table_name_detail'),
                config('migration_workflow.workflows'),
                $className,
                $version
            ),
            new DeleteMigrationWorkflowTableEloquentHandler
        );

        $fullClassName = $this->getFullClasName($deleteMigrationWorkflowHandler);
        
        $deleteMigrationWorkflowHandler->deleteMigrationWorkflowFromDatabase();
        $this->logger->info("Migration Workflow $fullClassName removed from database");
        if ($this->option('file')) {
            $deleteMigrationWorkflowHandler->removeFile();
            $this->logger->info("Migration Workflow $fullClassName file removed");
        }
    }

    /**
     * Configure Logger
     *
     * @return void
     */
    private function configureLogger()
    {
        $loggerClass = config('migration_workflow.logger', ConsoleMonologLogger::class);
        LoggerFactory::setLogger(new $loggerClass);
        $this->logger = LoggerFactory::getLogger();
    }

    /**
     * Get full class name of class to remove
     *
     * @param DeleteMigrationWorkflowHandler $deleteMigrationWorkflowHandler
     * @return string
     */
    private function getFullClasName(DeleteMigrationWorkflowHandler $deleteMigrationWorkflowHandler) : string
    {
        $deleteData = $deleteMigrationWorkflowHandler->deleteData();
        $workflowData = $deleteData->workflowData();
        $workflow = $workflowData->workflow();
        return get_class($workflow);
    }
}