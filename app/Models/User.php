<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRolesAndAbilities, SoftDeletes,LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'lastname',
        'identification',
        'email',
        'password',
        'active',
        'reset_password'
    ];

    /**
     * The attributes log activity.
     *
     * @var array
     */
    protected static $logAttributes = ['name', 'lastname', 'identification', 'email', 'active', 'reset_password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active'=>'boolean',
        'reset_password'=>'boolean'
    ];

    /**
     * mutador para nombre
     * @param $value
     *
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    /**
     * accesor para nombre
     * @param $value
     * @return string
     */
    public function getNameAttribute($value): string
    {
        return ucwords($value);
    }

    /**
     * mutador para apellido
     * @param $value
     *
     */
    public function setLastnameAttribute($value)
    {
        $this->attributes['lastname'] = strtolower($value);
    }


    /**
     * accesor para nombre
     * @param $value
     * @return string
     */
    public function getLastnameAttribute($value):string
    {
        return ucwords($value);
    }

    /**
     * @param $value
     *
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->name} {$this->lastname}";
    }

    /**
     * Buscador
     *
     * @param $query
     * @param $buscar
     * @return mixed
     */
    public function scopeSearch($query, $search)
    {
        if (trim($search) != "" and $search != "null") {
            return $query->where('name', 'LIKE', "%$search%")
                ->orWhere('lastname', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%")
                ->orWhere('identification', 'LIKE', "%$search%");
        }
    }

}
