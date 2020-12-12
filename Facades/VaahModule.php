<?php namespace WebReinvent\VaahLaravel\Facades;


use Illuminate\Support\Facades\Facade;

class VaahModule extends Facade {
    protected static function getFacadeAccessor() {
        return 'vaahmodule';
    }
}
