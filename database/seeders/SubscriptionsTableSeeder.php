<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subscriptions')->insert([
            [
                'name' => 'Standard',
                'price' => 50,
                'duration' => 'Monthly',
                'support' => 'Basic technical support',
                'stripe_id' => 'price_1Q0INdSGK4A29iyOyOiMxUbK',
            ],
            [
                'name' => 'Premium',
                'price' => 500,
                'duration' => 'Annually',
                'support' => '14/7 Support',
                'stripe_id' => 'price_1Q0INdSGK4A29iyOCkJplUoh',
            ],
            [
                'name' => 'Enterprise',
                'price' => 200,
                'duration' => 'Lifetime',
                'support' => '24/7 Support',
                'stripe_id' => 'price_1Q0INdSGK4A29iyOrBy5Y86o',
            ],
        ]);
    }
}
