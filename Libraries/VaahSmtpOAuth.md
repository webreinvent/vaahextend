# VaahSmtpOAuth
> Small Laravel Helpers

### Installation

Install following packages:
```
composer require league/oauth2-google
composer require phpmailer/phpmailer
```

Watch following video to get Gmail Client Id & Secret:

https://www.youtube.com/watch?v=QfA70GbV08c


### Usages
```php
$inputs = array(
    'client_id' => env('GMAIL_API_CLIENT_ID'),
    'client_secret' => env('GMAIL_API_CLIENT_SECRET'),
    'username' => 'example@gmail.com',
    'refresh_token' => "", // Read VaahGoogleCloud.md
);

$smtp = new VaahSmtpOAuth(
    $inputs['client_id'],
    $inputs['client_secret'],
    $inputs['username'],
    $inputs['refresh_token'],
);

$from_array = ['from@email.com' => 'From Name'];
$to_array = ['to@email.com' => 'To Name'];
$subject = "Test";
$message = "<b>Test</b>";

$response = $smtp->send($from_array, $to_array, $subject, $message);

```


