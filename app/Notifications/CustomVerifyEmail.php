<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Email Address')
            ->view('emails.custom_verify_email', ['url' => $verificationUrl, 'user' => $notifiable]);
    }

    protected function verificationUrl($notifiable): string
    {
        $frontendUrl = config('app.url'); // Your frontend URL

        $temporarySignedUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        // Extract path and query from the API URL
        $pathAndQuery = parse_url($temporarySignedUrl, PHP_URL_PATH) . '?' . parse_url($temporarySignedUrl, PHP_URL_QUERY);
        $pathAndQuery = str_replace('/api/auth/', '/', $pathAndQuery);
        // Construct the frontend verification URL
        return $frontendUrl . '/verify-email-handler' . $pathAndQuery;
    }

    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
