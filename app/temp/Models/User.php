<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;
//use Spatie\Permission\Traits\HasRoles;

;

use Ramsey\Uuid\Uuid;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    //  public   $incrementing = false; // Disable auto-incrementing of the ID

    //  protected $keyType = 'string'; // Set the ID data type to string

    //  protected $primaryKey = 'id'; // Set the primary key column name

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // protected static function boot()
    // {
        // parent::boot();
//
        // Generate a UUID for new records
        // static::creating(function ($model) {
        //     $model->id = (string) Uuid::uuid4();
        // });

        // static::created(function ($user) {
        //     $taskRole = Role::where('name', 'task')->first();
        //     $user->assignRole($taskRole);
        // });
    //}

    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class);
    // }

    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }


    // public function tasksAssigned()
    // {
    //     return $this->hasMany(Task::class, 'assigned_to');
    // }

    public function tasksCreated()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
