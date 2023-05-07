<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id'; // Set the primary key column name

    protected $fillable = [
        'name',
        'description',
    ];


   public function users()
    {
        return $this->belongsToMany(User::class);
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
