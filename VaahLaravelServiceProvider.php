<?php namespace WebReinvent\VaahLaravel;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use WebReinvent\VaahCms\Facades\VaahExcelFacade;
use WebReinvent\VaahCms\Facades\VaahFileFacade;


class VaahLaravelServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {

        App::bind('vaahassets',function() {
            return new \WebReinvent\VaahLaravel\Libraries\VaahAssets();
        });

        App::bind('vaahartisan',function() {
            return new \WebReinvent\VaahLaravel\Libraries\VaahArtisan();
        });

        App::bind('vaahcountry',function() {
            return new \WebReinvent\VaahLaravel\Libraries\VaahCountry();
        });


        App::bind('vaahmodule',function() {
            return new \WebReinvent\VaahLaravel\Libraries\VaahModule();
        });

        App::bind('vaahurl',function() {
            return new \WebReinvent\VaahLaravel\Libraries\VaahUrl();
        });

        App::bind('vaahfile',function() {
            return new \WebReinvent\VaahLaravel\Libraries\VaahFiles();
        });

        $this->registerAlias();

    }

    //--------------------------------------------------------------------

    private function registerAlias()
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('VaahAssets', \WebReinvent\VaahLaravel\Facades\VaahAssets::class);
        $loader->alias('VaahArtisan', \WebReinvent\VaahLaravel\Facades\VaahArtisan::class);
        $loader->alias('VaahCountry', \WebReinvent\VaahLaravel\Facades\VaahCountry::class);
        $loader->alias('VaahModule', \WebReinvent\VaahLaravel\Facades\VaahModule::class);
        $loader->alias('VaahUrl', \WebReinvent\VaahLaravel\Facades\VaahUrl::class);
        $loader->alias('VaahFile', \WebReinvent\VaahLaravel\Facades\VaahFile::class);


    }
    //--------------------------------------------------------------------



}
