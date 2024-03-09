<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $table        = "patient";
    protected $primaryKey   = "px_id";
    protected $fillable     = [
        'px_norm',
        'px_name',
        'px_address'
    ];

}
