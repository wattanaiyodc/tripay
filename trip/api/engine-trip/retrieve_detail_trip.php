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

$user_id = (int)$_SESSION['user_id'];
$trip_id = (int)$data['trip_id'];
$qr_id   = (int)$data["qr_id"];
try {

    $sql = "
        SELECT *
        FROM trips
        WHERE trip_id = :trip_id
        LIMIT 1
    ";
    $sth = $pdo2->prepare($sql);
    $sth->execute([
        ':trip_id' => $trip_id
    ]);

    if ($sth->errorInfo()[0] !== "00000") {
        exit(json_encode([
            'status'  => 'error',
            'message' => $sth->errorInfo()[2] ?? $sth->errorInfo()[0]
        ]));
    }

    $trip = $sth->fetch(PDO::FETCH_ASSOC);

    if (!$trip) {
        exit(json_encode([
            'status'  => 'error',
            'message' => 'trip not found'
        ]));
    }

    $sql = "select role 
            from members
            where trip_id = :trip_id and
                  user_id = :user_id
            limit 1 ";
    $sth = $pdo2->prepare($sql);
    $sth->execute([
        ":trip_id"   => $trip_id,
        ":user_id"   => $user_id
    ]);
    if ($sth->errorInfo()[0] != "00000" && !empty($sth->errorInfo()[0])) {
      $answer["message"] = (empty($sth->errorInfo()[2])) ? $sth->errorInfo()[0] : $sth->errorInfo()[2];
      exit(json_encode($answer));
    }
    $permission = $sth->fetchColumn();
    if (!empty($qr_id)) {
        $sql = "select amount from qr_request where trip_id = :trip_id and qr_id = :qr_id";
        $sth = $pdo2->prepare($sql);
        $sth->execute([
            ":trip_id"   => $trip_id,
            ":qr_id"     => $qr_id
        ]);
        if ($sth->errorInfo()[0] != "00000" && !empty($sth->errorInfo()[0])) {
          $answer["message"] = (empty($sth->errorInfo()[2])) ? $sth->errorInfo()[0] : $sth->errorInfo()[2];
          exit(json_encode($answer));
        }
        $answer["amount"] = $sth->fetchColumn();
    }
    $answer["role"]   = $permission;
    $answer["status"] = 'success';
    $answer["result"] = $trip;
   
    exit(json_encode($answer));
} catch (PDOException $e) {
    exit(json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]));
}
