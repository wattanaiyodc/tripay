<?php
session_start();
include('../db.php');

$answer = array("success" => 0, "message" => "");

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

$trip_id = $data["trip_id"];
$user_id = $data["user_id"];

try{
    $sql = "select *
            from notification
            where user_id = :user_id and
                  trip_id = :trip_id";
    $sth = $pdo2->prepare($sql);
    $sth->execute([
        ":user_id"   => $user_id,
        ":trip_id"   => $trip_id
    ]);
    if ($sth->errorInfo()[0] != "00000" && !empty($sth->errorInfo()[0])) {
      $answer["message"] = (empty($sth->errorInfo()[2])) ? $sth->errorInfo()[0] : $sth->errorInfo()[2];
      exit(json_encode($answer));
    }
    $answer["result"] = array();
    while($r = $sth->fetch(PDO::FETCH_ASSOC)){
        array_push($answer["result"], $r);
    }

    $sql = "select count(*)
            from notification
            where user_id = :user_id and
                  trip_id = :trip_id and
                  is_read = 0";
    $sth = $pdo2->prepare($sql);
    $sth->execute([
        ":user_id"   => $user_id,
        ":trip_id"   => $trip_id
    ]);
    if ($sth->errorInfo()[0] != "00000" && !empty($sth->errorInfo()[0])) {
        $answer["message"] = (empty($sth->errorInfo()[2])) ? $sth->errorInfo()[0] : $sth->errorInfo()[2];
        exit(json_encode($answer));
    }
    $count = $sth->fetchColumn();
    $answer["count"]   = $count;
    $answer["success"] = 1;
    $answer["message"] = 'success';
    exit(json_encode($answer));
} catch (Exception $e) {

    $answer["message"] = $e->getMessage();
}