<?php namespace WebReinvent\VaahLaravel\Facades;


use Illuminate\Support\Facades\Facade;

class VaahUrl extends Facade {
    protected static function getFacadeAccessor() {
        return 'vaahurl';
    }
}
