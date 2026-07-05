<?php

namespace Modules\Skiply\Http\Controllers;

use App\Http\Controllers\PaymentController;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\PaymentMethodSetting\Entities\PaymentMethod;
use Modules\Payment\Entities\Checkout;
use Illuminate\Support\Facades\Log;

class SkiplyController extends Controller
{
    public function redirectToDashboard()
    {
        if (auth()->user()->role_id == 3) {
            return redirect(route('studentDashboard'));
        } else {
            return redirect(route('dashboard'));
        }
    }

    public function paymentProcess($amount, $checkout_info)
    {
        $client_id = getPaymentEnv('SKIPLY_CLIENT_ID');
        $client_secret = getPaymentEnv('SKIPLY_CLIENT_SECRET');
        $environment = getPaymentEnv('SKIPLY_ENVIRONMENT');
        $base_url = $environment == 'Production' ? 'https://skiply.ae' : 'https://qa.skiply.ae';

        // Get token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base_url . "/skiply-userprofile/oauth/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "Grant-Type=client_credentials");
        curl_setopt($ch, CURLOPT_USERPWD, $client_id . ":" . $client_secret);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $headers = array("Content-Type: application/x-www-form-urlencoded");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        
        $token_response = json_decode($result, true);
        if (!isset($token_response['access_token'])) {
            Toastr::error("Failed to authenticate with Skiply", trans('common.Error'));
            return redirect()->back();
        }
        $access_token = $token_response['access_token'];

        // Generate OTT
        $ottVerifier = bin2hex(random_bytes(16));
        $salt = getPaymentEnv('SKIPLY_SALT'); 
        $input = $ottVerifier . "-" . $salt;
        $hashBytes = hash("sha256", $input, true);
        $ottChallenge = base64_encode($hashBytes);

        $skiply_token = bin2hex(random_bytes(16));
        session()->put('skiply_token_' . $checkout_info->id, $skiply_token);
        session()->put('skiply_ott_' . $checkout_info->id, $ottVerifier);
        session()->save();

        // Create Order
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base_url . "/skiply-payment/checkout/authorize");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $payload = json_encode([
            "ottExpiry" => 10080,
            "ottChallenge" => $ottChallenge,
            "redirectUrl" => str_replace("http://", "https://", route('skiply.success', ['checkout' => $checkout_info->id, 'token' => $skiply_token])),
            "payload" => [
                "orderInfo" => [
                    "orderId" => "ORDER_" . time() . "_" . $checkout_info->id,
                    "currency" => "AED",
                    "items" => [
                        [
                            "title" => "Course Purchase",
                            "quantity" => 1,
                            "description" => "Course Purchase",
                            "amount" => (float)$amount
                        ]
                    ],
                    "subTotal" => (float)$amount,
                    "vat" => 0,
                    "miscellaneousCharges" => [
                        [
                            "title" => "Processing Fee",
                            "amount" => 0
                        ]
                    ],
                    "netTransactionAmount" => (float)$amount
                ],
                "customerInfo" => [
                    "name" => Auth::user()->name,
                    "email" => Auth::user()->email,
                    "additionalFields" => [
                        [
                            "title" => "Customer ID",
                            "value" => (string)Auth::id()
                        ]
                    ]
                ]
            ]
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $headers = array();
        $headers[] = "Authorization: Bearer " . $access_token;
        $headers[] = "Grant-Type: client_credentials";
        $headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result, true);
        if (isset($response['body']['url']) && isset($response['body']['ott'])) {
            session()->put('skiply_returned_ott_' . $checkout_info->id, $response['body']['ott']);
            session()->save();

            $redirectUrl = $response['body']['url'] . $ottVerifier;
            return redirect($redirectUrl);
        } else {
            \Log::error("Skiply checkout failed", ['response' => $response, 'result' => $result]);
            Toastr::error("Failed to initiate Skiply checkout", trans('common.Error'));
            return redirect()->back();
        }
    }

    public function skiplySuccess(Request $request)
    {
        \Log::info('Skiply Callback URL:', $request->all());

        if ($request->input('checkout') && $request->input('token')) {
            $checkout_id = $request->input('checkout');
            $token = substr($request->input('token'), 0, 32);

            if ($request->input('status') === 'failure' || $request->input('status') === 'declined') {
                Toastr::error(trans('frontend.Transaction is declined'));
                return $this->redirectToDashboard();
            }

            if (session()->get('skiply_token_' . $checkout_id) !== $token) {
                Toastr::error(trans('common.Invalid Token or Transaction'));
                return $this->redirectToDashboard();
            }
            session()->forget('skiply_token_' . $checkout_id);

            $checkout = Checkout::find($checkout_id);
            if (!$checkout) {
                Toastr::error(trans('common.Something Went Wrong'));
                return $this->redirectToDashboard();
            }

            if ($checkout->status != 0){
                Toastr::error(trans('common.Already Enrolled'));
                return $this->redirectToDashboard();
            }

            // Verify status via Skiply API
            $skiply_ott = session()->get('skiply_returned_ott_' . $checkout_id);
            if ($skiply_ott) {
                $client_id = getPaymentEnv('SKIPLY_CLIENT_ID');
                $client_secret = getPaymentEnv('SKIPLY_CLIENT_SECRET');
                $environment = getPaymentEnv('SKIPLY_ENVIRONMENT');
                $base_url = $environment == 'Production' ? 'https://skiply.ae' : 'https://qa.skiply.ae';

                // Get API token
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $base_url . "/skiply-userprofile/oauth/token");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "Grant-Type=client_credentials");
                curl_setopt($ch, CURLOPT_USERPWD, $client_id . ":" . $client_secret);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $headers = array("Content-Type: application/x-www-form-urlencoded");
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $result = curl_exec($ch);
                curl_close($ch);
                
                $token_response = json_decode($result, true);
                if (isset($token_response['access_token'])) {
                    $access_token = $token_response['access_token'];
                    
                    // Call Status endpoint
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $base_url . "/skiply-payment/checkout/" . $skiply_ott . "/status");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $headers = array();
                    $headers[] = "Authorization: Bearer " . $access_token;
                    $headers[] = "Grant-Type: client_credentials";
                    $headers[] = "Accept: application/json";
                    $headers[] = "Content-Type: application/json";
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $status_result = curl_exec($ch);
                    $curl_err = curl_error($ch);
                    curl_close($ch);
                    
                    $status_response = json_decode($status_result, true);
                    session()->forget('skiply_returned_ott_' . $checkout_id);
                    session()->forget('skiply_ott_' . $checkout_id);

                    $is_success = false;
                    if (isset($status_response['status'])) {
                        $code = strtoupper($status_response['status']['code']);
                        // Y or S or 00 indicates success for the API call itself.
                        if ($code === 'S' || $code === 'Y' || $code === '00' || $code === '000') {
                            // Now check the actual payment status
                            if (isset($status_response['body']['paymentStatus'])) {
                                $paymentStatus = strtoupper($status_response['body']['paymentStatus']);
                                if ($paymentStatus === 'SUCCESS' || $paymentStatus === 'COMPLETED' || $paymentStatus === 'PAID') {
                                    $is_success = true;
                                }
                            }
                        }
                    }

                    if (!$is_success) {
                        \Log::error("Skiply Transaction Failed", [
                            'response' => $status_response,
                            'raw_result' => $status_result,
                            'curl_error' => $curl_err
                        ]);
                        Toastr::error(trans('frontend.Transaction is declined'));
                        return $this->redirectToDashboard();
                    }
                } else {
                    \Log::error("Skiply API Auth Failed for verification", ['result' => $result]);
                    Toastr::error("Failed to verify transaction with Skiply");
                    return $this->redirectToDashboard();
                }
            } else {
                Toastr::error(trans('common.Invalid Token or Transaction'));
                return $this->redirectToDashboard();
            }

            $payment = new PaymentController();
            $payWithGateway = $payment->payWithGateWay(json_encode($request->all()), "Skiply", $user = null, session()->get('invoice'));
            if ($payWithGateway) {
                Toastr::success(trans('frontend.Payment done successfully'), trans('common.Success'));
                if (Settings('frontend_active_theme') == 'tvt') {
                    return redirect('/');
                }
                return $this->redirectToDashboard();
            } else {
                Toastr::error(trans('frontend.Something Went Wrong'), trans('common.Error'));
                if (Settings('frontend_active_theme') == 'tvt') {
                    return redirect('/');
                }
                return $this->redirectToDashboard();
            }
        } else {
            Toastr::error(trans('frontend.Transaction is declined'));
            return $this->redirectToDashboard();
        }
    }

    public function testProcess(Request $request)
    {
        $data = $request->all();
        $client_id = getPaymentEnv('SKIPLY_CLIENT_ID');
        $client_secret = getPaymentEnv('SKIPLY_CLIENT_SECRET');
        $environment = getPaymentEnv('SKIPLY_ENVIRONMENT');
        $base_url = $environment == 'Production' ? 'https://skiply.ae' : 'https://qa.skiply.ae';

        // Get token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base_url . "/skiply-userprofile/oauth/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "Grant-Type=client_credentials");
        curl_setopt($ch, CURLOPT_USERPWD, $client_id . ":" . $client_secret);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $headers = array("Content-Type: application/x-www-form-urlencoded");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        
        $token_response = json_decode($result, true);
        if (!isset($token_response['access_token'])) {
            Toastr::error("Failed to authenticate with Skiply", trans('common.Error'));
            return redirect()->back();
        }
        $access_token = $token_response['access_token'];

        // Generate OTT
        $ottVerifier = bin2hex(random_bytes(16));
        $salt = getPaymentEnv('SKIPLY_SALT'); 
        $input = $ottVerifier . "-" . $salt;
        $hashBytes = hash("sha256", $input, true);
        $ottChallenge = base64_encode($hashBytes);

        // Create Order
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base_url . "/skiply-payment/checkout/authorize");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $payload = json_encode([
            "ottExpiry" => 10080,
            "ottChallenge" => $ottChallenge,
            "redirectUrl" => route('skiply.test.success'),
            "payload" => [
                "orderInfo" => [
                    "orderId" => "TEST_" . time(),
                    "currency" => "AED",
                    "items" => [
                        [
                            "title" => "Test Payment",
                            "quantity" => 1,
                            "description" => "Test Payment",
                            "amount" => (float)$data['test_amount']
                        ]
                    ],
                    "subTotal" => (float)$data['test_amount'],
                    "vat" => 0,
                    "netTransactionAmount" => (float)$data['test_amount']
                ],
                "customerInfo" => [
                    "name" => Auth::user()->name,
                    "email" => Auth::user()->email
                ]
            ]
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $headers = array();
        $headers[] = "Authorization: Bearer " . $access_token;
        $headers[] = "Grant-Type: client_credentials";
        $headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result, true);
        if (isset($response['body']['url'])) {
            $redirectUrl = $response['body']['url'] . $ottVerifier;
            return redirect($redirectUrl);
        } else {
            Toastr::error("Failed to initiate Skiply checkout", trans('common.Error'));
            return redirect()->back();
        }
    }

    public function skiplyTestSuccess(Request $request)
    {
        Toastr::success(trans('frontend.Payment done successfully'), trans('common.Success'));
        return redirect()->route('paymentmethodsetting.test');
    }

    public function skiplyTestFailed()
    {
        Toastr::error(trans('frontend.User is canceled the payment'), trans('common.Failed'));
        return redirect()->route('paymentmethodsetting.test');
    }
}
