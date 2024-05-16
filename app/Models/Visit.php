<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'visit_id';
    protected $table = 'public.visit';
    protected $fillable = [
        'diagnosa_awal',
        'dpjp_id',
        'last_srv_type',
        'no_suratrujukan',
        'nomor_antrian',
        'perujuk_id',
        'px_id',
        'pxsurety_no',
        'reg_code',
        'reg_from',
        'surety_id',
        'unit_id',
        'user_act',
        'user_id',
        'user_ip',
        'user_mac',
        'visit_age_d',
        'visit_age_m',
        'visit_age_y',
        'visit_date',
        'visit_desc',
        'visit_finish',
        'visit_px_address',
        'visit_status',
        'visit_type'
    ];
}
