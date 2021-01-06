<?php namespace WebReinvent\VaahExtend\Facades;


use Illuminate\Support\Facades\Facade;

class VaahAssets extends Facade {
    protected static function getFacadeAccessor() {
        return 'vaahassets';
    }
}
