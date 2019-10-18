<?php

require 'vendor/autoload.php';

use MrCoto\MigrationWorkflow\Infrastructure\Logger\ConsoleMonologLogger;

$logger = new ConsoleMonologLogger();

$logger->debug("Debug log sentence");
$logger->info("Info log sentence");
$logger->warning("Warning log sentence");
$logger->error("Error log sentence");