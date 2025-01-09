<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        return Vehicle::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'license_plate' => 'required|string|max:255|unique:vehicles',
            'status' => 'required|in:available,maintenance,unavailable',
            'last_maintenance_date' => 'nullable|date',
        ]);
    
        $vehicle = Vehicle::create($validated);
        return response()->json($vehicle, 201);
    }
    

    public function show($license_plate)
    {
        $vehicle = Vehicle::where('license_plate', $license_plate)->firstOrFail();
        return response()->json($vehicle, 200);
    }
    
    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::where('license_plate', $id)->firstOrFail();
    
        $validated = $request->validate([
            'license_plate' => 'nullable|string|max:255|unique:vehicles,license_plate,' . $vehicle->id,
            'status' => 'nullable|in:available,maintenance,unavailable',
            'last_maintenance_date' => 'nullable|date',
        ]);

        if($validated['last_maintenance_date'] == null){
            $validated['last_maintenance_date'] = $vehicle->last_maintenance_date;
        }
    
        $vehicle->update($validated);
        return response()->json($vehicle, 200);
    }

    
    

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();
        return response()->json(['message' => 'Vehicle deleted successfully'], 200);
    }
}

