<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        return Maintenance::all();
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'vehicle_id' => 'required|exists:vehicles,id',
        'user_id' => 'required|exists:users,id',
        'tipe_maintenance' => 'required|string|max:255',
        'note' => 'nullable|string',
        'date' => 'required|date',
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
    
        $user = null;
        if ($validated['status'] === 'Need Repair') {
            $maintenance->status = $validated['status'];
            $user = User::find($maintenance->user_id);
            if ($user) {
                $vehicle = Vehicle::find($user->vehicle_id);
                if ($vehicle) {
                    $vehicle->update([
                        'status' => 'unavailable',
                    ]);
                } else {
                    return response()->json(['error' => 'Vehicle not found'], 404);
                }
            } else {
                return response()->json(['error' => 'User not found'], 404);
            }
        } elseif ($validated['status'] === 'Completed') {
            $maintenance->status = $validated['status'];
            $user = User::find($maintenance->user_id);
            if ($user) {
                $vehicle = Vehicle::find($user->vehicle_id);
                if ($vehicle) {
                    $vehicle->update([
                        'status' => 'available',
                        'last_maintenance_date' => now(),
                    ]);
                } else {
                    return response()->json(['error' => 'Vehicle not found'], 404);
                }
            } else {
                return response()->json(['error' => 'User not found'], 404);
            }
        }        
    
        $maintenance->save();
    
        return response()->json($maintenance, 200);
    }

    

public function show(Request $request, $id)
{
    // Validasi status dari request, status boleh null
    $validated = $request->validate([
        'status' => 'nullable|string',  // status bisa berupa string, dan boleh null
    ]);

    $maintenance = null;
    // Jika status ada di request, maka filter berdasarkan status
    if ($validated['status'] !== null) {
        $maintenance = Maintenance::where('status', $validated['status'])->get();
    } else {
        // Jika status tidak diberikan (null), ambil data berdasarkan id vehicle
        $maintenance = Maintenance::where('user_id', $id)->get();
    }

    return response()->json($maintenance);
}
    

    public function destroy($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $maintenance->delete();
        return response()->json(['message' => 'Maintenance record deleted successfully'], 200);
    }
}

