<?php

use Illuminate\Database\Seeder;

class DeviceSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Device::class,50)->create(['state' => \App\Enums\DeviceStateEnum::ACTIVE]);
        factory(\App\Device::class,50)->create(['state' => \App\Enums\DeviceStateEnum::NOT_ACTIVE]);
    }
}
