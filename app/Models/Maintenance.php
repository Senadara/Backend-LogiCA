<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;
    
    protected $fillable = [ 'user_id', 'mechanic_id', 'status', 'evidence_photo', 'tipe_maintenance', 'date', 'note'];

}
