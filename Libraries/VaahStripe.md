# VaahStripe
> Small Laravel Helpers

### Installation

VaahStripe provides an expressive, fluent interface to Stripe's One Time Payment 
and subscription billing services. 

For extra fraud protection, 3D Secure (3DS) requires customers to complete an 
additional verification step with the card issuer when paying. 
Typically, you direct the customer to an authentication page on their bank’s 
website, and they enter a password associated with the card or a 
code sent to their phone. This process is familiar to customers 
through the card networks’ brand names, such as Visa Secure and 
Mastercard Identity Check.

### Dependencies
- [STRIPE BY CARTALYST](https://github.com/cartalyst/stripe-laravel)

### Installation

```shell script
composer require cartalyst/stripe-laravel
```

Add Facade in `config/app.php`:
```php
'aliases' => [
...
'VaahStripe' => WebReinvent\VaahExtend\Facades\VaahStripe::class,
...
]
```

Add env configuration:
```

...
STRIPE_API_KEY=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
...

```

Reference url: https://stripe.com/docs/api

### Methods

- Stripe One Time Payment 

```

    $customer => [
        'name'   => 'xxxxxx',
        'email'  => 'xx@example.com'
    ];
    
    $card => [
        'number'        => 'xxxx-xxxx-xxxx-xxxx',    
        'exp_month'     => '01',                        // 01-12
        'exp_year'      => '2021',
        'cvc'           => 'xxx'
    ];
    
    $package => [
        'currency'      => 'usd',                       // usd / USD
        'amount'        => '01',
        'description'   => 'xxxxxx'
    ];
    
    $address => [
        'city'          => 'xxxxxx',                    // optional    
        'country'       => 'xxxxxx',                                   
        'line1'         => 'xxxxxx',                    
        'line2'         => 'xxxxxx',                    // optional
        'postal_code'   => '123456',                    // optional
        'state'         => 'xxxxxx'                     // optional
    ];
    
    $return_url = http://localhost/vaahcms/public/      // URL to redirect your customer back to after they authenticate or cancel their payment

    \VaahStripe::pay($customer, $card, $package, $address, $return_url);

```

Response

```
{
    "status": "success",
    "data": {
        "id": "pi_1LMvsxxxxxxxxxxxxxxxxxx",
        "object": "payment_intent",
        "allowed_source_types": [
            "card"
        ],
        "amount": 200,
        "amount_capturable": 0,
        "amount_details": {
            "tip": []
        },
        "amount_received": 0,
        "application": null,
        "application_fee_amount": null,
        "automatic_payment_methods": null,
        "canceled_at": null,
        "cancellation_reason": null,
        "capture_method": "automatic",
        "charges": {
            "object": "list",
            "data": [],
            "has_more": false,
            "total_count": 0,
            "url": "/v1/charges?payment_intent=pi_1LMvsxxxxxxxxxxxxxxxxxx"
        },
        "client_secret": "pi_1LMvsxxxxxxxxxxxxxxxxxx_secret_8RZiIyhUEGDqfyLOgSUEAEhXN",
        "confirmation_method": "automatic",
        "created": 1658158118,
        "currency": "usd",
        "customer": "cus_M564xxxxxxx",
        "description": "testing",
        "invoice": null,
        "last_payment_error": null,
        "livemode": false,
        "metadata": [],
        "next_action": {
            "redirect_to_url": {
                "return_url": "http://localhost/vikram/vaahcms-dev-env/public",
                "url": "https://hooks.stripe.com/3d_secure_2/hosted?merchant=                      // Url to open 3d Secure / OTP Page
                acct_1G43A8GBqaITyEUt&payment_intent=pi_1LMvsxxxxxxxxxxxxxxxxxx&
                payment_intent_client_secret=pi_1LMvsxxxxxxxxxxxxxxxxxx_secret_8RZiIyhUEGDqfyLOgSUEAEhXN&
                publishable_key=pk_test_xaKKES0OlRzwNj6mCRBbjfc200upEjyqmB&source=src_1LMvsEGBqaITyEUtehquCgzp"
            },
            "type": "redirect_to_url"
        },
        "next_source_action": {
            "type": "authorize_with_url",
            "authorize_with_url": {
                "return_url": "http://localhost/vikram/vaahcms-dev-env/public",
                "url": "https://hooks.stripe.com/3d_secure_2/hosted?merchant=                     // Url to open 3d Secure / OTP Page
                acct_1G43A8GBqaITyEUt&payment_intent=pi_1LMvsxxxxxxxxxxxxxxxxxx&
                payment_intent_client_secret=pi_1LMvsxxxxxxxxxxxxxxxxxx_secret_8RZiIyhUEGDqfyLOgSUEAEhXN&
                publishable_key=pk_test_xaKKES0OlRzwNj6mCRBbjfc200upEjyqmB&source=src_1LMvsEGBqaITyEUtehquCgzp"
            }
        },
        "on_behalf_of": null,
        "payment_method": "pm_1LMvsxxxxxxxxxxxxxxxxxx",
        "payment_method_options": {
            "card": {
                "installments": null,
                "mandate_options": null,
                "network": null,
                "request_three_d_secure": "automatic"
            }
        },
        "payment_method_types": [
            "card"
        ],
        "processing": null,
        "receipt_email": null,
        "review": null,
        "setup_future_usage": null,
        "shipping": null,
        "source": null,
        "statement_descriptor": null,
        "statement_descriptor_suffix": null,
        "status": "requires_source_action",
        "transfer_data": null,
        "transfer_group": null
    }
}
```

- Stripe Subscription
```php

    $customer => [
        'name'   => 'xxxxxx',
        'email'  => 'xx@example.com'
    ];
    
    $card => [
        'number'        => 'xxxx-xxxx-xxxx-xxxx',    
        'exp_month'     => '01',                        // 01-12
        'exp_year'      => '2021',
        'cvc'           => 'xxx'
    ];
    
    $address => [
        'city'          => 'xxxxxx',                    // optional    
        'country'       => 'xxxxxx',                                   
        'line1'         => 'xxxxxx',                    
        'line2'         => 'xxxxxx',                    // optional
        'postal_code'   => '123456',                    // optional
        'state'         => 'xxxxxx'                     // optional
    ];

    $price_id      // Price define the unit cost, currency, and (optional) billing cycle for Subcription   
    
    $return_url = http://localhost/vaahcms/public/      // URL to redirect your customer back to after they authenticate or cancel their payment

    \VaahStripe::subscription($customer, $card, $address, $price_id, $return_url);

```

- Create Product
```php

    $request => [
        'name'          => 'xxxxxx',
        'description'   => 'xxxxxx'
    ];
    
    \VaahStripe::createProduct($request);

```

- Create Price
```php

    $request => [
        'product_id'    => 'xxxxxx',
        'currency'      => 'usd',
        'amount'        => '01',
        'interval'      => '01'
       
    ];
    
    \VaahStripe::createPrice($request);

```

- Find Product
```php
    
    \VaahStripe::findProductByName($name);

```

- Find Price
```php

    $product_id    
    
    $value          //optional
    
    $by             //optional       default = amount       amount/currency/interval
    
    \VaahStripe::getProductPrice($product_id, $value, $by);

```
