<?php

require 'vendor/autoload.php';

use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\MigrationWorkflow;
use MrCoto\MigrationWorkflow\Infrastructure\Logger\ConsoleMonologLogger;
use MrCoto\MigrationWorkflow\Infrastructure\MigrationWorkflow\Handlers\MigrationEloquentStepHandler;

$logger = new ConsoleMonologLogger();

$logger->debug("Debug log sentence");
$logger->info("Info log sentence");
$logger->warning("Warning log sentence");
$logger->error("Error log sentence");

// $workflow = MigrationWorkflow::workflow(
//     MigrationWorkflow::step('type'),
// )

$step = MigrationWorkflow::step('migration', [
    'MrCoto\MigrationWorkflow\Infrastructure\Logger\ConsoleMonologLogger'
]);

$migrationEloquentStepHandler = new MigrationEloquentStepHandler;
$migrationEloquentStepHandler->handle(1, $step);