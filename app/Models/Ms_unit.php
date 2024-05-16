<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ms_unit extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'unit_id';
    protected $table = 'public.ms_unit';
    protected $fillable = [
'unit_id',
'is_service',
'is_vip',
'kode_antrean',
'kode_poli_jkn',
'kode_subspesialis',
'kodeaskes',
'unit_active',
'unit_code',
'unit_id_parent',
'unit_inpatient_status',
'unit_logo',
'unit_name',
'unit_nickname',
'unit_support_status',
'unit_type'
];
}
