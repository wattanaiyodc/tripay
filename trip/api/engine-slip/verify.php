<?php 
$data = json_decode($_POST["json"] , true);
$qr = $data["qr"] ?? '';
if (!$qr) {
    exit(json_encode([
        'status' => 'error',
        'message' => 'QR string empty'
    ]));
}

$k = 'LVf2nurv0c0O14rK8ayw8vHLLA1cv+uOgzI3SBoEaYs=';
$ch = curl_init('https://connect.slip2go.com/api/verify-slip/qr-code/info');

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer '. $k,
        'Content-Type: application/json'
    ],
    CURLOPT_POSTFIELDS => json_encode([
        'payload' => [
            'qrCode' => $qr
        ]
    ])

]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

$slip = $data['data'] ?? [];

// แยกวันที่ / เวลา จาก dateTime
$txDateTime = $slip['dateTime'] ?? '';
$txDate = '';
$txTime = '';

if ($txDateTime) {
    $dt = new DateTime($txDateTime);
    $txDate = $dt->format('Y-m-d');
    $txTime = $dt->format('H:i:s');
}

exit(json_encode([
    'status' => 'success',
    'data' => [
        'bank_from' => $slip['sender']['bank']['name'] ?? '',
        'bank_to'   => $slip['receiver']['bank']['name'] ?? '',

        'amount'    => $slip['amount'] ?? 0,

        'tx_date'   => $txDate,
        'tx_time'   => $txTime,
        'tx_datetime' => $txDateTime,

        'from'      => $slip['sender']['account']['name'] ?? '',
        'to'        => $slip['receiver']['account']['name'] ?? '',

        'ref'       => $slip['ref1'] ?? '',
        'trans_ref' => $slip['transRef'] ?? '',
        'reference_id' => $slip['referenceId'] ?? ''
    ]
]));
