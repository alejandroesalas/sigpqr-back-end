<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Token extends Model
{
    use SoftDeletes;
    protected $table = 'tokens';
    protected $fillable = [
        'token','user_id','correo','tipo','status'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
