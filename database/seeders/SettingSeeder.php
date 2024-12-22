<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Admin\Entities\Setting\Settinge;
use Modules\Admin\Entities\Setting\SmsSetting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //        setting
        $setting = Settinge::query()->first();
        if(empty($setting)) {
            Settinge::query()->create([
                'title' => 'وبلاگ',
                'keywords' => 'وبلاگ',
                'description' => 'وبلاگ',
                'icon' => 'test',
                'logo_footer' => 'test',
                'logo_header' => 'test',
            ]);
        }

        //      sms  setting
        $sms = SmsSetting::query()->first();
        if(empty($sms)) {
            SmsSetting::query()->create([
                'username' => 'test',
                'password' => 'test',
                'from' => 'test',
            ]);
        }

    }
}
