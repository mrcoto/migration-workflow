<?php 

namespace MrCoto\MigrationWorkflow\Test\Domain;

use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowDataCollection;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class MigrationWorkflowDataCollectionTest extends LaravelTest
{

    public function test_should_collect_dev_workflows()
    {
        $collection = new MigrationWorkflowDataCollection(
            [
                'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data2',
                'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data1',
            ],
            ['dev']
        );

        $items = $collection->items();
        $this->assertEquals(2, count($items));
        foreach($items as $item) {
            $this->assertEquals('dev', $item->version());
        }
        
    }

}