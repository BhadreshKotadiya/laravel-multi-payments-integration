<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class SubscriptionController extends Controller
{
    public function showSubscriptions()
    {
        $subscriptions = DB::table('subscriptions')->get();
        return view('subscriptions.index', ['subscriptions' => $subscriptions]);
    }

    public function checkout(Request $request)
    {

        //TODO: You just need to hit this URL for webhook after installand login process "http://localhost:8000/stripe/webhook"
        return $request->user()
            ->newSubscription('prod_Qs2Sz2RupWFjAS', 'price_1Q0MWwSGK4A29iyOgYDROfVE')
            ->checkout([
                'success_url' => route('product.index'),
                'cancel_url' => route('subscription.cancel'),
            ]);
    }

    public function cancel()
    {
        dd('not success');
        return view('subscriptions.cancel');
    }

}
