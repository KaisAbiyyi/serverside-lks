<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Vaccine;
use App\Models\SpotVaccine;
use App\Models\Vaccination;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ConsultationController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'disease_history' => 'nullable',
            'current_symptoms' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $consultation = new Consultation();
        $consultation->society_id = Auth::id();
        $consultation->disease_history = $request->disease_history;
        $consultation->current_symptoms = $request->current_symptoms;
        $consultation->save();

        return response()->json(['message' => 'Request consultation sent successful'], 200);
    }

    public function index()
    {
        $consultation = Consultation::where('society_id', Auth::id())->with('doctor:id,name')->get();;
        foreach ($consultation as $c) {
            $c->makeHidden(['doctor_id', 'created_at', 'updated_at']);
        }

        return response()->json([
            'consultation' => $consultation
        ]);
    }

    public function storeVaccination(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'spot_id' => 'required',
            'date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 401);
        }

        $consultation = Consultation::where(['society_id' => Auth::id(), 'status' => 'accepted'])->whereNotNull('doctor_id')->first();
        if ($consultation) {
            $vaccination = Vaccination::where(['society_id' => Auth::id(), 'spot_id' => $request->spot_id, 'dose' => 1])->first();
            $vaccination2 = Vaccination::where(['society_id' => Auth::id(), 'dose' => 2])->first();
            $spot_vaccine = SpotVaccine::where('spot_id', $request->spot_id)->first();
            if ($vaccination && $vaccination2) {
                return response()->json([
                    'message' => 'Society has been 2x vaccinated'
                ], 401);
            }
            if (!$vaccination) {
                $vacc = new Vaccination();
                $vacc->dose = 1;
                $vacc->date = $request->date;
                $vacc->society_id = Auth::id();
                $vacc->spot_id = $request->spot_id;
                $vacc->save();
                $dose = 'First';
            } else {
                if ($request->date > Carbon::parse($vaccination->date)->addDays(30)) {
                    $vacc = new Vaccination();
                    $vacc->dose = 2;
                    $vacc->date = $request->date;
                    $vacc->society_id = Auth::id();
                    $vacc->spot_id = $request->spot_id;
                    $vacc->vaccine_id = $vaccination->vaccine_id;
                    $vacc->save();
                    $dose = 'Second';
                } else {
                    return response()->json([
                        'message' => 'Wait at least 30 days from 1st Vaccination'
                    ], 401);
                }
            }
            return response()->json([
                'message' => $dose . ' vaccination registered successfull'
            ]);
        } else {
            return response()->json([
                'message' => 'Your consultation must be accepted by doctor before'
            ], 401);
        }
    }

    public function indexVaccination()
    {
        $vaccination = Vaccination::where('society_id', Auth::id())->orderBy('dose', 'asc')->get();
        if ($vaccination) {
            foreach ($vaccination as $vacc) {
                $vacc->vaccination_date = $vacc->date;
                $vacc->spot->makeHidden('regional_id');
                $vacc->spot->regional;
                if ($vacc->doctor) {
                    $vacc->status = 'done';
                    $vacc->vaccine;
                    $vacc->vaccinator = $vacc->doctor;
                    $vacc->vaccinator->makeHidden('spot_id', 'user_id');
                } else {
                    $vacc->status = 'pending';
                }
                $vacc->makeHidden('id', 'date', 'spot_id', 'doctor_id', 'society_id', 'vaccine_id', 'officer_id', 'doctor');
            }
        }

        return response()->json([
            'vaccinations' => [
                'first' => $vaccination[0] ?? null,
                'second' => $vaccination[1] ?? null
            ]
        ]);
    }
}
