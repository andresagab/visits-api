<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends BaseController
{

    /**
     * Login de usuario que crea un token de acceso.
     * Este puede ser utilizado por Bearer Token Authorization
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        # obtener datos
        $input = $request->only(['email', 'password', 'device_name']);
        # validar datos
        $validation = Validator::make($input, [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        # si hay errores de validación, enviarlos
        if ($validation->fails())
            return $this->send_error("Error de validación.", $validation->errors());

        # cargar usuario
        $user = User::query()->where('email', $input['email'])->first();

        # verificar si el usuario existe y la contraseña es correcta
        if ($user && Hash::check($input['password'], $user->password))
        {
            # generar token
            $token = $user->createToken($input['device_name'], expiresAt: now()->days(7))->plainTextToken;
            return $this->send_response(['token' => $token], 'Login exitoso.');
        }

        return $this->send_error('Credenciales incorrectas.', 401);

    }

}
