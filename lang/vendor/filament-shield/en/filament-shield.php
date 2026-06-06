<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Table Columns
    |--------------------------------------------------------------------------
    */

    'column.name' => 'Name',
    'column.guard_name' => 'Guard Name',
    'column.team' => 'Team',
    'column.roles' => 'Roles',
    'column.permissions' => 'Permissions',
    'column.updated_at' => 'Updated At',

    /*
    |--------------------------------------------------------------------------
    | Form Fields
    |--------------------------------------------------------------------------
    */

    'field.name' => 'Name',
    'field.guard_name' => 'Guard Name',
    'field.permissions' => 'Permissions',
    'field.team' => 'Team',
    'field.team.placeholder' => 'Select a team ...',
    'field.select_all.name' => 'Select All',
    'field.select_all.message' => 'Enables/Disables all Permissions for this role',

    /*
    |--------------------------------------------------------------------------
    | Navigation & Resource
    |--------------------------------------------------------------------------
    */

    'nav.group' => 'Filament Shield',
    'nav.role.label' => 'Roles',
    'nav.role.icon' => 'heroicon-o-shield-check',
    'resource.label.role' => 'Role',
    'resource.label.roles' => 'Roles',

    /*
    |--------------------------------------------------------------------------
    | Section & Tabs
    |--------------------------------------------------------------------------
    */

    'section' => 'Entities',
    'resources' => 'Resources',
    'widgets' => 'Widgets',
    'pages' => 'Pages',
    'custom' => 'Custom Permissions',

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    'forbidden' => 'You do not have permission to access',

    /*
    |--------------------------------------------------------------------------
    | Resource Permissions' Labels
    |--------------------------------------------------------------------------
    */

    'resource_permission_prefixes_labels' => [
        'view' => 'View - see record details',
        'view_any' => 'View Any - see the list of records',
        'create' => 'Create - create a new record',
        'update' => 'Update - edit an existing record',
        'delete' => 'Delete - delete own record',
        'delete_any' => 'Delete Any - delete any record',
        'force_delete' => 'Force Delete - permanently delete from soft delete',
        'force_delete_any' => 'Force Delete Any - permanently delete any record',
        'restore' => 'Restore - restore own soft deleted record',
        'reorder' => 'Reorder - change record order',
        'restore_any' => 'Restore Any - restore any soft deleted record',
        'replicate' => 'Replicate - duplicate a record',
        'widget' => 'Widget - permission to view this widget',
        'page' => 'Page - permission to access this page',
    ],
];
