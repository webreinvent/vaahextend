<?php namespace WebReinvent\VaahExtend\Facades;


use Illuminate\Support\Facades\Facade;

class VaahArtisan extends Facade {
    protected static function getFacadeAccessor() {
        return 'vaahartisan';
    }
}
