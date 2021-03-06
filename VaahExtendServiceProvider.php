<?php namespace WebReinvent\VaahExtend;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use WebReinvent\VaahCms\Facades\VaahExcelFacade;
use WebReinvent\VaahCms\Facades\VaahFileFacade;


class VaahExtendServiceProvider extends ServiceProvider {

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
            return new \WebReinvent\VaahExtend\Libraries\VaahArtisan();
        });


        App::bind('vaahassets',function() {
            return new \WebReinvent\VaahExtend\Libraries\VaahAssets();
        });


        App::bind('vaahcountry',function() {
            return new \WebReinvent\VaahExtend\Libraries\VaahCountry();
        });

        App::bind('vaahfile',function() {
            return new \WebReinvent\VaahExtend\Libraries\VaahFiles();
        });

        App::bind('vaahmail',function() {
            return new \WebReinvent\VaahExtend\Libraries\VaahMail();
        });

        App::bind('vaahmodule',function() {
            return new \WebReinvent\VaahExtend\Libraries\VaahModule();
        });

        App::bind('vaahurl',function() {
            return new \WebReinvent\VaahExtend\Libraries\VaahUrl();
        });


        $this->registerAlias();

    }

    //--------------------------------------------------------------------

    private function registerAlias()
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('VaahArtisan', \WebReinvent\VaahExtend\Facades\VaahArtisan::class);
        $loader->alias('VaahAssets', \WebReinvent\VaahExtend\Facades\VaahAssets::class);
        $loader->alias('VaahCountry', \WebReinvent\VaahExtend\Facades\VaahCountry::class);
        $loader->alias('VaahFile', \WebReinvent\VaahExtend\Facades\VaahFile::class);
        $loader->alias('VaahMail', \WebReinvent\VaahExtend\Facades\VaahMail::class);
        $loader->alias('VaahModule', \WebReinvent\VaahExtend\Facades\VaahModule::class);
        $loader->alias('VaahUrl', \WebReinvent\VaahExtend\Facades\VaahUrl::class);

    }
    //--------------------------------------------------------------------



}
