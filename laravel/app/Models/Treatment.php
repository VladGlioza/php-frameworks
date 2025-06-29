<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    protected $table = 'treatment';

    use HasFactory;

    protected $fillable = [
        'diagnosis_id',
        'treatment_plan',
        'start_date',
        'end_date'
    ];

    public function diagnosis()
    {
        return $this->belongsTo(Diagnosis::class);
    }
}
