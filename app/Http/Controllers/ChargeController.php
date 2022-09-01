<?php

namespace App\Http\Controllers;

use App\Models\OmsSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LucasDotVin\Soulbscription\Models\Plan;
use Yoco\Exceptions\ApiKeyException;
use Yoco\Exceptions\DeclinedException;
use Yoco\Exceptions\InternalException;
use Yoco\YocoClient;

class ChargeController extends Controller
{
    public function charge()
    {
        $this->validate(request(), [
            'token' => 'required',
            'amountInCents' => 'required|int',
            'currency' => 'required',
            'metadata' => 'required',
        ]);
        $token = request()->input('token');
        $amountInCents = request()->input('amountInCents');
        $currency = request()->input('currency');
        $metadata = request()->input('metadata') ?? [];

        $client = new YocoClient(config('yoco.secret_key'), config('yoco.public_key'));

        try {
            $response = response()->json($client->charge($token, $amountInCents, $currency, $metadata));

            $this->CheckMethod($metadata['method']);

            return $response;
        } catch (ApiKeyException $e) {
            Log::error("Failed to charge card with token $token, amount $currency $amountInCents : ".$e->getMessage());

            return response()->json(['charge_error' => $e], 400);
        } catch (DeclinedException $e) {
            Log::error("Failed to charge card with token $token, amount $currency $amountInCents : ".$e->getMessage());

            return response()->json(['charge_error' => $e], 400);
        } catch (InternalException $e) {
            Log::error("Failed to charge card with token $token, amount $currency $amountInCents : ".$e->getMessage());

            return response()->json(['charge_error' => $e], 400);
        }
    }

    private function CheckMethod($method)
    {
        switch ($method) {
            case 'subscribe-to-basic-plan':
                $this->subscribeTo(1);
                break;
            case 'subscribe-to-premium-plan':
                $this->subscribeTo(2);
                break;
            default:
                $this->renew();
                break;
        }
    }

    private function renew()
    {
        $subscription = OmsSetting::first();

        $subscription->subscription->renew();

        $this->redoCache();
    }

    private function subscribeTo($id)
    {
        $plan = Plan::find($id);

        $subscription = OmsSetting::first();

        DB::table('subscriptions')->where('subscriber_id', $subscription->id)->delete();

        $subscription->subscribeTo($plan);

        $this->redoCache();
    }

    private function redoCache()
    {
        cache()->forget('subscription');

        cache()->forget('current_plan');

        cache()->forget('hasExpired');

        cache()->forever('subscription', OmsSetting::first()->subscription);

        cache()->forever('current_plan', OmsSetting::first()->subscription->plan->name);
    }
}
