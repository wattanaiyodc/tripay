<?php
session_start();
require '../db.php';
header('Content-Type: application/json');

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');


$stmt = $pdo->prepare("
    SELECT *
    FROM users
    WHERE username = ?
      AND password = ?
    LIMIT 1
");

$stmt->execute([
    $username,
    md5($password)
]);

$user = $stmt->fetch();

if ($user) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Username หรือ Password ไม่ถูกต้อง'
    ]);
}
