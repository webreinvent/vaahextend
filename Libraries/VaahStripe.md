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

Success Response 

```
{
    "status": "success",
    "data": {
        "id": "pi_1LMvsxxxxxxxxxxxxxxxxxx",          // Payment Intent ID
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
                "url": "https://hooks.stripe.com/3d_secure_2/hosted?merchant=                      // Url to complete your 3D secure Payment or open OTP Page
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
                "url": "https://hooks.stripe.com/3d_secure_2/hosted?merchant=                     // Url to complete your 3D secure Payment or open OTP Page
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

    $price_id    You can generate Price ID from createPrice Method of Vaah Stripe                                 // Price define the unit cost, currency, and (optional) billing cycle for Subcription   
    or You can create directly from Stripe Dashboard by visit https://dashboard.stripe.com/test/products/create 
    
    $return_url = http://localhost/vaahcms/public/      // URL to redirect your customer back to after they authenticate or cancel their payment

    \VaahStripe::subscription($customer, $card, $address, $price_id, $return_url);

```

Success Response 

```
{
    "status": "success",
    "data": {
        "id": "pi_1LMwxxxxxxxxxxxxxxxxxxxx",                // Payment Intent ID
        "object": "payment_intent",
        "allowed_source_types": [
            "card"
        ],
        "amount": 79900,
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
            "url": "/v1/charges?payment_intent=pi_1LMwxxxxxxxxxxxxxxxxxxxx"
        },
        "client_secret": "pi_1LMwxxxxxxxxxxxxxxxxxxxx_secret_usv8FrEvzU0CuWEyRjmUKlmjD",
        "confirmation_method": "automatic",
        "created": 1658161603,
        "currency": "usd",
        "customer": "cus_M570qAPnoC6xl1",
        "description": "Subscription creation",
        "invoice": "in_1LMwmxxxxxxxxxxxxxxxxxxx",
        "last_payment_error": null,
        "livemode": false,
        "metadata": [],
        "next_action": {
            "redirect_to_url": {
                "return_url": "http://localhost/vikram/vaahcms-dev-env/public",
                "url": "https://hooks.stripe.com/3d_secure_2/hosted?merchant=                                               // Url to complete your 3D secure Payment or open OTP Page
                acct_1G43A8GBqaITyEUt&payment_intent=pi_1LMwxxxxxxxxxxxxxxxxxxxx&
                payment_intent_client_secret=pi_1LMwxxxxxxxxxxxxxxxxxxxx_secret_usv8FrEvzU0CuWEyRjmUKlmjD&
                publishable_key=pk_test_xaKKES0OlRzwNj6mCRBbjfc200upEjyqmB&source=src_1LMwmQGBqaITyEUtQsNKeDZn"
            },
            "type": "redirect_to_url"
        },
        "next_source_action": {
            "type": "authorize_with_url",
            "authorize_with_url": {
                "return_url": "http://localhost/vikram/vaahcms-dev-env/public",
                "url": "https://hooks.stripe.com/3d_secure_2/hosted?merchant=                                               // Url to complete your 3D secure Payment or open OTP Page
                acct_1G43A8GBqaITyEUt&payment_intent=pi_1LMwxxxxxxxxxxxxxxxxxxxx&
                payment_intent_client_secret=pi_1LMwxxxxxxxxxxxxxxxxxxxx_secret_usv8FrEvzU0CuWEyRjmUKlmjD&
                publishable_key=pk_test_xaKKES0OlRzwNj6mCRBbjfc200upEjyqmB&source=src_1LMwmQGBqaITyEUtQsNKeDZn"
            }
        },
        "on_behalf_of": null,
        "payment_method": null,
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
        "setup_future_usage": "off_session",
        "shipping": null,
        "source": "card_1LMxxxxxxxxxxxxxxxxx",
        "statement_descriptor": null,
        "statement_descriptor_suffix": null,
        "status": "requires_source_action",
        "transfer_data": null,
        "transfer_group": null
    }
}
```

- Create Product
```php

    $request => [
        'name'          => 'xxxxxx',
        'description'   => 'xxxxxx'
    ];
    
    \VaahStripe::createProduct($request);

```

Success Response 

```
{
    "status": "success",
    "data": {
        "id": "prod_M57xxxxxxxxxxx",         // Product Id
        "object": "product",
        "active": true,
        "attributes": [],
        "created": 1658162168,
        "default_price": null,
        "description": "Testing Product",
        "images": [],
        "livemode": false,
        "metadata": [],
        "name": "Vikram",
        "package_dimensions": null,
        "shippable": null,
        "skus": {
            "object": "list",
            "data": [],
            "has_more": false,
            "total_count": 0,
            "url": "/v1/skus?product=prod_M57xxxxxxxxxxx&active=true"
        },
        "statement_descriptor": null,
        "tax_code": null,
        "type": "service",
        "unit_label": null,
        "updated": 1658162168,
        "url": null
    }
}
```

- Create Price
```php

    // You need to have Product id to create Price.

    $request => [
        'product_id'    => 'xxxxxx',
        'currency'      => 'usd',
        'amount'        => '01',
        'recurring'     => [
            'interval'    =>   'month'                         //  month, year, week, or day
        ]
       
    ];
    
    \VaahStripe::createPrice($request);

```

Success Response

```
{
    "status": "success",
    "data": {
        "id": "price_1LMxxxxxxxxxxxxxxxx",        // Price id
        "object": "price",
        "active": true,
        "billing_scheme": "per_unit",
        "created": 1658162583,
        "currency": "usd",
        "custom_unit_amount": null,
        "livemode": false,
        "lookup_key": null,
        "metadata": [],
        "nickname": null,
        "product": "prod_M579xxxxxxxx",          // Product id
        "recurring": {
            "aggregate_usage": null,
            "interval": "month",
            "interval_count": 1,
            "trial_period_days": null,
            "usage_type": "licensed"
        },
        "tax_behavior": "unspecified",
        "tiers_mode": null,
        "transform_quantity": null,
        "type": "recurring",
        "unit_amount": 20,
        "unit_amount_decimal": "20"
    }
}
```

- Find Product
```php

    $name                                   // Product Name
    
    \VaahStripe::findProductByName($name);

```

Success Response 

```
{
    "status": "success",
    "data": {
        "id": "prod_K1xxxxxxxx",
        "object": "product",
        "active": true,
        "attributes": [],
        "caption": null,
        "created": 1628777285,
        "deactivate_on": [],
        "default_price": null,
        "description": "WP-Maintenance-Premium-Monthly",
        "images": [],
        "livemode": false,
        "metadata": [],
        "name": "WP-Maintenance-Standard-Monthly",
        "package_dimensions": null,
        "shippable": true,
        "skus": {
            "object": "list",
            "data": [],
            "has_more": false,
            "total_count": 0,
            "url": "/v1/skus?product=prod_K1xxxxxxxxx&active=true"
        },
        "tax_code": null,
        "type": "good",
        "updated": 1628777285,
        "url": null
    }
}
```

- Find Price
```php

    //    You need to have Product id to create Price.

    $product_id    
    
    $value          //optional
    
    $by             //optional       default = amount       amount/currency/interval
    
    \VaahStripe::getProductPrice($product_id, $value, $by);

```

Success Response

```
{
    "status": "success",
    "data": {
        "id": "price_1LMx2xxxxxxxxxxxxxxx",
        "object": "price",
        "active": true,
        "billing_scheme": "per_unit",
        "created": 1658162583,
        "currency": "usd",
        "custom_unit_amount": null,
        "livemode": false,
        "lookup_key": null,
        "metadata": [],
        "nickname": null,
        "product": "prod_M57xxxxxxxxxxxx",
        "recurring": {
            "aggregate_usage": null,
            "interval": "month",
            "interval_count": 1,
            "trial_period_days": null,
            "usage_type": "licensed"
        },
        "tax_behavior": "unspecified",
        "tiers_mode": null,
        "transform_quantity": null,
        "type": "recurring",
        "unit_amount": 20,
        "unit_amount_decimal": "20"
    }
}
```
