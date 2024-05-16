<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ms_reff extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'reff_id';
    protected $table = 'public.ms_reff';
    protected $fillable = [
'reff_id',
'has_detail',
'refcat_id',
'reff_active',
'reff_code',
'reff_name'
];
}
