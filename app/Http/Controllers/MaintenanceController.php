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
            'user_id' => 'required|exists:users,id',
            'evidence_photo' => 'nullable|image|max:2048',
        ]);
    
        if ($request->hasFile('evidence_photo')) {
            $path = $request->file('evidence_photo')->store('evidence_photos', 'public');
            $validated['evidence_photo'] = $path;
        }
    
        $maintenance = Maintenance::create($validated);
        Vehicle::find($validated['vehicle_id'])->update(['status' => 'maintenance']);
    
        return response()->json($maintenance, 201);
    }

    
    public function update(Request $request, $id)
    {

    $validated = $request->validate([
        'status' => 'nullable|in:Completed,Need Repair',
        'mechanic_id' => 'required|exists:mechanics,id',
        'evidence_photo' => 'nullable|image|max:2048',
    ]);
 
    $maintenance = Maintenance::findOrFail($id);

    if ($request->hasFile('evidence_photo')) {
        $path = $request->file('evidence_photo')->store('evidence_photos', 'public');
        $maintenance->evidence_photo = $path;
    }

    $maintenance->mechanic_id = $validated['mechanic_id'];
    $maintenance->status = 'On Going';

    if ($validated['status'] === 'Need Repair') {
        $maintenance->status = 'Need Repair';
        Vehicle::find($maintenance->vehicle_id)->update([
            'status' => 'unavailable',
        ]);
    } elseif ($validated['status'] === 'Completed') {
        $maintenance->status = 'Completed';
        Vehicle::find($maintenance->vehicle_id)->update([
            'status' => 'available',
            'last_maintenance_date' => now(),
        ]);
    }

    $maintenance->save();

    return response()->json($maintenance, 200);
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

