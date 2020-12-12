<?php namespace WebReinvent\VaahLaravel\Facades;


use Illuminate\Support\Facades\Facade;

class VaahCountry extends Facade {
    protected static function getFacadeAccessor() {
        return 'vaahcountry';
    }
}
