<?php

namespace Database\Seeders;

use App\Models\IpAddress;
use Illuminate\Database\Seeder;

class IpAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IpAddress::create([
            'ip' => '192.168.0.1',
            'status' => 1
        ]);
        IpAddress::create([
            'ip' => '192.168.0.2',
            'status' => 0
        ]);
        IpAddress::create([
            'ip' => '192.168.0.3',
            'status' => 0
        ]);
    }
}
