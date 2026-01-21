<?php
session_start();

$lang = $_POST['lang'] ?? 'th';
if ($lang !== 'th' && $lang !== 'en') {
    $lang = 'th';
}

$_SESSION['lang'] = $lang;

// กลับไปหน้าเดิม
$back = $_SERVER['HTTP_REFERER'] ?? '../trip/index.php';
header("Location: " . $back);
exit;
