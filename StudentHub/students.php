<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Student Management';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        $stmt = $pdo->prepare('DELETE FROM students WHERE id = ?');
        $stmt->execute([(int)$_POST['id']]);
        flash('success', 'Student record deleted successfully.');
        redirect('students.php');
    }
    $id = (int)($_POST['id'] ?? 0);
    $studentNo = trim($_POST['student_no'] ?? '');
    $name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $programme = trim($_POST['programme'] ?? '');
    $year = (int)($_POST['year_of_study'] ?? 1);
    $status = $_POST['status'] ?? 'Active';
    if ($studentNo === '' || $name === '' || $email === '' || $programme === '') {
        flash('danger', 'Please complete all required fields.');
        redirect('students.php' . ($id ? '?action=edit&id='.$id : '?action=add'));
    }
    try {
        if ($action === 'update') {
            $stmt = $pdo->prepare('UPDATE students SET student_no=?, full_name=?, email=?, programme=?, year_of_study=?, status=? WHERE id=?');
            $stmt->execute([$studentNo,$name,$email,$programme,$year,$status,$id]);
            flash('success', 'Student record updated successfully.');
        } else {
            $stmt = $pdo->prepare('INSERT INTO students (student_no, full_name, email, programme, year_of_study, status) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$studentNo,$name,$email,$programme,$year,$status]);
            flash('success', 'New student added successfully.');
        }
    } catch (PDOException $ex) {
        flash('danger', $ex->getCode() === '23000' ? 'Student ID or email already exists.' : 'Unable to save this student record.');
    }
    redirect('students.php');
}
$action = $_GET['action'] ?? '';
$editing = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM students WHERE id=?');
    $stmt->execute([(int)$_GET['id']]);
    $editing = $stmt->fetch();
    if (!$editing) { flash('danger','Student record not found.'); redirect('students.php'); }
}
$search = trim($_GET['search'] ?? '');
if ($search !== '') {
    $stmt = $pdo->prepare('SELECT * FROM students WHERE student_no LIKE ? OR full_name LIKE ? OR email LIKE ? OR programme LIKE ? ORDER BY id DESC');
    $like = "%{$search}%"; $stmt->execute([$like,$like,$like,$like]); $students = $stmt->fetchAll();
} else {
    $students = $pdo->query('SELECT * FROM students ORDER BY id DESC')->fetchAll();
}
require __DIR__ . '/includes/header.php';
?>
<div class="content-heading"><div><h2>Student records</h2><p class="muted">Add, update, search and manage student information.</p></div><a class="btn btn-primary" href="students.php?action=add"><i class="bi bi-plus-lg me-2"></i>Add student</a></div>
<?php if ($action === 'add' || $action === 'edit'): $v = $editing ?? ['student_no'=>'','full_name'=>'','email'=>'','programme'=>'','year_of_study'=>1,'status'=>'Active']; ?>
<div class="panel form-panel"><div class="panel-heading"><div><h3><?= $editing ? 'Edit student' : 'Add new student' ?></h3><p class="muted">Fields marked with * are required.</p></div><a class="btn btn-light btn-sm" href="students.php">Back to list</a></div>
<form method="post" class="row g-3"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="<?= $editing ? 'update' : 'create' ?>"><input type="hidden" name="id" value="<?= (int)($v['id'] ?? 0) ?>">
<div class="col-md-6"><label class="form-label">Student ID *</label><input class="form-control" name="student_no" required value="<?= e($v['student_no']) ?>" placeholder="e.g. 20260001"></div>
<div class="col-md-6"><label class="form-label">Full name *</label><input class="form-control" name="full_name" required value="<?= e($v['full_name']) ?>" placeholder="Student full name"></div>
<div class="col-md-6"><label class="form-label">Email address *</label><input class="form-control" type="email" name="email" required value="<?= e($v['email']) ?>" placeholder="student@example.com"></div>
<div class="col-md-6"><label class="form-label">Programme *</label><input class="form-control" name="programme" required value="<?= e($v['programme']) ?>" placeholder="e.g. Information Management"></div>
<div class="col-md-6"><label class="form-label">Year of study</label><select class="form-select" name="year_of_study"><?php for($i=1;$i<=4;$i++): ?><option value="<?= $i ?>" <?= (int)$v['year_of_study']===$i?'selected':'' ?>>Year <?= $i ?></option><?php endfor; ?></select></div>
<div class="col-md-6"><label class="form-label">Status</label><select class="form-select" name="status"><option <?= $v['status']==='Active'?'selected':'' ?>>Active</option><option <?= $v['status']==='Inactive'?'selected':'' ?>>Inactive</option></select></div>
<div class="col-12 form-actions"><button class="btn btn-primary" type="submit">Save student</button><a class="btn btn-light" href="students.php">Cancel</a></div></form></div>
<?php else: ?>
<div class="panel"><div class="list-toolbar"><form method="get" class="search-form"><i class="bi bi-search"></i><input type="search" name="search" value="<?= e($search) ?>" placeholder="Search students..."><button class="btn btn-light btn-sm" type="submit">Search</button></form><span class="muted small"><?= count($students) ?> record(s) found</span></div>
<div class="table-responsive"><table class="table app-table align-middle"><thead><tr><th>Student</th><th>Student ID</th><th>Email</th><th>Programme</th><th>Year</th><th>Status</th><th class="text-end">Actions</th></tr></thead><tbody>
<?php foreach($students as $s): ?><tr><td><div class="person-cell"><span class="table-avatar"><?= e(strtoupper(substr($s['full_name'],0,1))) ?></span><strong><?= e($s['full_name']) ?></strong></div></td><td><?= e($s['student_no']) ?></td><td><?= e($s['email']) ?></td><td><?= e($s['programme']) ?></td><td>Year <?= (int)$s['year_of_study'] ?></td><td><span class="status-pill <?= strtolower($s['status']) ?>"><?= e($s['status']) ?></span></td><td><div class="table-actions"><a class="icon-btn" title="Edit" href="students.php?action=edit&id=<?= (int)$s['id'] ?>"><i class="bi bi-pencil"></i></a><form method="post" onsubmit="return confirm('Delete this student record?')"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$s['id'] ?>"><button class="icon-btn danger" title="Delete" type="submit"><i class="bi bi-trash3"></i></button></form></div></td></tr><?php endforeach; ?>
<?php if(!$students): ?><tr><td colspan="7"><div class="empty-state compact"><i class="bi bi-search"></i><strong>No matching students</strong><span>Try another search or add a new student record.</span></div></td></tr><?php endif; ?>
</tbody></table></div></div>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
