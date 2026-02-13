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

/* ===== prepare ===== */
$user_id = (int)($data['user_id'] ?? 0);
$trip_id = (int)($data['trip_id'] ?? 0);
$type    = $data['type'] ?? '';
$detail  = $data['detail'] ?? [];

$amount  = (float)($detail['slip_amount'] ?? 0);
$tx_date = $detail['slip_tx_date'] ?? '';
$tx_time = $detail['slip_tx_time'] ?? '';
$desc    = $detail['slip_to'] ?? 'Slip import';
$ref     = $detail["slip_ref"];
$note    = $data["note"] ?? '';
$source  = $data["source"] ?? 'manual'; 

/* ===== validate ===== */
if ($trip_id <= 0) {
    exit(json_encode([
        'status' => 'error',
        'message' => 'trip_id invalid'
    ]));
}

if (!in_array($type, ['DR', 'CR'], true)) {
    exit(json_encode([
        'status' => 'error',
        'message' => 'type invalid'
    ]));
}

if ($amount <= 0) {
    exit(json_encode([
        'status' => 'error',
        'message' => 'amount invalid'
    ]));
}

/* ===== map DR / CR ===== */
$debit  = 0;
$credit = 0;

if ($type === 'DR') {
    $debit = $amount;
} else {
    $credit = $amount;
}

/* ===== tx_datetime ===== */
$tx_datetime = null;
if ($tx_date && $tx_time) {
    $tx_datetime = $tx_date . ' ' . $tx_time;
}

/* ===== insert ===== */
try {
    $sql = "
        INSERT INTO trip_transaction (
            trip_id,
            user_id,
            tx_date,
            tx_datetime,
            description,
            debit,
            credit,
            category,
            note,
            detail,
            reference

        ) VALUES (
            :trip_id,
            :user_id,
            :tx_date,
            :tx_datetime,
            :description,
            :debit,
            :credit,
            '',
            :note,
            :detail,
            :reference

        )
    ";

    $sth = $pdo2->prepare($sql);
    $sth->execute([
        ':trip_id'     => $trip_id,
        ':user_id'     => $user_id,
        ':tx_date'     => $tx_date,
        ':tx_datetime' => $tx_datetime,
        ':description' => $desc,
        ':debit'       => $debit,
        ':credit'      => $credit,
        ':note'        => $note,
        ':detail' => json_encode($detail, JSON_UNESCAPED_UNICODE),
        ':reference'   => $ref
    ]);

    exit(json_encode([
        'success' => 1,
        'status' => 'success'
    ]));
} catch (PDOException $e) {
    exit(json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]));
}
