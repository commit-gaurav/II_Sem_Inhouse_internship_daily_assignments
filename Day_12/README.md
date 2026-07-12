# NexaLearn — Final Project

Student registration app: CRUD + Login + Photo Upload + Search, built on top of
Day 9's `register.php` / `students.php`.

## Setup

1. **Database**
   ```
   mysql -u root -p < schema.sql
   ```
   This creates the `nexalearn` database, the `students` and `users` tables,
   and one default login.

2. **Config**
   Open `db.php` and set `$db_user` / `$db_pass` to match your local MySQL.

3. **Uploads folder**
   Make sure `uploads/` is writable by the web server:
   ```
   chmod 755 uploads
   ```

4. **Run it**
   Point Apache/XAMPP/PHP's built-in server at this folder, e.g.:
   ```
   php -S localhost:8000
   ```
   Then visit `http://localhost:8000/login.php`.

## Default login

| Username | Password  |
|----------|-----------|
| admin    | admin123  |

Change or add users directly in the `users` table — passwords must be
inserted as `password_hash($plainPassword, PASSWORD_DEFAULT)`, never plain text.

## File overview

| File | Purpose |
|---|---|
| `db.php` | MySQL connection |
| `auth.php` | Session guard — included at the top of every protected page |
| `login.php` / `logout.php` | Authentication |
| `register.php` | Create a student |
| `students.php` | Read / search / list students |
| `edit_student.php` | Update a student |
| `delete_student.php` | Delete a student |
| `dashboard.php` | Stats + Recent Registrations widget |
| `includes/header.php` / `footer.php` | Shared layout, navbar, flash messages |
| `includes/upload_helper.php` | Validated photo upload (type/size checked server-side) |
| `assets/style.css` | Fade-in/slide-up animation |
| `assets/preview.js` | Client-side image preview before upload |
| `schema.sql` | Database schema + default admin user |

## Bugs fixed from the Day 9 version

- Photo upload had no server-side type/size validation (only the client-side
  `accept="image/*"` hint, which anyone can bypass) — now checked in
  `upload_helper.php`.
- `uploads/` wasn't created automatically if missing.
- Original filenames were trusted directly; now replaced with an unguessable
  generated name so uploads never collide or leak file paths.
- No session/auth guard existed on any page — added via `auth.php`.
- Submitting `register.php` and refreshing the page would resubmit the form;
  fixed with a redirect-after-POST + flash message pattern.
- Editing a student's photo left the old file behind; `edit_student.php` now
  deletes the replaced file.

## Git workflow reminder

```
git add .
git commit -m "Add login, CRUD update/delete, search, and upload validation"
git push origin <your-roll-number-branch>
```
Then open the PR against `upstream` as usual.
