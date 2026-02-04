<?php
session_start();
include('lang/lang.php');
$lang = $_SESSION["lang"] ?? 'th';
$resource = load_lang_csv("trip_lang.csv", $lang);
if (!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit;
}

$cp_active     = 'transaction';
$cp_user_id    = $_SESSION['user_id'];
$cp_user_name  = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
$cp_trip_name  = $resource['transaction'];

include 'components/component_header.php';
include 'components/component_sidebar.php';
?>

<?php include("transaction/transaction_headerscript.php"); ?>
<?php include("transaction/transaction_body.php"); ?>
<?php include("transaction/transaction_footerscript.php"); ?>