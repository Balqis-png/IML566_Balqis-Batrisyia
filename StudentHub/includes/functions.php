<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function redirect(string $url): never {
    header("Location: {$url}");
    exit;
}
function require_login(): void {
    if (empty($_SESSION['user_id'])) {
        redirect('index.php');
    }
}
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        exit('Your session expired. Please go back and try again.');
    }
}
function flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}
function get_flash(): ?array {
    $message = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $message;
}
