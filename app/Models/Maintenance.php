<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;
    
    protected $fillable = ['vehicle_id', 'user_id', 'mechanic_id', 'status', 'evidence_photo'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
