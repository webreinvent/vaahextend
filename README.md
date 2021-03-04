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
\VaahArtisan::migrate($command, $db_connection_nane, $path); 
\VaahArtisan::seed($command, $db_connection_nane, $class);
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
---

### VaahEventBrite

Add Facade in `config/app.php`:
```php
'aliases' => [
...
'VaahEventBrite' => WebReinvent\VaahExtend\Facades\VaahEventBrite::class,
...
]
```

**Method**
```php
\VaahEventBrite::events()->get();
\VaahEventBrite::events()->find($event_id);
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

### VaahCountry

Add Facade in `config/app.php`:
```php
'aliases' => [
...
'VaahCountry' => \WebReinvent\VaahExtend\Facades\VaahCountry::class,
...
]
```
