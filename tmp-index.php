<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit;
}

$cp_title      = 'Detail';
$cp_active     = 'detail';
$cp_user_id    = $_SESSION['user_id'];
$cp_user_name  = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
$cp_trip_name  = 'Detail';

include '../components/component_header.php';
include '../components/component_sidebar.php';
?>
<style>
    .cp-box {
        background: #fff;
        width: 100%;
        padding: 20px;
        margin-bottom: 24px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
    }
</style>

<div class="cp-box">
    <table>

    </table>
</div>
<?php include '../components/component_footer.php'; ?>