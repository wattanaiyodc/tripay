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
try{
    $sql = "se";
    $sth = $pdo2->prepare($sql);
    $sth->execute();
    if ($sth->errorInfo()[0] != "00000" && !empty($sth->errorInfo()[0])) {
      $answer["message"] = (empty($sth->errorInfo()[2])) ? $sth->errorInfo()[0] : $sth->errorInfo()[2];
      exit(json_encode($answer));
    }
} catch (Exception $e) {

    if ($pdo2->inTransaction()) {
        $pdo2->rollBack();
    }

    exit(json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]));
}