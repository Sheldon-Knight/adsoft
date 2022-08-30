<?php

namespace Database\Seeders;

use App\Models\OmsSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;
use LucasDotVin\Soulbscription\Models\Feature;
use LucasDotVin\Soulbscription\Models\Plan;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       

        // Create Basic Plan

        $basic = Plan::create([
            'name'             => 'Basic',
            'periodicity_type' => PeriodicityType::Month,
            'periodicity'      => 1,
            'grace_days'       => 1,
        ]);

        // Create Premium Plan

        $premium = Plan::create([
            'name'             => 'Premium',
            'periodicity_type' => PeriodicityType::Month,
            'periodicity'      => 1,
            'grace_days'       => 1,
        ]);
      

        //Subsribe To A Plan

        $omsSetting = OmsSetting::find(1);

        $plan = Plan::find(1);

        $omsSetting->subscribeTo($plan, startDate: now());


   
    }
}
