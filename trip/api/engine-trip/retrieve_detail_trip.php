<?php
session_start();
include('../db.php');

header('Content-Type: application/json');

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

    echo json_encode([
        'status' => 'success',
        'result' => $trip   
    ]);
    exit();
} catch (PDOException $e) {
    exit(json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]));
}
