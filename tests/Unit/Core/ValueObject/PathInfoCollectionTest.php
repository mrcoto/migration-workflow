<?php 

namespace MrCoto\MigrationWorkflow\Test\Unit\Action\Deploy\ValueObject;

use MrCoto\MigrationWorkflow\Core\ValueObject\PathInfoCollection;
use PHPUnit\Framework\TestCase;

class PathInfoCollectionTest extends TestCase
{

    public function test_should_collect_dev_workflows()
    {
        $collection = new PathInfoCollection(
            [
                'tests',
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