<?php
// บรรทัดที่ 1 ต้องเริ่มด้วย <?php ทันที
ob_start(); 
include 'db.php';

$message = '';
$editUser = null;

// --- ส่วน Logic: บันทึก / แก้ไข / ลบ (ต้องอยู่ก่อน HTML) ---

// 1. ลบข้อมูล
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        header("Location: index.php?msg=deleted");
        exit;
    } catch (PDOException $e) { $message = "Error: " . $e->getMessage(); }
}

// 2. ดึงข้อมูลมาใส่ฟอร์มเพื่อแก้ไข
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $editUser = $stmt->fetch();
}

// 3. บันทึกข้อมูล (Insert หรือ Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if ($name != '' && $email != '') {
        try {
            if ($id) {
                // แก้ไขข้อมูล
                $stmt = $conn->prepare("UPDATE users SET fullname = ?, email = ? WHERE id = ?");
                $stmt->execute([$name, $email, $id]);
                header("Location: index.php?msg=updated");
            } else {
                // เพิ่มข้อมูลใหม่
                $stmt = $conn->prepare("INSERT INTO users (fullname, email) VALUES (?, ?)");
                $stmt->execute([$name, $email]);
                header("Location: index.php?msg=created");
            }
            exit;
        } catch (PDOException $e) { $message = "Error: " . $e->getMessage(); }
    } else {
        $message = "กรุณากรอกข้อมูลให้ครบถ้วน";
    }
}

// 4. ดึงรายการทั้งหมดมาแสดงผล
$users = $conn->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();

if (isset($_GET['msg'])) {
    $msgs = ['created'=>'เพิ่มข้อมูลสำเร็จ', 'updated'=>'แก้ไขข้อมูลสำเร็จ', 'deleted'=>'ลบข้อมูลสำเร็จ'];
    $message = $msgs[$_GET['msg']] ?? '';
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบจัดการข้อมูล - CMU AMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2><?php echo $editUser ? 'แก้ไขข้อมูล' : 'เพิ่มข้อมูลผู้ใช้งาน'; ?></h2>
    
    <?php if($message): ?> <div class="message"><?php echo $message; ?></div> <?php endif; ?>

    <form method="POST" action="index.php">
        <?php if($editUser): ?> 
            <input type="hidden" name="id" value="<?php echo $editUser['id']; ?>"> 
        <?php endif; ?>
        
        <div class="form-group">
            <label>ชื่อ-นามสกุล</label>
            <input type="text" name="fullname" required value="<?php echo $editUser['fullname'] ?? ''; ?>">
        </div>
        <div class="form-group">
            <label>อีเมล</label>
            <input type="email" name="email" required value="<?php echo $editUser['email'] ?? ''; ?>">
        </div>
        
        <div style="margin-top: 20px;">
            <button type="submit" class="btn-submit">บันทึกข้อมูล</button>
            <?php if($editUser): ?> <a href="index.php" class="btn-cancel">ยกเลิก</a> <?php endif; ?>
        </div>
    </form>

    <table>
        <thead>
            <tr><th>ชื่อ-นามสกุล</th><th>อีเมล</th><th style="text-align:center;">จัดการ</th></tr>
        </thead>
        <tbody>
            <?php foreach($users as $u): ?>
            <tr>
                <td><?php echo htmlspecialchars($u['fullname']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td class="actions" style="text-align:center;">
                    <a href="index.php?action=edit&id=<?php echo $u['id']; ?>" class="edit">แก้ไข</a>
                    <a href="index.php?action=delete&id=<?php echo $u['id']; ?>" class="delete" onclick="return confirm('ยืนยันการลบข้อมูล?')">ลบ</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php ob_end_flush(); ?>