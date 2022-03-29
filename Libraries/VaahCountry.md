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
