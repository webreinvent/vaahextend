# VaahStripe

Command to add Package:

```
composer require cartalyst/stripe-laravel
```
- https://github.com/cartalyst/stripe-laravel

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

**Method**

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
    
    $return_url    // URL to redirect your customer back to after they authenticate or cancel their payment

    \VaahStripe::pay($customer, $card, $package, $address, $return_url);

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
    
    $return_url    // URL to redirect your customer back to after they authenticate or cancel their payment

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
