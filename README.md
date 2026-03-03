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

## cPanel deployment quick guide
1. Upload project files into your domain’s `public_html` (or subdomain document root).
2. Create a MySQL database and user in cPanel, grant all privileges.
3. Import `sql/schema.sql` using phpMyAdmin.
4. Update `config/config.php` with cPanel DB credentials (`DB_HOST` is often `localhost`).
5. Install Composer dependencies:
   - via SSH Terminal in cPanel: `composer install --no-dev --optimize-autoloader`
   - or run Composer locally and upload `vendor/`.
6. Set `uploads/` folder writable by PHP (usually 755/775 depending on host).
7. Log in at `/admin/login.php`, update admin password, then set SMTP in `/admin/settings.php`.

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
- CSRF protection for admin forms and public AJAX spin/submission endpoints
- Session-based spin lock (one spin per session)
- Session-bound prize submission validation
- Upload MIME/size checks for sponsor logos

## GitHub push notes
If your local branch is `work` and you want it on remote `main`:
```bash
git push -u origin work:main
```

If you get `CONNECT tunnel failed, response 403`, your environment proxy is blocking GitHub:
```bash
unset HTTP_PROXY HTTPS_PROXY http_proxy https_proxy ALL_PROXY all_proxy
```
Then retry push.

## Folder layout
- `config/` app config
- `includes/` shared PHP modules
- `assets/` CSS/JS
- `admin/` secure admin panel
- `uploads/` sponsor logos
- `sql/` schema
