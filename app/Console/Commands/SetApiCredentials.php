<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Sets/rotates the API Basic Auth credentials used to protect the read-only
 * API. Credentials are written to .env only (password as a bcrypt hash) —
 * nothing is ever written to the shared SQL Server database.
 */
class SetApiCredentials extends Command
{
    protected $signature = 'api:credentials {username : Must NOT be a valid email address} {password}';

    protected $description = 'Set the username/password that protect the read-only API (stored hashed in .env, never in the database)';

    public function handle(): int
    {
        $username = (string) $this->argument('username');
        $password = (string) $this->argument('password');

        $validator = Validator::make(
            ['username' => $username, 'password' => $password],
            [
                'username' => ['required', 'string', 'min:4', 'max:64', 'regex:/^[A-Za-z0-9_.\-]+$/'],
                'password' => ['required', 'string', 'min:12'],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        // Strict requirement: the username must NOT look like an email address.
        if (filter_var($username, FILTER_VALIDATE_EMAIL) !== false) {
            $this->error('The username must not be a valid email address.');

            return self::FAILURE;
        }

        $this->updateEnv([
            'API_AUTH_USERNAME' => $username,
            'API_AUTH_PASSWORD_HASH' => Hash::make($password),
        ]);

        $this->info('API credentials updated in .env.');
        $this->comment('Run `php artisan config:clear` if the app was already running with config cached.');

        return self::SUCCESS;
    }

    /**
     * Update (or add) keys in the project's .env file in place.
     *
     * @param  array<string, string>  $values
     */
    protected function updateEnv(array $values): void
    {
        $path = base_path('.env');

        $contents = file_exists($path) ? file_get_contents($path) : '';

        foreach ($values as $key => $value) {
            $escaped = addcslashes($value, '"\\');
            $line = sprintf('%s="%s"', $key, $escaped);
            $pattern = '/^'.preg_quote($key, '/').'=.*$/m';

            if (preg_match($pattern, $contents)) {
                // preg_replace_callback (not preg_replace) is required here: the
                // replacement may contain bcrypt hashes like "$2y$12$...", and
                // preg_replace would misinterpret "$2"/"$12" as backreferences.
                $contents = preg_replace_callback($pattern, fn () => $line, $contents);
            } else {
                $contents = rtrim($contents, "\n")."\n".$line."\n";
            }
        }

        file_put_contents($path, $contents);
    }
}
