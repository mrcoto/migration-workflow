<?php 

namespace MrCoto\MigrationWorkflow\Action\Migrate\Commands;

use Illuminate\Console\Command;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\MigrateHandler;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\MigrateHookHandler;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\MigrateStepHandler;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflowContract;
use MrCoto\MigrationWorkflow\Logger\Handler\ConsoleMonologLogger;
use MrCoto\MigrationWorkflow\Logger\ILogger;
use MrCoto\MigrationWorkflow\Logger\LoggerFactory;

class MigrateMigrationWorkflowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:workflow {--class=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run specific Migration Workflow file';

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
        $migrateHandler = $this->getMigrateHandler();
        $migrationWorkflow = $this->getMigrationWorkFlowClass();
        $migrateHandler->handle($migrationWorkflow);
    }

    /**
     * Get migration workflow instance
     *
     * @return MigrationWorkflowContract
     */
    private function getMigrationWorkFlowClass() : MigrationWorkflowContract
    {
        $migrationWorkflowClass = $this->option('class');
        if (!$migrationWorkflowClass) {
            $this->logger->error("You need to provide --class argument. Example: php artisan $this->signature --class=App\\MigrationWorkflow\\MigrationWorkflow15102019");
            exit(-1);
        }
        return new $migrationWorkflowClass;
    }

    /**
     * Get Migrate Migration Workflow Handler
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