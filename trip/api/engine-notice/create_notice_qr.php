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

$user_id = (int)$data["user_id"];
$trip_id = (int)$data["trip_id"];
$amount  = (float)$data["amount"];
$mode    = $data["to_mode"] ?? 'all';
$message = "มีคำขอชำระเงิน " . number_format($amount, 2) . " บาท";
$title = "มีคำขอชำระเงิน";

/* ================= BUILD RECEIVER LIST ================= */

$data["to_ids"] = $data["to_ids"] ?? [];

// ถ้าไม่มี to_ids = mode all
if (empty($data["to_ids"])) {

    $sql = "
        SELECT user_id
        FROM members
        WHERE trip_id = :trip_id
        AND user_id != :creator
    ";

    $sth = $pdo2->prepare($sql);
    $sth->execute([
        ':trip_id' => $trip_id,
        ':creator' => $user_id
    ]);

    $data["to_ids"] = $sth->fetchAll(PDO::FETCH_COLUMN);

    if (empty($data["to_ids"])) {
        exit(json_encode([
            "success" => 0,
            "message" => "no member in trip"
        ]));
    }
}

try {

    $pdo2->beginTransaction();

    /* ================= INSERT QR ================= */

    $sql  = "INSERT INTO qr_request (trip_id, creator_id, amount, detail, to_mode)
             VALUES (:trip_id, :user_id, :amount, :detail, :mode)";

    $sth = $pdo2->prepare($sql);
    $sth->execute([
        ':trip_id' => $trip_id,
        ':user_id' => $user_id,
        ':amount'  => $amount,
        ':detail'  => $message,
        ':mode'    => $mode
    ]);

    if ($sth->errorInfo()[0] !== "00000") {
        throw new Exception($sth->errorInfo()[2]);
    }

    $qr_id = $pdo2->lastInsertId();

    /* ================= INSERT qr_request_member ================= */

    $sql = "
            INSERT INTO qr_request_member
            (qr_id, user_id, status)
            VALUES
            ";

    $exe = [];
    $i   = 0;

    foreach ($data["to_ids"] as $user) {

        $sql .= ($i == 0 ? "" : ",");
        $sql .= "(:qr_id{$i}, :user_id{$i}, :status{$i})";

        $exe[":qr_id{$i}"]   = $qr_id;
        $exe[":user_id{$i}"] = (int)$user;
        $exe[":status{$i}"]  = 'pending';

        $i++;
    }

    $sth = $pdo2->prepare($sql);
    $sth->execute($exe);

    if ($sth->errorInfo()[0] !== "00000") {
        throw new Exception($sth->errorInfo()[2]);
    }

    /* ================= INSERT NOTIFICATION ================= */

    $sql = "
        INSERT INTO `notification`
        (`user_id`, `trip_id, `title`,`message`,`ref_type`,`ref_id`)
        VALUES
    ";

    $exe = [];
    $i   = 0;

    foreach ($data["to_ids"] as $user) {

        $sql .= ($i == 0 ? "" : ",");
        $sql .= "(
            :user_id{$i},
            :trip_id{$i},
            :title{$i},
            :message{$i},
            :ref_type{$i},
            :ref_id{$i}
        )";

        $exe[":user_id{$i}"]  = (int)$user;
        $exe[":trip_id{$i}"]  = (int)$trip_id;
        $exe[":title{$i}"]    = $title;
        $exe[":message{$i}"]  = $message;
        $exe[":ref_type{$i}"] = 'qr';
        $exe[":ref_id{$i}"]   = $qr_id;

        $i++;
    }

    $sth = $pdo2->prepare($sql);
    $sth->execute($exe);

    if ($sth->errorInfo()[0] !== "00000") {
        throw new Exception($sth->errorInfo()[2]);
    }

    $pdo2->commit();

    $answer["success"] = 1;
    $answer["message"] = "success";
} catch (Exception $e) {

    $pdo2->rollBack();
    $answer["message"] = $e->getMessage();
}

echo json_encode($answer);
exit;
