<?php

namespace App;

use App\Notifications\CustomResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;
    const CC_TYPE = 'CC';
    const TI_TYPE = 'TI';
    const ACTIVE_STATE = 'activo';
    const FALSE_STATE = 'desactivado';
    const VERIFIED_USER = '1';
    const NOT_VERIFIED_USER = '0';
    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';
    const ADMIN_PROFILE = 1;
    const COORDINATOR_PROFILE = 2;
    const STUDENT_PROFILE = 3;
    const TEACHER_PROFILE = 4;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lastname', 'id_type','id_num',
        'password','email','verified','status','admin',
        'program_id','profile_id','verification_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
        ,'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setNameAttribute($valor)
    {
        $this->attributes['name'] = Str::lower($valor);
    }

    public function getNameAttribute($valor)
    {
        return ucwords($valor);
    }

    public function setEmailAttribute($valor)
    {
        $this->attributes['email'] = Str::lower($valor);
    }

    public function setLastNameAttribute($valor)
    {
        $this->attributes['lastname'] = Str::lower($valor);
    }

    public function getLastNameAttribute($valor)
    {
        return ucwords($valor);
    }

    public function isVerified()
    {
        return $this->verified == User::VERIFIED_USER;
    }

    public function isAdmin()
    {
        return $this->admin == User::ADMIN_USER;
    }

    public static function createVerificationToken()
    {
        return Str::random(40);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class,'profile_id');
    }

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
      return [];
    }

    public function sendPasswordResetNotification($token){
        $this->notify(new CustomResetPassword($token));
    }

}
