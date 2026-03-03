<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function site_setting(string $key, ?string $default = null): ?string
{
    $stmt = db()->prepare('SELECT setting_value FROM settings WHERE setting_key = :key LIMIT 1');
    $stmt->execute(['key' => $key]);
    $row = $stmt->fetch();
    return $row['setting_value'] ?? $default;
}

function set_site_setting(string $key, string $value): void
{
    $sql = 'INSERT INTO settings (setting_key, setting_value) VALUES (:k, :v)
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)';
    $stmt = db()->prepare($sql);
    $stmt->execute(['k' => $key, 'v' => $value]);
}

function current_admin(): ?array
{
    if (empty($_SESSION['admin_id'])) {
        return null;
    }

    $stmt = db()->prepare('SELECT id, username FROM admins WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => (int) $_SESSION['admin_id']]);
    $admin = $stmt->fetch();

    return $admin ?: null;
}

function require_admin(): void
{
    if (!current_admin()) {
        header('Location: login.php');
        exit;
    }
}

function weighted_random_prize(array $prizes): array
{
    $totalWeight = 0;
    foreach ($prizes as $prize) {
        $totalWeight += max(1, (int) $prize['weight']);
    }

    $random = random_int(1, $totalWeight);
    $running = 0;

    foreach ($prizes as $prize) {
        $running += max(1, (int) $prize['weight']);
        if ($random <= $running) {
            return $prize;
        }
    }

    return $prizes[array_key_last($prizes)];
}

function json_response(array $payload, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit;
}
