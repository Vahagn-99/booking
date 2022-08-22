<?php

namespace App\Repositories;

use App\Models\User;
use Auth;
use \Stripe\Stripe;
use \Stripe\Customer;
use \Stripe\Charge;
use \Stripe\Source;
use \Stripe\Token;
use \Stripe\PaymentIntent;

class PaymentRepository implements PaymentRepositoryInterface
{
    protected $currenciesForStripe;

    public function __construct()
    {
        $adminstripe = User::find(1)->stripe_secret_key;
        $this->stripe_api_key = env('STRIPE_MODE') == 'live' ? $adminstripe : env('STRIPE_TEST_API_KEY');
        $this->currenciesForStripe = array(
            "EUR" => "eur",
            "RUB" => "rub",
            "CAD" => "cad",
            "BRL" => "brl",
            "GBP" => "gbp",
            "USD" => "usd"
        );
    }

    /**
     * Add new Customer to Stripe
     *
     * @return Customer
     */
    public function createToken($card,$property)
    {
        $curl = new \Stripe\HttpClient\CurlClient();
        $curl->setEnablePersistentConnections(false);
        \Stripe\ApiRequestor::setHttpClient($curl);

        if ($property->ownerInfo->stripe_secret_key && $property->ownerInfo->stripe_secret_key != ""  && env('STRIPE_MODE') == 'live') {
            Stripe::setApiKey($property->ownerInfo->stripe_secret_key);
        } else {
            Stripe::setApiKey($this->stripe_api_key);
        }

        return Token::create(["card" => $card]);
    }

    /**
     * Add new Customer to Stripe
     *
     * @return Customer
     */
    public function createCustomer($useremail,$property)
    {
        $curl = new \Stripe\HttpClient\CurlClient();
        $curl->setEnablePersistentConnections(false);
        \Stripe\ApiRequestor::setHttpClient($curl);

        if ($property->ownerInfo->stripe_secret_key && $property->ownerInfo->stripe_secret_key != ""  && env('STRIPE_MODE') == 'live') {
            Stripe::setApiKey($property->ownerInfo->stripe_secret_key);
        } else {
            Stripe::setApiKey($this->stripe_api_key);
        }

        $customer = Customer::create([
            "description" => "Customer for ".$useremail,
            "email" => $useremail
        ]);

        return $customer;
    }

    public function createSource($property,$customerId,$token)
    {
        $curl = new \Stripe\HttpClient\CurlClient();
        $curl->setEnablePersistentConnections(false);
        \Stripe\ApiRequestor::setHttpClient($curl);

        if ($property->ownerInfo->stripe_secret_key && $property->ownerInfo->stripe_secret_key != ""  && env('STRIPE_MODE') == 'live') {
            $stripe = new \Stripe\StripeClient($property->ownerInfo->stripe_secret_key);
        } else {
            $stripe = new \Stripe\StripeClient($this->stripe_api_key);
        }

        return $stripe->customers->createSource($customerId, ['source' => $token,]);
    }

    /**
     * Pay with Stripe
     *
     * @return Charge
     */
    public function payStripe($useremail,$customerId,$amount,$currency,$property,$check_in,$check_out)
    {
        $curl = new \Stripe\HttpClient\CurlClient();
        $curl->setEnablePersistentConnections(false);
        \Stripe\ApiRequestor::setHttpClient($curl);
        if ($property->ownerInfo->stripe_secret_key && $property->ownerInfo->stripe_secret_key != ""  && env('STRIPE_MODE') == 'live') {
            Stripe::setApiKey($property->ownerInfo->stripe_secret_key);
        } else {
            Stripe::setApiKey($this->stripe_api_key);
        }


        return Charge::create([
            'amount' => $amount,
            'currency' => $this->currenciesForStripe[$currency],
            'description' => "Payment for reservation from " . $check_in . ' - ' . $check_out .', Property: ' . $property->name .', Contact Email: ' . $useremail ,
            'receipt_email' => $useremail,
            'customer' => $customerId,
        ]);
    }
}
