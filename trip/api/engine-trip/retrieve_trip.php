<?php
session_start();
include('../db.php');

if(!isset($_SESSION["user_id"])){
    exit(json_encode([
        'status'  => 'error',
        'message' => 'no user'
    ]));
}

$user_id = (int)$_SESSION{"user_id"};

try {

    $sql = "SELECT * FROM trips";
    $sth = $pdo->prepare($sql);
    $sth->execute();

    if ($sth->errorInfo()[0] !== "00000") {
        echo json_encode([
            'status'  => 'error',
            'message' => $sth->errorInfo()[2] ?? $sth->errorInfo()[0]
        ]);
        exit();
    }

    echo json_encode([
        'status' => 'success',
        'result' => $sth->fetchAll(PDO::FETCH_ASSOC)
    ]);
    exit();

} catch (PDOException $e) {
    echo json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]);
    exit();
}
