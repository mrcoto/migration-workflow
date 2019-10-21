<?php 

return [

    /*
    |--------------------------------------------------------------------------
    | Migration Workflow Table Name
    |--------------------------------------------------------------------------
    |
    | All your deployed migrations run via migrate:deploy will be placed in this table
    |
    */
    'table_name' => 'migration_workflow',

    /*
    |--------------------------------------------------------------------------
    | Migration Workflow Detail Table Name
    |--------------------------------------------------------------------------
    |
    | All steps and files (detail of migration workflow header table) will be placed here
    |
    */
    'table_name_detail' => 'migration_workflow_detail',

    /*
    |--------------------------------------------------------------------------
    | Folders where workflows will be looked for
    |--------------------------------------------------------------------------
    |
    | You may specify namespace path from where your workflows are placed
    | Notice that migrate:deploy run your workflows in ascending order,
    | omitting already migrated
    |
    */
    'workflows' => [
        'App\Workflows'
    ],

];