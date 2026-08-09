<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;
use Modules\SidebarManager\Entities\PermissionSection;

class AddQuestionPoolPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Create a PermissionSection if it doesn't exist, or just use the existing logic where "question-pool" menu will be registered.
        // Actually, looking at SidebarManager, we can just create the Permissions with type=1, type=2, etc.

        // Add parent menu item
        $parent = Permission::create([
            'name' => 'Question Pool',
            'route' => 'question-pool',
            'parent_route' => null,
            'type' => 1,
            'icon' => 'fas fa-layer-group',
            'backend' => 1
        ]);

        // Add submenus
        $submenus = [
            [
                'name' => 'Question List',
                'route' => 'question-pool.index',
                'parent_route' => 'question-pool',
                'type' => 2,
                'backend' => 1
            ],
            [
                'name' => 'Add Question',
                'route' => 'question-pool.create',
                'parent_route' => 'question-pool',
                'type' => 2,
                'backend' => 1
            ],
            [
                'name' => 'Bulk Import',
                'route' => 'question-pool.bulk-import',
                'parent_route' => 'question-pool',
                'type' => 2,
                'backend' => 1
            ]
        ];

        foreach ($submenus as $submenu) {
            Permission::create($submenu);
        }

        // Actions
        $actions = [
            ['name' => 'Store Question', 'route' => 'question-pool.store', 'parent_route' => 'question-pool.create', 'type' => 3, 'backend' => 1],
            ['name' => 'Edit Question', 'route' => 'question-pool.edit', 'parent_route' => 'question-pool.index', 'type' => 3, 'backend' => 1],
            ['name' => 'Update Question', 'route' => 'question-pool.update', 'parent_route' => 'question-pool.edit', 'type' => 3, 'backend' => 1],
            ['name' => 'Delete Question', 'route' => 'question-pool.delete', 'parent_route' => 'question-pool.index', 'type' => 3, 'backend' => 1],
            ['name' => 'Bulk Import Submit', 'route' => 'question-pool.bulk-import-submit', 'parent_route' => 'question-pool.bulk-import', 'type' => 3, 'backend' => 1],
            ['name' => 'Download Sample', 'route' => 'question-pool.download-sample', 'parent_route' => 'question-pool.bulk-import', 'type' => 3, 'backend' => 1],
            ['name' => 'Get Chapters', 'route' => 'question-pool.get-chapters', 'parent_route' => 'question-pool.create', 'type' => 3, 'backend' => 1],
            ['name' => 'Get Lessons', 'route' => 'question-pool.get-lessons', 'parent_route' => 'question-pool.create', 'type' => 3, 'backend' => 1],
        ];

        foreach ($actions as $action) {
            Permission::create($action);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $routes = [
            'question-pool',
            'question-pool.index',
            'question-pool.create',
            'question-pool.bulk-import',
            'question-pool.store',
            'question-pool.edit',
            'question-pool.update',
            'question-pool.delete',
            'question-pool.bulk-import-submit',
            'question-pool.download-sample',
            'question-pool.get-chapters',
            'question-pool.get-lessons'
        ];
        
        Permission::whereIn('route', $routes)->delete();
    }
}
