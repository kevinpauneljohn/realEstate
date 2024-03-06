<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'super admin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'agent']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'referral']);
        Role::create(['name' => 'architect']);
        Role::create(['name' => 'client']);
        Role::create(['name' => 'builder admin']);
        Role::create(['name' => 'builder member']);
        Role::create(['name' => 'account manager']);
        Role::create(['name' => 'online warrior']);
        Role::create(['name' => 'JJ online warrior']);
        Role::create(['name' => 'Finance Admin']);
        Role::create(['name' => 'dhg_ojt']);
        Role::create(['name' => 'dhg_staff']);
        Role::create(['name' => 'team leader']);

        Permission::create(['name' => 'add role']);
        Permission::create(['name' => 'view role']);
        Permission::create(['name' => 'edit role']);
        Permission::create(['name' => 'delete role']);

        Permission::create(['name' => 'add permission']);
        Permission::create(['name' => 'view permission']);
        Permission::create(['name' => 'edit permission']);
        Permission::create(['name' => 'delete permission']);

        Permission::create(['name' => 'add user']);
        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']);

        Permission::create(['name' => 'add sales']);
        Permission::create(['name' => 'view sales']);
        Permission::create(['name' => 'edit sales']);
        Permission::create(['name' => 'delete sales']);

        Permission::create(['name' => 'add lead']);
        Permission::create(['name' => 'view lead']);
        Permission::create(['name' => 'edit lead']);
        Permission::create(['name' => 'delete lead']);

        Permission::create(['name' => 'add project']);
        Permission::create(['name' => 'view project']);
        Permission::create(['name' => 'edit project']);
        Permission::create(['name' => 'delete project']);

        Permission::create(['name' => 'add schedule']);
        Permission::create(['name' => 'view schedule']);
        Permission::create(['name' => 'edit schedule']);
        Permission::create(['name' => 'delete schedule']);

        Permission::create(['name' => 'add announcement']);
        Permission::create(['name' => 'view announcement']);
        Permission::create(['name' => 'edit announcement']);
        Permission::create(['name' => 'delete announcement']);

        Permission::create(['name' => 'add model unit']);
        Permission::create(['name' => 'view model unit']);
        Permission::create(['name' => 'edit model unit']);
        Permission::create(['name' => 'delete model unit']);

        Permission::create(['name' => 'add commissions']);
        Permission::create(['name' => 'view commissions']);
        Permission::create(['name' => 'edit commissions']);
        Permission::create(['name' => 'delete commissions']);

        Permission::create(['name' => 'add requirements']);
        Permission::create(['name' => 'view requirements']);
        Permission::create(['name' => 'edit requirements']);
        Permission::create(['name' => 'delete requirements']);
        Permission::create(['name' => 'duplicate requirements']);
        Permission::create(['name' => 'view requirement template']);

        Permission::create(['name' => 'upload requirements']);
        Permission::create(['name' => 'view settings']);

        Permission::create(['name' => 'add action']);
        Permission::create(['name' => 'view action']);
        Permission::create(['name' => 'edit action']);
        Permission::create(['name' => 'delete action']);

        Permission::create(['name' => 'add canned message']);
        Permission::create(['name' => 'view canned message']);
        Permission::create(['name' => 'edit canned message']);
        Permission::create(['name' => 'delete canned message']);

        Permission::create(['name' => 'add computation']);
        Permission::create(['name' => 'view computation']);
        Permission::create(['name' => 'edit computation']);
        Permission::create(['name' => 'delete computation']);

        Permission::create(['name' => 'view down line leads']);
        Permission::create(['name' => 'view down lines']);

        Permission::create(['name' => 'add contacts']);
        Permission::create(['name' => 'view contacts']);
        Permission::create(['name' => 'edit contacts']);
        Permission::create(['name' => 'delete contacts']);

        Permission::create(['name' => 'view wallet']);
        Permission::create(['name' => 'withdraw money']);

        Permission::create(['name' => 'add rank']);
        Permission::create(['name' => 'view rank']);
        Permission::create(['name' => 'edit rank']);
        Permission::create(['name' => 'delete rank']);

        Permission::create(['name' => 'add task']);
        Permission::create(['name' => 'view task']);
        Permission::create(['name' => 'edit task']);
        Permission::create(['name' => 'delete task']);
        Permission::create(['name' => 'add ojt task']);

        Permission::create(['name' => 'add client']);
        Permission::create(['name' => 'view client']);
        Permission::create(['name' => 'edit client']);
        Permission::create(['name' => 'delete client']);

        Permission::create(['name' => 'add documentation']);
        Permission::create(['name' => 'view documentation']);
        Permission::create(['name' => 'edit documentation']);
        Permission::create(['name' => 'delete documentation']);

        //added Dec. 11, 2020 permission for builder features
        Permission::create(['name' => 'add builder']);
        Permission::create(['name' => 'view builder']);
        Permission::create(['name' => 'edit builder']);
        Permission::create(['name' => 'delete builder']);

        //added Dec. 13, 2020 permission for dream home guide project module
        Permission::create(['name' => 'add dhg project']);
        Permission::create(['name' => 'view dhg project']);
        Permission::create(['name' => 'edit dhg project']);
        Permission::create(['name' => 'delete dhg project']);

        //added Dec. 20, 2020 permission for client payment module
        Permission::create(['name' => 'add client payment']);
        Permission::create(['name' => 'view client payment']);
        Permission::create(['name' => 'edit client payment']);
        Permission::create(['name' => 'delete client payment']);

        //added Dec. 27, 2020 permission for builder member module
        Permission::create(['name' => 'add builder member']);
        Permission::create(['name' => 'view builder member']);
        Permission::create(['name' => 'edit builder member']);
        Permission::create(['name' => 'delete builder member']);

        Permission::create(['name' => 'view assigned lead'])->syncRoles(['online warrior','account manager','admin']);
        Permission::create(['name' => 'assign leads'])->syncRoles(['account manager','admin']);

        Permission::create(['name' => 'view checklist'])->syncRoles(['online warrior','account manager','admin']);
        Permission::create(['name' => 'add checklist'])->syncRoles(['account manager','admin']);
        Permission::create(['name' => 'edit checklist'])->syncRoles(['account manager','admin']);
        Permission::create(['name' => 'delete checklist'])->syncRoles(['account manager','admin']);

        Permission::create(['name' => 'view staycation client'])->syncRoles(['JJ online warrior']);
        Permission::create(['name' => 'add staycation client'])->syncRoles(['JJ online warrior']);
        Permission::create(['name' => 'edit staycation client'])->syncRoles(['JJ online warrior']);
        Permission::create(['name' => 'delete staycation client'])->syncRoles(['JJ online warrior']);

        Permission::create(['name' => 'view staycation appointment'])->syncRoles(['JJ online warrior']);
        Permission::create(['name' => 'add staycation appointment'])->syncRoles(['JJ online warrior']);
        Permission::create(['name' => 'edit staycation appointment'])->syncRoles(['JJ online warrior']);
        Permission::create(['name' => 'delete staycation appointment'])->syncRoles(['JJ online warrior']);

        Permission::create(['name' => 'change password']);

        Permission::create(['name' => 'view commission request'])->syncRoles(['team leader','admin','manager','agent','finance admin']);
        Permission::create(['name' => 'request commission'])->syncRoles(['team leader','manager','agent']);

        Permission::create(['name' => 'view task export'])->syncRoles(['account manager','admin']);
        Permission::create(['name' => 'declare contest winner']);
    }
}
