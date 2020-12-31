<?php namespace WebReinvent\VaahLaravel\Libraries;


class VaahArtisan{

    static $params;
    static $command;


    //-------------------------------------------------
    public static function setParams()
    {
        self::$params = [];
        self::$params['--force'] = true;
        self::$params['--quiet'] = true;
    }
    //-------------------------------------------------
    public static function validateMigrateCommands($command)
    {
        //acceptable commands
        $commands = [
            "migrate",
            "migrate:fresh",
            "migrate:install",
            "migrate:refresh",
            "migrate:reset",
            "migrate:rollback",
            "migrate:status",
        ];
        if(!in_array($command, $commands))
        {
            $response['status'] = 'failed';
            $response['errors'][] = 'Invalid command';
            if(env('APP_DEBUG'))
            {
                $response['hint']['acceptable_commands'] = $commands;
            }
            return $response;
        }
        $response['status'] = 'success';
        $response['data'][] = '';
        return $response;
    }
    //-------------------------------------------------
    public static function artisan()
    {
        try{
            \Artisan::call(self::$command, self::$params);
            $response['status'] = 'success';
            $response['data'] = [];
            $response['messages'][] = 'Migrate command "'.self::$command.'" successfully executed';
        }catch(\Exception $e)
        {
            $response['status'] = 'failed';
            $response['errors'][] = $e->getMessage();
        }
        return $response;
    }
    //-------------------------------------------------
    public static function migrate($command='migrate', $path=null, $db_connection_name=null )
    {
        self::setParams();
        $is_valid = self::validateMigrateCommands($command);
        if($is_valid['status'] == 'failed')
        {
            return $is_valid;
        }
        if($path)
        {
            self::$params['--path'] = $path;
        }
        if($path)
        {
            self::$params['--database'] = $db_connection_name;
        }

        self::$command = $command;

        return self::artisan();
    }
    //-------------------------------------------------
    public static function migrationReset($path=null, $db_connection_name=null)
    {
        return self::migrate('migrate:reset', $db_connection_name, $path);
    }
    //-------------------------------------------------
    public static function validateSeedCommand($command)
    {
        //acceptable commands
        $commands = [
            "db:seed",
            "db:wipe",
        ];
        if(!in_array($command, $commands))
        {
            $response['status'] = 'failed';
            $response['errors'][] = 'Invalid command';
            if(env('APP_DEBUG'))
            {
                $response['hint']['acceptable_commands'] = $commands;
            }
            return $response;
        }
        $response['status'] = 'success';
        $response['data'][] = '';
        return $response;
    }
    //-------------------------------------------------
    public static function seed($command='db:seed', $class=null,  $db_connection_name=null )
    {
        self::setParams();
        $is_valid = self::validateSeedCommand($command);
        if($is_valid['status'] == 'failed')
        {
            return $is_valid;
        }
        if($class)
        {
            self::$params['--class'] = $class;
        }
        if($db_connection_name)
        {
            self::$params['--database'] = $db_connection_name;
        }
        self::$command = $command;

        return self::artisan();
    }
    //-------------------------------------------------
    public static function publish($provider, $tag)
    {
        self::setParams();
        self::$params['--provider'] = $provider;
        self::$params['--tag'] = $tag;
        self::$command = 'vendor:publish';
        return self::artisan();
    }
    //-------------------------------------------------
    public static function publishMigrations($provider)
    {
        self::setParams();
        self::$params['--provider'] = $provider;
        self::$params['--tag'] = 'migrations';
        self::$command = 'vendor:publish';
        return self::artisan();
    }
    //-------------------------------------------------
    public static function publishSeeds($provider)
    {
        self::setParams();
        self::$params['--provider'] = $provider;
        self::$params['--tag'] = 'seeds';
        self::$command = 'vendor:publish';
        return self::artisan();
    }
    //-------------------------------------------------
    public static function publishAssets($provider)
    {
        self::setParams();
        self::$params['--provider'] = $provider;
        self::$params['--tag'] = 'assets';
        self::$command = 'vendor:publish';
        return self::artisan();
    }
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------

}
