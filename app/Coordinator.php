<?php

namespace App;


class Coordinator extends User
{
    public function programs()
    {
        return $this->hasMany(Program::class);
    }
    //devuelve las respuestas que ha realizado un coordinador
    /*
    public function responses(){
        return $this->hasMany(Response::class);
    }*/
    public function profile()
    {
        return parent::profile();
    }
}
