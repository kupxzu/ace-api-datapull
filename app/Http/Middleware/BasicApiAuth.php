<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Strict HTTP Basic Auth guard for the read-only API.
 *
 * We only have one (shared) SQL Server database and are not allowed to
 * create tables in it or otherwise manipulate it, so this guard never
 * touches any database - not even indirectly. That's why failed-attempt
 * throttling below deliberately uses the local "file" cache store instead
 * of Laravel's default cache/rate limiter (which, in this app, is
 * configured to use the same restricted SQL Server connection).
 *
 * Username/password are read from config (backed by .env), and the
 * password is only ever compared as a bcrypt hash via Hash::check().
 */
class BasicApiAuth
{
    /** Max failed attempts allowed per IP within the decay window. */
    protected const MAX_ATTEMPTS = 10;

    /** How long (in minutes) an IP is locked out after too many failures. */
    protected const DECAY_MINUTES = 1;

    public function handle(Request $request, Closure $next): Response
    {
        $expectedUsername = (string) config('apiauth.username');
        $expectedPasswordHash = (string) config('apiauth.password_hash');

        // Fail closed: if credentials were never configured, reject everything.
        if ($expectedUsername === '' || $expectedPasswordHash === '') {
            Log::warning('API auth rejected a request because no API credentials are configured.');

            return $this->unauthorized();
        }

        if ($this->tooManyAttempts($request)) {
            Log::warning('API auth blocked a request: too many failed attempts.', ['ip' => $request->ip()]);

            return response()->json([
                'success' => false,
                'message' => 'Too many failed attempts. Try again later.',
            ], 429);
        }

        [$username, $password] = $this->credentialsFromRequest($request);

        $usernameValid = $username !== null && hash_equals($expectedUsername, $username);
        $passwordValid = $password !== null && Hash::check($password, $expectedPasswordHash);

        if (! $usernameValid || ! $passwordValid) {
            $this->recordFailedAttempt($request);
            Log::warning('API auth failed.', ['ip' => $request->ip()]);

            return $this->unauthorized();
        }

        $this->clearAttempts($request);

        return $next($request);
    }

    protected function tooManyAttempts(Request $request): bool
    {
        return $this->attemptsCache()->get($this->attemptsKey($request), 0) >= self::MAX_ATTEMPTS;
    }

    protected function recordFailedAttempt(Request $request): void
    {
        $store = $this->attemptsCache();
        $key = $this->attemptsKey($request);

        $store->put($key, $store->get($key, 0) + 1, now()->addMinutes(self::DECAY_MINUTES));
    }

    protected function clearAttempts(Request $request): void
    {
        $this->attemptsCache()->forget($this->attemptsKey($request));
    }

    protected function attemptsKey(Request $request): string
    {
        return 'api-auth-attempts:'.$request->ip();
    }

    /**
     * Always use the local "file" cache store so this never touches the
     * shared SQL Server database, regardless of the app's default cache store.
     */
    protected function attemptsCache()
    {
        return Cache::store('file');
    }

    /**
     * Extract the Basic Auth username/password from the request, falling
     * back to a manual parse of the Authorization header for server setups
     * where PHP_AUTH_USER / PHP_AUTH_PW aren't populated.
     *
     * @return array{0: ?string, 1: ?string}
     */
    protected function credentialsFromRequest(Request $request): array
    {
        $username = $request->getUser();

        if ($username !== null) {
            return [$username, $request->getPassword()];
        }

        $header = (string) $request->headers->get('Authorization', '');

        if (stripos($header, 'Basic ') === 0) {
            $decoded = base64_decode(trim(substr($header, 6)), true);

            if ($decoded !== false && str_contains($decoded, ':')) {
                [$username, $password] = explode(':', $decoded, 2);

                return [$username, $password];
            }
        }

        return [null, null];
    }

    protected function unauthorized(): Response
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized.',
        ], 401, [
            'WWW-Authenticate' => 'Basic realm="ACEMCT API"',
        ]);
    }
}
