<?php

namespace App\Http\Controllers\Program;

use App\Program;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProgramRequestController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Program $program)
    {
        $programRequest = $program->requests()
            ->with('responses')
            ->get();
        return $this->showAll($programRequest);
    }

}
