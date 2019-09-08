<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Program extends Model
{
    use SoftDeletes;
    protected $table = 'programs';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name','faculty_id','coordinator_id'
    ];

    public function setNameAttribute($valor)
    {
        $this->attributes['name'] = Str::lower($valor);
    }

    public function getNameAttribute($valor)
    {
        return ucwords($valor);
    }

    public function faculty(){
        return $this->belongsTo(Faculty::class,'faculty_id');
    }

    public function coordinator()
    {
        return $this->belongsTo(Coordinator::class,'coordinator_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

}
