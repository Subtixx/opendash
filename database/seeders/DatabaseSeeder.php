<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Widget;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@local.host',
            'password' => bcrypt('password'),
        ]);


        /*
1,Sample Widget,0,0,2,2,App\Widgets\SampleWidget,[],,2023-11-22 11:10:21,2023-11-22 11:10:21
2,Line Chart Widget,2,0,2,2,App\Widgets\ChartWidget,[],,2023-11-22 11:10:21,2023-11-22 11:10:21
3,Bar Chart Widget,4,0,2,2,App\Widgets\ChartWidget,'{''type'':''bar''}',,2023-11-22 11:10:21,2023-11-22 11:10:21
4,Bubble Chart Widget,6,0,2,2,App\Widgets\ChartWidget,'{''type'':''bubble'',''datasets'':[{''label'':''My First dataset'',''data'':[{''x'':20,''y'':30,''r'':15},{''x'':40,''y'':10,''r'':10},{''x'':30,''y'':22,''r'':5}]}]}',,2023-11-22 11:10:21,2023-11-22 11:10:21
5,CPU Info Widget,0,2,2,2,App\Widgets\Cpu\CpuTemperatureWidget,[],,2023-11-22 11:10:21,2023-11-22 11:10:21
6,CPU Load Widget,2,2,2,2,App\Widgets\Cpu\CpuLoadWidget,[],,2023-11-22 11:10:21,2023-11-22 11:10:21
7,HTTP Check Widget,4,2,2,1,App\Widgets\HttpCheckWidget,'{''url'':''https:\/\/www.google.com'',''okCode'':200,''timeout'':10}',,2023-11-22 11:10:21,2023-11-22 11:10:21
8,HTTP Check Widget 2,4,3,2,1,App\Widgets\HttpCheckWidget,'{''url'':''https:\/\/www.facebook.com'',''okCode'':200,''timeout'':10}',,2023-11-22 11:10:21,2023-11-22 11:10:21
         */
    }
}
