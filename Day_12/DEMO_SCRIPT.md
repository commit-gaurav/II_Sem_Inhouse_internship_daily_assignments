# 3-Minute Demo Script

**0:00–0:20 — Intro**
"This is NexaLearn's student registration system — a full CRUD app with
login, photo uploads, and search, built in PHP and MySQL."

**0:20–0:50 — Login & session guard**
- Visit `students.php` directly while logged out → show it redirects to `login.php`.
- Log in with `admin` / `admin123`.
- Point out the navbar now shows Dashboard / Students / Register + username.

**0:50–1:20 — Dashboard**
- Show the three stat cards (Total Students, Average CGPA, CGPA above 8.0).
- Show the Recent Registrations widget.

**1:20–2:10 — Create + Update**
- Go to Register, fill the form, pick a photo → point out the live preview
  before submitting.
- Submit → land on Students list, new row highlighted green if CGPA > 8.0.
- Click Edit on that row, change the branch, replace the photo, save →
  show the updated row.

**2:10–2:40 — Search + Delete**
- Type a branch name into the search box → show filtered results.
- Clear search, delete a test record → confirm dialog → row disappears,
  flash message confirms deletion.

**2:40–3:00 — Wrap-up**
- Mention validation: server-side photo type/size checks, prepared
  statements everywhere (SQL injection safe), session guards on every
  protected page.
- Close with the GitHub repo / commit history as the "portfolio" takeaway.
