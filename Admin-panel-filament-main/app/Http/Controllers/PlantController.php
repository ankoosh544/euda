<?php

namespace App\Http\Controllers;
use App\Models\Plant;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    public function store(StorePlantRequest $request)
    {
        abort_unless(\Gate::allows('plant_create'), 403);
        $plant = Plant::create($request->all());
        return redirect()->route('admin.plants.index');
    }
}
