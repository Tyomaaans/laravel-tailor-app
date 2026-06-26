<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Redis\Factory as RedisFactory;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\JWTAuth;

class AuthService
{
    public function __construct(
        private readonly AuthManager $auth,
        private readonly JWTAuth $jwt,
        private readonly RedisFactory $redis,
    ) {}

    public function login(array $credentials): array
    {
        if (! $token = $this->auth->guard('api')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        /** @var User $user */
        $user = $this->auth->guard('api')->user();
        $refreshToken = $this->storeRefreshToken($user);

        return $this->tokenResponse($token, $refreshToken);
    }

    public function refresh(string $refreshToken): array
    {
        $tokenHash = hash('sha256', $refreshToken);
        $connection = $this->redis->connection();
        $matchingKey = null;

        foreach ($connection->keys('refresh_token:*:'.$tokenHash) as $key) {
            $matchingKey = $key;
            break;
        }

        if (! $matchingKey) {
            throw ValidationException::withMessages([
                'refresh_token' => ['Invalid or expired refresh token.'],
            ]);
        }

        $parts = explode(':', (string) $matchingKey);
        $userId = $parts[1] ?? null;
        $user = User::query()->find($userId);

        if (! $user) {
            throw ValidationException::withMessages([
                'refresh_token' => ['Invalid or expired refresh token.'],
            ]);
        }

        $connection->del($matchingKey);

        $accessToken = $this->jwt->fromUser($user);
        $newRefreshToken = $this->storeRefreshToken($user);

        return $this->tokenResponse($accessToken, $newRefreshToken);
    }

    public function logout(string $accessToken, string $refreshToken): void
    {
        $this->jwt->setToken($accessToken);

        try {
            $payload = $this->jwt->getPayload();
            $jti = $payload->get('jti');
            $exp = $payload->get('exp');
            $remainingTtl = max(1, $exp - time());

            $this->redis->connection()->setex('blacklist:'.$jti, $remainingTtl, '1');
        } catch (\Throwable) {
            // Token may already be invalid; continue refresh token cleanup.
        }

        $tokenHash = hash('sha256', $refreshToken);
        $connection = $this->redis->connection();

        foreach ($connection->keys('refresh_token:*:'.$tokenHash) as $key) {
            $connection->del($key);
        }
    }

    public function me(): User
    {
        /** @var User $user */
        $user = $this->auth->guard('api')->user();

        return $user->load(['roles', 'permissions',]);
    }

    private function storeRefreshToken(User $user): string
    {
        $refreshToken = Str::random(64);
        $tokenHash = hash('sha256', $refreshToken);
        $ttlSeconds = (int) config('jwt.refresh_ttl') * 60;

        $this->redis->connection()->setex(
            'refresh_token:'.$user->id.':'.$tokenHash,
            $ttlSeconds,
            '1'
        );

        return $refreshToken;
    }

    private function tokenResponse(string $accessToken, string $refreshToken): array
    {
        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => (int) config('jwt.ttl') * 60,
        ];
    }
}
