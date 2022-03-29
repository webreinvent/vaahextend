<?php namespace WebReinvent\VaahExtend\Libraries;


class VaahModule{


    //-----------------------------------------------------------------------------------
    public static function getVaahCmsModulesPath()
    {
        return config('vaahcms.modules_path');
    }
//-----------------------------------------------------------------------------------
    public static function getRootPath($module_name)
    {
        return self::getVaahCmsModulesPath()."/".$module_name;
    }
//-----------------------------------------------------------------------------------
    public static function getRelativePath($module_name)
    {
        return "/VaahCms/Modules/".$module_name;
    }
//-----------------------------------------------------------------------------------
    public static function getAllPaths()
    {

        $found_modules = [];

        $modules_path = self::getVaahCmsModulesPath();

        foreach (\File::directories($modules_path) as $module)
        {
            $found_modules[] = $module;
        }

        return $found_modules;

    }
//-----------------------------------------------------------------------------------
    public static function getAllNames()
    {
        $list = self::getAllPaths();

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
    public static function getConfigs($module_name)
    {
        $path_settings = self::getRootPath($module_name).'/Config/config.php';

        $config = require $path_settings;

        if($config)
        {
            return $config;
        }

        return null;
    }
//-----------------------------------------------------------------------------------
    public static function getConfig($module_name, $key)
    {
        $configs = self::getConfigs($module_name);

        if(!isset($configs[$key]))
        {
            return null;
        }

        return $configs[$key];
    }
//-----------------------------------------------------------------------------------
    public static function getVersion($module_name)
    {
        $composer_path = self::getRootPath($module_name).'/composer.json';

        $composer_path = json_decode(file_get_contents($composer_path), true);

        if(!isset($composer_path['version']))
        {
            return null;
        }

        return $composer_path['version'];
    }
//-----------------------------------------------------------------------------------
    public static function getVersionNumber($module_name)
    {
        $version = self::getVersion($module_name);

        $version_number = null;

        if(isset($version))
        {
            $version_number = (int) filter_var($version, FILTER_SANITIZE_NUMBER_INT);
        }

        return $version_number;
    }
//-----------------------------------------------------------------------------------
    public static function getAssetsUrl($module_name, $file_path)
    {
        $slug = \Str::slug($module_name);
        $version = config($slug.'.version');
        $url = url("vaahcms/modules/".$slug."/assets/".$file_path)."?v=".$version;
        return $url;
    }
//-----------------------------------------------------------------------------------
    public static function getMigrationPath($module_name)
    {
        $path =config('vaahcms.modules_path')."/".$module_name."/Database/Migrations/";
        $path = str_replace(base_path()."/", "", $path);
        return $path;
    }
//-----------------------------------------------------------------------------------
    public static function getSeedsClass($module_name)
    {
        return config('vaahcms.root_folder')."\Modules\\{$module_name}\\Database\Seeds\DatabaseTableSeeder";
    }
//-----------------------------------------------------------------------------------
    public static function getSampleDataClass($module_name)
    {
        return config('vaahcms.root_folder')."\Modules\\{$module_name}\\Database\Seeds\SampleDataTableSeeder";
    }
//-----------------------------------------------------------------------------------
    public static function getTenantMigrationPath($module_name)
    {
        $path =config('vaahcms.modules_path')."/".$module_name."/Database/Migrations/Tenant";
        $path = str_replace(base_path()."/", "", $path);
        return $path;
    }
//-----------------------------------------------------------------------------------
    public static function getTenantSeedsClass($module_name)
    {
        return config('vaahcms.root_folder')."\Modules\\{$module_name}\\Database\Seeds\\Tenant\\DatabaseTableSeeder";
    }
//-----------------------------------------------------------------------------------
    public static function getTenantSampleDataClass($module_name)
    {
        return config('vaahcms.root_folder')."\Modules\\{$module_name}\\Database\Seeds\\Tenant\\SampleDataTableSeeder";
    }
//-----------------------------------------------------------------------------------
    public static function getNamespace($module_name)
    {
        $namespace = "VaahCms\Modules\\".$module_name;
        return $namespace;
    }
//-----------------------------------------------------------------------------------
    public static function getServiceProvider($module_name)
    {
        $provider = "VaahCms\Modules\\".$module_name."\\Providers\\".$module_name."ServiceProvider";
        return $provider;
    }
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------

}
