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

        App::bind('vaahartisan',function() {
            return new \WebReinvent\VaahLaravel\Libraries\VaahArtisan();
        });

        App::bind('vaahcountry',function() {
            return new \WebReinvent\VaahLaravel\Libraries\VaahCountry();
        });


        App::bind('vaahmodule',function() {
            return new \WebReinvent\VaahLaravel\Libraries\VaahModule();
        });


        $this->registerAlias();

    }

    //--------------------------------------------------------------------

    private function registerAlias()
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('VaahArtisan', \WebReinvent\VaahLaravel\Facades\VaahArtisan::class);
        $loader->alias('VaahModule', \WebReinvent\VaahLaravel\Facades\VaahModule::class);

    }
    //--------------------------------------------------------------------



}
