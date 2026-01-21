<?php
require '../db.php';
header('Content-Type: application/json');

// ===== รับ JSON =====
$raw  = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!$data) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Invalid JSON'
    ]);
    exit;
}

$username = trim($data['username'] ?? '');
$username = strtolower($username);
$password = $data['password'] ?? '';
$email = $data['email'] ?? '';

// ===== validate =====
if ($username === '' || $password === '') {
    echo json_encode([
        'status'  => 'error',
        'message' => 'username และ password ห้ามว่าง'
    ]);
    exit;
}

if (preg_match('/\s/', $username)) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'username ห้ามมีเว้นวรรค'
    ]);
    exit;
}

if (strlen($password) < 4) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'password ต้องอย่างน้อย 4 ตัว'
    ]);
    exit;
}

// ===== check username ซ้ำ =====
$stmt = $pdo2->prepare("SELECT 1 FROM users WHERE username = ? LIMIT 1");
$stmt->execute([$username]);

if ($stmt->fetch()) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'username นี้ถูกใช้แล้ว'
    ]);
    exit;
}

$hash = md5($password);

// ===== insert =====
$stmt = $pdo2->prepare("
    INSERT INTO users (username, password)
    VALUES (?, ?)
");

$stmt->execute([$username, $hash]);

echo json_encode([
    'status'  => 'ok',
    'message' => 'สมัครสมาชิกสำเร็จ'
]);
