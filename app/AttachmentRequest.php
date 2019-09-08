<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttachmentRequest extends Model
{
    protected $table = 'attachment_requests';
    protected $fillable = [
        'request_id','route','name','extension'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class,'request_id');
    }

}
