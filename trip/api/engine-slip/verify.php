<?php 
$data = json_decode($_POST["json"] , true);
$qr = $data["qr"] ?? '';
if (!$qr) {
    exit(json_encode([
        'status' => 'error',
        'message' => 'QR string empty'
    ]));
}

$rootPath = dirname(__DIR__, 3);
// engine-slip -> api -> trip -> ROOT

$envPath = $rootPath . '/.env';

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        [$key, $value] = explode('=', $line, 2);
        $_ENV[$key] = trim($value);
    }
}
$k = $_ENV['SLIP2GO_KEY'] ?? '';

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
