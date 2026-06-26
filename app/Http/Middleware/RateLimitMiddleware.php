<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use Closure;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Redis\Factory as RedisFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    public function __construct(
        private readonly RedisFactory $redis,
        private readonly AuthFactory $auth,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $limit = (int) config('app.rate_limit_per_minute', env('RATE_LIMIT_PER_MINUTE', 60));
        $identifier = $this->auth->guard('api')->id() ?? $request->ip();
        $key = 'rate_limit:'.$identifier;
        $connection = $this->redis->connection();

        $current = (int) $connection->incr($key);

        if ($current === 1) {
            $connection->expire($key, 60);
        }

        $remaining = max(0, $limit - $current);
        $ttl = (int) $connection->ttl($key);
        $retryAfter = $ttl > 0 ? $ttl : 60;

        if ($current > $limit) {
            return ApiResponse::error('Too many requests.', null, 429)
                ->withHeaders([
                    'X-RateLimit-Limit' => (string) $limit,
                    'X-RateLimit-Remaining' => '0',
                    'Retry-After' => (string) $retryAfter,
                ]);
        }

        $response = $next($request);

        return $response->withHeaders([
            'X-RateLimit-Limit' => (string) $limit,
            'X-RateLimit-Remaining' => (string) $remaining,
        ]);
    }
}
