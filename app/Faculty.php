<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Faculty extends Model
{
    protected $table = 'faculties';
    protected $fillable = [
        'name'
    ];

    public function setNameAttribute($valor)
    {
        $this->attributes['name'] = Str::lower($valor);
    }

    public function getNameAttribute($valor)
    {
        return ucwords($valor);
    }

    /**
     * Función que devuelve los programas que pertenecen a una facultad
     */
    public function programs()
    {
        return $this->hasMany(Program::class);
    }
}
