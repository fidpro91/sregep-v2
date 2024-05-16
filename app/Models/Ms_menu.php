<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ms_menu extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'menu_id';
    protected $table = 'public.ms_menu';
    protected $fillable = [
'menu_id',
'menu_code',
'menu_function',
'menu_icon',
'menu_name',
'menu_parent_id',
'menu_status',
'menu_target',
'menu_url',
'modul_id'
];
}
