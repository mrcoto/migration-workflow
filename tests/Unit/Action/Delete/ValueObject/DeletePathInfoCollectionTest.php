<?php 

namespace MrCoto\MigrationWorkflow\Test\Unit\Action\Delete\ValueObject;

use MrCoto\MigrationWorkflow\Action\Delete\ValueObject\DeletePathInfoCollection;
use PHPUnit\Framework\TestCase;

class DeletePathInfoCollectionTest extends TestCase
{

    public function test_should_collect_dev_workflows()
    {
        $collection = new DeletePathInfoCollection(
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