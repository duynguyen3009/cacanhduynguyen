<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\AdminModel;

class Sliders extends AdminModel
{
    use HasFactory;

    protected $table    = 'sliders';
    protected $fillable = ['name', 'image', 'href', 'description', 'status', 'ordering', 'date_show_start', 'date_show_end'];

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
