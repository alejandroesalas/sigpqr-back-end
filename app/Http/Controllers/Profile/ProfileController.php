<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\ApiController;
use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SebastianBergmann\Environment\Console;

class ProfileController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['usersByProfile','index']]);
        //$this->middleware('auth',['except'=>['auth/login']]);
    }
    private $rules =array(
        'name'=>'required','description'=>'max:200'
    );
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $profiles = Profile::all();
        return $this->showAll($profiles);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        if (!Empty($json)){
            $params_array = array_map('trim', json_decode($json, true));
            if (!Empty($params_array)){
                $validate = $this->checkValidation($params_array,$this->rules);
                if ($validate->fails()){
                    return $this->errorResponse("datos no validos",$validate->errors());
                }else{
                    $profile = Profile::create($params_array);
                    return $this->showOne($profile);
                }
            }else{
                return $this->errorResponse('Datos Vacios!',422);
            }
        }else{
            return $this->errorResponse('La estrucutra del json no es valida',422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Profile $profile
     * @return Response
     */
    public function show(Profile $profile)
    {
        return $this->showOne($profile);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Profile $profile
     * @return Response
     */
    public function update(Request $request, Profile $profile)
    {
        $json = $request->input('json', null);
        if (!Empty($json)){
            $params_array = array_map('trim', json_decode($json, true));
            if (!Empty($params_array)){
                $validate = $this->checkValidation($params_array,$this->rules);
                if ($validate->fails()){
                    return $this->errorResponse("datos no validos",$validate->errors());
                }else{
                    $profile->name = $params_array['title'];
                    $profile->description = $params_array['description'];
                    if($profile->isDirty()){
                        return $this->errorResponse('se debe especificar al menos un valor',422);
                    }
                    $profile->save();
                    return $this->showOne($profile);
                }
            }else{
                return $this->errorResponse('Datos Vacios!',422);
            }
        }else{
            return $this->errorResponse('La estrucutra del json no es valida',422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Profile $profile
     * @return Response
     */
    public function destroy(Profile $profile)
    {
        $profile->delete();
        return $this->showOne($profile);
    }

    public function usersByProfile($id){
        $profile = Profile::findOrFail($id);
        return $this->showAll($profile->users);
       // return $this->showAll($profile->users());
    }
}
