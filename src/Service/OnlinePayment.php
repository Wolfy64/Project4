<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class OnlinePayment
{
    const SECRET_KEY = "sk_test_vR13pPT8iogxBJKWC1FOuDDj";
    const PUBLISHED_KEY = "pk_test_zwG6fcavFG9NgdGA3aOaY2oZ";

    private $token;
    private $email;
    private $amount;
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        // $this->token = $token;
        // $this->email = $email;
        // $this->amount = $amout;
        \dump($this->requestStack);
    }

    public function charge()
    {
        \Stripe\Stripe::setApiKey(self::SECRET_KEY);

        $customer = \Stripe\Customer::create(array(
            'email' => $this->email,
            'source' => $this->token
        ));

        $charge = \Stripe\Charge::create(array(
            'customer' => $customer->id,
            'amount' => $this->amount,
            'currency' => 'usd'
        ));
    }
}