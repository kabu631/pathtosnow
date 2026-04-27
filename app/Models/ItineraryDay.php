<?php
// app/Models/ItineraryDay.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ItineraryDay extends Model
{
    protected $fillable = [
        'package_id','day_number','title','description','accommodation',
        'meals','distance_km','altitude_m','elevation_gain_m','elevation_loss_m',
        'place_name','notes',
    ];
    protected $casts = ['meals' => 'array'];

    public function package() { return $this->belongsTo(Package::class); }
}
