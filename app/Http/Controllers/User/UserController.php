<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Http\Request;
use App\Profile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api',['except'=>['checkEmail','store']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @param Profile $profile
     * @return void
     */
    public function index(Profile $profile)
    {
        $students  = $profile
            ->where('name','=','docente')
            ->with('users')
            ->get()
            ->pluck('users')
            ->collapse()
            ->values();
        return $this->showAll($students);
    }

    public function countTeachers(User $user)
    {
        $countTeachers = $user
            ->where('profile_id', User::TEACHER_PROFILE)
            ->count();
        return $this->showOther($countTeachers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name'=>'required',
            'profile_id'=>'required',
            'lastname'=>'required',
            'email' => 'email|unique:users',
            'id_type' => 'required|in:'. User::CC_TYPE . ',' . User::TI_TYPE,
            'id_num' => 'required|unique:users',
        ];
        $json = $request->input('json', null);
        if (!Empty($json)){
            $params_array = array_map('trim', json_decode($json, true));
            if (!Empty($params_array)){
                $validation = $this->checkValidation($params_array,$rules);
                if ($validation->fails()){
                    return $this->errorResponse("datos no validos",$validation->errors(),400 );
                }else{
                    unset($params_array['program_id ']);
                    $params_array['password'] = bcrypt($params_array['id_num']);
                    // $params_array['profile_id'] = User::TEACHER_PROFILE;
                    $params_array['status'] = User::FALSE_STATE;
                    $params_array['admin'] = User::REGULAR_USER;
                    $params_array['verified']= User::VERIFIED_USER;
                   // $params_array['verification_token'] =User::createVerificationToken();
                    $user = User::create($params_array);
                    return $this->showOne($user);
                }
            }else{
                return $this->errorResponse('Datos Vacios!','' ,400);
            }
        }else{
            return $this->errorResponse('La estrucutra del json no es valida','',415);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'email' => 'email|unique:users,id,'.$user->id,
            'id_num' => 'unique:users,id,'. $user->id,
        ];

        $json = $request->input('json', null);
        if (!Empty($json)){
            $params_array = array_map('trim', json_decode($json, true));
            if (!Empty($params_array)){
                $validate = $this->checkValidation($params_array, $rules);
                if ($validate->fails()){
                    return $this->errorResponse("datos no validos", $validate->errors(),400);
                }else{

                    if(Arr::has($params_array, 'name')) {
                        $user->name = $params_array['name'];
                    }

                    if(Arr::has($params_array, 'lastname')) {
                        $user->lastname = $params_array['lastname'];
                    }

                    if(Arr::has($params_array, 'id_type')) {
                        $user->id_type = $params_array['id_type'];
                    }

                    if(Arr::has($params_array, 'id_num')) {
                        $user->id_num = $params_array['id_num'];
                    }

                    if(!$user->isDirty()){
                        return $this->errorResponse('se debe especificar al menos un valor', 422);
                    }

                    $user->save();
                    return $this->showOne($user);
                }
            }else{
                return $this->errorResponse('Datos Vacios!', '',422);
            }
        }else{
            return $this->errorResponse('La estrucutra del json no es valida', '',422);
        }
    }

    public function ascent(Request $request, User $user)
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
                    /*if(!$user->isDirty()){
                        //dd($user);
                        return $this->errorResponse('se debe especificar al menos un valor', '',422);
                    }*/
                    $isTeacher = $user->where('id', $user->id)
                        ->where('profile_id', User::TEACHER_PROFILE)
                        ->count();
                    if($isTeacher == 0) {
                        return $this->errorResponse("Este usuario no es docente", '',404);
                    }
                    DB::transaction(function () use ($user, $params_array) {
                        DB::table('users')->where('id', $user->id)
                            ->update(['profile_id' => User::COORDINATOR_PROFILE]);
                        DB::table('users')->where('id', $user->id)
                            ->update(['status' => User::ACTIVE_STATE]);
                        DB::table('programs')->where('id', $params_array['program_id'])
                            ->update(['coordinator_id' => $user->id]);
                    });
                    return $this->showMessage("$user->name ahora es coordinador");
                }
            }else{
                return $this->errorResponse('Datos Vacios!', '',422);
            }
        }else{
            return $this->errorResponse('La estrucutra del json no es valida', '',422);
        }
    }


    public function onlyTrashed(User $user)
    {
        $users = $user->where('profile_id', User::TEACHER_PROFILE)
            ->onlyTrashed()
            ->get();
        return $this->showAll($users);
    }

    public function countTeachersEliminated(User $user)
    {
        $countUsersEliminated = $user
            ->where('profile_id', User::TEACHER_PROFILE)
            ->onlyTrashed()
            ->count();
        return $this->showOther($countUsersEliminated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $isUser = $user->where('id', $user->id)
            ->where('profile_id', User::TEACHER_PROFILE)
            ->count();
        if($isUser == 0){
            return $this->errorResponse("El usuario ingresado no es docente", 422);
        }
        $user->delete();
        return $this->showOne($user);
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->where('id', $id)
            ->where('profile_id', User::TEACHER_PROFILE)
            ->first();
            $user->restore();
        return $this->showOne($user);
    }
    public function checkEmail(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:users',
        ];
        $json = $request->input('json', null);
        if (!Empty($json)){
            $params_array = array_map('trim', json_decode($json, true));
            if (!Empty($params_array)){
                $validate = $this->checkValidation($params_array, $rules);
                if ($validate->fails()){
                    return $this->showOther(1);
                }else{
                    return $this->showOther(0);
                }
            }else{
                return $this->errorResponse('Datos Vacios!', 422);
            }
        }else{
            return $this->errorResponse('La estrucutra del json no es valida', 422);
        }
    }
    public function verify($token)
    {
        $user = User::where('verification_token',$token)->firstOrFail();
        $user->verified = User::VERIFIED_USER;
        $user->status =  User::ACTIVE_STATE;
        $user->verification_token = null;
        $user->save();
        return $this->showMessage('Correo validado con exito.');
    }
}
