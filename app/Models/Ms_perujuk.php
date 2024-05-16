<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ms_perujuk extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'perujuk_id';
    protected $table = 'public.ms_perujuk';
    protected $fillable = [
'perujuk_id',
'created_at',
'kode_faskes_bpjs',
'perujuk_active',
'perujuk_address',
'perujuk_city',
'perujuk_district',
'perujuk_name',
'perujuk_phone',
'perujuk_prov',
'perujuk_resident',
'tipe_ppk',
'updated_at'
];
}
