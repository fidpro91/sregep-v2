<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule_doctor_service extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'public.schedule_doctor_service';
    protected $fillable = [
        'created_by',
        'day',
        'kuota_jkn',
        'kuota_non_jkn',
        'par_id',
        'time_end',
        'time_start'
    ];

    public function dokter() {
        return $this->hasOne(Employee::class, 'employee_id', 'par_id');
    }
}
