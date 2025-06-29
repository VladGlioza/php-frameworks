<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patient';

    use HasFactory;

    protected $fillable = [
        'name',
        'date_of_birth',
        'gender',
        'contact_info'
    ];
}
