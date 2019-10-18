<?php 

namespace MrCoto\MigrationWorkflow\Infrastructure\Logger;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use MrCoto\MigrationWorkflow\Domain\Logger\Logger;
use \Bramus\Monolog\Formatter\ColoredLineFormatter;

class ConsoleMonologLogger implements Logger
{

    private $channel = 'migration-workflow';
    private $outputFormat = "[%datetime%] %channel%.%level_name%: %message%\n";
    
    private $logger;

    public function __construct()
    {
        $streamHandler = new StreamHandler('php://stdout', MonologLogger::DEBUG);
        $streamHandler->setFormatter(new ColoredLineFormatter(null, $this->outputFormat));
        
        $this->logger = new MonologLogger($this->channel);
        $this->logger->pushHandler($streamHandler);
    }

    /**
     * Debug information
     *
     * @param string $message
     * @return void
     */
    public function debug(string $message)
    {
        $this->logger->debug($message);
    }

    /**
     * Normal log
     *
     * @param string $message
     * @return void
     */
    public function info(string $message)
    {
        $this->logger->info($message);
    }

    /**
     * Warning log (not an error)
     *
     * @param string $message
     * @return void
     */
    public function warning(string $message)
    {
        $this->logger->warning($message);
    }

    /**
     * Error happened
     *
     * @param string $message
     * @return void
     */
    public function error(string $message)
    {
        $this->logger->error($message);
    }

}