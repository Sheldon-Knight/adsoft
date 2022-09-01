<?php

namespace App\Console\Commands;

use App\Models\OmsSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class CheckIfSubscriptionExpires extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subsriptions:expires';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check If Subsriptions Expires and Suppress It';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $omsSettings = OmsSetting::first();

        if ($omsSettings->hasExpired()) {
            DB::table('subscriptions')->where('subscriber_id', $omsSettings->id)->delete();
        }

        Artisan::call('cache:clear');
    }
}
