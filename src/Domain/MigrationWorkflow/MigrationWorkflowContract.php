<?php 

namespace MrCoto\MigrationWorkflow\Domain\MigrationWorkflow;

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
     * [
     *      [
     *          'type' => 'migration',
     *          'files' => [
     *              'Modules/Pos/Database/Migrations/2019_07_29_144309_edit_pos_voucher_counter_table',
     *          ]
     *      ],
     *      [
     *          'type' => 'seed',
     *          'files' => [
     *              "Modules\General\Database\Seeders\\Production\AuditHistoricPriceTableSeeder",
     *          ]
     *      ]
     * ]
     *
     * @return array
     */
    function getWorkFlow() : array;

}