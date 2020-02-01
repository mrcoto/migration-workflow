<?php 

namespace MrCoto\MigrationWorkflow\Core\ValueObject;

use MrCoto\MigrationWorkflow\Core\Exceptions\MigrationWorkflowBadClassNameException;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflowConstant;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflowContract;
use ReflectionClass;

/**
 * Separate a MigrationWorkflow filename into variables,
 * For example:
 * MigrationWorkflow_develop_2020_01_20_160420
 * into
 * $version => develop
 * $date => '2020-01-20 16:04:20'
 */
class PathInfo
{

    /** @var string $version */
    private $version;

    /** @var string $date */
    private $date;

    /** @var int $timestamp */
    private $timestamp;

    private $workflow;

    public function __construct(
        MigrationWorkflowContract $workflow
    )
    {
        $reflection = new ReflectionClass($workflow);
        $className = $reflection->getShortName();
        if (!preg_match(MigrationWorkflowConstant::MIGRATION_WORKFLOW_CLASS_REGEX, $className)) {
            throw new MigrationWorkflowBadClassNameException($className);
        }
        $this->retrieveInfoFromClassName($className);
        $this->workflow = $workflow;
    }

    /**
     * Get workflow information via class name
     *
     * @param string $className
     * @return void
     */
    private function retrieveInfoFromClassName(string $className)
    {
        // $tokens = ['MigrationWorkflow', 'develop', '2020', '02', '01', '171520'];
        $tokens = explode('_', $className);
        $this->version = $tokens[1];
        $datePart = implode('-', array_slice($tokens, 2, 3));
        $timePart = date('H:i:s', strtotime(end($tokens)));
        $this->date = date("$datePart $timePart");
        $this->timestamp = strtotime($this->date);
    }

    /**
     * Get workflow version
     *
     * @return string
     */
    public function version() : string
    {
        return $this->version;
    }

    /**
     * Get workflow timestamp
     *
     * @return string
     */
    public function date() : string
    {
        return $this->date;
    }

    /**
     * Get workflow timestamp
     *
     * @return int
     */
    public function timestamp() : int
    {
        return $this->timestamp;
    }

    /**
     * Get workflow
     *
     * @return MigrationWorkflowContract
     */
    public function workflow() : MigrationWorkflowContract
    {
        return $this->workflow;
    }


}