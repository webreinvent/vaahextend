# VaahSmtpOAuth
> Small Laravel Helpers

### Installation

Install following packages:
```
composer require league/oauth2-google
```

Watch following video to get Gmail Client Id & Secret:

https://www.youtube.com/watch?v=QfA70GbV08c



### Usages

Set Following ENV variables:
```env
GMAIL_API_CLIENT_ID=
GMAIL_API_CLIENT_SECRET=
GMAIL_API_REDIRECT_URL=
```

Note: `GMAIL_API_REDIRECT_URL` at this url you have to call `VaahGoogleCloud::getGmailApiToken($request)` method to get the refresh token;


To get gmail authorization url, `VaahGoogleCloud::getGmailApiAuthUrl()` will return google authorization url and user will be redirected to the url:

```php
public function getGmailAuthorizationUrl(Request $request)
{
    $response = VaahGoogleCloud::getGmailApiAuthUrl();

    if(!$response['success'])
    {
        return $response;
    }

    return redirect($response['data']['authorization_url']);
}
```

After successful authorization the user will be redirected to `GMAIL_API_REDIRECT_URL` url. `GMAIL_API_REDIRECT_URL` must present in the Google Cloud Platform app, refer to the video.

In `GMAIL_API_REDIRECT_URL` url' controller you can get the gmail refresh token using following method, `refresh_token` must be stored in encrypted form for further usages:

```php
public function getGmailToken(Request $request)
{
    $response = VaahGoogleCloud::getGmailApiToken($request);
    return $response;
}
```
