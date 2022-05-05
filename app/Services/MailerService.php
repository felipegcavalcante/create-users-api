<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserMail;

class MailerService
{
    public function confirmationMail($user): void
    {
        Mail::to($user['email'])->send(new NewUserMail($user['name']));
    }
}
