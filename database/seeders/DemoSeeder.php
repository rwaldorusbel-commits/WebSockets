<?php
// database/seeders/DemoSeeder.php

namespace Database\Seeders;

use App\Models\Counter;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        Service::insert([
            ['name' => 'Trámite documentario', 'prefix' => 'A', 'is_active' => true],
            ['name' => 'Pagos y tributos',     'prefix' => 'B', 'is_active' => true],
            ['name' => 'Consultas',            'prefix' => 'C', 'is_active' => true],
        ]);

        $op = User::create([
            'name'     => 'Operador 1',
            'email'    => 'op1@demo.com',
            'password' => bcrypt('password'),
            'role'     => 'operator',
        ]);

        Counter::create([
            'name'    => 'Ventanilla 1',
            'user_id' => $op->id,
        ]);
    }
}
