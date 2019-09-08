<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
//ruta del controlador de auth para login
Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('payload', 'AuthController@payload');
});
//rutas para restablecimiento de contraseñas
Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('password/reset','Auth\ResetPasswordController@reset');
//ruta del controlador de facultades
Route::apiResource('faculties','Faculty\FacultyController');
Route::resource('faculties.programs','Faculty\FacultyProgramController', ['only' => ['index', 'update']]);
//Route::get('faculties/{id}/students','Faculty\FacultyController@facultyUsers');
Route::get('count-faculties','Faculty\FacultyController@countFaculties');
//ruta del controlador de programas
Route::apiResource('programs','Program\ProgramController');
Route::get('only-programs-trashed','Program\ProgramController@onlyTrashed');
Route::post('restore-program/{id}','Program\ProgramController@restore');
Route::get('count-programs-eliminated','Program\ProgramController@countProgramsEliminated');
Route::get('unassigned-programs','Program\ProgramController@showUnassignedPrograms');
Route::get('programs/{id}/faculties','Program\ProgramController@faculty');
Route::get('programs/{id}/coordinators','Program\ProgramController@getCoordinator');
Route::get('programs/{id}/students','Program\ProgramController@getStudents');
//Route::get('programs/{id}/programs','Program\ProgramController@getRequests');
Route::resource('programs.requests','Program\ProgramRequestController', ['only' => ['index']]);
Route::get('count-programs','Program\ProgramController@countPrograms');
//ruta del controlador de perfiles
Route::apiResource('profiles','Profile\ProfileController');
//mostrar usuarios de un perfil especifico
Route::get('profiles/{id}/users','Profile\ProfileController@usersByProfile');
//Ruta para las requestsType(tipos de solicitudes)
Route::resource('request-types','RequestType\RequestTypeController');
//ruta del controlador de Estudiantes
Route::resource('students', 'Student\StudentController', ['except' => ['create', 'edit']]);
Route::get('count-students','Student\StudentController@countStudents');
Route::resource('students.requests','Student\StudentRequestController', ['except' => ['create', 'edit']]);
//soft deleting students
Route::get('only-students-trashed','Student\StudentController@onlyTrashed');
Route::post('restore-student/{id}','Student\StudentController@restore');
Route::get('count-students-eliminated','Student\StudentController@countStudentsEliminated');
//ruta del controlador de Coordinadores
Route::resource('coordinators', 'Coordinator\CoordinatorController', ['except' => ['create', 'edit']]);
Route::put('degradeCoordinator/{coordinator}', 'Coordinator\CoordinatorController@degrade');
Route::resource('coordinators.responses', 'Coordinator\CoordinatorResponseController', ['except' => ['create', 'edit']]);
Route::get('count-coordinators','Coordinator\CoordinatorController@countCoordinators');
Route::get('count-teachers','User\UserController@countTeachers');
//ruta del controlador de usuarios
Route::resource('users', 'User\UserController', ['except' => ['create', 'edit']]);
Route::post('check-email', 'User\UserController@checkEmail');
Route::put('ascent-users/{user}', 'User\UserController@ascent');
//soft deleting teachers
Route::get('only-teachers-trashed','User\UserController@onlyTrashed');
Route::post('restore-teacher/{id}','User\UserController@restore');
Route::get('count-teachers-eliminated','User\UserController@countTeachersEliminated');
//Verificacion del correo del usuario
Route::name('verify')->get('users/verify/{token}','User\UserController@verify');
Route::resource('request-types.requests','RequestType\RequestTypeRequestController', ['only' => ['index']]);
Route::get('student-request-types/{request_type}/requests','RequestType\RequestTypeRequestController@showByStudent');

//Rutas para el requests
Route::post('requests/uploadFiles','Request\RequestController@uploadFiles');
Route::resource('requests','Request\RequestController',['only'=>['index','show','store', 'update']]);
Route::resource('request-types.requests','RequestType\RequestTypeRequestController', ['only' => ['index']]);
Route::get('student-request-types/{request_type}/requests','RequestType\RequestTypeRequestController@showByStudent');
Route::resource('responses','Response\ResponseController', ['only' => ['store', 'update']]);
/*Route::post('/api/users/upload','PostController@upload');
Route::get('/api/post/avatar/{filename}','PostController@getImage');
Route::get('/api/post/category/{id}','PostController@getPostsByCategory');
Route::get('/api/post/user/{id}','PostController@getPostsByUser');*/
