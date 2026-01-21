<?php
session_start();
include('lang/lang.php');
$lang = $_SESSION["lang"] ?? 'th';
$resource = load_lang_csv("trip_lang.csv", $lang);
if (!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit;
}
$trip_id = (int)($_GET['trip_id'] ?? 0);
if ($trip_id === 0) {
    exit('invalid trip');
}

//$trip_id = (int)($_GET['trip_id'] ?? 0);
//if ($trip_id === 0) {
//    exit('invalid trip');
//}
$cp_trip_name = $resource['edit_member'];
$cp_title  = 'Manage_member';
$cp_active = 'manage_member';

include 'components/component_header.php';
include 'components/component_sidebar.php';
?>
<style>
    /* ===== layout ===== */
    .cp-box {
        background: #fff;
        width: 100%;
        padding: 20px;
        margin-bottom: 24px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
    }

    .cp-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 999px;
        background: #f3f4f6;
        color: #111827;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }

    .cp-back-btn:hover {
        background: #e5e7eb;
    }

    /* ===== layout ===== */
    .cp-box {
        background: #fff;
        width: 100%;
        padding: 20px;
        margin-bottom: 24px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
    }

    .cp-box-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
    }

    .cp-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 999px;
        background: #f3f4f6;
        color: #111827;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
    }

    .cp-back-btn:hover {
        background: #e5e7eb;
    }

    /* ===== title ===== */
    .cp-page-title {
        font-size: 18px;
        font-weight: 800;
        margin: 0;
        color: #111827;
    }

    .cp-page-sub {
        font-size: 13px;
        color: #6b7280;
        margin-top: 4px;
    }

    /* ===== toolbar ===== */
    .cp-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .cp-search {
        flex: 1;
        min-width: 220px;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        padding: 10px 12px;
        border-radius: 12px;
    }

    .cp-search input {
        border: none;
        outline: none;
        background: transparent;
        width: 100%;
        font-size: 14px;
    }

    .cp-btn {
        border: none;
        cursor: pointer;
        padding: 10px 14px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        transition: all .15s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .cp-btn-primary {
        background: #6366f1;
        color: #fff;
        box-shadow: 0 8px 18px rgba(99, 102, 241, .22);
    }

    .cp-btn-primary:hover {
        background: #4f46e5;
        transform: translateY(-1px);
    }

    .cp-btn-secondary {
        background: #f3f4f6;
        color: #111827;
    }

    .cp-btn-secondary:hover {
        background: #e5e7eb;
    }

    /* ===== table ===== */
    .cp-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .cp-table thead th {
        text-align: left;
        font-size: 12px;
        color: #6b7280;
        font-weight: 700;
        padding: 0 12px 6px;
    }

    .cp-table tbody tr {
        background: #fff;
    }

    .cp-table td {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        padding: 12px;
        vertical-align: middle;
    }

    .cp-table td:first-child {
        border-radius: 12px 0 0 12px;
    }

    .cp-table td:last-child {
        border-radius: 0 12px 12px 0;
    }

    .cp-usercell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .cp-avatar2 {
        width: 38px;
        height: 38px;
        border-radius: 999px;
        background: #eef2ff;
        color: #3730a3;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 14px;
        border: 1px solid #c7d2fe;
        overflow: hidden;
        flex-shrink: 0;
    }

    .cp-avatar2 img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .cp-name {
        font-weight: 800;
        color: #111827;
        line-height: 1.1;
    }

    .cp-email {
        font-size: 12px;
        color: #6b7280;
        margin-top: 2px;
    }

    .cp-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #374151;
    }

    .cp-badge-master {
        background: #ecfeff;
        border-color: #a5f3fc;
        color: #155e75;
    }

    .cp-badge-member {
        background: #fef2f2;
        border-color: #fecaca;
        color: #991b1b;
    }

    .cp-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .cp-icon-action {
        width: 36px;
        height: 36px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        background: #fff;
        cursor: pointer;
        font-size: 16px;
        transition: all .15s ease;
    }

    .cp-icon-action:hover {
        background: #f3f4f6;
        transform: translateY(-1px);
    }

    .cp-icon-danger:hover {
        background: rgba(239, 68, 68, .12);
        border-color: rgba(239, 68, 68, .35);
    }

    .cp-empty {
        text-align: center;
        color: #6b7280;
        padding: 18px 0;
        font-weight: 600;
    }
</style>
<div class="cp-box">
    <div class="cp-box-header">
        <div>
            <div class="cp-page-sub">
                <?= htmlspecialchars($resource['manage_member_desc'] ?? 'จัดการสมาชิกในทริป เพิ่ม/ลบ/กำหนดสิทธิ์') ?>
            </div>
        </div>

        <a href="index.php" id="btn_back" class="cp-back-btn">← <?= htmlspecialchars($resource['back'] ?? 'กลับ') ?></a>
    </div>

    <!-- ===== Toolbar ===== -->
    <div class="cp-toolbar">
        <div class="cp-search">
            <span>🔎</span>
            <input type="text" id="member_search" placeholder="<?= htmlspecialchars($resource['search_member'] ?? 'ค้นหาสมาชิก...') ?>">
        </div>

        <div style="display:flex; gap:10px;">
            <button type="button" class="cp-btn cp-btn-secondary" id="btn_refresh_member">
                🔄 <?= htmlspecialchars($resource['refresh'] ?? 'รีเฟรช') ?>
            </button>

            <button type="button" class="cp-btn cp-btn-primary" id="btn_add_member">
                ➕ <?= htmlspecialchars($resource['add_member'] ?? 'เพิ่มสมาชิก') ?>
            </button>
        </div>
    </div>

    <!-- ===== Member Table ===== -->
    <table class="cp-table">
        <thead>
            <tr>
                <th style="width:55%;"><?= htmlspecialchars($resource['name'] ?? 'ชื่อ') ?></th>
                <th style="width:20%;"><?= htmlspecialchars($resource['role'] ?? 'สิทธิ์') ?></th>
                <th style="width:25%; text-align:right;"><?= htmlspecialchars($resource['action'] ?? 'จัดการ') ?></th>
            </tr>
        </thead>

        <tbody id="member_list">
            <tr>
                <td colspan="3" class="cp-empty"><?= htmlspecialchars($resource['no_member'] ?? 'ยังไม่มีสมาชิก') ?></td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    var json_request = {
        user_id: <?php echo $_SESSION['user_id']; ?>,
        trip_id: <?php echo $trip_id; ?>
    };

    function retrieve_member() {
        var q = json_request;
        var json = JSON.stringify(q);
        $.ajax({
            url: 'api/engine-member/retrieve_trip_member.php',
            type: 'post',
            data: {
                json: json
            },
            dataType: 'json',
            success: function(res) {
                if (res.status !== 'success') {
                    alert(res.message);
                    return;
                }

                var html = '';
                $.each(res.result, function(idx, user) {
                    html += `<tr>`;
                    html += `<td>${user.first_name} ${user.last_name}</td>`;
                    html += `<td>${user.role}</td>`;
                    html += `</tr>`;
                });
                $('#member_list').html(html);
            }
        });
    }
    $(document).ready(function() {
        retrieve_member();
    });
</script>