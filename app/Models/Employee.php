<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'employee_id';
    protected $table = 'public.employee';
    protected $fillable = [
        'employee_id',
        'absen_code',
        'empcat_id',
        'employee_active',
        'employee_address',
        'employee_bt',
        'employee_ft',
        'employee_jabatan',
        'employee_name',
        'employee_nik',
        'employee_nip',
        'employee_pendidikan',
        'employee_permanent',
        'employee_photo',
        'employee_salary',
        'employee_sex',
        'employee_tmp_tgl_lahir',
        'employee_tmt',
        'employee_type',
        'kodehfis',
        'signature'
    ];
}
