<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Profile extends Model
{
     const  ADMIN_PROFILE = 'administrador';
     const  STUDENT_PROFILE = 'estudiante';
     const  COOR_PROFILE = 'coordinador';
    const  COOR_PROFILE_NUM = 2;
    use SoftDeletes;
    protected $table = 'profiles';
    protected $fillable = [
        'name','description'
    ];

    public function setNameAttribute($valor)
    {
        $this->attributes['name'] = Str::lower($valor);
    }

    public function getNameAttribute($valor)
    {
        return ucwords($valor);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
