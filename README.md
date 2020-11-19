
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
---

### VaahArtisan

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

---

### VaahCountry

Add Facade in `config/app.php`:
```php
'aliases' => [
...
'VaahCountry' => \WebReinvent\VaahLaravel\Facades\VaahCountry::class,
...
]
```

**Method**
```php
\VaahCountry::getCountryByCode($country_code);
\VaahCountry::getCountryByName($country_name);
\VaahCountry::getCountryByCallingCode($calling_code);
\VaahCountry::getCountryListSelectOptions($show='country_name');
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
'VaahModule' => \WebReinvent\VaahLaravel\Facades\VaahModule::class,
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
