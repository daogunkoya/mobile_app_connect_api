<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Hasone;
//use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'mm_user';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id_user'; // Set the primary key column name

    protected $fillable = [
      //  'id',
        'first_name',
        'last_name',
        'user_name',
        'user_handle',
        'store_id',
        'email',
        'password',
        'user_role_type'
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


    protected static function boot()
    {
        parent::boot();

        // Generate a UUID for new records
        static::creating(function ($model) {
            $model->id_user = (string) Uuid::uuid4();

            $model->user_name = Str::slug($model->first_name . ' ' . $model->last_name, '-');

            // Check if the generated username already exists
            $count = 1;
            while (static::where('user_name', $model->user_name)->exists()) {
                $model->user_name = Str::slug($model->first_name . ' ' . $model->last_name, '-') . '-' . $count;
                $count++;
            }

        });
    }

    public function rate(): HasMany
    {
        return $this->hasMany(Rate::class, 'user_id');
    }

    public function currency(): HasOne
    {
        return $this->hasOne(Currency::class, 'active_currency_id');
    }
}
