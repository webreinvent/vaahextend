
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
\VaahModule::getVaahCmsModulesPath();
\VaahModule::getModuleRootPath($module_name);
\VaahModule::getModuleRelativePath($module_name);
\VaahModule::getAllModulesPaths();
\VaahModule::getAllModulesNames();
\VaahModule::getModuleConfigs($module_name);
\VaahModule::getModuleConfig($module_name, $key);
\VaahModule::getModuleAssetsUrl($module_name, $file_path);
\VaahModule::getModuleMigrationPath($module_name);
\VaahModule::getModuleSeedsClass($module_name);
\VaahModule::getModuleTenantMigrationPath($module_name);
\VaahModule::getModuleTenantSeedsClass($module_name);
\VaahModule::getModuleTenantSampleData($module_name);
\VaahModule::getModuleNamespace($module_name);
\VaahModule::getModuleServiceProvider($module_name);
```
