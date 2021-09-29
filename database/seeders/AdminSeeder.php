<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\HeartStore\Services\RoleService;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            
            $admin = new User;
            $admin->username = 'Admin';
            $admin->first_name = 'Sonu';
            $admin->last_name = 'Chauhan';
            $admin->email = 'sonu.chauhan@opayn.com';
            $admin->password = Hash::make('123456');
            $admin->save();
            
            //Assign role
            $admin->assignRole(RoleService::ADMIN);

            echo "Success:" . 'Record created.' . PHP_EOL;

        } catch (\Throwable $e) {
            echo "Error:" . $e->getMessage() . PHP_EOL;
            Log::error($e->getMessage());
        }
    }
}
