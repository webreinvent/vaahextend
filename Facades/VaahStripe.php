<?php namespace WebReinvent\VaahExtend\Facades;


use Illuminate\Support\Facades\Facade;

class VaahStripe extends Facade {
    protected static function getFacadeAccessor() {
        return 'vaahstripe';
    }
}
