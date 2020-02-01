<?php 

namespace MrCoto\MigrationWorkflow\Action\Delete\Commands;

use Illuminate\Console\Command;
use MrCoto\MigrationWorkflow\Action\Delete\Handler\DeleteHandler;
use MrCoto\MigrationWorkflow\Action\Delete\Handler\DeleteRepository;
use MrCoto\MigrationWorkflow\Action\Delete\ValueObject\DeleteData;
use MrCoto\MigrationWorkflow\Logger\Handler\ConsoleMonologLogger;
use MrCoto\MigrationWorkflow\Logger\ILogger;
use MrCoto\MigrationWorkflow\Logger\LoggerFactory;

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
    protected $description = 'Delete workflow database record and. If --file is passed, then remove it from file system';

    /** @var ILogger $logger */
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

        $deleteData = new DeleteData(
            config('migration_workflow.table_name'),
            config('migration_workflow.table_name_detail'),
            config('migration_workflow.workflows'),
            $className,
            $version
        );
        $this->deleteMigrationWorkflow($deleteData);
    }

    /**
     * Delete Migration Workflow from database and/or file system
     *
     * @param string $fullClassName
     * @return void
     */
    private function deleteMigrationWorkflow(DeleteData $deleteData)
    {
        $fullClassName = $this->getFullClasName($deleteData);

        $deleteHandler = $this->getDeleteHandler($deleteData);
        
        $deleteHandler->deleteFromDatabase();
        $this->logger->info("Migration Workflow $fullClassName removed from database");
        
        if ($this->option('file')) {
            $deleteHandler->removeFile();
            $this->logger->info("Migration Workflow $fullClassName file removed");
        }
    }

    /**
     * Get full class name of class to remove
     *
     * @param DeleteData $deleteData
     * @return string
     */
    public function getFullClasName(DeleteData $deleteData) : string
    {
        $workflowData = $deleteData->workflowData();
        $workflow = $workflowData->workflow();
        return get_class($workflow);
    }

    /**
     * Get Migration Workflow Delete Handler
     *
     * @param DeleteData $deleteData
     * @return DeleteHandler
     */
    private function getDeleteHandler(DeleteData $deleteData) : DeleteHandler
    {
        return new DeleteHandler(
            $deleteData,
            new DeleteRepository
        );
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
}