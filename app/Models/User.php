<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enum\UserRoleType;
use App\Enum\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Hasone;
//use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

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
        'currency_id',
        'user_handle',
        'store_id',
        'email',
        'password',
        'user_role_type',
        'status'
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
        'user_role_type' => UserRoleType::class,
        'status' => UserStatus::class
    ];


    protected static function boot()
    {
        parent::boot();

        // Generate a UUID for new records
        static::creating(function ($model) {
            $model->id_user = (string)Uuid::uuid4();

            $model->user_name = Str::slug($model->first_name . ' ' . $model->last_name, '-');

            // Check if the generated username already exists
            $count = 1;
            while (static::where('user_name', $model->user_name)->exists()) {
                $model->user_name = Str::slug($model->first_name . ' ' . $model->last_name, '-') . '-' . $count;
                $count++;
            }

        });
    }

    public function scopeFilter(Builder $query, array $filter): void
    {
        $query
            ->when($filter['search'] ?? false, function ($query, $search) use($filter) {
                $search = trim($filter['search']);
                $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                   ;
            })
            ->when($filter['userId'] ?? false, fn($query) => $query->where('id_user', $filter['userId']))
          
           // ->when($filter['status'] ?? false, fn($query) => $query->where('user_status', Status::getStatusEnumInstance($filter['status'])))  
            ->when($filter['date'] ?? false, function ($query,$search)use($filter) {
                $searchDate = $filter['date'];
                $query->when($searchDate, function ($query, $date) {
                    return $query->where(fn ($query) => match ($date) {
                        'today' => $query->whereDate('created_at', today()),
                        'yesterday' => $query->whereDate('created_at', today()->subDay()),
                        'week' => $query->whereBetween('created_at', [now()->subDays(7)->startOfDay(), now()->endOfDay()]),
                        'month' => $query->whereBetween('created_at', [now()->subDays(30)->startOfDay(), now()->endOfDay()]),
                        default => $query->whereBetween('created_at', [
                            Carbon::createFromFormat('d/m/Y', $date['from'])->startOfDay(),
                            Carbon::createFromFormat('d/m/Y', $date['to'])->endOfDay(),
                        ]),
                    });
                });
            });
    }


    public function sender(): HasMany
    {
        return $this->hasMany(Sender::class, 'user_id');
    }

    public function rate(): HasMany
    {
        return $this->hasMany(Rate::class, 'user_id');
    }

    public function commission(): HasMany
    {
        return $this->hasMany(Commission::class, 'user_id');
    }

    public function userRate(): HasOne
    {

        return $this->hasOne(Rate::class, 'user_id')->latest();
    }


    public function userCommission(): Hasone
    {
        $userId = $this->id_user;
        return $this->hasOne(Commission::class, 'user_id');
    }


    public function currency(): HasOne
    {
        return $this->hasOne(Currency::class, 'active_currency_id');
    }

    public function receiver():HasMany
    {
        return $this->hasMany(Receiver::class, 'sender_id', 'id_user');
    }

    public function transaction():HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id_user');
    }

    public function receivers():HasManyThrough
    {
        return $this->hasManyThrough(
            Receiver::class,
            Sender::class,
            'user_id', // Foreign key on the senders table
            'sender_id', // Foreign key on the receivers table
            'id_user', // Local key on the users table
            'id_sender' // Local key on the senders table
        );

    }

    public function userCurrencies():HasMany
    {
       return $this->hasMany(UserCurrency::class, 'sender_id', 'id_sender');
    }

    public function latestUserCurrency(): HasOne
    {
        return $this->hasOne(UserCurrency::class, 'user_id', 'id_user')
                    ->latest('created_at');
    }
}
