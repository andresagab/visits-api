<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{

    /**
     * Envíar una respuesta de éxito
     * @param $result => array|collection|JsonResource con el resultado de la petición
     * @param string $message => string con el mensaje de la petición
     * @return \Illuminate\Http\JsonResponse
     */
    public function send_response($result, string $message)
    {
        # definir el formato de la respuesta
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if (isset($result['data']))
            $response['data'] = $result['data'];
        else
            $response['data'] = $result;

        if (isset($result['meta']))
            $response['meta'] = $result['meta'];

        if (isset($result['links']))
            $response['links'] = $result['links'];

        return response()->json($response, 200);
    }

    /**
     * Envíar una respuesta de error
     * @param $error => string con el mensaje de la petición
     * @param $errorMessages => Lista de errores con clave y valor
     * @param int $code => HTTP Status Code, default 404 (Valor o registro no encontrado)
     * @return \Illuminate\Http\JsonResponse
     */
    public function send_error($error, $errorMessages = [], int $code = 404)
    {
        # definir el formato de la respuesta
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages))
            $response['data'] = $errorMessages;

        return response()->json($response, $code);
    }

}
