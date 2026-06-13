<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;
use Modules\Setting\Model\GeneralSetting;

class AddFloatingIconSetupPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add Floating Icon Setup Permission
        Permission::withoutEvents(function () {
            Permission::updateOrCreate([
                'route' => 'setting.floating_icon_setup'
            ], [
                'module_id' => 12,
                'parent_id' => 12,
                'name' => 'Floating Icon Setup',
                'type' => 2,
                'status' => 1,
                'menu_status' => 1,
                'icon' => 'fas fa-th',
                'position' => 99999,
                'parent_route' => 'settings'
            ]);
        });

        // Add default settings
        $settings = [
            'floating_whatsapp' => '971566835512',
            'floating_email' => 'sales@elegant-training.ae',
            'floating_phone' => '+971566835512',
        ];

        foreach ($settings as $key => $value) {
            GeneralSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('route', 'setting.floating_icon_setup')->delete();
        
        GeneralSetting::whereIn('key', ['floating_whatsapp', 'floating_email', 'floating_phone'])->delete();
    }
}
