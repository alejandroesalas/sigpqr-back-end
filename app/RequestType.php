<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RequestType extends Model
{
    use SoftDeletes;
    protected $table = 'request_types';
    protected $fillable = [
        'type','description'
    ];

    public function setTypeAttribute($valor)
    {
        $this->attributes['type'] = Str::lower($valor);
    }

    public function getTypeAttribute($valor)
    {
        return ucwords($valor);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }
}
