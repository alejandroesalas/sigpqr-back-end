<?php


namespace App\Traits;
use Illuminate\Support\Facades\Validator;

trait ValitadorTrait
{
    public function checkValidation($params,$rules){
        $validate = Validator::make($params, $rules);
        return $validate;
    }

}
