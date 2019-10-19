<?php 

namespace MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent;

use Illuminate\Database\Migrations\Migration;
use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationWorkflowStepHandler;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep;
use MrCoto\MigrationWorkflow\Infrastructure\Exceptions\ClassFileIsNotMigrationException;
use MrCoto\MigrationWorkflow\Infrastructure\Exceptions\MigrationFileNotFoundException;

class MigrationEloquentStepHandler implements MigrationWorkflowStepHandler
{
    
    /**
     * Handle migration step with eloquent
     *
     * @param integer $stepNumber
     * @param MigrationWorkflowStep $step
     * @return void
     */
    public function handle(int $stepNumber, MigrationWorkflowStep $step)
    {
        foreach($step->files() as $file)
        {
            $fileName = "$file.php";
            $filePath = base_path($fileName);
            if (!file_exists($filePath)) {
                throw new MigrationFileNotFoundException($fileName);
            }
            require_once $filePath;
            /**
             * Note: Before i use tokenizer to get Class from File,
             * For some reason this doesn't work for testing with Orchestra,
             * That's the reason why i'm using get_declared_classes()
             */
            $declaredClasses = get_declared_classes();
            $migrationClass =  $this->getMigrationClass($declaredClasses, $file);
            $migrationObj = new $migrationClass;
            if (!$migrationObj instanceof Migration) {
                throw new ClassFileIsNotMigrationException($file);
            }
            $migrationObj->up();
        }
    }

    /**
     * Get declared migration class
     *
     * @param array $declaredClasses
     * @param string $file
     * @return string
     */
    private function getMigrationClass(array $declaredClasses, string $file) : string
    {
        $classToFind = $this->getMigrationClassToFind($file);
        $migrationClasses = array_filter($declaredClasses, function(string $declaredClass) use ($classToFind) {
            return $this->endsWith($declaredClass, $classToFind);
        });
        return array_shift($migrationClasses);
    }

    /**
     * Return true if $endString ends in $string
     * 'HelloWorld' and 'World' should return true
     *
     * @param string $string
     * @param string $endString
     * @return boolean
     */
    private function endsWith(string $string, string $endString) : bool
    { 
        $len = strlen($endString); 
        if ($len == 0) { 
            return true; 
        } 
        return (substr($string, -$len) === $endString); 
    } 

    /**
     * Convert migration class to find into class
     * Example:
     * database/migrations/2019_10_19_120000_create_dummy_table
     * into
     * CreateDummyTable
     * @param string $file
     * @return string
     */
    private function getMigrationClassToFind(string $file) : string
    {
        $exploded = explode('/', $file);
        $migration = end($exploded);
        $tokens = explode('_', $migration);
        $acceptedTokens = $this->getAcceptedTokensDefinedInClass($tokens);
        $migrationClass = array_reduce($acceptedTokens, function(string $carry, string $item) {
            return $carry.ucfirst($item);
        }, '');
        return $migrationClass;
    }

    /**
     * Get accepted tokens defined in class.
     * Example:
     * [2019, 10, 19, 120000, create, dummy, 2, table]
     * returns
     * [create, dummy, 2, table]
     *
     * @param array $tokens
     * @return array
     */
    private function getAcceptedTokensDefinedInClass(array $tokens) : array
    {
        foreach($tokens as $index => $token) {
            if (!ctype_digit($token)) {
                return array_slice($tokens, $index);
            }
        }
        return $tokens;
    }

}