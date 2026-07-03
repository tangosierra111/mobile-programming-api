<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as LaravelAuth;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class VerifyFirebaseToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $idToken = $request->bearerToken();

        if (! $idToken) {
            return $this->unauthorized('Firebase ID token tidak ditemukan.');
        }

        try {
            $token = app(FirebaseAuth::class)->verifyIdToken($idToken);
            $claims = $token->claims();
            $uid = (string) $claims->get('sub');
            $email = (string) $claims->get('email', $uid.'@firebase.local');
            $name = (string) $claims->get('name', str($email)->before('@'));

            $user = User::updateOrCreate(
                ['firebase_uid' => $uid],
                [
                    'name' => $name ?: 'Pengguna',
                    'email' => $email,
                    'email_verified_at' => $claims->get('email_verified', false) ? now() : null,
                ],
            );

            if (! $user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun sedang dinonaktifkan.',
                ], 403);
            }

            LaravelAuth::setUser($user);
            $request->setUserResolver(fn (): User => $user);
        } catch (FailedToVerifyToken) {
            return $this->unauthorized('Firebase ID token tidak valid atau kedaluwarsa.');
        } catch (Throwable $exception) {
            Log::error('Firebase authentication failed.', [
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Layanan autentikasi belum dapat digunakan.',
            ], 500);
        }

        return $next($request);
    }

    private function unauthorized(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 401);
    }
}
