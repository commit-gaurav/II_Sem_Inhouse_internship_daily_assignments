# Secure Student Management System — Auth Module

Built for the 30-minute build sprint + "Take It Further" bonus assignment.

## Files

| File | Purpose |
|---|---|
| `db.php` | MySQL connection (edit credentials for your machine) |
| `login.php` | Login form + authentication |
| `dashboard.php` | Protected home page, shows name + last login |
| `logout.php` | Destroys session, redirects to login |
| `access_denied.php` | Friendly page shown for unauthorized access |
| `includes/auth.php` | `require_login()` session guard — include on every protected page |
| `includes/header.php` / `includes/footer.php` | Shared navbar + sidebar layout |
| `forgot_password.php` | Bonus: forgot-password UI (no email sending) |
| `change_password.php` | Bonus: current/new/confirm password form |
| `profile.php` | Bonus: profile picture upload + display |
| `schema.sql` | Database schema + sample user |
| `reset_admin_password.php` | Run once to generate a working hash for the sample login |

## Setup

1. Create the database:
   ```bash
   mysql -u root -p < schema.sql
   ```
2. Update credentials in `db.php` if needed.
3. Start PHP's built-in server from this folder:
   ```bash
   php -S localhost:8000
   ```
4. Visit `http://localhost:8000/reset_admin_password.php` once, to generate
   a working bcrypt hash for the demo account (bcrypt hashes are
   environment-specific, so the one in `schema.sql` is a placeholder).
5. Go to `http://localhost:8000/login.php` and sign in with:
   - Email: `admin@nexalearn.com`
   - Password: `Admin@123`

## How the pieces fit together

- Every protected page (`dashboard.php`, `profile.php`, `change_password.php`)
  starts with:
  ```php
  require_once __DIR__ . '/db.php';
  require_once __DIR__ . '/includes/auth.php';
  require_login();
  ```
  `require_login()` redirects to `access_denied.php` if `$_SESSION['user_id']`
  isn't set.
- `login.php` verifies credentials with `password_verify()`, starts the
  session, stores `user_id` / `user_name` / `user_avatar`, and stamps
  `last_login`.
- `includes/header.php` reads the session to show the logged-in user's name
  and avatar in the top navbar, and provides the sidebar navigation used on
  every protected page.

## Pushing to GitHub

```bash
git init
git add .
git commit -m "Add secure login system with sessions, protected pages, and profile/password features"
git remote add origin <your-fork-url>
git push origin <your-roll-number-branch>
```
Then open a pull request from your branch into the college repo, as usual.
