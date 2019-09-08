<?php

namespace App\Http\Controllers\Program;

use App\Coordinator;
use App\Http\Controllers\ApiController;
use App\Program;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProgramController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
        //$this->middleware('auth',['except'=>['auth/login']]);
    }

    private $rules =array(
        'name'=>'required',
        'faculty_id'=>'required|integer',
        'coordinator_id'=>'integer'
    );
    private $updateRules =array(
        'name'=>'required',
    );
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programs = Program::all();
        return $this->showAll($programs);
    }

    public function countPrograms()
    {
        $countPrograms = Program::count();
        return $this->showOther($countPrograms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        if (!Empty($json)){
            $params_array = array_map('trim', json_decode($json, true));
            if (!Empty($params_array)){
                $validate = $this->checkValidation($params_array,$this->rules);
                if ($validate->fails()){
                    return $this->errorResponse("datos no validos", 422, $validate->errors());
                }else{
                    if(Arr::has($params_array, 'coordinator_id')) {
                        $coordinator = Coordinator::findOrFail($params_array['coordinator_id']);
                        if($coordinator->profile->name == 'coordinador'){
                            $program = Program::create($params_array);
                            return $this->showOne($program);
                        }else{
                            return $this->errorResponse('El usuario especificado no es un coordinador',422);
                        }
                    }
                    $program =  Program::create($params_array);
                    return $this->showOne($program);
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
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function show(Program $program)
    {
        return $this->showOne($program);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Program $program)
    {
        $json = $request->input('json', null);
        if (!Empty($json)){
            $params_array = array_map('trim', json_decode($json, true));
            if (!Empty($params_array)){
                $validate = $this->checkValidation($params_array, $this->updateRules);
                if ($validate->fails()){
                    return $this->errorResponse("datos no validos", 422, $validate->errors());
                }else{
                    $program->name = $params_array['name'];
                    if(!$program->isDirty()){
                        return $this->errorResponse('se debe especificar al menos un valor','',422);
                    }
                    $program->save();
                    return $this->showOne($program);
                }
            }else{
                return $this->errorResponse('Datos Vacios!',422);
            }
        }else{
            return $this->errorResponse('La estrucutra del json no es valida',422);
        }
    }

    public function showUnassignedPrograms()
    {
        $programs = Program::doesntHave('coordinator')->get();
       /* $programs = Program::all()->where('coordinator_id', '=',null);
        dd($programs);*/
        return $this->showAll($programs);
    }

    public function onlyTrashed(Program $program)
    {
        $programs = $program->onlyTrashed()
            ->get();
        return $this->showAll($programs);
    }

    public function countProgramsEliminated(Program $program)
    {
        $countProgramsEliminated = $program->onlyTrashed()
            ->count();
        return $this->showOther($countProgramsEliminated);
    }

    public function restore($id)
    {
        $program = Program::onlyTrashed()->where('id', $id)
            ->first();
            $program->restore();
        return $this->showOne($program);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $program)
    {
        DB::transaction(function () use ($program) {
            $idCoordinator = $program->coordinator_id;
            DB::table('users')->where('id', $idCoordinator)
                ->update([
                    'profile_id' => User::TEACHER_PROFILE,
                    'status' => User::FALSE_STATE
                ]);
            DB::table('programs')->where('id', $program->id)
                ->update(['coordinator_id' => null]);
            $program->delete();
        });
        return $this->showOne($program);
    }

    public function getCoordinator(Program $program){
        $user = auth()->user();
        $currentProgram = Program::find($user->program_id);
        if (!$currentProgram){
            return $this->errorResponse("No existe coordinador para el programa $currentProgram->name");
        }
        return  $this->showOne($currentProgram->coordinator);
    }
}
