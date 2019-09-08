<?php

namespace App\Http\Controllers\Faculty;

use App\Faculty;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class FacultyProgramController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Faculty $faculty)
    {
        $facultyPrograms = $faculty
            ->with('programs')
            ->first();
        return $this->showOne($facultyPrograms);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faculty $faculty)
    {
        //
    }
}
