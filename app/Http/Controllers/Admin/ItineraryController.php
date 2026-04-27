<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\{Package, ItineraryDay};

class ItineraryController extends Controller
{
    public function index(Package $package)
    {
        return Inertia::render('Admin/Packages/Itinerary', [
            'package' => $package->only('id', 'name', 'type', 'duration_days'),
            'days'    => $package->itineraryDays,
        ]);
    }

    public function store(Request $req, Package $package)
    {
        $data = $req->validate([
            'day_number'        => 'required|integer|min:1',
            'title'             => 'required|string|max:255',
            'description'       => 'required|string',
            'accommodation'     => 'nullable|string|max:255',
            'meals'             => 'nullable|array',
            'distance_km'       => 'nullable|integer',
            'altitude_m'        => 'nullable|integer',
            'elevation_gain_m'  => 'nullable|integer',
            'elevation_loss_m'  => 'nullable|integer',
            'place_name'        => 'nullable|string|max:255',
            'notes'             => 'nullable|string',
        ]);
        $package->itineraryDays()->create($data);
        return back()->with('success', 'Day added!');
    }

    public function update(Request $req, Package $package, ItineraryDay $day)
    {
        $day->update($req->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'accommodation'    => 'nullable|string',
            'meals'            => 'nullable|array',
            'distance_km'      => 'nullable|integer',
            'altitude_m'       => 'nullable|integer',
            'elevation_gain_m' => 'nullable|integer',
            'place_name'       => 'nullable|string',
            'notes'            => 'nullable|string',
        ]));
        return back()->with('success', 'Day updated!');
    }

    public function destroy(Package $package, ItineraryDay $day)
    {
        $day->delete();
        return back()->with('success', 'Day removed.');
    }

    public function reorder(Request $req, Package $package)
    {
        foreach ($req->days as $item) {
            ItineraryDay::where('id', $item['id'])->update(['day_number' => $item['day_number']]);
        }
        return back()->with('success', 'Itinerary reordered.');
    }
}
