<?php

namespace App\Scopes;
namespace App\User;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CoordinatorScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->User::whereHas('profiles', function($query) {
            $query->where('name', '=', 'coordinador');
        })->get();
    }
}
