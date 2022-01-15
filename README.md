# VaahLaravel
> Small Laravel Helpers

### Installation
```shell script
composer require webreinvent/vaahextend
```

Add ServiceProvide in `config/app.php`:
```php
'providers' => [
...
WebReinvent\VaahExtend\VaahExtendServiceProvider::class,
...
]
```
---

### Helper Libraries

- [VaahAjax](Libraries/VaahAjax.md)
- [VaahArtisan](Libraries/VaahArtisan.md)
- [VaahAssets](Libraries/VaahAssets.md)
- [VaahCountry](Libraries/VaahCountry.md)
- [VaahExtract](Libraries/VaahExtract.md)
- [VaahFiles](Libraries/VaahFiles.md)
- [VaahImap](Libraries/VaahImap.md)
- [VaahMail](Libraries/VaahMail.md)
- [VaahModule](Libraries/VaahModule.md)
- [VaahSmtp](Libraries/VaahSmtp.md)
- [VaahStripe](Libraries/VaahStripe.md)
- [VaahUrl](Libraries/VaahUrl.md)



### VaahArtisan

Add Facade in `config/app.php`:
```php
'aliases' => [
...
'VaahArtisan' => \WebReinvent\VaahExtend\Facades\VaahArtisan::class,
...
]
```

**Method**
```php
\VaahArtisan::migrate($command, $path, $db_connection_name ); 
\VaahArtisan::seed($command, $class, $db_connection_nane);
```

---

### VaahCountry

Add Facade in `config/app.php`:
```php
'aliases' => [
...
'VaahCountry' => \WebReinvent\VaahExtend\Facades\VaahCountry::class,
...
]
```

**Method**
```php
\VaahCountry::getByCode($country_code);
\VaahCountry::getByName($country_name);
\VaahCountry::getByCallingCode($calling_code);
\VaahCountry::getListSelectOptions($show='country_name');
\VaahCountry::getList();
\VaahCountry::getListWithSlug();
\VaahCountry::getListWithSlugAsCallingCode();
\VaahCountry::getTimeZones();
```

---

### VaahModule

Add Facade in `config/app.php`:
```php
'aliases' => [
...
'VaahModule' => \WebReinvent\VaahExtend\Facades\VaahModule::class,
...
]
```

**Method**
```php
\VaahModule::getVaahCmsPath();
\VaahModule::getRootPath($module_name);
\VaahModule::getRelativePath($module_name);
\VaahModule::getAllPaths();
\VaahModule::getAllNames();
\VaahModule::getConfigs($module_name);
\VaahModule::getConfig($module_name, $key);
\VaahModule::getVersion($module_name);
\VaahModule::getVersionNumber($module_name);
\VaahModule::getAssetsUrl($module_name, $file_path);
\VaahModule::getMigrationPath($module_name);
\VaahModule::getSeedsClass($module_name);
\VaahModule::getTenantMigrationPath($module_name);
\VaahModule::getTenantSeedsClass($module_name);
\VaahModule::getTenantSampleData($module_name);
\VaahModule::getNamespace($module_name);
\VaahModule::getServiceProvider($module_name);
```


### VaahEventBrite

Add Facade in `config/app.php`:
```php
'aliases' => [
...
'VaahEventBrite' => WebReinvent\VaahExtend\Facades\VaahEventBrite::class,
...
]
```

Add env configuration:
```

...
EVENTBRITE_KEY=xxxxxxxxxxxxxxxx
EVENTBRITE_ORG_ID=xxxxxxxxxxxxx
...

```

Reference url: https://www.eventbrite.com/platform/api#/reference

**Method**
```php
\VaahEventBrite::events()->get();

$event_id = 12345;

\VaahEventBrite::events()->find($event_id);

$event = [
    'name'=>'Event Name',
    'description'=>'Event description',
     ....
     ...
];


\VaahEventBrite::events()->store($event);
\VaahEventBrite::events()->update($event_id, $event);
\VaahEventBrite::events()->cancel($event_id);
\VaahEventBrite::events()->publish($event_id);
\VaahEventBrite::events()->delete($event_id);
\VaahEventBrite::attendees()->get($event_id);
\VaahEventBrite::attendees()->find($event_id, $attendee_id);
\VaahEventBrite::orders()->find($order_id);
\VaahEventBrite::organizations()->get();
```


### VaahStripe

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

### VaahCountry

Add Facade in `config/app.php`:
```php
'aliases' => [
...
'VaahCountry' => \WebReinvent\VaahExtend\Facades\VaahCountry::class,
...
]
```


### VaahMail

Add Facade in `config/app.php`:
```php
'aliases' => [
...
'VaahMail' => WebReinvent\VaahExtend\Facades\VaahMail::class,
...
]
```

### VaahImap

- reference: https://github.com/barbushin/php-imap 

Add Facade in `config/app.php`:
```php
'aliases' => [
...
'VaahImap' => WebReinvent\VaahExtend\Facades\VaahImap::class,
...
]
```
