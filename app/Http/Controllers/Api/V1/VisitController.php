<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\V1\VisitCollection;
use App\Http\Resources\V1\VisitResource;
use App\Models\Visit;
use Illuminate\Http\Request;

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
        //
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
    public function update(Request $request, Visit $visit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visit $visit)
    {
        //
    }
}
