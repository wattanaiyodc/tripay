<?php
session_start();
require '../db.php';
header('Content-Type: application/json');

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
$id      = (int)$data["id"];
$qr_id   = (int)$data["qr_id"];
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

try{
    $pdo2->beginTransaction();
    $sql = "update qr_request_member
                    set status = 'paid'
                    where id = :id";
    $sth = $pdo2->prepare($sql);
    $sth->execute([
        ":id" => $id
    ]);
    if ($sth->errorInfo()[0] != "00000" && !empty($sth->errorInfo()[0])) {
      $answer["message"] = (empty($sth->errorInfo()[2])) ? $sth->errorInfo()[0] : $sth->errorInfo()[2];
      exit(json_encode($answer));
    }

    //update revelent
    include('update_revelent_qr.php');

    $pdo2->commit();
    $answer["status"] = 1;
    $answer["message"] = 'update qr: ' . $qr_id . ' success';
    exit(json_encode($answer));
} catch (PDOException $e) {
    exit(json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]));
}