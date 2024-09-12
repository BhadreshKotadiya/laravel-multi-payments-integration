   <h1>Laravel Project README</h1>
    <p>This project integrates multiple payment gateways in a Laravel application. Below is the list of packages used and the installation steps.</p>
    <h2>Requirements</h2>
    <p>Ensure your system meets the following requirements:</p>
    <ul>
    <li>PHP >= 8.0</li>
   <li>Laravel >= 8.x</li>
   <li>Composer</li>
   </ul>
   <h2>Installation</h2>
   <p>Follow the steps below to install the necessary packages:</p>
   <h3>1. Razorpay Payment Gateway</h3>
   <p>Run the following command to install the <strong>Razorpay</strong> package:</p>
   <pre><code>composer require razorpay/razorpay</code></pre>
   <h3>2. PayPal Payment Gateway</h3>
   <p>Run the following command to install the <strong>PayPal</strong> package:</p>
   <pre><code>composer require srmklive/paypal:~3.0</code></pre>
   <p>For detailed documentation, refer to the official <a href="https://github.com/srmklive/laravel-paypal">Laravel PayPal package</a>.</p>
   <h3>3. Stripe Payment Gateway</h3>
   <p>Run the following command to install the <strong>Stripe</strong> package:</p>
   <pre><code>composer require stripe/stripe-php</code></pre>
   <p>For more information, refer to the <a href="https://github.com/stripe/stripe-php">Stripe PHP library</a>.</p>
   <h2>Usage</h2>
  <p>After installation, configure your <code>.env</code> file with the necessary credentials for each payment gateway:</p>
  <h3>Razorpay</h3>
  <pre><code>
RAZORPAY_KEY=your_key
RAZORPAY_SECRET=your_secret
    </code></pre>
    <h3>PayPal</h3>
    <pre><code>
PAYPAL_CLIENT_ID=your_client_id
PAYPAL_SECRET=your_secret
PAYPAL_MODE=sandbox
    </code></pre>
    <h3>Stripe</h3>
    <pre><code>
STRIPE_KEY=your_key
STRIPE_SECRET=your_secret
    </code></pre>
