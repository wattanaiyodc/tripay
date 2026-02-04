<?php
session_start();
include('../db.php');


if (!isset($_SESSION['user_id'])) {
    exit(json_encode([
        'status'  => 'error',
        'message' => 'no user'
    ]));
}

$user_id = (int)($_SESSION['user_id']);

$data = json_decode($_POST['json'] ?? '', true);
if (!is_array($data)) {
    exit(json_encode([
        'status'  => 'error',
        'message' => 'invalid payload'
    ]));
}
$trip_id   = (int)($data['trip_id'] ?? 0);
$member_id = (int)($data['member_id'] ?? 0);

if ($trip_id <= 0 || $member_id <= 0) {
    exit(json_encode([
        'status'  => 'error',
        'message' => 'missing trip_id or member_id'
    ]));
}

/* ===== CHECK ROLE MASTER ===== */
$sth = $pdo2->prepare("
    SELECT role
    FROM members
    WHERE user_id = :user_id
      AND trip_id = :trip_id
    LIMIT 1
");
$sth->execute([
    ':user_id' => $user_id,
    ':trip_id' => $trip_id
]);

$role = $sth->fetchColumn();

if ($role !== 'master') {
    exit(json_encode([
        'status'  => 'error',
        'message' => 'no permission'
    ]));
}

/* ===== DELETE MEMBER ===== */
try {
    $pdo2->beginTransaction();

    // กัน master ลบตัวเอง (ถ้าคุณไม่อยากกัน ลบบล็อคนี้ทิ้งได้)
    $sth = $pdo2->prepare("
        SELECT user_id
        FROM members
        WHERE member_id = :member_id
        LIMIT 1
    ");
    $sth->execute([
        ':member_id' => $member_id,
    ]);

    $target_user_id = (int)$sth->fetchColumn();

    if ($target_user_id <= 0) {
        throw new Exception("ไม่พบสมาชิกที่ต้องการลบ");
    }

    if ($target_user_id === $user_id) {
        throw new Exception("ไม่สามารถลบตัวเองได้");
    }

    // ลบจริง
    $sth = $pdo2->prepare("
        DELETE FROM members
        WHERE member_id = :member_id
        LIMIT 1
    ");
    $sth->execute([
        ':member_id' => $member_id
    ]);

    if ($sth->rowCount() <= 0) {
        throw new Exception("ลบไม่สำเร็จ หรือถูกลบไปแล้ว");
    }

    $pdo2->commit();

    exit(json_encode([
        'status'  => 'success',
        'message' => 'delete success'
    ]));
} catch (Exception $e) {

    if ($pdo2->inTransaction()) {
        $pdo2->rollBack();
    }

    exit(json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]));
}
