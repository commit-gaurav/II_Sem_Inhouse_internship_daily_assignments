# Student Registration Confirmation System

A simple PHP + Bootstrap 5 project for the NexaLearn internship build sprint.

## What it does
- Shows a registration form (Name, Email, CGPA, Branch, College, Gender, Course, Address, optional photo).
- On submit, sends the data via POST to `process.php`.
- `process.php` validates that all fields are filled in correctly, then calculates a
  letter grade from the CGPA using `calculateGrade()`.
- If validation fails, the errors are listed and the user can go back and fix them.
- If validation passes, a confirmation card is shown with all submitted details,
  the calculated grade, and today's date.

## Files
- `index.php` – the registration form
- `process.php` – handles POST data, validation, grade logic, confirmation card
- `header.php` – shared navbar, included on every page
- `footer.php` – shared footer, included on every page
- `style.css` – small custom styles on top of Bootstrap

## How to run
1. Put this folder in your local PHP server directory (e.g. XAMPP's `htdocs`).
2. Start Apache (and PHP) from XAMPP / your local server.
3. Visit `http://localhost/student-registration/index.php` in your browser.

## Notes
- The photo upload field is a UI placeholder only — no file is actually
  uploaded/stored on the server.
- Uses Bootstrap 5 and Font Awesome via CDN, so an internet connection is
  needed for styling/icons to load.
