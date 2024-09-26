@extends('layouts.master')

@section('title', 'Prime Version Benefits')

@section('content')
    <div class="body">
        <h1 class="title">Subscription Plans</h1>
        <div class="subscribe-container">
            @foreach (config('subscriptions_plan') as $subscription)
                <div class="offers">
                    <h2 class="subscribe-name">{{ $subscription['name'] }}</h2>
                    <h3 class="subscribe-price">₹{{ $subscription['price'] }}</h3>
                    <small class="subscribe-duration">{{ $subscription['duration'] }}</small>
                    <p class="subscribe-p">{{ $subscription['support'] }}</p>
                    <form action="{{ route('subscription.checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="subscription_id" value="{{ $subscription['stripe_id'] }}">
                        <button type="submit" class="subscribe-btn">Subscribe</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endsection
