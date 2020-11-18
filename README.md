# VaahLaravel
> Small Laravel Helpers

### Installation
```shell script
composer require webreinvent/vaahlaravel
```

Add ServiceProvide in `config/app.php`:
```php
'providers' => [
...
WebReinvent\VaahLaravel\VaahLaravelServiceProvider::class,
...
]
```

#### VaahArtisan

Add Facade in `config/app.php`:
```php
'aliases' => [
...
'VaahArtisan' => \WebReinvent\VaahLaravel\Facades\VaahArtisan::class,
...
]
```

**Method**
```php
\VaahArtisan::migrate($command, $db_connection_nane, $path); 
\VaahArtisan::seed($command, $db_connection_nane, $class);
```
