<?php namespace WebReinvent\VaahLaravel\Facades;


use Illuminate\Support\Facades\Facade;

class VaahArtisanFacade extends Facade {
    protected static function getFacadeAccessor() { return 'vaahartisan'; }
}
