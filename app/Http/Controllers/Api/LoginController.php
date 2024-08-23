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
     * @OA\Post(
     *      path="/login",
     *      summary="Login de usuario",
     *      description="Autentica a un usuario y genera un token de acceso. Este token se puede utilizar para la autorización Bearer.",
     *      operationId="loginUser",
     *      tags={"Autenticación"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "password", "device_name"},
     *              @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password123"),
     *              @OA\Property(property="device_name", type="string", example="MyDevice"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Login exitoso. Devuelve un token de acceso.",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string", example="1|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxIiwiaWF0IjoxNjQwMjk4MjA1LCJleHBpcmF0aW9uIjpudWxsLCJleHBpcmF0aW9uX2FjdGl2ZSI6IkFkbWluIiwic3ViX2FjX2F1dG9yIjpudWxsLCJzdWIiOiJhZG1pbiJ9.AF7TFi4kED2W-A9d1lJrZ3sHgMk8wS8fCd9Zz6CykxY"),
     *              @OA\Property(property="message", type="string", example="Login exitoso."),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Credenciales incorrectas.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Credenciales incorrectas."),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Error de validación.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Error de validación."),
     *              @OA\Property(property="errors", type="object", example={"email": "El campo email es obligatorio."}),
     *          )
     *      )
     *  )
     *
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
            return $this->send_error("Error de validación.", $validation->errors(), 400);

        # cargar usuario
        $user = User::query()->where('email', $input['email'])->first();

        # verificar si el usuario existe y la contraseña es correcta
        if ($user && Hash::check($input['password'], $user->password))
        {
            # generar token
            $token = $user->createToken($input['device_name'], expiresAt: now(tz: env('APP_TIMEZONE', 'America/Bogota'))->addDays(7))->plainTextToken;
            return $this->send_response(['token' => $token], 'Login exitoso.');
        }

        return $this->send_error('Credenciales incorrectas.', 401);

    }

}
