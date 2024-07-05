<?php

namespace App\Http\Services;

use App\Mail\CodeVerification;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMail
{
    public static function confirmationCodeMail($data, $code)
    {
        try {
            $send = Mail::to($data->email)->send(new CodeVerification($code));
            if ($send) {
                return ['success'];
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return ['error'];
        }
    }
}
