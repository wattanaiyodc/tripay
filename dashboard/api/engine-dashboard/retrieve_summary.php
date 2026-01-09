<?php
session_start();
require '../db.php';

header('Content-Type: application/json');

try {

  if (!isset($_SESSION['user_id'])) {
    throw new Exception('not login');
  }

  $user_id = (int)$_SESSION['user_id'];

  $sql = "
        SELECT 
          sum(case when type = 'income' then amount else 0 end) as income,
          sum(case when type = 'expense' then amount else 0 end) as expense,
          sum(case when type = 'income' then amount else 0 end) - sum(case when type = 'expense' then amount else 0 end) as balance
        FROM transactions
        WHERE user_id = :user_id
        ORDER BY transaction_date DESC, transaction_id DESC
    ";

  $sth = $pdo->prepare($sql);
  $sth->execute([
    ':user_id' => $user_id
  ]);

  $result = $sth->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode([
    'status'  => 'ok',
    'message' => 'success',
    'result'  => $result
  ]);
  exit;
} catch (Throwable $e) {

  echo json_encode([
    'status'  => 'error',
    'message' => $e->getMessage()
  ]);
  exit;
}
