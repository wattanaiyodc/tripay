<?php
    header('Content-Type: application/json');
    session_start();
    include('../db.php');

if (!(isset($_POST['json']))) {
    $answer["message"] = "No data is received!";
    exit(json_encode($answer));
}


$data = json_decode($_POST['json'], true);

$user_id = (int)($_SESSION['user_id'] ?? 0);
if ($user_id === 0) exit('no user');

$type = $data['type'] ?? '';
if ($type === '') exit('no type');

$title  = trim($data['title'] ?? '');
$amount = (int)($data['amount'] ?? 0);
$date   = $data['date'] ?? '';

try {
    $pdo2->beginTransaction();
    $sql = "insert into transactions ( user_id, type, title, amount, transaction_date ) values ( :user_id, :type, :title, :amount, :transaction_date )
            ";
    $sth = $pdo2->prepare($sql);
    $sth->execute([
        ':user_id' => $user_id,
        ':type' => $type,
        ':title' => $title,
        ':amount' => $amount,
        ':transaction_date' => $date
    ]);
    if( $sth->errorInfo()[0]!="00000" && !empty($sth->errorInfo()[0]) ){
      $answer["message"] = (empty($sth->errorInfo()[2]))?$sth->errorInfo()[0]:$sth->errorInfo()[2];
      exit(json_encode($answer));
    }
    $pdo2->commit();

    echo json_encode([
        'status'  => 'ok',
        'message' => 'success'
    ]);
} catch (Throwable $e) {

    $pdo2->rollBack();
    echo $e->getMessage();
}
?>