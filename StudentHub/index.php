<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
if (!empty($_SESSION['user_id'])) redirect('dashboard.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id, username, full_name, password FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        redirect('dashboard.php');
    }
    $error = 'Incorrect username or password. Please try again.';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Sign in to StudentHub">
<title>Login | StudentHub</title>
<link rel="icon" type="image/svg+xml" href="assets/img/favicon.svg">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="auth-page">
<div class="auth-wrap">
    <div class="auth-brand"><span class="brand-mark"><i class="bi bi-mortarboard-fill"></i></span><span>Student<span class="brand-light">Hub</span></span></div>
    <div class="auth-card">
        <div class="auth-icon"><i class="bi bi-person-lock"></i></div>
        <h1>Welcome back</h1>
        <p class="muted">Sign in to continue to your dashboard.</p>
        <?php if ($error): ?><div class="alert alert-danger py-2"><?= e($error) ?></div><?php endif; ?>
        <?php if (isset($_GET['registered'])): ?><div class="alert alert-success py-2">Registration successful. You can now log in.</div><?php endif; ?>
        <form method="post" autocomplete="on">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <label class="form-label" for="username">Username</label>
            <div class="input-icon"><i class="bi bi-person"></i><input class="form-control" id="username" name="username" required autofocus placeholder="Enter your username" value="<?= e($_POST['username'] ?? '') ?>"></div>
            <label class="form-label mt-3" for="password">Password</label>
            <div class="input-icon"><i class="bi bi-lock"></i><input class="form-control" id="password" name="password" type="password" required placeholder="Enter your password"></div>
            <button class="btn btn-primary w-100 auth-submit" type="submit">Sign in <i class="bi bi-arrow-right ms-2"></i></button>
        </form>
        <p class="auth-switch">Don't have an account? <a href="register.php">Create an account</a></p>
    </div>
    <p class="auth-foot">A simpler way to manage student information.</p>
</div>
</body></html>
