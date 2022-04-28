<?php namespace WebReinvent\VaahExtend\Facades;


use Illuminate\Support\Facades\Facade;

class VaahGoogleCloud extends Facade {
    protected static function getFacadeAccessor() {
        return 'vaahgooglecloud';
    }
}
