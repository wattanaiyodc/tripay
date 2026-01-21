<?php
session_start();
require '../db.php';
header('Content-Type: application/json');

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

/* ===== validate required ===== */
$required = ['trip_id', 'tl_date', 'tl_starttime', 'tl_title'];
foreach ($required as $key) {
    if (empty($data[$key])) {
        exit(json_encode([
            'status'  => 'error',
            'message' => "missing field: {$key}"
        ]));
    }
}

$trip_id   = (int)$data['trip_id'];
$date      = trim($data['tl_date']);        // YYYY-MM-DD
$start     = trim($data['tl_starttime']);   // HH:MM
$end       = trim($data['tl_endtime'] ?? '');
$title     = trim($data['tl_title']);
$location  = trim($data['tl_location'] ?? '');
$detail    = trim($data['tl_detail'] ?? '');

/* ===== compose DATETIME ===== */
$start_time = $date . ' ' . $start . ':00';
$end_time   = $end !== '' ? $date . ' ' . $end . ':00' : null;

try {
    $pdo2->beginTransaction();

    $sql = "
        INSERT INTO timeline
        (trip_id, activity_date, start_time, end_time, title, location, detail)
        VALUES
        (:trip_id, :activity_date, :start_time, :end_time, :title, :location, :detail)
    ";

    $sth = $pdo2->prepare($sql);
    $sth->execute([
        ':trip_id'        => $trip_id,
        ':activity_date' => $date,
        ':start_time'    => $start_time,
        ':end_time'      => $end_time,
        ':title'         => $title,
        ':location'      => $location,
        ':detail'        => $detail
    ]);

    $pdo2->commit();

    echo json_encode([
        'status'  => 'success',
        'message' => 'timeline created'
    ]);
    exit;
} catch (Throwable $e) {

    if ($pdo2->inTransaction()) {
        $pdo2->rollBack();
    }

    echo json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]);
    exit;
}
