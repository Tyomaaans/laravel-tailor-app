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

            return ApiResponse::success($tokens, 'Login successfully');
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
            $validated = $request->validate([
                'refresh_token' => ['required', 'string'],
            ]);

            $tokens = $this->authService->refresh($validated['refresh_token']);

            return ApiResponse::success($tokens, 'Token refreshed successfully');
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
            $validated = $request->validate([
                'refresh_token' => ['required', 'string'],
            ]);

            $accessToken = $request->bearerToken();

            if (! $accessToken) {
                return ApiResponse::error('Access token is required.', null, 401);
            }

            $this->authService->logout($accessToken, $validated['refresh_token']);

            return ApiResponse::success(null, 'Logged out successfully');
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
}
