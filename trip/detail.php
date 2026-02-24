<?php
session_start();
include('lang/lang.php');
$lang = $_SESSION["lang"] ?? 'th';
$resource = load_lang_csv("trip_lang.csv", $lang);
if (!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit;
}

$trip_id = (int)($_GET['trip_id'] ?? $_SESSION['trip_id'] ?? 0);
if ($trip_id <= 0) {
    exit('invalid trip');
}
$_SESSION['trip_id'] = $trip_id;
$cp_trip_name = $resource["detail"];
$cp_title  = 'Detail';
$cp_active = 'detail';

include 'components/component_header.php';
include 'components/component_sidebar.php';
?>

<?php include('detail/detail_headerscript.php'); ?>
<?php include('detail/detail_modal.php'); ?>

<!-- ================= กล่องรายละเอียดทริป ================= -->
<!-- ===== Trip Info ===== -->
<div class="cp-box">
    <a href="#" id="btn_back" class="cp-back-btn">← กลับ</a>

    <h2 id="trip_name" class="cp-trip-title"></h2>

    <div class="cp-trip-meta">
        <div class="cp-meta-item">
            <div class="cp-meta-label">วันออกเดินทาง</div>
            <div class="cp-meta-value" id="date_from">-</div>
        </div>

        <div class="cp-meta-item">
            <div class="cp-meta-label">วันสิ้นสุด</div>
            <div class="cp-meta-value" id="date_to">-</div>
        </div>

        <div class="cp-meta-item cp-meta-item-full">
            <div class="cp-meta-label">📍 สถานที่</div>

            <a id="trip_location_link" class="cp-map-card2" href="#" target="_blank" style="display:none;">
                <div class="cp-map-card2-icon">📍</div>
                <div class="cp-map-card2-body">
                    <div class="cp-map-card2-title" id="trip_location_text">-</div>
                    <div class="cp-map-card2-sub">เปิดใน Google Maps</div>
                </div>
                <div class="cp-map-card2-action">เปิดแผนที่ ↗</div>
            </a>

            <div id="trip_location_plain" class="cp-meta-empty" style="display:none;">-</div>
        </div>
    </div>
</div>


<div class="cp-box">
    <!-- <button id="import_slip" class="cp-btn-import">
        📷 <?php echo $resource["import_transaction"] ?>
    </button> -->

    <button class="cp-btn-create" data-target="#qr_modal">
        📄 <?php echo $resource["create_qrcode"] ?>
    </button>

    <!-- input แนบรูป (ซ่อนไว้) -->
    <input type="file" id="slip_file" accept="image/*" hidden>
    <div id="qr-temp" style="display:none;"></div>
</div>

<!-- ===== Timeline + Member ===== -->
<div class="cp-grid-2">

    <!-- Timeline -->
    <div class="cp-box">
        <div class="cp-box-header">
            <h3>📅 Timeline</h3>
            <button id="add_timeline" class="cp-icon-btn">+</button>
        </div>

        <div id="timeline_form" class="cp-inline-form" style="display:none;">
            <div class="cp-form-group">
                <label>วันที่ <span style="color:red">*</span></label>
                <input type="date" id="tl_date" required>
            </div>

            <div class="cp-form-row">
                <div class="cp-form-group">
                    <label>เวลาเริ่ม <span style="color:red">*</span></label>
                    <input type="time" id="tl_starttime" required>
                </div>
                <div class="cp-form-group">
                    <label>เวลาสิ้นสุด <span style="color:red">*</span></label>
                    <input type="time" id="tl_endtime" required>
                </div>
            </div>

            <div class="cp-form-group">
                <label>กิจกรรม <span style="color:red">*</span></label>
                <input type="text" id="tl_title" required>
            </div>

            <div class="cp-form-group">
                <label class="cp-label">สถานที่ (Google Maps) </label>
                <input type="text"
                    id="tl_location"
                    placeholder="วางลิงก์จาก Google Maps ที่นี่">
            </div>

            <div class="cp-form-group">
                <label>รายละเอียด</label>
                <textarea id="tl_detail"></textarea>
            </div>

            <div class="cp-form-actions">
                <button type="button" class="cp-btn cp-btn-secondary" id="cancel_timeline">
                    ยกเลิก
                </button>
                <button type="button" class="cp-btn cp-btn-primary" id="save_timeline">
                    บันทึก
                </button>
            </div>

        </div>
        <div id="timeline_tabs" class="cp-tabs"></div>
        <table class="cp-timeline-table">
            <tbody id="timeline">
            </tbody>
        </table>
    </div>

    <div class="cp-box">
        <div class="cp-box-header">
            <h3>👥 สมาชิกทริป</h3>
            <button id="btn_edit_member" class="cp-edit-btn" type="button" title="จัดการสมาชิก">
                ✏️
            </button>
        </div>
        <table class="member-table" width="100%">
            <thead>
                <tr>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody id="member_list">
            </tbody>
        </table>
    </div>

</div>


<?php include('detail/detail_footerscript.php'); ?>


<?php include '../components/component_footer.php'; ?>