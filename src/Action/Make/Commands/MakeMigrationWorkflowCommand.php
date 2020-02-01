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
    protected $signature = 'make:workflow {className} {versions=v1} {--owndir} {--date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate workflows php file';

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
        $versions = $this->getVersions();
        foreach($versions as $version) {
            $className = $this->getClassName($version);
            $baseNamespace = $this->getBaseNamespace($version);
            $stub = new Stub($baseNamespace, $className);
            $stub->generate();
            $this->logger->info("Class $baseNamespace\\$className generated");
        }
    }

    /**
     * Get versions
     *
     * @return array
     */
    private function getVersions() : array
    {
        $versions = $this->argument('versions');
        if (!$versions || empty($versions)) {
            return [];
        }
        return array_map(function(string $version) {
            return trim($version);
        }, explode(',', $versions));
    }

    /**
     * Get Base Namespace:
     * 
     * Default => App\MigrationWorkflows
     * If --owndir passed => App\MigrationWorkflows\Version
     * If --date passed => App\MigrationWorkflows\YYYY\MM\DD
     * IF --owndir and --date passed => App\MigrationWorkflows\Version\YYYY\MM\DD
     *
     * @return string
     */
    private function getBaseNamespace(string $version) : string
    {
        return self::DEFAULT_NAMESPACE.
               ($this->option('owndir') ? '\\'.ucfirst($version) : '').
               ($this->option('date') ? '\\'.str_replace('-', '\\', date('Y-m-d')) : '');
    }

    /**
     * Return class name
     *
     * @param string $version
     * @return string
     */
    private function getClassName(string $version) : string
    {
        return $this->argument('className').'_'.$version.'_'.$this->date;
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