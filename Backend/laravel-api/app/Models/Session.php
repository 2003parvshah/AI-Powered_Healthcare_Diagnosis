<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'sessions'; // Specify the table name

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity'
    ];

    public $timestamps = false; // The sessions table does not have created_at and updated_at

    /**
     * Get the user that owns this session.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
