<?php

namespace App\Http\Controllers\Coordinator;

use App\Coordinator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Response;

class CoordinatorResponseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        //$this->middleware('auth',['except'=>['auth/login']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Response $response, Coordinator $coordinator)
    {

        $coordinatorResponse = $response
            ->where('coordinator_id', $coordinator->id)
            ->get();
        return $this->showAll($coordinatorResponse);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Coordinator  $coordinator
     * @return \Illuminate\Http\Response
     */
    public function show(Coordinator $coordinator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Coordinator  $coordinator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coordinator $coordinator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Coordinator  $coordinator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coordinator $coordinator)
    {
        //
    }
}
