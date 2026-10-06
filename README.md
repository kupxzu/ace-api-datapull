to do

create database using mssql
connect database
add mock datas

## API Authentication

The shared SQL Server database (`sqlsrv` connection) cannot be altered — no new tables,
no migrations — so API access control does **not** live in the database. Every route in
[routes/api.php](routes/api.php) is protected by HTTP Basic Auth, backed by a single
username/password pair stored only in `.env` (the password is kept as a bcrypt hash,
never in plain text).

**Set or rotate the credentials:**

```bash
php artisan api:credentials "your_username" "your_strong_password"
```

- The username must **not** be a valid email address (enforced by the command).
- The password must be at least 12 characters (enforced by the command).
- This writes `API_AUTH_USERNAME` and `API_AUTH_PASSWORD_HASH` into `.env`. If the app is
  already running with cached config, run `php artisan config:clear` afterwards.
- If these values are empty, every API request is rejected (fail closed).

**Calling the API:**

Visiting a protected endpoint directly in a browser (e.g. `http://127.0.0.1:8000/api/diseases`)
triggers the browser's native username/password popup automatically — that's standard HTTP
Basic Auth behavior (the server replies `401` with a `WWW-Authenticate: Basic` header), no
custom login page needed.

```bash
curl -u your_username:your_strong_password https://your-host/api/diseases
```

Requests are additionally rate limited (10 failed attempts per IP, 1-minute lockout),
tracked with the local file cache so throttling never touches the shared database.