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
    function getModuleRootPath($module_name)
    {
        return $this->getVaahCmsModulesPath()."/".$module_name;
    }
//-----------------------------------------------------------------------------------
    function getModuleRelativePath($module_name)
    {
        return "/VaahCms/Modules/".$module_name;
    }
//-----------------------------------------------------------------------------------
    function getAllModulesPaths()
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
    function getAllModulesNames()
    {
        $list = $this->getAllModulesPaths();

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
    function getModuleConfigs($module_name)
    {
        $path_settings = $this->getModuleRootPath($module_name).'/Config/config.php';

        $config = require $path_settings;

        if($config)
        {
            return $config;
        }

        return null;
    }
//-----------------------------------------------------------------------------------
    function getModuleConfig($module_name, $key)
    {
        $configs = $this->getModuleConfigs($module_name);

        if(!isset($configs[$key]))
        {
            return null;
        }

        return $configs[$key];
    }
//-----------------------------------------------------------------------------------
    function getModuleAssetsUrl($module_name, $file_path)
    {
        $slug = \Str::slug($module_name);
        $version = config($slug.'.version');
        $url = url("vaahcms/modules/".$slug."/assets/".$file_path)."?v=".$version;
        return $url;
    }
//-----------------------------------------------------------------------------------
    function getModuleMigrationPath($module_name)
    {
        $path =config('vaahcms.modules_path')."/".$module_name."/Database/Migrations/";
        $path = str_replace(base_path()."/", "", $path);
        return $path;
    }
//-----------------------------------------------------------------------------------
    function getModuleSeedsClass($module_name)
    {
        return config('vaahcms.root_folder')."\Modules\\{$module_name}\\Database\Seeds\DatabaseTableSeeder";
    }
//-----------------------------------------------------------------------------------
    function getModuleTenantMigrationPath($module_name)
    {
        $path =config('vaahcms.modules_path')."/".$module_name."/Database/Migrations/Tenants";
        $path = str_replace(base_path()."/", "", $path);
        return $path;
    }
//-----------------------------------------------------------------------------------
    function getModuleTenantSeedsClass($module_name)
    {
        return config('vaahcms.root_folder')."\Modules\\{$module_name}\\Database\Seeds\\Tenants\\DatabaseTableSeeder";
    }
//-----------------------------------------------------------------------------------
    function getModuleTenantSampleData($module_name)
    {
        return config('vaahcms.root_folder')."\Modules\\{$module_name}\\Database\Seeds\\Tenants\\SampleTableSeeder";
    }
//-----------------------------------------------------------------------------------
    function getModuleNamespace($module_name)
    {
        $namespace = "VaahCms\Modules\\".$module_name;
        return $namespace;
    }
//-----------------------------------------------------------------------------------
    function getModuleServiceProvider($module_name)
    {
        $provider = "VaahCms\Modules\\".$module_name."\\Providers\\".$module_name."ServiceProvider";
        return $provider;
    }
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------

}
