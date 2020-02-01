<?php

namespace MrCoto\MigrationWorkflow\Logger;

use MrCoto\MigrationWorkflow\Logger\Handler\SilentLogger;

class LoggerFactory
{

    /** @var ILogger $logger */
    private static $logger;

    /**
     * Get logger for workflow classes
     *
     * @return ILogger
     */
    public static function getLogger() : ILogger
    {
        if (!self::$logger) {
            self::$logger = new SilentLogger;
        }
        return self::$logger;
    }

    /**
     * Set logger for workflow classes
     *
     * @param ILogger $logger
     * @return void
     */
    public static function setLogger(ILogger $logger)
    {
        self::$logger = $logger;
    }

}