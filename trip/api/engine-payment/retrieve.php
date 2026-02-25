<?php
session_start();
include('../db.php');

if (!isset($_SESSION["user_id"])) {
    exit(json_encode([
        'status'  => 'error',
        'message' => 'no user'
    ]));
}

$data = json_decode($_POST['json'] ?? '', true);

if (empty($data['trip_id'])) {
    exit(json_encode([
        'status'  => 'error',
        'message' => 'trip_id invalid'
    ]));
}

$user_id = (int)$data['user_id'];
$trip_id = (int)$data['trip_id'];

try{

    $sql = "select a.qr_id, b.id, a.amount, a.detail, b.status, c.first_name, c.last_name
            from qr_request a
            left join qr_request_member b on a.qr_id = b.qr_id
            left join users c on b.user_id = c.user_id
            where a.trip_id = :trip_id";
    $sth = $pdo2->prepare($sql);
    $sth->execute([
        ":trip_id" => $trip_id
    ]);
    if ($sth->errorInfo()[0] != "00000" && !empty($sth->errorInfo()[0])) {
      $answer["message"] = (empty($sth->errorInfo()[2])) ? $sth->errorInfo()[0] : $sth->errorInfo()[2];
      exit(json_encode($answer));
    }
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    $answer["result"] = $result;
    $answer["message"] = 'success';
    $answer["status"] = 1;
    exit(json_encode($answer));

} catch (PDOException $e) {
    exit(json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]));
}
