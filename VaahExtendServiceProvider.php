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

        App::bind('vaahimap',function() {
            return new \WebReinvent\VaahExtend\Libraries\VaahImap();
        });

        App::bind('vaahmodule',function() {
            return new \WebReinvent\VaahExtend\Libraries\VaahModule();
        });

        App::bind('vaahurl',function() {
            return new \WebReinvent\VaahExtend\Libraries\VaahUrl();
        });

        App::bind('vaaheventbrite',function() {
            return new \WebReinvent\VaahExtend\Libraries\VaahEventBrite\EventBrite();
        });

        App::bind('vaahextract',function() {
            return new \WebReinvent\VaahExtend\Libraries\VaahExtract();
        });

        App::bind('vaahajax',function() {
            return new \WebReinvent\VaahExtend\Libraries\VaahAjax();
        });

        App::bind('vaahstripe',function() {
            return new \WebReinvent\VaahExtend\Libraries\VaahStripe();
        });

        App::bind('vaahgooglecloud',function() {
            return new \WebReinvent\VaahExtend\Libraries\VaahGoogleCloud();
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
        $loader->alias('VaahImap', \WebReinvent\VaahExtend\Facades\VaahImap::class);
        $loader->alias('VaahModule', \WebReinvent\VaahExtend\Facades\VaahModule::class);
        $loader->alias('VaahUrl', \WebReinvent\VaahExtend\Facades\VaahUrl::class);
        $loader->alias('VaahEventBrite', \WebReinvent\VaahExtend\Facades\VaahEventBrite::class);
        $loader->alias('VaahExtract', \WebReinvent\VaahExtend\Facades\VaahExtract::class);
        $loader->alias('VaahAjax', \WebReinvent\VaahExtend\Facades\VaahAjax::class);
        $loader->alias('VaahStripe', \WebReinvent\VaahExtend\Facades\VaahStripe::class);
        $loader->alias('VaahGoogleCloud', \WebReinvent\VaahExtend\Facades\VaahGoogleCloud::class);

    }
    //--------------------------------------------------------------------



}
