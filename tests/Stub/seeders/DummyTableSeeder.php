<?php 

namespace MrCoto\MigrationWorkflow\Test\Stub\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('dummy')->insert(array(
            'dummy_column' => 'dummy_value'
        ));
    }

}