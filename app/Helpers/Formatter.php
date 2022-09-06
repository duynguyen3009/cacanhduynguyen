<?php 
namespace App\Helpers;

class Formatter 
{
    public static function convertStatus($value)
    {
        return ($value == "true") ? 'active' : 'inactive';
    }
}



?>