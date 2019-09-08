<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttachmentResponse extends Model
{
    protected $table = 'attachment_responses';
    protected $fillable = [
        'response_id','route','name','extension'
    ];

    public function response()
    {
        return $this->belongsTo(Response::class,'response_id');
    }

}
