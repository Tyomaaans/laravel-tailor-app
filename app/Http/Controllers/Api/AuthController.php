<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    public function login(Request $request): JsonResponse
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);

            $tokens = $this->authService->login($credentials);

            return $this->respondWithCookies($tokens, 'Login successfully');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Login failed', ['exception' => $exception]);
            return ApiResponse::error('Unable to login.', null, 500);
        }
    }

    public function refresh(Request $request): JsonResponse
    {
        try {
            // Ambil refresh_token dari cookie, bukan dari body
            $refreshToken = $request->cookie('refresh_token');

            if (! $refreshToken) {
                return ApiResponse::error('Refresh token missing.', null, 401);
            }

            $tokens = $this->authService->refresh($refreshToken);

            return $this->respondWithCookies($tokens, 'Token refreshed successfully');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Token refresh failed', ['exception' => $exception]);
            return ApiResponse::error('Unable to refresh token.', null, 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $accessToken  = $request->cookie('access_token');
            $refreshToken = $request->cookie('refresh_token');

            if (! $accessToken) {
                return ApiResponse::error('Access token is required.', null, 401);
            }

            $this->authService->logout($accessToken, $refreshToken ?? '');

            return ApiResponse::success(null, 'Logged out successfully')
                ->withoutCookie('access_token')
                ->withoutCookie('refresh_token');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Logout failed', ['exception' => $exception]);
            return ApiResponse::error('Unable to logout.', null, 500);
        }
    }

    public function me(): JsonResponse
    {
        try {
            $user = $this->authService->me();

            return ApiResponse::success(new UserResource($user), 'Authenticated user retrieved');
        } catch (\Throwable $exception) {
            Log::error('Fetch authenticated user failed', ['exception' => $exception]);

            return ApiResponse::error('Unable to retrieve authenticated user.', null, 500);
        }
    }

    // Helpers Cookie

    private function respondWithCookies(array $tokens, string $message): JsonResponse
    {
        $isProduction = app()->environment('production');
        $accessTtl    = (int) config('jwt.ttl');           // menit
        $refreshTtl   = (int) config('jwt.refresh_ttl');   // menit

        return ApiResponse::success(null, $message)
            ->cookie(
                'access_token', $tokens['access_token'],
                $accessTtl, '/', null,
                $isProduction, // secure: true di production (HTTPS)
                true,          // httpOnly
                false, 'Strict'
            )
            ->cookie(
                'refresh_token', $tokens['refresh_token'],
                $refreshTtl, '/', null,
                $isProduction,
                true,
                false, 'Strict'
            );
    }
}