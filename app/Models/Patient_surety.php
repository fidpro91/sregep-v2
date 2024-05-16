<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient_surety extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'public.patient_surety';
    protected $fillable = [
        'id',
        'class_id',
        'kode_ppk',
        'ppk_rujukan',
        'px_id',
        'pxsurety_no',
        'pxsurety_status',
        'surety_id'
    ];
}
