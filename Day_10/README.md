# Student Management Portal

A complete CRUD web app built with **PHP + MySQL + Bootstrap 5**.

## Features

- **Create** — Add a new student with server-side form validation
- **Read** — View all students in a responsive Bootstrap table
- **Update** — Edit a student via a pre-filled form, with a success message on save
- **Delete** — Confirm-dialog protected delete, with a success message
- **Search** — Multi-field search across name, email, and branch simultaneously
- **Filters** — Branch/course dropdown filter and Active/Inactive status filter
- **CGPA range filter** — Min/max CGPA, combinable with search and other filters
- **Dashboard stats** — Total students, average CGPA, and students-per-branch, all from SQL aggregate queries
- **Profile photo upload** — Stored on disk, filename saved in MySQL, shown as a thumbnail in the table

## Setup

1. **Create the database.**
   Import the schema (this also creates the database and seeds 6 sample students):
   ```bash
   mysql -u root -p < schema.sql
   ```

2. **Configure the DB connection.**
   Edit `config/db.php` and set your MySQL username/password:
   ```php
   $DB_USER = 'root';
   $DB_PASS = '';
   ```

3. **Make sure `uploads/` is writable** by the web server (for profile photos):
   ```bash
   chmod 755 uploads
   ```

4. **Run it.**
   - With PHP's built-in server (quick local testing):
     ```bash
     php -S localhost:8000
     ```
     then open http://localhost:8000
   - Or drop the whole folder into your XAMPP/WAMP/MAMP `htdocs`/`www` directory and visit
     `http://localhost/student-management-portal/`.

## Project structure

```
student-management-portal/
├── config/
│   └── db.php          # PDO database connection
├── includes/
│   ├── header.php       # Shared navbar + page head
│   └── footer.php       # Shared footer + scripts
├── assets/
│   └── css/style.css    # Custom styling on top of Bootstrap
├── uploads/              # Uploaded profile photos land here
├── schema.sql            # Database schema + sample data
├── index.php              # Dashboard: stats, search, filters, table
├── add.php                # Create student
├── edit.php               # Update student
└── delete.php              # Delete student
```

## Notes on how the pieces fit together

- All database access uses **PDO with prepared statements** — no raw string
  concatenation into SQL, so it's protected against SQL injection.
- The **search + filter bar** on the dashboard builds one combined `WHERE`
  clause dynamically (name/email/branch search, branch filter, status
  filter, CGPA range) so all filters can be applied together.
- The **stats row** is powered by three simple aggregate queries:
  `COUNT(*)`, `AVG(cgpa)`, and `GROUP BY branch`.
- **Validation** happens both in the browser (HTML5 `required`, `type`,
  `min`/`max`) and again on the server in PHP, since client-side validation
  alone can always be bypassed.
- **Photo uploads** are checked for MIME type and size before being moved
  into `uploads/` with a randomized filename (`stu_<uniqid>.<ext>`), so the
  original filename can never be used to overwrite another file. Deleting or
  updating a student's photo removes the old file from disk too.

## Extending it further

Some natural next steps if you want to keep building:
- Add pagination once the student list grows large
- Add sortable table columns (click a header to sort by that field)
- Add CSV export of the filtered results
- Add authentication so only logged-in staff can manage records
