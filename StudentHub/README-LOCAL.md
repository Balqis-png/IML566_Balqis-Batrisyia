# StudentHub – Local Development

StudentHub is a small student and course management web application prototype for the IMS566 individual assignment.

## Included features
- Login and registration with PHP sessions and password hashing.
- MySQL storage for user accounts, students and courses.
- Dashboard summary cards and a meaningful course-enrolment chart.
- Student and course CRUD (add, view, edit, delete), plus search.
- Favicon, dark/light mode preference, responsive left sidebar.
- Bootstrap 5, Bootstrap Icons and Chart.js.

## Requirements
- XAMPP with Apache and MySQL running.
- PHP 8.1+ (works with PHP 8.4).
- Google Chrome.
- Internet connection for Bootstrap, Bootstrap Icons, Chart.js and Google Fonts CDN assets.

## Setup with XAMPP
1. Copy the entire `StudentHub` folder into `C:\xampp\htdocs\`.
2. Open XAMPP Control Panel and start **Apache** and **MySQL**.
3. Open `http://localhost/phpmyadmin`.
4. Select **Import**, choose `StudentHub/database/studenthub.sql`, and click **Import**.
   - The SQL script creates the `studenthub` database and sample student/course records.
5. Check `StudentHub/config/database.php`. The default XAMPP settings are host `127.0.0.1`, username `root`, and an empty password.
6. Visit `http://localhost/StudentHub/`.
7. Choose **Create an account** to register your own username and password, then sign in.

## How data is saved
- User registration, student records and course records are stored in MySQL.
- Passwords are stored as secure PHP password hashes, not plain text.
- The dark/light preference is stored in the browser's localStorage.

## Main pages
- `index.php` – login
- `register.php` – registration
- `dashboard.php` – overview and chart
- `students.php` – student data management
- `courses.php` – course data management

This is a local prototype. It has not been uploaded to GitHub or deployed.
