<?php namespace WebReinvent\VaahExtend\Facades;


use Illuminate\Support\Facades\Facade;

class VaahImap extends Facade {
    protected static function getFacadeAccessor() {
        return 'vaahimap';
    }
}
