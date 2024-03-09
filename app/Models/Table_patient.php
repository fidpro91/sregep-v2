<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table_patient extends Model
{
    use HasFactory;

    protected $fillable = ['px_norm','px_name','px_address'];
    protected $table = "table_patient";

}
