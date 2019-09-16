<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Profile;
use App\Coordinator;
use App\Program;
use Illuminate\Support\Facades\DB;

class CoordinatorController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api');
        //$this->middleware('auth',['except'=>['auth/login']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @param Profile $profile
     * @return void
     */
    public function index(Profile $profile)
    {
        /*$cordinadores = Coordinator::where('users.profile_id','=',Profile::COOR_PROFILE_NUM)
            ->with(['programs'])->get();*/
        $cordinadores = Program::wherehas('coordinator')
            ->with(['coordinator'])
            ->get();

        /*$cordinadores = $profile
            ->where('name','=','coordinador')
            ->with(['users'])
            ->with('users.programs')
            ->get()
            ->pluck('users')
            ->collapse()
            ->values();*/
       // return $this->showAll($students);
        return $this->showAll($cordinadores);
    }

    public function countCoordinators(Coordinator $coordinator)
    {
        $countCoordinators = $coordinator
            ->where('profile_id', User::COORDINATOR_PROFILE)
            ->count();
        return $this->showOther($countCoordinators);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Coordinator  $coordinator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coordinator $coordinator)
    {

    }

    public function degrade(Request $request, Coordinator $coordinator)
    {
        $rules = [
            'program_id'=>'required|integer',
        ];

        $json = $request->input('json', null);
        if (!Empty($json)){
            $params_array = array_map('trim', json_decode($json, true));
            if (!Empty($params_array)){
                $validate = $this->checkValidation($params_array, $rules);
                if ($validate->fails()){
                    return $this->errorResponse("datos no validos", $validate->errors(),400);
                }else{
                    /*if(!$coordinator->isDirty()){
                        return $this->errorResponse('se debe especificar al menos un valor', '',422);
                    }*/
                    $isCoordinator = $coordinator->where('id', $coordinator->id)
                        ->where('profile_id', User::COORDINATOR_PROFILE)
                        ->count();
                    if($isCoordinator == 0) {
                        return $this->errorResponse("Este usuario no es coordinador", '',404);
                    }
                    //updating coordinator to teacher
                    DB::transaction(function () use ($coordinator, $params_array) {
                        $idCoordinator = $coordinator->id;
                        DB::table('users')->where('id', $idCoordinator)
                            ->update([
                                'profile_id' => User::TEACHER_PROFILE,
                                'status' => User::FALSE_STATE
                            ]);
                        DB::table('programs')->where('id', $params_array['program_id'])
                            ->update(['coordinator_id' => null]);
                    });
                    return $this->showOne($coordinator);
                }
            }else{
                return $this->errorResponse('Datos Vacios!','', 422);
            }
        }else{
            return $this->errorResponse('La estrucutra del json no es valida', '',422);
        }
    }

}
