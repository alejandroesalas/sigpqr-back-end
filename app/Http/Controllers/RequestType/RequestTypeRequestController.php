<?php

namespace App\Http\Controllers\RequestType;

use App\Http\Controllers\ApiController;
use App\RequestType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Program;
use Illuminate\Http\Response;

class RequestTypeRequestController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param RequestType $requestType
     * @return Response
     */
    public function index(RequestType $requestType)
    {
        $coordinator = auth()->user();
        $program = Program::where('coordinator_id', $coordinator->id)
            ->first();
        $requests = $requestType->requests()
            ->where('program_id', $program->id)
            ->get();
        return $this->showAll($requests);
    }

    public function showByStudent(RequestType $requestType)
    {
        $student = auth()->user();
        // $program = Program::where('coordinator_id', $student->id)
        //     ->first();
        //dd($student->id);
        $requests = $requestType->requests()
            ->where('student_id', $student->id)
            ->with('program')
            ->with('program.coordinator')
            ->get();
        return $this->showAll($requests);
    }

}
