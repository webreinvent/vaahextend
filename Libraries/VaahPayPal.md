# VaahPayPal
> Small Laravel Helpers

### Installation

VaahPaypal provides to you a simple way to integrate Paypal in your Laravel application.

### Dependencies
- [PayPal-PHP-SDK](https://github.com/paypal/PayPal-PHP-SDK)

### Installation

```shell script
composer require paypal/rest-api-sdk-php
```
### Add env variables:

PayPal live and PayPal sandbox are two different environments for using the PayPal API. The live environment is the real PayPal network that processes actual payments, while the sandbox environment is a simulated test environment that allows developers to test their integration with the PayPal API without using real money.
```env
## For PayPal Sandbox
PAYPAL_MODE=sandbox
PAYPAL_SANDBOX_CLIENT_ID=xxxxxxxxxxxxxxx
PAYPAL_SANDBOX_CLIENT_SECRET=xxxxxxxxxxxxx
## For PayPal Live
PAYPAL_MODE=live
PAYPAL_LIVE_CLIENT_ID=xxxxxxxxxxxxxxx
PAYPAL_LIVE_CLIENT_SECRET=xxxxxxxxxxxxx
```
To get your PayPal sandbox client secret and client ID, you first need to create a PayPal developer account at https://developer.paypal.com/. Once you have created your account, you can log in and create a sandbox account. This will allow you to test your integration with the PayPal API without using real money.

To create a sandbox account, log in to your developer account and click on the "Dashboard" tab. From there, click on the "Sandbox" tab and then click on the "Accounts" link. This will take you to the sandbox accounts page where you can create a new sandbox account.

Once you have created a sandbox account, you can view its client ID and client secret by clicking on the account and then clicking on the "Profile" link. This will open the account's profile page, where you can find the client ID and client secret.

### Initialize VaahPaypal
 ```php
//Initialize the VaahPaypal
    $vaahPaypal = new VaahPayPal(
    $client_id, //required
    $client_secret, //required
    $return_url, //optional [default: /api/vaah/paypal/execute]
    $cancel_url, //optional [default: /api/vaah/paypal/cancel]
    $mode //optional [default: sandbox]
);
```
The PayPal cancel and return URLs are the URLs that PayPal will redirect the user to after they have completed or canceled a payment. These URLs are specified by the developer in the PayPal API call, and they can be used to redirect the user back to the app or website after the payment is complete.

### Methods
- Paypal Create Order

```php
$vaahPaypal->pay([
            'name' => 'Name',
            'amount' => 100,
            'currency' => 'USD',
            'description' => 'Description',
            'quantity' => 1,
        ]);

//success response
  [
   'status' => 'success';
   'approval_url' = 'approval url';
   'payment_id' = 'xxxxx';
   'token' = 'EC-xxxxxxxx';
  ];
 
 //error response
  [
   'status' => 'error';
   'errors' = 'errors';
  ];
```

- Execute Order
```php
//Execute the order
   $payment_id = 'xxxx';
   $payer_id = 'xxxx';
   $vaahPaypal->executePayment($payment_id, $payer_id);

//success response
 [
   'status' => 'success';
   'data' => 'data'; //array
  ];
 //error response
  [
   'status' => 'error';
   'errors' = 'errors';
  ];
```
Reference url: https://developer.paypal.com/api/rest/
