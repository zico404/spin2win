# Spin2Win (PHP + MySQL)

Modern single-page Spin-to-Win app with a black + gold luxury theme.

## Stack
- PHP (no framework)
- MySQL (PDO)
- Vanilla JS + CSS
- PHPMailer (SMTP only)

## Setup
1. Create a MySQL database named `spin2win`.
2. Import SQL: `sql/schema.sql`.
3. Update DB credentials in `config/config.php`.
4. Install dependencies:
   ```bash
   composer install
   ```
5. Ensure `uploads/` is writable by the web server.
6. Serve project root with Apache/Nginx + PHP.

## Admin
- URL: `/admin/login.php`
- Default credentials:
  - Username: `admin`
  - Password: `admin123`
- Change password immediately after first login (update hash in DB).

## SMTP Configuration
Go to `/admin/settings.php` and configure:
- SMTP host, port, username, password
- TLS/SSL encryption
- From and admin notification email

No `mail()` is used; all notifications use PHPMailer SMTP.

## Security features
- Prepared statements (PDO)
- CSRF protection for admin forms
- Session-based spin lock (one spin per session)
- Session-bound prize submission validation
- Upload MIME/size checks for sponsor logos

## Folder layout
- `config/` app config
- `includes/` shared PHP modules
- `assets/` CSS/JS
- `admin/` secure admin panel
- `uploads/` sponsor logos
- `sql/` schema
