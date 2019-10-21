<?php 

namespace MrCoto\MigrationWorkflow\Application\Commands;

use Illuminate\Console\Command;
use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationWorkflowHandler;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflowContract;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\HookEloquentHandler;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\MigrationEloquentStepHandler;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\SeedEloquentStepHandler;
use MrCoto\MigrationWorkflow\Infrastructure\Logger\ConsoleMonologLogger;

class MigrateWorkflowCommand extends Command
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
    protected $description = 'Run specific Migration Workflow';

    private $logger;

    private $migrationWorkflowHandler;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->logger = new ConsoleMonologLogger;
        $this->migrationWorkflowHandler = new MigrationWorkflowHandler(
            $this->logger,
            new MigrationEloquentStepHandler,
            new SeedEloquentStepHandler,
            new HookEloquentHandler
        );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $migrationWorkflow = $this->getMigrationWorkFlowClass();
        $this->migrationWorkflowHandler->handle($migrationWorkflow);
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
        $instance = new $migrationWorkflowClass;
        return $instance;
    }

}