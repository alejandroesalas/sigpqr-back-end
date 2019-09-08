<?php

namespace App\Http\Controllers\Student;

use App\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\ApiController;
use App\Request as AppRequest;
use App\User;

class StudentRequestController extends ApiController
{
    /**
     *Devuelve las solicitudes que haya realizado el estudiante.
     * adicional envia las respuestas que la solicitud tenga
     *
     * @return Response
     */
    public function index(Student $student)
    {
        $studentRequest = $student->requests()
            ->with('responses')
            ->get();
        return $this->showAll($studentRequest);
    }

    /**
     * Crea una nueva solicitud asociada al estudiante
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request, Student $student)
    {
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'request_type_id' => 'required',
        ];

        $json = $request->input('json', null);

        if (!Empty($json)){
            $params_array = array_map('trim', json_decode($json, true));
            if (!Empty($params_array)){
                $validation = $this->checkValidation($params_array, $rules);
                if ($validation->fails()){
                    return $this->errorResponse("datos no validos", $validation->errors(), 404);
                }else{
                    $isStudent = $student->where('id', $student->id)
                        ->where('profile_id', User::STUDENT_PROFILE)
                        ->count();
                    if($isStudent == 0) {
                        return $this->errorResponse("Este usuario no es estudiante", null,404);
                    }
                    $params_array['status'] = 'true';
                    $params_array['student_id'] = $student->id;
                    $params_array['program_id'] = $student->program_id;
                    $requests = AppRequest::create($params_array);
                    return $this->showOne($requests);
                }
            }else{
                return $this->errorResponse('Datos Vacios!',400);
            }
        }else{
            return $this->errorResponse('La estrucutra del json no es valida',400);
        }
    }

    /**
     * Devuelve una solicitud en particular, que haya realizado el estudiante.
     * adicional envia las respuestas que la solicitud tenga.
     *
     * @param  \App\Student  $student
     * @return Response
     */
    public function show(Student $student, AppRequest $request)
    {
        $studentRequest = $student->requests()
            ->where('id', $request->id)
            ->with('responses')
            ->first();
        return $this->showOne($studentRequest);
    }


    /**
     * Actualiza los datos de una solicitud en particulas.
     * Campos modificables: titulo y descripcion, tipo de solicitud.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return Response
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Elimina una solicitud en particular solo si la solicitud se encuentra abierta y
     * no tenga respuestas asociadas
     *
     * @param  \App\Student  $student
     * @return Response
     */
    public function destroy(Student $student)
    {
        //
    }
}
