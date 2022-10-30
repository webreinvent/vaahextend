<?php namespace WebReinvent\VaahExtend\Libraries;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VaahDB{

    public static function isConnected()
    {
        try {
            return DB::connection()->getPdo();
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function isTableExist($table)
    {
        return Schema::hasTable($table);
    }

}
