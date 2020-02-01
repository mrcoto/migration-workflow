<?php 

namespace MrCoto\MigrationWorkflow\Test\Unit\Action\Deploy\ValueObject;

use PHPUnit\Framework\TestCase;
use MrCoto\MigrationWorkflow\Action\Deploy\ValueObject\DeployPathInfoCollection;

class DeployPathInfoCollectionTest extends TestCase
{

    public function test_should_collect_dev_workflows()
    {
        $collection = new DeployPathInfoCollection(
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