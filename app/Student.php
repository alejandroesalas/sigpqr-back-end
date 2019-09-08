<?php

namespace App;


class Student extends User
{

    public function program()
    {
        return $this->belongsTo(Program::class,'program_id');
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function profile()
    {
        return parent::profile();
    }
}
