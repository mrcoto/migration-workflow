<?php

namespace MrCoto\MigrationWorkflow\Domain\Logger;

class LoggerFactory
{

    /** @var Logger $logger */
    private static $logger;

    /**
     * Get logger for workflow classes
     *
     * @return Logger
     */
    public static function getLogger() : Logger
    {
        if (!self::$logger) {
            self::$logger = new SilentLogger;
        }
        return self::$logger;
    }

    /**
     * Set logger for workflow classes
     *
     * @param Logger $logger
     * @return void
     */
    public static function setLogger(Logger $logger)
    {
        self::$logger = $logger;
    }

}