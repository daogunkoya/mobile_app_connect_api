<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusChangeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // ID of the user who made the change
        'activity',
        'loggable_type', // The type of the model being changed (User, Transaction, etc.)
        'loggable_id',   // The ID of the model being changed
    ];

    // Polymorphic relationship
    public function loggable()
    {
        return $this->morphTo();
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
