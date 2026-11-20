<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Course Management';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        $stmt = $pdo->prepare('DELETE FROM courses WHERE id=?'); $stmt->execute([(int)$_POST['id']]);
        flash('success','Course record deleted successfully.'); redirect('courses.php');
    }
    $id=(int)($_POST['id']??0); $code=trim($_POST['course_code']??''); $name=trim($_POST['course_name']??'');
    $lecturer=trim($_POST['lecturer']??''); $enrolled=max(0,(int)($_POST['enrolled']??0)); $capacity=max(1,(int)($_POST['capacity']??30));
    if($code===''||$name===''){flash('danger','Please enter the course code and course name.');redirect('courses.php'.($id?'?action=edit&id='.$id:'?action=add'));}
    try {
        if($action==='update'){
            $stmt=$pdo->prepare('UPDATE courses SET course_code=?,course_name=?,lecturer=?,enrolled=?,capacity=? WHERE id=?');
            $stmt->execute([$code,$name,$lecturer,$enrolled,$capacity,$id]); flash('success','Course updated successfully.');
        } else {
            $stmt=$pdo->prepare('INSERT INTO courses(course_code,course_name,lecturer,enrolled,capacity) VALUES(?,?,?,?,?)');
            $stmt->execute([$code,$name,$lecturer,$enrolled,$capacity]); flash('success','New course added successfully.');
        }
    } catch(PDOException $ex){flash('danger',$ex->getCode()==='23000'?'That course code already exists.':'Unable to save this course.');}
    redirect('courses.php');
}
$action=$_GET['action']??''; $editing=null;
if($action==='edit'&&isset($_GET['id'])){$stmt=$pdo->prepare('SELECT * FROM courses WHERE id=?');$stmt->execute([(int)$_GET['id']]);$editing=$stmt->fetch();if(!$editing){flash('danger','Course not found.');redirect('courses.php');}}
$search=trim($_GET['search']??'');
if($search!==''){$stmt=$pdo->prepare('SELECT * FROM courses WHERE course_code LIKE ? OR course_name LIKE ? OR lecturer LIKE ? ORDER BY id DESC');$like="%{$search}%";$stmt->execute([$like,$like,$like]);$courses=$stmt->fetchAll();}
else{$courses=$pdo->query('SELECT * FROM courses ORDER BY id DESC')->fetchAll();}
require __DIR__ . '/includes/header.php';
?>
<div class="content-heading"><div><h2>Course records</h2><p class="muted">Organise course details and monitor enrolment numbers.</p></div><a class="btn btn-primary" href="courses.php?action=add"><i class="bi bi-plus-lg me-2"></i>Add course</a></div>
<?php if($action==='add'||$action==='edit'):$v=$editing??['course_code'=>'','course_name'=>'','lecturer'=>'','enrolled'=>0,'capacity'=>30];?>
<div class="panel form-panel"><div class="panel-heading"><div><h3><?= $editing?'Edit course':'Add new course' ?></h3><p class="muted">Enter the course information below.</p></div><a class="btn btn-light btn-sm" href="courses.php">Back to list</a></div>
<form method="post" class="row g-3"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="<?= $editing?'update':'create' ?>"><input type="hidden" name="id" value="<?= (int)($v['id']??0) ?>">
<div class="col-md-6"><label class="form-label">Course code *</label><input class="form-control" name="course_code" required value="<?= e($v['course_code']) ?>" placeholder="e.g. IMS566"></div>
<div class="col-md-6"><label class="form-label">Course name *</label><input class="form-control" name="course_name" required value="<?= e($v['course_name']) ?>" placeholder="Course title"></div>
<div class="col-md-6"><label class="form-label">Lecturer</label><input class="form-control" name="lecturer" value="<?= e($v['lecturer']) ?>" placeholder="Lecturer name"></div>
<div class="col-md-3"><label class="form-label">Enrolled students</label><input class="form-control" type="number" min="0" name="enrolled" value="<?= (int)$v['enrolled'] ?>"></div>
<div class="col-md-3"><label class="form-label">Capacity</label><input class="form-control" type="number" min="1" name="capacity" value="<?= (int)$v['capacity'] ?>"></div>
<div class="col-12 form-actions"><button class="btn btn-primary" type="submit">Save course</button><a class="btn btn-light" href="courses.php">Cancel</a></div></form></div>
<?php else:?>
<div class="panel"><div class="list-toolbar"><form method="get" class="search-form"><i class="bi bi-search"></i><input type="search" name="search" value="<?= e($search) ?>" placeholder="Search courses..."><button class="btn btn-light btn-sm" type="submit">Search</button></form><span class="muted small"><?= count($courses) ?> record(s) found</span></div>
<div class="table-responsive"><table class="table app-table align-middle"><thead><tr><th>Course</th><th>Course code</th><th>Lecturer</th><th>Enrolled</th><th>Capacity</th><th>Progress</th><th class="text-end">Actions</th></tr></thead><tbody>
<?php foreach($courses as $c):$pct=min(100,(int)round(((int)$c['enrolled']/max(1,(int)$c['capacity']))*100));?><tr><td><strong><?= e($c['course_name']) ?></strong></td><td><span class="code-pill"><?= e($c['course_code']) ?></span></td><td><?= e($c['lecturer']?:'—') ?></td><td><?= (int)$c['enrolled'] ?></td><td><?= (int)$c['capacity'] ?></td><td><div class="progress-cell"><div class="progress"><div class="progress-bar" style="width:<?= $pct ?>%"></div></div><small><?= $pct ?>%</small></div></td><td><div class="table-actions"><a class="icon-btn" title="Edit" href="courses.php?action=edit&id=<?= (int)$c['id'] ?>"><i class="bi bi-pencil"></i></a><form method="post" onsubmit="return confirm('Delete this course record?')"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$c['id'] ?>"><button class="icon-btn danger" title="Delete" type="submit"><i class="bi bi-trash3"></i></button></form></div></td></tr><?php endforeach;?>
<?php if(!$courses):?><tr><td colspan="7"><div class="empty-state compact"><i class="bi bi-journal-x"></i><strong>No courses found</strong><span>Add a course to get started.</span></div></td></tr><?php endif;?>
</tbody></table></div></div>
<?php endif;?>
<?php require __DIR__ . '/includes/footer.php'; ?>
