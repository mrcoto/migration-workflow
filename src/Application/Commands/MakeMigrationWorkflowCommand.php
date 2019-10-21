<?php 

namespace MrCoto\MigrationWorkflow\Application\Commands;

use Illuminate\Console\Command;
use MrCoto\MigrationWorkflow\Domain\ValueObject\Stub;

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
        $this->date = date('Y_m_d_His');
        $className = $this->getClassName();
        $stub = new Stub(self::DEFAULT_NAMESPACE, $className, 'migration_workflow');
        $stub->generate();
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