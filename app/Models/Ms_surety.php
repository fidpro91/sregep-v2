<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ms_surety extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'surety_id';
    protected $table = 'public.ms_surety';
    protected $fillable = [
'surety_id',
'surety_active',
'surety_code',
'surety_group',
'surety_group_antrian',
'surety_name',
'surety_organizer'
];
}
