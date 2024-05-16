<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ms_category_unit extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'catunit_id';
    protected $table = 'public.ms_category_unit';
    protected $fillable = [
'catunit_id',
'catunit_code',
'catunit_name',
'catunit_status',
'has_child',
'parent_id'
];
}
