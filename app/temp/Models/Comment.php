<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id'; // Set the primary key column name

    protected $fillable = [
        'user_id',
        'task_id',
        'body'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Generate a UUID for new records
        static::creating(function ($model) {
            $model->id = (string) Uuid::uuid4();
        });

    }


    
}
