<?php namespace WebReinvent\VaahExtend\Facades;


use Illuminate\Support\Facades\Facade;

class VaahEventBrite extends Facade {
    protected static function getFacadeAccessor() {
        return 'vaaheventbrite';
    }
}
