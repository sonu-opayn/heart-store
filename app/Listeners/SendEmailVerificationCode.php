<?php

namespace App\Listeners;

use App\Mail\SendEmailVerifyCode;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Events\UserRegister;

class SendEmailVerificationCode
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserRegister $event)
    {
        try {
            
            $context = $event->context;

            if(!ine($context, 'user')) {
                throw new Exception('Event content is not available');
            }

            $user = $event->context['user'];

            Mail::to($user->email)->send(new SendEmailVerifyCode($context));

            return true;
        } catch(\Throwable $e) {
            Log::error($e);
        }

        return false;
    }
}
