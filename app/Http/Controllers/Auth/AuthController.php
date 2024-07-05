<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Jobs\CodeVerifyJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function registerClient(RegisterRequest $request)
    {
        DB::beginTransaction();
        $data = $request->validate();
        $user = User::create($data);
        if ($user) {
            $code = random_int(100000, 999999);
            $response = CodeVerifyJob::dispatch($user, $code);
            if ($response[0] == 'success') {
                $user->code_verify = $code;
                $user->expiration_time = Carbon::now()->addMinutes(30);
                DB::commit();
                return response()->json(['message' => 'Merci d\'avoir créé un compte. Un code de vérification vous a été envoyé par mail'], 200);
            } else {
                DB::rollBack();
                return response()->json(['message' => 'Erreur de la création de votre compte. Veuillez rééssayer'], 403);
            }
        }
    }
}
