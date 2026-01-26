<?php
session_start();
include('../db.php');

if (!isset($_SESSION['user_id'])) {
    exit(json_encode([
        'status'  => 'error',
        'message' => 'no user'
    ]));
}

$data = json_decode($_POST['json'] ?? '', true);
if (!is_array($data)) {
    exit(json_encode([
        'status'  => 'error',
        'message' => 'invalid payload'
    ]));
}
$user_id = (int)$data['user_id'];
$trip_id = (int)$data['trip_id'];
$target_id = (int)$data['target_user_id'];

if (empty($data['trip_id'])) {
    exit(json_encode([
        'status'  => 'error',
        'message' => 'trip_id invalid'
    ]));
}

$sth = $pdo2->prepare("
    SELECT role
    FROM members
    WHERE user_id = :user_id and
          trip_id = :trip_id
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
// ============== START ==============
try {
    $pdo2->beginTransaction();

    $sql = "INSERT INTO members (trip_id, user_id, role)
            VALUES (:trip_id, :user_id, :role)";
    $sth = $pdo2->prepare($sql);

    $sth->execute([
        ':trip_id' => $trip_id,
        ':user_id' => $target_id,
        ':role'    => 'member'
    ]);

    if ($sth->errorInfo()[0] !== "00000") {
        $pdo2->rollBack();
        exit(json_encode([
            'status'  => 'error',
            'message' => $sth->errorInfo()[2] ?? $sth->errorInfo()[0]
        ]));
    }

    $pdo2->commit();

    exit(json_encode([
        'status'  => 'success',
        'message' => 'added'
    ]));
} catch (PDOException $e) {
    if ($pdo2->inTransaction()) {
        $pdo2->rollBack();
    }

    exit(json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]));
}
