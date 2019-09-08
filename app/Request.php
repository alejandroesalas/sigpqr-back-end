<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Request extends Model
{
    use SoftDeletes;
    protected $table = 'requests';
    protected $fillable = [
        'title','description','student_id','status','request_type_id',
        'program_id'
    ];

    public function setTitleAttribute($valor)
    {
        $this->attributes['title'] = Str::lower($valor);
    }

    public function getTitleAttribute($valor)
    {
        return ucwords($valor);
    }

    public function student()
    {
        return $this->belongsTo(Student::class,'student_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class,'program_id');
    }

    public function requestType()
    {
        return $this->belongsTo(RequestType::class,'request_type_id');
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }
    public function attachments(){
        return $this->hasMany(AttachmentRequest::class);
    }

}
