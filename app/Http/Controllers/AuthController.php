<?php

namespace App\Http\Controllers;

use App\Mail\passwordReset;
use App\User;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends ApiController
{
    public $tokens;

    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['login']]);
        //$this->middleware('auth',['except'=>['auth/login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        $count = $this->verifiedUser($credentials);
        if($count == 0){
            return response()->json(['error' => 'Correo y/o contraseña invalidos'], 401);
        }
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Correo y/o contraseña invalidos'], 401);
        }
        return $this->respondWithToken($token);
    }
    /**
     * Get credentials array.
     *
     * @param array $credentials
     *
     * @return int
     */
    protected function verifiedUser($credentials)
    {
        return User::where('email', $credentials['email'])
            ->where('verified', '1')
            ->count();
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    public function payload()
    {
        return response()->json(auth()->payload());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }
    public function sendResetLinkEmail(Request $request)
    {
       $user = $this->validateEmail($request);
        Mail::to($request->email)->send(new passwordReset($this->tokens->create($user)));
        return $this->showMessage('Link enviado con exito');
        //mandar correo  cambio de contraseña
    }
    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;
            //verificar que exista un usuario con el correo dado.
        $user = User::where('email',$email)->firstOrFail();
        return $user;
    }
}
