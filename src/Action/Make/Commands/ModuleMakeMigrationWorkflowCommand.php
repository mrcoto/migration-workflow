<?php 

namespace MrCoto\MigrationWorkflow\Action\Make\Commands;

use Illuminate\Console\Command;
use MrCoto\MigrationWorkflow\Action\Make\Handler\Stub;
use MrCoto\MigrationWorkflow\Logger\Handler\ConsoleMonologLogger;
use MrCoto\MigrationWorkflow\Logger\ILogger;
use MrCoto\MigrationWorkflow\Logger\LoggerFactory;

class ModuleMakeMigrationWorkflowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-workflow {module} {className} {version=v1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a workflow php file in module folder (useful for laravel package module)';

    private const DEFAULT_NAMESPACE = 'Modules\$MODULE\MigrationWorkflows';

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
        $namespace = str_replace('$MODULE', $this->argument('module'), self::DEFAULT_NAMESPACE);
        $stub = new Stub($namespace, $className, 'migration_workflow');
        $stub->generate();
        $this->logger->info("Class $namespace\\$className generated");
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