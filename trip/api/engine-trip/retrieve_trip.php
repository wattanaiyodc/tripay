<?php
session_start();
include('../db.php');

$user_id = (int)$_SESSION{"user_id"};

if(!isset($_SESSION["user_id"])){
    exit(json_encode([
        'status'  => 'error',
        'message' => 'no user'
    ]));
}


try {

    $sql = 'SELECT t.*
            FROM trips t
            INNER JOIN members m ON m.trip_id = t.trip_id
            WHERE m.user_id = :user_id
            ORDER BY t.trip_id DESC;
            ';
    $sth = $pdo2->prepare($sql);
    $sth->execute([
        ':user_id' => $user_id
    ]);

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
