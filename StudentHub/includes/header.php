<?php
$pageTitle = $pageTitle ?? 'Dashboard';
$currentPage = basename($_SERVER['PHP_SELF']);
$flashMessage = get_flash();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="StudentHub student and course management system">
    <title><?= e($pageTitle) ?> | StudentHub</title>
    <link rel="icon" type="image/svg+xml" href="assets/img/favicon.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script>
      (() => {
        const saved = localStorage.getItem('studenthub-theme');
        if (saved) document.documentElement.setAttribute('data-theme', saved);
      })();
    </script>
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <a class="brand" href="dashboard.php">
            <span class="brand-mark"><i class="bi bi-mortarboard-fill"></i></span>
            <span>Student<span class="brand-light">Hub</span></span>
        </a>
        <div class="sidebar-label">MAIN MENU</div>
        <nav class="side-nav">
            <a class="<?= $currentPage === 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php"><i class="bi bi-grid-1x2"></i><span>Dashboard</span></a>
            <a class="<?= $currentPage === 'students.php' ? 'active' : '' ?>" href="students.php"><i class="bi bi-people"></i><span>Students</span></a>
            <a class="<?= $currentPage === 'courses.php' ? 'active' : '' ?>" href="courses.php"><i class="bi bi-journal-bookmark"></i><span>Courses</span></a>
        </nav>
        <div class="sidebar-bottom">
            <div class="sidebar-label">PREFERENCES</div>
            <button class="side-link theme-toggle" type="button" id="themeToggle"><i class="bi bi-moon-stars"></i><span>Dark mode</span></button>
            <a class="side-link" href="logout.php"><i class="bi bi-box-arrow-left"></i><span>Log out</span></a>
            <div class="sidebar-user">
                <div class="avatar"><?= e(strtoupper(substr($_SESSION['full_name'] ?? 'S', 0, 1))) ?></div>
                <div class="user-meta"><strong><?= e($_SESSION['full_name'] ?? 'StudentHub User') ?></strong><small>Administrator</small></div>
            </div>
        </div>
    </aside>

    <main class="main-area">
        <header class="topbar">
            <button class="mobile-menu" id="mobileMenu" aria-label="Open navigation"><i class="bi bi-list"></i></button>
            <div>
                <div class="topbar-eyebrow">STUDENT MANAGEMENT</div>
                <h1><?= e($pageTitle) ?></h1>
            </div>
            <div class="topbar-right">
                <span class="date-label"><i class="bi bi-calendar3"></i> <?= date('d M Y') ?></span>
                <div class="avatar avatar-small"><?= e(strtoupper(substr($_SESSION['full_name'] ?? 'S', 0, 1))) ?></div>
            </div>
        </header>
        <section class="page-content">
            <?php if ($flashMessage): ?>
                <div class="alert alert-<?= e($flashMessage['type']) ?> alert-dismissible fade show" role="alert">
                    <?= e($flashMessage['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
