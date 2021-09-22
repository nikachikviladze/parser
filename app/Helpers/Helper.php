<?php


namespace App\Helpers;

class Helper
{
    public static function stringParser(string $string)
    {
        $x = preg_split('/\r\n|\r|\n/', strip_tags($string));
        $x= implode(' ', $x);
        $desc = preg_replace('/[\x00-\x1F\x7F]/', '', $x);
        return $desc;
    }

    public static function cleanDatabase()
    {
        foreach(\DB::select('SHOW TABLES') as $table) {
            $table_array = get_object_vars($table);
            \Schema::drop($table_array[key($table_array)]);
        }
    }

    
}