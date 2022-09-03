<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Sliders extends Model
{
    use HasFactory;

    protected $table    = 'sliders';
    protected $fillable = ['name', 'image', 'href', 'description', 'status', 'ordering', 'date_show_start', 'date_show_end'];


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

    public function dateShowStart(): Attribute
    {
        return new Attribute(
            get: fn ($value) => \Carbon\Carbon::parse($value)->format('Y/m/d'),
        );
    }

    public function dateShowEnd(): Attribute
    {
        return new Attribute(
            get: fn ($value) => \Carbon\Carbon::parse($value)->format('Y/m/d'),
        );
    }
}
