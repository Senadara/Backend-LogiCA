<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        return Maintenance::with('vehicle')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'status' => 'required|in:pending,completed',
            'evidence_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('evidence_photo')) {
            $path = $request->file('evidence_photo')->store('evidence_photos', 'public');
            $validated['evidence_photo'] = $path;
        }

        $maintenance = Maintenance::create($validated);

        // Update last maintenance date if completed
        if ($validated['status'] === 'completed') {
            Vehicle::find($validated['vehicle_id'])->update(['last_maintenance_date' => now()]);
        }

        return response()->json($maintenance, 201);
    }

    public function show($id)
    {
        return Maintenance::with('vehicle')->findOrFail($id);
    }

    public function destroy($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $maintenance->delete();
        return response()->json(['message' => 'Maintenance record deleted successfully'], 200);
    }
}

