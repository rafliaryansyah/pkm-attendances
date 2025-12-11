# Fix 403 Forbidden After Admin Login on Railway

## Problem
After successful login to `/admin/login`, getting 403 Forbidden error even though the user has `role='admin'`.

## Root Cause
Railway environment is using `SESSION_DRIVER=file`, which doesn't work on Railway's **ephemeral filesystem**. Session data is lost after each request, causing authentication to fail.

## Solution

### Step 1: Update Railway Environment Variables

Go to your Railway project dashboard and update these environment variables:

```bash
# Session Configuration - CRITICAL FOR AUTH TO WORK
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
SESSION_PATH=/
SESSION_DOMAIN=

# Cache Configuration
CACHE_STORE=database
CACHE_PREFIX=
```

### Step 2: Verify Database Session Table Exists

The `sessions` table should already exist from the initial migration `0001_01_01_000000_create_users_table.php`.

To verify, you can check your database or run:

```sql
SHOW TABLES LIKE 'sessions';
```

If the table doesn't exist, run migrations:

```bash
php artisan migrate
```

### Step 3: Clear Cache and Restart

After updating environment variables in Railway:

1. Railway will automatically restart your application
2. Try logging in again at: `https://your-app.up.railway.app/admin/login`

### Step 4: Verify User Role

Make sure your user has `role='admin'` in the database:

```sql
SELECT id, name, email, role FROM users WHERE email = 'your-email@example.com';
```

If role is not 'admin', update it:

```sql
UPDATE users SET role = 'admin' WHERE email = 'your-email@example.com';
```

## Why This Happens

1. **Railway's Ephemeral Filesystem**: Railway uses temporary storage that resets between deployments and doesn't persist across containers
2. **File-based Sessions**: When using `SESSION_DRIVER=file`, sessions are stored in `storage/framework/sessions/` which gets cleared
3. **Authentication Loss**: After login, the session file is lost, so Filament thinks you're not authenticated → 403 Forbidden

## Why Database Sessions Work

- Sessions are stored in the `sessions` table in your MySQL/PostgreSQL database
- Database persists across deployments and requests
- Session data remains available for authentication checks

## Additional Notes

- Local development works fine with `SESSION_DRIVER=file` because your local filesystem persists
- Production environments (Railway, Heroku, Cloud Run, etc.) should always use `SESSION_DRIVER=database` or `SESSION_DRIVER=redis`

## Verification

After applying the fix:

1. Login should work properly
2. You should see session records in the `sessions` database table
3. No more 403 Forbidden errors after login

```sql
-- Check active sessions
SELECT * FROM sessions ORDER BY last_activity DESC LIMIT 5;
```
