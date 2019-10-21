<?php 

namespace MrCoto\MigrationWorkflow\Application\Commands;

use Illuminate\Console\Command;
use MrCoto\MigrationWorkflow\Domain\Logger\Logger;
use MrCoto\MigrationWorkflow\Domain\ValueObject\Stub;
use MrCoto\MigrationWorkflow\Infrastructure\Logger\ConsoleMonologLogger;

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
        $this->logger = new ConsoleMonologLogger;
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

}