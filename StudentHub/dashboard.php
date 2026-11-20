<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Dashboard';
$totalStudents = (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
$totalCourses = (int)$pdo->query('SELECT COUNT(*) FROM courses')->fetchColumn();
$activeStudents = (int)$pdo->query("SELECT COUNT(*) FROM students WHERE status = 'Active'")->fetchColumn();
$recentStudents = $pdo->query('SELECT * FROM students ORDER BY created_at DESC, id DESC LIMIT 5')->fetchAll();
$courseStats = $pdo->query('SELECT course_name, enrolled FROM courses ORDER BY course_name')->fetchAll();
require __DIR__ . '/includes/header.php';
?>
<div class="welcome-row"><div><h2>Hello, <?= e(explode(' ', $_SESSION['full_name'] ?? 'there')[0]) ?> <span>👋</span></h2><p class="muted mb-0">Here is what's happening with your student records today.</p></div><a class="btn btn-primary" href="students.php?action=add"><i class="bi bi-plus-lg me-2"></i>Add student</a></div>
<div class="stats-grid">
    <div class="stat-card"><div class="stat-top"><span>Total students</span><span class="stat-icon blue"><i class="bi bi-people"></i></span></div><div class="stat-number"><?= $totalStudents ?></div><div class="stat-note">Registered student records</div></div>
    <div class="stat-card"><div class="stat-top"><span>Total courses</span><span class="stat-icon purple"><i class="bi bi-journal-bookmark"></i></span></div><div class="stat-number"><?= $totalCourses ?></div><div class="stat-note">Available courses</div></div>
    <div class="stat-card"><div class="stat-top"><span>Active students</span><span class="stat-icon green"><i class="bi bi-person-check"></i></span></div><div class="stat-number"><?= $activeStudents ?></div><div class="stat-note">Currently active records</div></div>
</div>
<div class="dashboard-grid">
    <div class="panel chart-panel"><div class="panel-heading"><div><h3>Course enrolment</h3><p class="muted">Number of students enrolled in each course</p></div><span class="panel-badge">Overview</span></div>
        <?php if ($courseStats): ?><div class="chart-wrap"><canvas id="enrolmentChart" aria-label="Course enrolment chart"></canvas></div><script>window.courseChartData = <?= json_encode($courseStats, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>;</script>
        <?php else: ?><div class="empty-state"><i class="bi bi-bar-chart"></i><strong>No course data yet</strong><span>Add courses to see the enrolment chart.</span><a href="courses.php?action=add">Add your first course</a></div><?php endif; ?>
    </div>
    <div class="panel quick-panel"><div class="panel-heading"><div><h3>Quick actions</h3><p class="muted">Manage your information</p></div></div>
        <a class="quick-action" href="students.php?action=add"><span class="quick-icon blue"><i class="bi bi-person-plus"></i></span><span><strong>Add student</strong><small>Create a student record</small></span><i class="bi bi-chevron-right ms-auto"></i></a>
        <a class="quick-action" href="courses.php?action=add"><span class="quick-icon purple"><i class="bi bi-journal-plus"></i></span><span><strong>Add course</strong><small>Create a course record</small></span><i class="bi bi-chevron-right ms-auto"></i></a>
        <a class="quick-action" href="students.php"><span class="quick-icon green"><i class="bi bi-card-list"></i></span><span><strong>View students</strong><small>Search and update records</small></span><i class="bi bi-chevron-right ms-auto"></i></a>
    </div>
</div>
<div class="panel recent-panel"><div class="panel-heading"><div><h3>Recently added students</h3><p class="muted">The latest student records in your system</p></div><a class="text-link" href="students.php">View all <i class="bi bi-arrow-right"></i></a></div>
<?php if ($recentStudents): ?><div class="table-responsive"><table class="table app-table"><thead><tr><th>Student</th><th>Student ID</th><th>Programme</th><th>Status</th></tr></thead><tbody>
<?php foreach ($recentStudents as $student): ?><tr><td><div class="person-cell"><span class="table-avatar"><?= e(strtoupper(substr($student['full_name'],0,1))) ?></span><strong><?= e($student['full_name']) ?></strong></div></td><td><?= e($student['student_no']) ?></td><td><?= e($student['programme']) ?></td><td><span class="status-pill <?= strtolower($student['status']) ?>"><?= e($student['status']) ?></span></td></tr><?php endforeach; ?>
</tbody></table></div><?php else: ?><div class="empty-state compact"><strong>No student records</strong><span>Start by adding your first student.</span></div><?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
