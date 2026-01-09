<?php
session_start();
include '../db.php';
header('Content-Type: application/json');

/* ================== helper : upload image ================== */
function upload_trip_image($file)
{
    if (empty($file) || empty($file['name'])) {
        return null;
    }

    // จำกัดชนิดไฟล์
    $allow = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $allow)) {
        throw new Exception('ไฟล์รูปไม่ถูกต้อง');
    }

    // จำกัดขนาด (5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception('ไฟล์ใหญ่เกิน 5MB');
    }

    $base_dir = __DIR__ . '/../../../uploads/trip/';
    $public_dir = 'uploads/trip/';

    if (!is_dir($base_dir)) {
        mkdir($base_dir, 0777, true);
    }

    $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('trip_') . '.' . $ext;
    $fullpath = $base_dir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $fullpath)) {
        throw new Exception('อัปโหลดรูปไม่สำเร็จ');
    }

    // 👉 path ที่เก็บลง DB
    return $public_dir . $filename;
}

/* ================== auth ================== */
$user_id = (int)($_SESSION['user_id'] ?? 0);
if ($user_id === 0) {
    exit(json_encode(['status' => 'error', 'message' => 'no user']));
}

$sth = $pdo->prepare("
    SELECT role
    FROM users
    WHERE user_id = :user_id
    LIMIT 1
");
$sth->execute([':user_id' => $user_id]);

$role = $sth->fetchColumn();
if ($role !== 'master') {
    exit(json_encode([
        'status'  => 'error',
        'message' => 'no permission'
    ]));
}

/* ================== input ================== */
if (!isset($_POST['json'])) {
    exit(json_encode([
        'status'  => 'error',
        'message' => 'No data is received!'
    ]));
}

$data = json_decode($_POST['json'], true);

$trip_name = $data['trip_name'] ?? '';
$date_from = $data['date_from'] ?? null;
$date_to   = $data['date_to'] ?? null;
try {

    $pdo->beginTransaction();

    // ===== upload image =====
    $trip_image = null;
    if (
        isset($_FILES['trip_image']) &&
        $_FILES['trip_image']['error'] === UPLOAD_ERR_OK
    ) {
        $trip_image = upload_trip_image($_FILES['trip_image']);
    }


    // ===== insert =====
    $sql = "INSERT INTO trips (
                trip_name,
                owner_id,
                date_from,
                date_to,
                trip_image
            ) VALUES (
                :trip_name,
                :user_id,
                :date_from,
                :date_to,
                :trip_image
            )";

    $sth = $pdo->prepare($sql);
    $sth->execute([
        ':trip_name'  => $trip_name,
        ':user_id'    => $user_id,
        ':date_from'  => $date_from,
        ':date_to'    => $date_to,
        ':trip_image' => $trip_image
    ]);

    if ($sth->errorInfo()[0] !== "00000") {
        throw new Exception($sth->errorInfo()[2] ?? 'DB error');
    }

    $pdo->commit();

    echo json_encode([
        'status' => 'success'
    ]);
    exit;
} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]);
    exit;
}
