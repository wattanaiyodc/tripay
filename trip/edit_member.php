<?php
session_start();
include('lang/lang.php');
$lang = $_SESSION["lang"] ?? 'th';
$resource = load_lang_csv("trip_lang.csv", $lang);
if (!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit;
}
$trip_id = (int)($_SESSION['trip_id'] ?? 0);
if ($trip_id === 0) {
    exit('invalid trip');
}

$cp_trip_name = $resource['edit_member'];
$cp_title  = 'Edit_Member';
$cp_active = 'edit_member';

include 'components/component_header.php';
include 'components/component_sidebar.php';
?>
<?php include("edit_member/edit_member_headerscript.php") ;?> 
<div class="cp-box">
    <div class="cp-box-header">
        <div>
            <div class="cp-page-sub">
                <?= htmlspecialchars($resource['manage_member_desc'] ?? 'จัดการสมาชิกในทริป เพิ่ม/ลบ/กำหนดสิทธิ์') ?>
            </div>
        </div>

        <a href="manage_member.php" id="btn_back" class="cp-back-btn">← <?= htmlspecialchars($resource['back'] ?? 'กลับ') ?></a>

    </div>
</div>