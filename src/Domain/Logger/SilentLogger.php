<?php 

namespace MrCoto\MigrationWorkflow\Domain\Logger;

use MrCoto\MigrationWorkflow\Domain\Logger\Logger;

class SilentLogger implements Logger
{

    /**
     * Debug information
     *
     * @param string $message
     * @return void
     */
    public function debug(string $message)
    {
        // No Code... Is a Silent Logger
    }

    /**
     * Normal log
     *
     * @param string $message
     * @return void
     */
    public function info(string $message)
    {
        // No Code... Is a Silent Logger
    }

    /**
     * Warning log (not an error)
     *
     * @param string $message
     * @return void
     */
    public function warning(string $message)
    {
        // No Code... Is a Silent Logger
    }

    /**
     * Error happened
     *
     * @param string $message
     * @return void
     */
    public function error(string $message)
    {
        // No Code... Is a Silent Logger
    }

}