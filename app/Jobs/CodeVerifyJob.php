<?php

namespace App\Jobs;

use App\Mail\CodeVerification;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CodeVerifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $data;
    private $code;
    /**
     * Create a new job instance.
     */
    public function __construct($data, $code)
    {
        $this->data = $data;
        $this->code = $code;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $send = Mail::to($this->data->email)->send(new CodeVerification($this->code));
            if ($send) {
                return ['success'];
            }
        } catch (Exception $e) {
            return ['error'];
        }
    }
}
