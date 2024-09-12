<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
































































































































































































































// STRIPE_KEY=pk_test_51Ou8cLSGK4A29iyOPgTaEJn5LwDGzZuR6Q8vCblpvsARz3hWPPdoQyD51Y8ahwryHGM8UsRvQyZIjbLOOnc1K50700AZZW1j37
// STRIPE_SECRET=sk_test_51Ou8cLSGK4A29iyOLdna9eImuAKBelWAD1wp5yYT6KDefkcMQpKbk9t9rISzuqxfOOVZasE74UHFibJFH7OuV3rY00CrHu0sBC
// STRIPE_WEBHOOK_SECRET=whsec_dd03308c0ba7853723ea0e890cd57585ad93cebaab83ae210f92ba91362db864


// #google pay
// Merchant_ID=BCR2DN4T6OL7JPCJ

// #phone pay
// PHONEPE_MERCHANT_ID=PGTESTPAYUAT86
// PHONEPE_MERCHANT_KEY=96434309-7796-489d-8924-ab56988a6076

// #paypal
// PAYPAL_MODE=sandbox
// PAYPAL_SANDBOX_CLIENT_ID=AcAfmnkyJ402JZcPQWgPjpVb0aXHChJdXY5uXciH_U4X0y8B14RiIJlJGOLkHy352mvXrNQXDaX1e4-X
// PAYPAL_SANDBOX_CLIENT_SECRET=EOEr_9teEYO0tlNrs4YUCRTqM4iCGux7gv2xozC0BsE4EzjhpEqCMquE74lc-6Ho-OT4zngNpxc6XGQD

// #razor pay
// RAZORPAY_KEY=rzp_test_kgiqNLvIMRszQY
// RAZORPAY_SECRET=xkI8ORliQssXNIHTkrMcvSvI
