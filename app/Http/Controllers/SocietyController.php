<?php

namespace App\Http\Controllers;

use App\Models\Society;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SocietyController extends Controller
{
    public function user()
    {
        $user = Society::all();
        return $user;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_card_number' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $society = Society::where(['id_card_number' => $request->id_card_number, 'password' => $request->password])->with('regional')->first();
        if ($society) {
            $society->login_tokens = Str::random(50);
            $society->save();
            $society->makeHidden(['id', 'id_card_number', 'login_tokens', 'regional_id']);
            $society->token = $society->login_tokens;
            return response()->json($society, 200);
        } else {
            return response()->json([
                'message' => 'ID Card Number or Password Incorrect'
            ], 401);
        }
    }

    public function logout()
    {
        $society = Society::where("id", Auth::id())->first();
        if ($society) {
            $society->login_tokens = null;
            $society->save();

            return response()->json(['message' => 'Logout success'], 200);
        }
    }
}
