<?php 

namespace MrCoto\MigrationWorkflow\Action\Make\Commands;

use Illuminate\Console\Command;
use MrCoto\MigrationWorkflow\Action\Make\Handler\Stub;
use MrCoto\MigrationWorkflow\Logger\Handler\ConsoleMonologLogger;
use MrCoto\MigrationWorkflow\Logger\ILogger;
use MrCoto\MigrationWorkflow\Logger\LoggerFactory;

class MakeMigrationWorkflowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:workflow {className} {version=v1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a workflow php file';

    private const DEFAULT_NAMESPACE = 'App\MigrationWorkflows';

    /** @var string $date */
    private $date;

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
        $this->date = date('Y_m_d_His');
        $className = $this->getClassName();
        $stub = new Stub(self::DEFAULT_NAMESPACE, $className, 'migration_workflow');
        $stub->generate();
        $this->logger->info("Class ".self::DEFAULT_NAMESPACE."\\$className generated");
    }

    /**
     * Return class name
     *
     * @return string
     */
    private function getClassName() : string
    {
        return $this->argument('className').'_'.$this->argument('version').'_'.$this->date;
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