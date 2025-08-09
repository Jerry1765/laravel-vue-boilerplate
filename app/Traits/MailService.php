<?php

namespace App\Traits;

use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

trait MailService
{
    protected function sendTestEmail($data): void
    {
        $cc = $data->email;
        $to = config('mail.bcc_email', 'hello@example.com');

        Mail::to($to)
            ->cc($cc)
            ->send(new TestMail($data));
    }
}
