<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\AdminModel;

class Categories extends AdminModel
{
    use HasFactory;

    protected $table    = 'categories';
    protected $fillable = ['name', 'status', 'ordering'];

}
