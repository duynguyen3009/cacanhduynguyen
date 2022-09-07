<?php 
namespace App\Helpers;

use App\Helpers\Formatter;
use Illuminate\Support\Facades\DB;

class AdminProcess 
{
    public static function updateStatus($table, $formFields)
    {
        $record = DB::table($table)
                    ->where('id', $formFields['id'])
                    ->update([
                        'status' => Formatter::convertStatus($formFields['status'])
                    ]);
        return $record;
    }
    public static function updateOrdering($table, $formFields)
    {
        $record = DB::table($table)
                    ->where('id', $formFields['id'])
                    ->update([
                        'ordering' => $formFields['ordering']
                    ]);

        return $record;
    }
}



?>