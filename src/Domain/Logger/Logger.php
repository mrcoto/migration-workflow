<?php 

namespace MrCoto\MigrationWorkflow\Domain\Logger;

interface Logger
{

    /**
     * Debug information
     *
     * @param string $message
     * @return void
     */
    public function debug(string $message);

    /**
     * Normal log
     *
     * @param string $message
     * @return void
     */
    public function info(string $message);

    /**
     * Warning log (not an error)
     *
     * @param string $message
     * @return void
     */
    public function warning(string $message);

    /**
     * Error happened
     *
     * @param string $message
     * @return void
     */
    public function error(string $message);

}