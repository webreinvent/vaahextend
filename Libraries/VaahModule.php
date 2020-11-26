<?php namespace WebReinvent\VaahLaravel\Libraries;


class VaahModule{


    public function __construct()
    {

    }

    //-----------------------------------------------------------------------------------
    function getVaahCmsModulesPath()
    {
        return config('vaahcms.modules_path');
    }
//-----------------------------------------------------------------------------------
    function getRootPath($module_name)
    {
        return $this->getVaahCmsModulesPath()."/".$module_name;
    }
//-----------------------------------------------------------------------------------
    function getRelativePath($module_name)
    {
        return "/VaahCms/Modules/".$module_name;
    }
//-----------------------------------------------------------------------------------
    function getAllPaths()
    {

        $found_modules = [];

        $modules_path = $this->getVaahCmsModulesPath();

        foreach (\File::directories($modules_path) as $module)
        {
            $found_modules[] = $module;
        }

        return $found_modules;

    }
//-----------------------------------------------------------------------------------
    function getAllNames()
    {
        $list = $this->getAllPaths();

        $names = null;

        if(count($list)>0)
        {
            foreach ($list as $item)
            {
                $names[] = basename($item);
            }
        }

        return $names;
    }
//-----------------------------------------------------------------------------------
    function getConfigs($module_name)
    {
        $path_settings = $this->getRootPath($module_name).'/Config/config.php';

        $config = require $path_settings;

        if($config)
        {
            return $config;
        }

        return null;
    }
//-----------------------------------------------------------------------------------
    function getConfig($module_name, $key)
    {
        $configs = $this->getConfigs($module_name);

        if(!isset($configs[$key]))
        {
            return null;
        }

        return $configs[$key];
    }
//-----------------------------------------------------------------------------------
    function getVersion($module_name)
    {
        $composer_path = $this->getRootPath($module_name).'/composer.json';

        $composer_path = json_decode(file_get_contents($composer_path), true);

        if(!isset($composer_path['version']))
        {
            return null;
        }

        return $composer_path['version'];
    }
//-----------------------------------------------------------------------------------
    function getVersionNumber($module_name)
    {
        $version = $this->getVersion($module_name);

        $version_number = null;

        if(isset($version))
        {
            $version_number = (int) filter_var($version, FILTER_SANITIZE_NUMBER_INT);
        }

        return $version_number;
    }
//-----------------------------------------------------------------------------------
    function getAssetsUrl($module_name, $file_path)
    {
        $slug = \Str::slug($module_name);
        $version = config($slug.'.version');
        $url = url("vaahcms/modules/".$slug."/assets/".$file_path)."?v=".$version;
        return $url;
    }
//-----------------------------------------------------------------------------------
    function getMigrationPath($module_name)
    {
        $path =config('vaahcms.modules_path')."/".$module_name."/Database/Migrations/";
        $path = str_replace(base_path()."/", "", $path);
        return $path;
    }
//-----------------------------------------------------------------------------------
    function getSeedsClass($module_name)
    {
        return config('vaahcms.root_folder')."\Modules\\{$module_name}\\Database\Seeds\DatabaseTableSeeder";
    }
//-----------------------------------------------------------------------------------
    function getSampleDataClass($module_name)
    {
        return config('vaahcms.root_folder')."\Modules\\{$module_name}\\Database\Seeds\SampleDataTableSeeder";
    }
//-----------------------------------------------------------------------------------
    function getTenantMigrationPath($module_name)
    {
        $path =config('vaahcms.modules_path')."/".$module_name."/Database/Migrations/Tenants";
        $path = str_replace(base_path()."/", "", $path);
        return $path;
    }
//-----------------------------------------------------------------------------------
    function getTenantSeedsClass($module_name)
    {
        return config('vaahcms.root_folder')."\Modules\\{$module_name}\\Database\Seeds\\Tenants\\DatabaseTableSeeder";
    }
//-----------------------------------------------------------------------------------
    function getTenantSampleDataClass($module_name)
    {
        return config('vaahcms.root_folder')."\Modules\\{$module_name}\\Database\Seeds\\Tenants\\SampleDataTableSeeder";
    }
//-----------------------------------------------------------------------------------
    function getNamespace($module_name)
    {
        $namespace = "VaahCms\Modules\\".$module_name;
        return $namespace;
    }
//-----------------------------------------------------------------------------------
    function getServiceProvider($module_name)
    {
        $provider = "VaahCms\Modules\\".$module_name."\\Providers\\".$module_name."ServiceProvider";
        return $provider;
    }
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------

}
