<?php namespace WebReinvent\VaahLaravel\Libraries;


class VaahArtisan{

    public $params;

    public function __construct()
    {



    }

    //-------------------------------------------------
    public function validateMigrateCommand($command)
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
    public function migrate($command, $db_connection_name=null, $path=null )
    {

        $is_valid = $this->validateMigrateCommand($command);

        if($is_valid['status'] == 'failed')
        {
            return $is_valid;
        }

        if($path)
        {
            $this->params['--path'] = $path;
        }

        if($path)
        {
            $this->params['--database'] = $db_connection_name;
        }

        $response = [
            'status' => 'success'
        ];

        try{
            \Artisan::call($command, $this->params);
            $response['status'] = 'success';
            $response['data'] = [];
            $response['messages'][] = 'Migrate command "'.$command.'" successfully executed';

        }catch(\Exception $e)
        {
            $response['status'] = 'failed';
            $response['errors'][] = $e->getMessage();
        }
        return $response;

    }
    //-------------------------------------------------
    public function validateSeedCommand($command)
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
    public function seed($command, $db_connection_name=null, $class=null)
    {
        $is_valid = $this->validateSeedCommand($command);

        if($is_valid['status'] == 'failed')
        {
            return $is_valid;
        }

        if($class)
        {
            $this->params['--class'] = $class;
        }

        if($db_connection_name)
        {
            $this->params['--database'] = $db_connection_name;
        }

        $response = [
            'status' => 'success'
        ];

        try{
            \Artisan::call($command, $this->params);
            $response['status'] = 'success';
            $response['data'] = [];
            $response['messages'][] = 'Database has been seeded successfully';

        }catch(\Exception $e)
        {
            $response['status'] = 'failed';
            $response['errors'][] = $e->getMessage();
        }
        return $response;

    }
    //-------------------------------------------------

}
