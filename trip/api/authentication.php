<?php
session_start();
include('db.php');


if (!isset($_SESSION['user_id'])) {
    exit(json_encode([
        'status'  => 'error',
        'message' => 'no user'
    ]));
}

$user_id = $_SESSION['user_id'];
try{
    $sth = $pdo2->prepare("
    SELECT role
    FROM users
    WHERE user_id = :user_id
    LIMIT 1
");
    $sth->execute([':user_id' => $user_id]);
    if ($sth->errorInfo()[0] != "00000" && !empty($sth->errorInfo()[0])) {
      $answer["message"] = (empty($sth->errorInfo()[2])) ? $sth->errorInfo()[0] : $sth->errorInfo()[2];
      exit(json_encode($answer));
    }
    $role = $sth->fetch(PDO::FETCH_ASSOC);
    exit(json_encode([
        'role' => $role,
        'status' => 'success'
    ]));
} catch (PDOException $e) {
    exit(json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]));
}