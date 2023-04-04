<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Spot;
use App\Models\Vaccine;
use App\Models\SpotVaccine;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function spots()
    {
        $spots = Spot::where('regional_id', auth()->user()->regional_id)->get();
        $vaccines = Vaccine::all();
        foreach ($spots as $s) {
            foreach ($vaccines as $v) {
                $v->ketersediaan = SpotVaccine::where(['spot_id' => $s->id, 'vaccine_id' => $v->id])->exists();
            }
            $available_vaccines = $vaccines->pluck('ketersediaan', 'name')->mapWithKeys(function ($value, $key) {
                return [$key => $value];
            });
            $s->available_vaccines = $available_vaccines;
        }

        return response()->json([
            'spots' => $spots
        ]);
    }

    public function showSpots($id)
    {
        $spot = Spot::where('id', $id)->first();
        $spot->makeHidden('vaccinations');

        return response()->json([
            'date' => Carbon::parse(request()->input('date'))->format('F d, Y') ?? Carbon::now()->format('F d, Y'),
            'spot' => $spot,
            'vaccinations_count' => $spot->vaccinations->where('date', '=', request()->input('date'))->count()
        ]);
    }
}
