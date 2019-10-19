<?php 

namespace MrCoto\MigrationWorkflow\Domain;

use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowCollection;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep;

interface MigrationWorkflowContract
{

    /**
     * Return migration workflow,
     * each element in the array is a workflow's step.
     * The step can be:
     * - Run specific migration files
     * - Run specific Seeds
     *
     * Example:
     *
     * return MigrationWorkflow::workflow(
     *      MigrationWorkflow::step('migration', [
     *          'Modules/Warehouse/Database/Migrations/2019_10_18_082139_drop_wh_product_wholesale_ref_table',
     *          'Modules/Warehouse/Database/Migrations/2019_10_18_082121_drop_wh_subfamily_wholesale_ref_table',
     *          'Modules/Sale/Database/Migrations/2019_10_18_085640_drop_sl_wholesale_ref_table',
     *      ]),
     *      MigrationWorkflow::step('seed', [
     *          'Modules\General\Database\Seeders\Production\RolePermissionTableSeeder',
     *          'Modules\General\Database\Seeders\Production\GModuleTableSeeder',
     *          'Modules\General\Database\Seeders\Production\GMenuTableSeeder',
     *          'Modules\General\Database\Seeders\Production\GSubmenuTableSeeder',
     *          'Modules\General\Database\Seeders\Production\ModuleAndGroupPermissionTableSeeder',
     *      ]),
     *  )
     *
     * @return MigrationWorkflowCollection
     */
    function getWorkFlow() : MigrationWorkflowCollection;

}