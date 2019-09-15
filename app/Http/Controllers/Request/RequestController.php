<?php

namespace App\Http\Controllers\Request;

use App\AttachmentRequest;
use App\Http\Controllers\ApiController;
use App\Request as AppRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use lluminate\Support\Facades\File;
use Tymon\JWTAuth\JWTAuth;

class RequestController extends ApiController
{
    private $rules = array(
        'title' => 'required|max:200',
        'description' => 'max:500',
        'request_type_id' => 'required|integer',
        'program_id' => 'required|integer',
        'student_id' => 'required|integer',
    );
    private $updateRules = array(
        'title' => 'required|max:200',
        'description' => 'max:500',
    );

    public function index()
    {
        $student = auth()->user();
        $_requests = AppRequest::where('student_id', $student->id)
            ->with('program.coordinator')
            ->get();
        return $this->showAll($_requests);
    }

    public function show(AppRequest $request)
    {
        $request->load(['student','responses','attachments']);
        return $this->showOne($request);
    }

    public function uploadFiles(Request $request)
    {
        $attachments = array();
        $student = auth()->user();
        $files = $request->allFiles();
        if ($files) {
            foreach ($files as $file) {
                $attachmentRequest = new AttachmentRequest();

                $fileName = time() . $file->getClientOriginalName();
                $attachmentRequest->name = $fileName;
                if (!Storage::disk('upload')->exists($student->id_num)) {
                    Storage::disk('upload')->makeDirectory($student->id_num);
                }
                Storage::disk('upload')->put($student->id_num . '/' . $fileName, \File::get($file));
                $attachmentRequest->route = 'upload/' . $student->id_num . '/' . $fileName;
                $attachments[] = $attachmentRequest;
            }
            return $this->showOther($attachments);

        } else {
            return $this->showMessage('error al subi archivos', 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        if (!Empty($json)) {
            // $params_array = array_map('trim', json_decode($json, true));
            $params_array = json_decode(trim($json), true);

            if (!Empty($params_array)) {
                $validate = $this->checkValidation($params_array, $this->rules);
                if ($validate->fails()) {
                    return $this->errorResponse("datos no validos", $validate->errors());
                } else {
                    $response = new AppRequest;
                    $response->title = $params_array['title'];
                    $response->description = $params_array['description'];
                    $response->status = 'abierta';
                    $response->request_type_id = $params_array['request_type_id'];
                    $response->program_id = $params_array['program_id'];
                    $response->student_id = $params_array['student_id'];

                    DB::transaction(function () use ($params_array) {
                        $response = AppRequest::create($params_array);
                        $requestId = $response->id;
                        if (Arr::has($params_array, 'attachments')) {
                            $length = count($params_array['attachments']);
                            for ($i = 0; $i < $length; $i++) {
                                $params_array['attachments'][$i]['request_id'] = $requestId;
                                AttachmentRequest::create($params_array['attachments'][$i]);
                            }
                        }
                    });
                    return $this->showOne($response);
                }
            } else {
                return $this->errorResponse('Datos Vacios!', 422);
            }
        } else {
            return $this->errorResponse('La estrucutra del json no es valida', 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param \App\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request1, AppRequest $request)
    {
        $json = $request1->input('json', null);
        if (!Empty($json)) {
            $params_array = array_map('trim', json_decode($json, true));
            if (!Empty($params_array)) {
                $validate = $this->checkValidation($params_array, $this->updateRules);
                if ($validate->fails()) {
                    return $this->errorResponse("datos no validos", $validate->errors());
                } else {
                    $request->title = $params_array['title'];
                    $request->description = $params_array['description'];
                    if ($request->isDirty()) {
                        return $this->errorResponse('se debe especificar al menos un valor', 422);
                    }
                    $request->save();
                    return $this->showOne($request);
                }
            } else {
                return $this->errorResponse('Datos Vacios!', 422);
            }
        } else {
            return $this->errorResponse('La estrucutra del json no es valida', 422);
        }
    }

}
