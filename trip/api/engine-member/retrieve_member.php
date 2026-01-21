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

if (empty($data['trip_id'])) {
    exit(json_encode([
        'status'  => 'error',
        'message' => 'trip_id invalid'
    ]));
}

$trip_id = $data["trip_id"]; 

try{
    $sql = "select b.first_name, b.last_name, b.role
            from members a
            inner join users b on a.user_id = b.user_id
            where trip_id = :trip_id";
    $sth = $pdo2->prepare($sql);
    $sth->execute([
        ':trip_id' => $trip_id
    ]);
    if ($sth->errorInfo()[0] != "00000" && !empty($sth->errorInfo()[0])) {
      $answer["message"] = (empty($sth->errorInfo()[2])) ? $sth->errorInfo()[0] : $sth->errorInfo()[2];
      exit(json_encode($answer));
    }
    $result = array();
    while($r = $sth->fetch(PDO::FETCH_ASSOC)){
        array_push($result,$r);
    }

    exit(json_encode([
        'status' => 'success',
        'result' => $result
    ]));
} catch (PDOException $e) {
    exit(json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]));
}