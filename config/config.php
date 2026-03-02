<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const DB_HOST = '127.0.0.1';
const DB_NAME = 'spin2win';
const DB_USER = 'root';
const DB_PASS = '';
const DB_CHARSET = 'utf8mb4';

const BASE_URL = '/';
const UPLOAD_DIR = __DIR__ . '/../uploads/';
const UPLOAD_URL = 'uploads/';

const ADMIN_EMAIL_FALLBACK = 'admin@example.com';

const APP_TIMEZONE = 'UTC';
date_default_timezone_set(APP_TIMEZONE);
