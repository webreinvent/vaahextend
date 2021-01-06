<?php namespace WebReinvent\VaahExtend\Facades;


use Illuminate\Support\Facades\Facade;

class VaahMail extends Facade {
    protected static function getFacadeAccessor() {
        return 'vaahmail';
    }
}
