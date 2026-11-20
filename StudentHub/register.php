<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
if (!empty($_SESSION['user_id'])) redirect('dashboard.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $fullName = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if ($fullName === '' || $username === '' || strlen($password) < 6) {
        $error = 'Please complete all fields. Password must contain at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'The passwords do not match.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO users (full_name, username, password) VALUES (?, ?, ?)');
            $stmt->execute([$fullName, $username, password_hash($password, PASSWORD_DEFAULT)]);
            redirect('index.php?registered=1');
        } catch (PDOException $ex) {
            $error = $ex->getCode() === '23000' ? 'That username is already taken. Please choose another.' : 'Registration could not be completed. Please try again.';
        }
    }
}
?>
<!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Register | StudentHub</title><link rel="icon" type="image/svg+xml" href="assets/img/favicon.svg">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"><link href="assets/css/style.css" rel="stylesheet">
</head><body class="auth-page"><div class="auth-wrap">
<div class="auth-brand"><span class="brand-mark"><i class="bi bi-mortarboard-fill"></i></span><span>Student<span class="brand-light">Hub</span></span></div>
<div class="auth-card"><div class="auth-icon"><i class="bi bi-person-plus"></i></div><h1>Create account</h1><p class="muted">Register to start managing your student records.</p>
<?php if ($error): ?><div class="alert alert-danger py-2"><?= e($error) ?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<label class="form-label" for="full_name">Full name</label><input class="form-control" id="full_name" name="full_name" required value="<?= e($_POST['full_name'] ?? '') ?>" placeholder="Your full name">
<label class="form-label mt-3" for="username">Username</label><input class="form-control" id="username" name="username" required value="<?= e($_POST['username'] ?? '') ?>" placeholder="Choose a username">
<label class="form-label mt-3" for="password">Password</label><input class="form-control" id="password" name="password" type="password" minlength="6" required placeholder="At least 6 characters">
<label class="form-label mt-3" for="confirm_password">Confirm password</label><input class="form-control" id="confirm_password" name="confirm_password" type="password" minlength="6" required placeholder="Re-enter your password">
<button class="btn btn-primary w-100 auth-submit" type="submit">Create account <i class="bi bi-arrow-right ms-2"></i></button></form>
<p class="auth-switch">Already have an account? <a href="index.php">Sign in</a></p></div><p class="auth-foot">Your account details are saved securely in the local database.</p></div></body></html>
