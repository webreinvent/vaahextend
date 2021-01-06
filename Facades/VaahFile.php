<?php namespace WebReinvent\VaahExtend\Facades;


use Illuminate\Support\Facades\Facade;

class VaahFile extends Facade {
    protected static function getFacadeAccessor() {
        return 'vaahfile';
    }
}
