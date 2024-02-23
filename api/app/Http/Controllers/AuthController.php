<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use JWTAuth;

class AuthController extends Controller
{
    public function signup(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
                'role' => 'required|string',
            ]);

            if($validator->fails()){
                    return response()->json($validator->errors()->toJson(), 400);
            }

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json(compact('user','token'),201);
        }

        public function authenticate(Request $request)
        {
            $credentials = $request->only('email', 'password');
            
            // Recuperar el usuario de la base de datos
            $user = User::where('email', $credentials['email'])->first();
        
            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }
        
            // Verificar si el password proporcionado coincide con el almacenado en la base de datos
            if (!Hash::check($credentials['password'], $user->password)) {
                return response()->json(['error' => 'Credenciales inválidas'], 401);
            }
        
            try {
                // Autenticar el usuario y generar el token JWT
                if (!$token = JWTAuth::fromUser($user)) {
                    return response()->json(['error' => 'No se pudo generar el token'], 500);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'No se pudo crear el token'], 500);
            }
        
            // Devolver el token JWT
            return response()->json(compact('token'));
        }
        
    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                    return response()->json(['token_expired'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                    return response()->json(['token_invalid'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                    return response()->json(['token_absent'], $e->getStatusCode());
            }
            return response()->json(compact('user'));
    }

}
