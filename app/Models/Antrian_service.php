<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian_service extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'antrian_id';
    protected $table = 'public.antrian_service';
    protected $fillable = [
        'antrian_code',
        'antrian_date',
        'antrian_group_id',
        'antrian_num',
        'antrian_status',
        'visit_id',
        'visit_id_online'
    ];
}
