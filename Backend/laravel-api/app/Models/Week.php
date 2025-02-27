<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    use HasFactory;

    protected $table = 'weeks';

    protected $fillable = ['name'];

    public function timings()
    {
        return $this->hasMany(doctor_timeTable::class, 'day', 'name');
    }
}
