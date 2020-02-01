<?php 

namespace MrCoto\MigrationWorkflow\Action\Deploy\ValueObject;

use MrCoto\MigrationWorkflow\Core\MigrationWorkflowContract;
use ReflectionClass;

class DeployPathInfo
{

    /** @var string $version */
    private $version;

    /** @var string $date */
    private $date;

    /** @var int $timestamp */
    private $timestamp;

    private $workflow;

    private const DEFAULT_VERSION = 'v1';

    private const MINIMAL_LENGTH = 5;

    public function __construct(
        MigrationWorkflowContract $workflow
    )
    {
        $reflection = new ReflectionClass($workflow);
        $className = $reflection->getShortName();
        $tokens = explode('_', $className);
        if (count($tokens) > self::MINIMAL_LENGTH) {
            $tokens = array_splice($tokens, count($tokens) - self::MINIMAL_LENGTH);
            $this->version = $tokens[0];
            $datePart = implode('-', array_splice($tokens, 1, 3));
            $timePart = implode(':', str_split($tokens[count($tokens) - 1], 2));
            $this->date = date("$datePart $timePart");
        } else {
            $this->version = self::DEFAULT_VERSION;
            $this->date = date('Y-m-d 00:00:00');
        }
        $this->timestamp = strtotime($this->date);
        $this->workflow = $workflow;
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