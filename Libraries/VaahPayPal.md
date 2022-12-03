# VaahPaypal
> Small Laravel Helpers

### Installation

VaahPaypal provides to you a simple way to integrate Paypal in your Laravel application.

### Dependencies
- [PayPal-PHP-SDK](https://github.com/paypal/PayPal-PHP-SDK)

### Installation

```shell script
composer require paypal/rest-api-sdk-php
```

[comment]: <> (Add Facade in `config/app.php`:)

[comment]: <> (```php)

[comment]: <> ('aliases' => [)

[comment]: <> (...)

[comment]: <> ('VaahStripe' => WebReinvent\VaahExtend\Facades\VaahStripe::class,)

[comment]: <> (...)

[comment]: <> (])

[comment]: <> (```)

Add env configuration:
```env
PAYPAL_MODE=sandbox
PAYPAL_SANDBOX_CLIENT_ID=xxxxxxxxxxxxxxx
PAYPAL_SANDBOX_CLIENT_SECRET=xxxxxxxxxxxxx
```

Reference url: https://developer.paypal.com/api/rest/

### Methods

- Paypal Create Order

```php
//Initialize the VaahPaypal
$vaahPaypal = new VaahPayPal(
            $client_id,
            $client_secret,
            $return_url,
            $cancel_url,
        );
//Create Order
$vaahPaypal->pay([
            'name' => 'Name',
            'amount' => 100,
            'currency' => USD,
            'description' => 'Description',
            'quantity' => 1,
        ]);
```

- Execute Order
```php
//Initialize the VaahPaypal
   $vaahPaypal = new VaahPayPal(
            $client_id,
            $client_secret,
            $return_url,
            $cancel_url,
        );
      //Execute the order
   $payment_id = 'xxxx';
   $payer_id = 'xxxx';
   $vaahPaypal->executePayment($payment_id, $payer_id);
```
