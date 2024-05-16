<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'px_id';
    protected $table = 'public.patient';
    protected $fillable = [
'px_id',
'company_id',
'edu_id',
'is_data_migrasi',
'lag_id',
'position_id',
'px_active',
'px_address',
'px_birthdate',
'px_bloodgroup',
'px_born',
'px_city',
'px_district',
'px_id_ektp',
'px_name',
'px_nik',
'px_nokk',
'px_noktp',
'px_norm',
'px_phone',
'px_prov',
'px_reg',
'px_regby',
'px_resident',
'px_rfid',
'px_sex',
'px_status',
'religion_id',
'status_id_khusus',
'tribe_id',
'user_ip',
'work_id'
];
}
