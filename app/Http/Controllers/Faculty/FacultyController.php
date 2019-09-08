<?php

namespace App\Http\Controllers\Faculty;

use App\Faculty;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\ApiController;

class FacultyController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
        //$this->middleware('auth',['except'=>['auth/login']]);
    }
    private $rules =array(
        'name'=>'required'
    );
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $faculty = Faculty::all()->load('programs');
        return $this->showAll($faculty);
    }

    public function countFaculties()
    {
        $countFaculties = Faculty::count();
        return $this->showOther($countFaculties);
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
                    return $this->errorResponse("datos no validos", 400, $validate->errors());
                }else{
                    $faculty = Faculty::create($params_array);
                    return $this->showOne($faculty);
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
     * @param Faculty $faculty
     * @return Response
     */
    public function show(Faculty $faculty)
    {
        return $this->showOne($faculty);
    }

    /**
     * Update the specified resource in storage.*
     * @param Request $request
     * @param Faculty $faculty
     * @return Response
     */
    public function update(Request $request, Faculty $faculty)
    {
        $json = $request->input('json', null);
        if (!Empty($json)){
            $params_array = array_map('trim', json_decode($json, true));
            if (!Empty($params_array)){
                $validate = $this->checkValidation($params_array,$this->rules);
                if ($validate->fails()){
                    return $this->errorResponse("datos no validos", 400, $validate->errors());
                }else{
                    $faculty->name = $params_array['name'];
                    if(!$faculty->isDirty()){
                        return $this->errorResponse('se debe especificar al menos un valor','',422);
                    }
                    $faculty->save();
                    return $this->showOne($faculty);
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
     * @param Faculty $faculty
     * @return Response
     * @throws Exception
     */
    public function destroy(Faculty $faculty)
    {
        $faculty->delete();
        return $this->showOne($faculty);
    }

    /**
     * Devuelve los programas existentes en una facultad.
     * @param $id
     * @return JsonResponse
     */
    public function facultyprograms($id){
        $faculties = Faculty::findOrFail($id);
        return $this->showAll($faculties->programs);
    }

    /**
     * @param $id
     * @return JsonResponse
     * Devuele los estudiantes pertenecientes a una facultad.
     */
    public function facultyUsers($id){
        $faculties = Faculty::findOrFail($id);
        $students = $faculties->programs()->with(['students'=>function($query){
            $query->where('profile_id','=',3);
        }])
            ->get()
        ->pluck('students');
        return $this->showAll($students);
    }
}
