<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\V1\VisitCollection;
use App\Http\Resources\V1\VisitResource;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->query('per_page', 15);
        $data = Visit::query()->paginate($per_page);
        $collection = new VisitCollection($data);

        return $this->send_response($collection->response()->getData(true), 'Registros cargados exitosamente.');
    }

    /**
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
            return $this->send_error("Error de validación.", $validator->errors());

        # crear registro
        $visit = Visit::create($input);

        return $this->send_response(new VisitResource($visit), 'Registro creado exitosamente.');
    }

    /**
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
            return $this->send_error("Error de validación.", $validator->errors());

        # modificar campos
        $visit->name = $input['name'];
        $visit->email = $input['email'];
        $visit->latitude = $input['latitude'];
        $visit->longitude = $input['longitude'];
        $visit->save();

        return $this->send_response(new VisitResource($visit), 'Registro actualizado exitosamente.');
    }

    /**
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
