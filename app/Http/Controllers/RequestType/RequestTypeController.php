<?php

namespace App\Http\Controllers\RequestType;

use App\Http\Controllers\ApiController;
use App\RequestType;
use App\User;
use App\Program;
use App\Request as AppRequest;
use Illuminate\Http\Request;

class RequestTypeController extends ApiController
{
    public function __construct()
    {
        //$this->middleware('auth:api');
        //$this->middleware('auth',['except'=>['auth/login']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->showAll(RequestType::all());
    }


    public function countRequestType(RequestType $requestType)
    {
        $requestTypeId = $requestType->id;
        $user = auth()->user();
        $userId = $user->id;
        $result = 0;
        if($user->profile_id == User::STUDENT_PROFILE) {
            $result = AppRequest::where('student_id', $userId)
                ->where('request_type_id', $requestTypeId)
                ->count();
        } else {
            $program = Program::where('coordinator_id', $userId)->first();
            $result = AppRequest::where('program_id', $program->id)
                ->where('request_type_id', $requestTypeId)
                ->count();
        }
        return  $this->showOther($result);
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
            'type' => 'required',
            'description' => 'required',
        ];
        $json = $request->input('json', null);
        if (!Empty($json)){
            $params_array = array_map('trim', json_decode($json, true));
            if (!Empty($params_array)){
                $validation = $this->checkValidation($params_array, $rules);
                if ($validation->fails()){
                    return $this->errorResponse("datos no validos", 404, $validation->errors());
                }else{
                    $requestType = RequestType::create($params_array);
                    return $this->showOne($requestType);
                }
            }else{
                return $this->errorResponse('Datos Vacios!',400);
            }
        }else{
            return $this->errorResponse('La estrucutra del json no es valida',400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RequestType  $requestType
     * @return \Illuminate\Http\Response
     */
    public function show(RequestType $requestType)
    {
        return $this->showOne($requestType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RequestType  $requestType
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequestType $requestType)
    {
        $requestType->delete();
        return $this->showOne($requestType);
    }
}
