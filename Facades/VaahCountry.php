<?php namespace WebReinvent\VaahExtend\Facades;


use Illuminate\Support\Facades\Facade;

class VaahCountry extends Facade {
    protected static function getFacadeAccessor() {
        return 'vaahcountry';
    }
}
