<?php namespace WebReinvent\VaahLaravel;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use WebReinvent\VaahLaravel\Helpers\VaahArtisan;

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
            return new VaahArtisan();
        });

    }




}
