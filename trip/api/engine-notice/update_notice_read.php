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

//prepare variable
$trip_id = $data["trip_id"];
$user_id = $data["user_id"];
$notice_id = $data["notice_id"];
try{
    $pdo2->beginTransaction();
    $sql = "update notification
                set is_read = 1
                where trip_id = :trip_id and
                      user_id = :user_id and
                      notice_id = :notice_id";
    $sth = $pdo2->prepare($sql);
    $sth->execute([
        ":trip_id"   => $trip_id,
        ":user_id"   => $user_id,
        ":notice_id" => $notice_id
    ]);
    if ($sth->errorInfo()[0] != "00000" && !empty($sth->errorInfo()[0])) {
      $answer["message"] = (empty($sth->errorInfo()[2])) ? $sth->errorInfo()[0] : $sth->errorInfo()[2];
      exit(json_encode($answer));
    }
    $pdo2->commit();
    $answer["success"] = 1;
    $answer["message"] = "success";

} catch (Exception $e) {

    $answer["message"] = $e->getMessage();
}