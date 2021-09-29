<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\HeartStore\Services\RoleService;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = RoleService::getRolesConst();

        

        foreach($roles as $role) {
            try {
                $role = Role::findOrCreate($role);
                echo $role->name . ' Created' . PHP_EOL;
            } catch (\Throwable $e) {
                echo $e->getMessage() . PHP_EOL;
                Log::error($e->getMessage());
            }
        }
    }
}
