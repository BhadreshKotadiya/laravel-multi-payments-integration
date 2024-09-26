<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class PhonePeController extends Controller
{
    public function processPhonePayCheckout(Request $request)
    {

        $cart = session()->get('cart');
        $amount = 0;
        $user = auth()->user();

        foreach ($cart as $item) {
            $price = $item['price'];
            $discountedPrice = $price * 0.8;

            $amount += $discountedPrice * $item['quantity'];
        }

        // $merchantId = 'PGTESTPAYUAT';
        // $apiKey = '099eb0cd-02cf-4e2a-8aca-3e6c6aff0399';

        $merchantId = 'PGTESTPAYUAT86';
        $apiKey = '96434309-7796-489d-8924-ab56988a6076';

        $redirectUrl = route('confirm');
        $order_id = uniqid();


        $transaction_data = array(
            'merchantId' => "$merchantId",
            'merchantTransactionId' => "$order_id",
            "merchantUserId" => $order_id,
            'amount' => $amount * 100,
            'redirectUrl' => "$redirectUrl",
            'redirectMode' => "POST",
            'callbackUrl' => "$redirectUrl",

            "paymentInstrument" => array(
                "type" => "PAY_PAGE",
            )
        );


        $encode = base64_encode(json_encode($transaction_data));
        $salt_index = 1;
        $payload = $encode . "/pg/v1/pay" . $apiKey;
        $sha256 = hash("sha256", $payload);
        $final_x_header = $sha256 . '###' . $salt_index;

        $resp = Curl::to('https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay')
            ->withHeader('Content-Type:application/json')
            ->withHeader('X-VERIFY:' . $final_x_header)
            ->withData(json_encode(['request' => $encode]))
            ->post();
        $res = json_decode($resp, true);

        if (isset($respData['error'])) {
            // Handle the error accordingly
            dd('Error occurred: ' . $res['error']['message']);
        }

        //    $payUrl = $res->data->instrumentResponse->redirectInfo->url;
        $payUrl = $res['data']['instrumentResponse']['redirectInfo']['url'];
        return redirect()->away($payUrl);
    }

    public function confirmPayment(Request $request)
    {
        $data = $request->all();
        if ($request->code == 'PAYMENT_SUCCESS') {
            $transactionId = $request->transactionId;
            $merchantId = $request->merchantId;
            $providerReferenceId = $request->providerReferenceId;
            $merchantOrderId = $request->merchantOrderId;
            $checksum = $request->checksum;
            $status = $request->code;

            //Transaction completed, You can add transaction details into database

            $data = [
                'providerReferenceId' => $providerReferenceId,
                'checksum' => $checksum,

            ];
            if ($merchantOrderId != '') {
                $data['merchantOrderId'] = $merchantOrderId;
            }

            // Payment::where('transaction_id', $transactionId)->update($data);
            dd('Payment successful');
            // return view('confirm_payment', compact('providerReferenceId', 'transactionId'));

        } else {

            //HANDLE YOUR ERROR MESSAGE HERE
            dd('ERROR : ' . $request->code . ', Please Try Again Later.');
        }


    }
}

