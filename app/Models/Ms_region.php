<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ms_region extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'reg_code';
    protected $table = 'public.ms_region';
    protected $fillable = [
'reg_code',
'reg_active',
'reg_level',
'reg_name',
'reg_parent'
];
}
