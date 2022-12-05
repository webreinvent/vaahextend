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

`PayPal live` and `PayPal sandbox` are two different environments for using the PayPal API. The live environment is the real PayPal network that processes actual payments, while the sandbox environment is a simulated test environment that allows developers to test their integration with the PayPal API without using real money.
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
To obtain your PayPal sandbox client ID and client secret, you will need to complete the following steps:

 1. Visit the [PayPal Developer](https://developer.paypal.com/home) website and sign in to your account.
 2. Click on the `Sandbox > Accounts` option in the top menu.
 3.  In the `Business` section, click on the `Create` button to create a
    new sandbox business account.
    4. Enter the required information to create your account and click
    `Create Account`.
    5. Once your account has been created, click on the `Profile` link next
    to the account.
    6. On the next page, you will see your `sandbox client ID` and 
    `client secret`. Make sure to save these values, as you will need them to
    authenticate your app with PayPal in the sandbox environment.

### Initialize VaahPaypal
 ```php
//Initialize the VaahPaypal
    $vaahPaypal = new VaahPayPal(
    $client_id,
    $client_secret, 
    $return_url, 
    $cancel_url, 
    $mode 
);
```
| Name | Description | Required | Default | 
|--|--|--|--|
| client_id | PayPal Live/Sandbox client id| Yes
| client_secret| PayPal Live/Sandbox client_secret| Yes
| return_ url|  redirect the user to after they have completed a payment.| No |  /api/vaah/paypal/execute
| cancel_url | redirect the user to after they have canceled a payment. | No| /api/vaah/paypal/cancel|
| mode | environments for managing payments | No | sandbox

### Methods
- Paypal Create Order

|Name| Required  | Type | 
|--|--|--|
| name | yes  | String |
| quantity| yes  | String
| amount| yes  | String
| description| yes  | String
| currency | yes  | String | 
|shipping | No | Integer
|tax| No | Integer
```php
$vaahPaypal->pay([
            'name' => 'Name',
            'amount' => 100,
            'currency' => 'USD',
            'description' => 'Description',
            'quantity' => 1,
        ]);
 ```

**Success response**
 ```php
  [
   'status' => 'success';
   'approval_url' = 'approval url';
   'payment_id' = 'xxxxx';
   'token' = 'EC-xxxxxxxx';
  ];
  ```
  **Error Response**
  ```php
   [
   'status' => 'error';
   'errors' = 'errors';
  ];
  ```
  - Execute Order

|Name| Required
|--|--|
| payment_id | yes |
| payer_id | yes  |
```php
   $payment_id = 'xxxx';
   $payer_id = 'xxxx';
   $vaahPaypal->executePayment($payment_id, $payer_id);
   ```

**Success response**
```php
 [
   'status' => 'success';
   'data' => 'data'; //array
  ];
  ```
  **Error response**
 ```php
  [
   'status' => 'error';
   'errors' = 'errors';
  ];
```
Reference url: https://developer.paypal.com/api/rest/
