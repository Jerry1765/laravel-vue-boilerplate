<?php

namespace App\Http\Controllers;

use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use PragmaRX\Google2FAQRCode\Exceptions\MissingQrCodeServiceException;
use PragmaRX\Google2FAQRCode\Google2FA;

class TwoFactorAuthController extends Controller
{
    use JsonResponseTrait;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function enableEmail(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->forceFill([
            'two_factor_enabled' => true,
            'two_factor_method' => 'email',
            'two_factor_secret' => null, // Clear app secret if switching
            'two_factor_recovery_codes' => null, // Clear recovery codes
        ])->save();

        return $this->successResponse(['message' => 'Two-factor authentication enabled.']);
    }

    /**
     * @throws IncompatibleWithGoogleAuthenticatorException
     * @throws SecretKeyTooShortException
     * @throws InvalidCharactersException
     * @throws MissingQrCodeServiceException
     */
    public function enable(Request $request): JsonResponse
    {
        $user = $request->user();
        $google2fa = new Google2FA();

        $user->forceFill([
            'two_factor_secret' => $secret = $google2fa->generateSecretKey(),
            'two_factor_recovery_codes' => $this->generateRecoveryCodes(),
        ])->save();

        $qrCodeSvg = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );

        return $this->successResponse([
            'qr_code_svg' => $qrCodeSvg,
            'recovery_codes' => $user->two_factor_recovery_codes,
        ]);
    }

    /**
     * @throws IncompatibleWithGoogleAuthenticatorException
     * @throws InvalidCharactersException
     * @throws SecretKeyTooShortException
     */
    public function confirm(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string']);
        $user = $request->user();
        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($user->two_factor_secret, $request->code)) {
            $user->forceFill(['two_factor_enabled' => true, 'two_factor_method' => 'app'])->save();
            return $this->successResponse(['message' => 'Two-factor authentication enabled.']);
        }

        return $this->errorResponse(['message' => 'Invalid code.'], 422);
    }

    public function disable(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->forceFill([
            'two_factor_enabled' => false,
            'two_factor_method' => null,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
        ])->save();

        return $this->successResponse(['message' => 'Two-factor authentication disabled.']);
    }

    private function generateRecoveryCodes(): Collection
    {
        return Collection::times(8, function () {
            return Str::random(10) . '-' . Str::random(10);
        });
    }
}
