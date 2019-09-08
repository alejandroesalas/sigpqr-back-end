<?php

namespace App\Policies;

use App\User;
use App\Program;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProgramPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any programs.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->profile_id == 1 || $user->profile_id == 2){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the program.
     *
     * @param User $user
     * @param Program $program
     * @return mixed
     */
    public function view(User $user, Program $program)
    {
        if ($user->program_id == $program->id){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create programs.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->profile_id == 1|| $user->isAdmin()){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the program.
     *
     * @param User $user
     * @param Program $program
     * @return mixed
     */
    public function update(User $user, Program $program)
    {
        if ($user->profile_id == 1|| $user->isAdmin()){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the program.
     *
     * @param User $user
     * @param Program $program
     * @return mixed
     */
    public function delete(User $user, Program $program)
    {
        if ($user->profile_id == 1|| $user->isAdmin()){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the program.
     *
     * @param User $user
     * @param Program $program
     * @return mixed
     */
    public function restore(User $user, Program $program)
    {
        if ($user->profile_id == 1|| $user->isAdmin()){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the program.
     *
     * @param User $user
     * @param Program $program
     * @return mixed
     */
    public function forceDelete(User $user, Program $program)
    {
        if ($user->profile_id == 1|| $user->isAdmin()){
            return true;
        }
        return false;
    }
}
