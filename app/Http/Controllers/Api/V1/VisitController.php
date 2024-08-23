<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\V1\VisitCollection;
use App\Http\Resources\V1\VisitResource;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API de visitas de clientes",
 *     version="1.0.0",
 *     description="API para gestión de visitas de clientes.",
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api/v1",
 * )
 */
class VisitController extends BaseController
{

    /**
     * @OA\Schema(
     *     schema="Visit",
     *     type="object",
     *     title="Visita",
     *     required={"name", "email", "latitude", "longitude"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="John Doe"),
     *     @OA\Property(property="email", type="string", example="john@example.com"),
     *     @OA\Property(property="latitude", type="number", format="float", example=34.0522),
     *     @OA\Property(property="longitude", type="number", format="float", example=-118.2437),
     *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-23T00:00:00.000000Z"),
     *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-23T00:00:00.000000Z")
     * )
     */


    /**
     * @OA\Get(
     *     path="/visits",
     *     summary="Obtener todos los registros.",
     *     tags={"Visits"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Número de registros por página",
     *          required=false,
     *          @OA\Schema(type="integer", example=15)
     *      ),
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Página a mostrar",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     * @OA\Response(
     *     response=200,
     *     description="Lista de visitas.",
     *     @OA\JsonContent(
     *          type="array",
     *          @OA\Items(
     *              @OA\Schema(ref="#/components/schemas/Visit")
     *          )
     *      )
     * ),
     * @OA\Response(
     *     response=401,
     *     description="No autorizado. Token no proporcionado o inválido."
     * ),
     * @OA\Response(
     *     response=500,
     *     description="Error del servidor."
     *      )
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $per_page = $request->query('per_page', 15);
        $data = Visit::query()->paginate($per_page);
        $collection = new VisitCollection($data);

        return $this->send_response($collection->response()->getData(true), 'Registros cargados exitosamente.');
    }

    /**
     * @OA\Post(
     *      path="/visits",
     *      summary="Crear un nuevo registro.",
     *      tags={"Visits"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email", "latitude", "longitude"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", example="john@example.com"),
     *              @OA\Property(property="latitude", type="number", format="float", example=34.0522),
     *              @OA\Property(property="longitude", type="number", format="float", example=-118.2437)
     *          )
     *      ),
     *      @OA\Response(
     *           response=200,
     *           description="Registro creado exitosamente.",
     *           @OA\JsonContent(
     *                  @OA\Property(property="name", type="string", example="John Doe"),
     *                  @OA\Property(property="email", type="string", example="john@example.com"),
     *                  @OA\Property(property="latitude", type="number", format="float", example=34.0522),
     *                  @OA\Property(property="longitude", type="number", format="float", example=-118.2437)
     *           )
     *      ),
     *      @OA\Response(
     *           response=400,
     *           description="Error de validación.",
     *           @OA\JsonContent(
     *               @OA\Property(property="error", type="string", example="Error de validación."),
     *           )
     *      )
     *  )
     *
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        # obtener datos
        $input = $request->all();

        # validate data
        $validator = Validator::make($input, [
            'name' => 'required|string|max:200',
            'email' => 'required|email|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails())
            return $this->send_error("Error de validación.", $validator->errors(), 400);

        # crear registro
        $visit = Visit::create($input);

        return $this->send_response(new VisitResource($visit), 'Registro creado exitosamente.');
    }

    /**
     * @OA\Get(
     *      path="/visits/{id}",
     *      summary="Obtener un registro específico.",
     *      tags={"Visits"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Response(
     *           response=200,
     *           description="Registro encontrado.",
     *           @OA\JsonContent(
     *                  @OA\Property(property="name", type="string", example="John Doe"),
     *                  @OA\Property(property="email", type="string", example="john@example.com"),
     *                  @OA\Property(property="latitude", type="number", format="float", example=34.0522),
     *                  @OA\Property(property="longitude", type="number", format="float", example=-118.2437)
     *           )
     *      ),
     *      @OA\Response(
     *           response=404,
     *           description="Registro no encontrado.",
     *           @OA\JsonContent(
     *               @OA\Property(property="error", type="string", example="Registro no encontrado.")
     *           )
     *      )
     *  )
     *
     * Display the specified resource.
     */
    public function show($id)
    {
        $visit = Visit::find($id);

        if (empty($visit))
            return $this->send_error('Registro no encontrado.');

        return $this->send_response(new VisitResource($visit), 'Registro encontrado.');
    }

    /**
     * @OA\Put(
     *      path="/visits/{id}",
     *      summary="Actualizar un registro existente.",
     *      tags={"Visits"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email", "latitude", "longitude"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", example="john@example.com"),
     *              @OA\Property(property="latitude", type="number", format="float", example=34.0522),
     *              @OA\Property(property="longitude", type="number", format="float", example=-118.2437)
     *          )
     *      ),
     *      @OA\Response(
     *           response=200,
     *           description="Registro actualizado exitosamente.",
     *           @OA\JsonContent(
     *                  @OA\Property(property="name", type="string", example="John Doe"),
     *                  @OA\Property(property="email", type="string", example="john@example.com"),
     *                  @OA\Property(property="latitude", type="number", format="float", example=34.0522),
     *                  @OA\Property(property="longitude", type="number", format="float", example=-118.2437)
     *           )
     *      ),
     *      @OA\Response(
     *           response=400,
     *           description="Error de validación.",
     *           @OA\JsonContent(
     *               @OA\Property(property="error", type="string", example="Error de validación."),
     *           )
     *      ),
     *      @OA\Response(
     *           response=404,
     *           description="Registro no encontrado.",
     *           @OA\JsonContent(
     *               @OA\Property(property="error", type="string", example="Registro no encontrado.")
     *           )
     *      )
     *  )
     *
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        # cargar visita
        $visit = Visit::query()->find($id);

        # si el registro no existe, enviar error
        if (empty($visit))
            return $this->send_error('Registro no encontrado, no es posible continuar con la petición.');

        # obtener datos
        $input = $request->all();

        # validar datos
        $validator = Validator::make($input, [
            'name' => 'required|string|max:200',
            'email' => 'required|email|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails())
            return $this->send_error("Error de validación.", $validator->errors(), 400);

        # modificar campos
        $visit->name = $input['name'];
        $visit->email = $input['email'];
        $visit->latitude = $input['latitude'];
        $visit->longitude = $input['longitude'];
        $visit->save();

        return $this->send_response(new VisitResource($visit), 'Registro actualizado exitosamente.');
    }

    /**
     * @OA\Delete(
     *      path="/visits/{id}",
     *      summary="Eliminar un registro existente.",
     *      tags={"Visits"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Response(
     *           response=200,
     *           description="Registro eliminado exitosamente.",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Registro eliminado exitosamente.")
     *           )
     *      ),
     *      @OA\Response(
     *           response=404,
     *           description="Registro no encontrado.",
     *           @OA\JsonContent(
     *               @OA\Property(property="error", type="string", example="Registro no encontrado.")
     *           )
     *      )
     *  )
     *
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        # cargar visita
        $visit = Visit::query()->find($id);

        # si el registro no existe, enviar error
        if (empty($visit))
            return $this->send_error('Registro no encontrado, no es posible continuar con la petición.');

        # eliminar registro
        $visit->delete();

        return $this->send_response([], 'Registro eliminado exitosamente.');
    }
}
