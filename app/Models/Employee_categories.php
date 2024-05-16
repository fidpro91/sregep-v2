<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee_categories extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'empcat_id';
    protected $table = 'public.employee_categories';
    protected $fillable = [
'empcat_id',
'empcat_active',
'empcat_code',
'empcat_name',
'empcat_parent'
];
}
