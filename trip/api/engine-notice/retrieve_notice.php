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
            where ";
    $sth = $pdo2->prepare($sql);
    $sth->execute();
    if ($sth->errorInfo()[0] != "00000" && !empty($sth->errorInfo()[0])) {
      $answer["message"] = (empty($sth->errorInfo()[2])) ? $sth->errorInfo()[0] : $sth->errorInfo()[2];
      exit(json_encode($answer));
    }
} catch (Exception $e) {

    $pdo2->rollBack();
    $answer["message"] = $e->getMessage();
}