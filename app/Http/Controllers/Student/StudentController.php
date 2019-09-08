<?php

namespace App\Http\Controllers\Student;

use App\Student;
use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\ApiController;
use App\Profile;

class StudentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Profile $profile)
    {
        $students  = $profile
            ->where('name','=','estudiante')
            ->with('users')
            ->get()
            ->pluck('users')
            ->collapse()
            ->values();
        return $this->showAll($students);
    }

    public function countStudents(Student $student)
    {
        $countStudents = $student
            ->where('profile_id', User::STUDENT_PROFILE)
            ->count();
        return $this->showOther($countStudents);
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
            'lastname'=>'required',
            'email' => 'email|unique:users',
            'password' => 'min:6|confirmed',
            'id_type' => 'required|in:'. User::CC_TYPE . ',' . User::TI_TYPE,
            'id_num' => 'required|unique:users',
            'program_id' => 'required|integer',
        ];
        $json = $request->input('json', null);
        if (!Empty($json)){
            $params_array = array_map('trim', json_decode($json, true));
            if (!Empty($params_array)){
                $validation = $this->checkValidation($params_array,$rules);
                if ($validation->fails()){
                    return $this->errorResponse("datos no validos", $validation->errors(),400);
                }else{
                    $params_array['password'] = bcrypt($params_array['password']);
                    $params_array['profile_id'] = User::STUDENT_PROFILE;
                    $params_array['status'] = User::FALSE_STATE;
                    $params_array['admin'] = User::REGULAR_USER;
                    $params_array['verified']= User::NOT_VERIFIED_USER;
                    $params_array['verification_token'] =User::createVerificationToken();
                    $user = Student::create($params_array);
                    return $this->showOne($user);
                }
            }else{
                return $this->errorResponse('Datos Vacios!', '',400);
            }
        }else{
            return $this->errorResponse('La estrucutra del json no es valida','',415);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        return $this->showOne($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    public function onlyTrashed(Student $student)
    {
        $students = $student->where('profile_id', User::STUDENT_PROFILE)
            ->onlyTrashed()
            ->get();
        return $this->showAll($students);
    }

    public function countStudentsEliminated(Student $student)
    {
        $countStudentsEliminated = $student
            ->where('profile_id', User::STUDENT_PROFILE)
            ->onlyTrashed()
            ->count();
        return $this->showOther($countStudentsEliminated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $isStudent = $student->where('id', $student->id)
            ->where('profile_id', User::STUDENT_PROFILE)
            ->count();
        if($isStudent == 0){
            return $this->errorResponse("El usuario ingresado no es estudiante", 422);
        }
        $student->delete();
        return $this->showOne($student);
    }

    public function restore($id)
    {
        $user = Student::onlyTrashed()->where('id', $id)
            ->where('profile_id', User::STUDENT_PROFILE)
            ->first();
            $user->restore();
        return $this->showOne($user);
    }
}
