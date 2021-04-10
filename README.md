
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

### VaahCountry

Add Facade in `config/app.php`:
```php
'aliases' => [
...
'VaahCountry' => \WebReinvent\VaahExtend\Facades\VaahCountry::class,
...
]
```
