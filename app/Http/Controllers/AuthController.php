<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Notifications\TwoFactorCodeNotification;
use App\Services\CacheKeyService;
use App\Services\SettingsService;
use App\Traits\CacheTrait;
use App\Traits\JsonResponseTrait;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use PragmaRX\Google2FA\Google2FA;
use Random\RandomException;

class AuthController extends Controller
{
    use JsonResponseTrait, CacheTrait;

    public function register(Request $request): JsonResponse
    {
        if (SettingsService::instance()->isRegistrationEnabled() === false) {
            return $this->errorResponse([
                'message' => 'Registration is disabled.'
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::query()
            ->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

        // Assign default 'user' role
        $userRole = Role::query()
            ->where('name', 'user')
            ->first();
        $user->roles()->attach($userRole);

        $user->sendEmailVerificationNotification();

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * @throws RandomException
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::query()
            ->where('email', $request->email)
            ->first();
        $cacheKey = CacheKeyService::instance()->getFailedAttemptsKey($request->email);

        if (!Auth::attempt($request->only('email', 'password'))) {
            $attempts = $this->cacheGet($cacheKey) ?? 0;

            if ($attempts >= 5) {
                if ($user) {
                    $user->blockUser();
                }
                return $this->errorResponse([
                    'message' => 'Too many failed login attempts.'
                ]);
            }

            $this->cacheSet($cacheKey, $attempts + 1);
            return $this->errorResponse([
                'message' => 'Incorrect login details.'
            ]);
        }

        if ($user->isBlocked()) {
            return $this->errorResponse([
                'message' => 'Account is blocked. Please contact support.'
            ]);
        }

        if (!$user->hasVerifiedEmail()) {
            return $this->errorResponse(['message' => 'not-verified'], 403);
        }

        $this->cacheForget($cacheKey);

        if ($user->two_factor_enabled) {
            // If 2FA is enabled, generate and send code if method is email
            if ($user->two_factor_method === 'email') {
                $user->forceFill([
                    'two_factor_email_code' => random_int(100000, 999999),
                    'two_factor_email_expires_at' => now()->addMinutes(10),
                ])->save();
                $user->notify(new TwoFactorCodeNotification());
            }

            $method = $user->two_factor_method;

            // Log out the user for now and return a challenge required response
            Auth::logout();

            return $this->successResponse([
                'two_factor_required' => true,
                'user_id' => $user->id,
                'method' => $method
            ]);
        }

        //update updated_at timestamp to log last login
        $user->touch();

        $tokenResult = $user->createToken('auth_token', expiresAt: Carbon::now()->addDays(7));

        return $this->successResponse([
            'user' => $user,
            'access_token' => $tokenResult->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::now()->addDays(7)
        ]);
    }

    /**
     * @throws IncompatibleWithGoogleAuthenticatorException
     * @throws SecretKeyTooShortException
     * @throws InvalidCharactersException
     */
    public function loginWithTwoFactor(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'code' => 'required|string',
        ]);

        $user = User::query()->find($request->user_id);

        $codeIsValid = false;
        if ($user->two_factor_method === 'app') {
            $google2fa = new Google2FA();
            $codeIsValid = $google2fa->verifyKey($user->two_factor_secret, $request->code);
        } elseif ($user->two_factor_method === 'email') {
            $codeIsValid = $request->code == $user->two_factor_email_code && now()->lessThan($user->two_factor_email_expires_at);
        }

        if (!$codeIsValid) {
            return $this->errorResponse(['message' => 'Invalid or expired code.'], 422);
        }

        // Clear email code after use
        $user->forceFill([
            'two_factor_email_code' => null,
            'two_factor_email_expires_at' => null,
        ])->save();

        // Log the user in and create token
        Auth::login($user);
        $tokenResult = $user->createToken('auth_token', expiresAt: Carbon::now()->addDays(7));

        $user->touch();

        return $this->successResponse([
            'user' => $user,
            'access_token' => $tokenResult->plainTextToken,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return $this->successResponse(['message' => 'Logged out']);
    }

    public function user(Request $request): JsonResponse
    {
        return $this->successResponse($request->user()->load('roles'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function verify(Request $request): JsonResponse
    {
        $user = User::query()->find($request->route('id'));

        if (!hash_equals((string)$request->route('hash'), sha1($user->getEmailForVerification()))) {
            return $this->errorResponse(['message' => 'Invalid token'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->successResponse(['message' => 'Email is already verified.']);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return $this->successResponse(['message' => 'Email successfully verified!']);
    }

    public function resend(Request $request): JsonResponse
    {
        $user = User::query()->where('email', $request->email)->first();

        if (!$user) {
            return $this->errorResponse(['message' => 'Invalid request.'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->errorResponse(['message' => 'Email is already verified.'], 400);
        }

        $user->sendEmailVerificationNotification();

        return $this->successResponse(['message' => 'Link to verify email sent.']);
    }
}
