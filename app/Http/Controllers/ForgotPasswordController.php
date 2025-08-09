<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\CustomPasswordResetNotification;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ForgotPasswordController extends Controller
{
    use JsonResponseTrait;

    /**
     * Handle an incoming password reset link request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendResetLinkEmail(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::query()->where('email', $request->email)->first();

        if (!$user) {
            // We don't want to reveal that the user does not exist.
            // We'll return a success message regardless.
            return $this->successResponse(['message' => 'If you have an account with us, we will send you an email with a link to reset your password.']);
        }

        // Create a password reset token
        $token = Password::createToken($user);

        // Send the notification
        $user->notify(new CustomPasswordResetNotification($token));

        return $this->successResponse(['message' => 'If you have an account with us, we will send you an email with a link to reset your password.']);
    }

    /**
     * Handle an incoming new password request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function reset(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $response == Password::PASSWORD_RESET
            ? $this->successResponse(['message' => 'Password successfully reset.'])
            : $this->errorResponse(['email' => __($response)], 422);
    }
}
