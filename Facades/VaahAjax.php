<?php namespace WebReinvent\VaahExtend\Facades;


use Illuminate\Support\Facades\Facade;

class VaahAjax extends Facade {
    protected static function getFacadeAccessor() {
        return 'vaahajax';
    }
}
