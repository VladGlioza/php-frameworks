<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctor';

    use HasFactory;

    protected $fillable = [
        'name',
        'specialty',
        'phone',
        'email'
    ];
}
