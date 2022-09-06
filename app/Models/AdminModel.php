<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminModel extends Model
{
    use HasFactory;

    public function scopeFilter($query, $request)
    {
        if (isset($request['q']) && $request['q'] != null) {
            $query->orWhere(function($qb) use($request){
                foreach ($request['fieldsAcceptSearch'] as $column) {
                    $qb->orWhere($column, 'LIKE', '%' . $request['q'] . '%');
                }
            });
        }
        if (isset($request['s']) && $request['s'] != 0) {
            $query->where('status', $request['s']);
        }
  
        return $query;
    }

    public function scopeOrderByCus($query, $request)
    {   
        $orderBies = $request['defaultOrderBy'];

        if (isset($request['o']) && $request['o'] != null) {
            $collectDefaultOrderBy = collect($request['defaultOrderBy']);
            $collectDefaultOrderBy->prepend('DESC', $request['o']);
            $orderBies = $collectDefaultOrderBy->all();
        } 
        
        foreach ($orderBies as $column => $v) {
            $query = $query->orderBy($column, $v);
        }
  
        return $query;
    }
}
