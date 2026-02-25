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
<style>
    .cp-box {
        background: #fff;
        width: 100%;
        padding: 24px;
        margin-bottom: 24px;
        border-radius: 14px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, .05);
    }

    .cp-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        table-layout: fixed;
        font-size: 14px;
    }

    .cp-table thead th {
        background: #f8fafc;
        padding: 14px 12px;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-weight: 600;
        text-align: left;
        text-align: center;
    }

    .cp-table tbody td {
        padding: 14px 12px;
        border-bottom: 1px solid #f1f5f9;
        color: #0f172a;
        vertical-align: middle;
    }

    .cp-table tbody tr {
        transition: .15s ease;
    }

    .cp-table tbody tr:hover {
        background: #fafafa;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .name {
        font-weight: 600;
    }

    /* ===== STATUS BADGE ===== */
    .status {
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .status-red {
        color: #ef4444;
        background: #fef2f2;
    }

    .status-yellow {
        color: #f59e0b;
        background: #fffbeb;
    }

    .status-green {
        color: #22c55e;
        background: #f0fdf4;
    }

    /* ===== ACTION BUTTON (ซ่อนก่อน) ===== */
    .action-btn {
        margin-left: 8px;
        padding: 4px 8px;
        border-radius: 6px;
        border: none;
        background: #22c55e;
        color: #fff;
        font-size: 12px;
        cursor: pointer;
        opacity: 0;
        transform: translateY(4px);
        transition: .15s;
    }

    /* ⭐ hover แถวแล้วค่อยโชว์ */
    .cp-table tbody tr:hover .action-btn {
        opacity: 1;
        transform: translateY(0);
    }

    .btn-confirm {
        background: #f59e0b;
        color: #fff;
        border: none;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: .15s;
    }

    .btn-confirm:hover {
        background: #d97706;
    }

    .cp-table th,
    .cp-table td {
        text-align: center;
    }
</style>

<div class="cp-box">
    <div>asdasd</div>
    <table class="cp-table">
        <thead>
            <tr>
                <th style="width:60px">#</th>
                <th style="width:200px">ชื่อ</th>
                <th>รายละเอียด</th>
                <th style="width:120px" class="text-right">จำนวน</th>
                <th style="width:200px" class="text-center">สถานะ</th>
            </tr>
        </thead>
        <tbody id="output">
        </tbody>
    </table>
</div>

<script>
    var json_request = {
        user_id: <?php echo $_SESSION['user_id']; ?>,
        trip_id: <?php echo $trip_id; ?>
    };

    function retrieve_payment() {
        var q = json_request;
        var url = 'api/engine-payment/retrieve.php';
        var json = JSON.stringify(q);
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {
                json: json
            },
            success: function(res) {
                if (res.status != 1) {
                    return;
                }
                let items = res.result;
                var html = '';
                const STATUS = {
                    pending: {
                        text: 'Pending',
                        class: 'status-red'
                    },
                    waiting: {
                        text: 'Waiting',
                        class: 'status-yellow'
                    },
                    paid: {
                        text: 'Paid',
                        class: 'status-green'
                    }
                };
                items.forEach((item, index) => {

                    let statusHTML = '';

                    // ⭐ waiting = ปุ่ม
                    if (item.status === 'waiting') {
                        statusHTML = `
                                        <button class="btn-confirm" data-id="${item.id}" data-qr-id="${item.qr_id}">
                                            ยืนยันรับเงิน
                                        </button>`;
                    } else if (item.status === 'paid') {
                        statusHTML = `<span class="status status-green">Paid</span>`;
                    } else {
                        statusHTML = `<span class="status status-red">Pending</span>`;
                    }

                    html += `
                            <tr>
                                <td>${index+1}</td>
                                <td class="name">${item.first_name + ' ' + item.last_name}</td>
                                <td>${item.detail}</td>
                                <td class="text-right">${item.amount}</td>
                                <td class="text-center">${statusHTML}</td>
                            </tr>`;
                });
                $('#output').html(html);

            }
        });
    }

    function update_status_paid(id, qr_id) {
        var q = json_request;
        q.id = id ?? '';
        q.qr_id = qr_id ?? '';
        var url = 'api/engine-payment/update_status.php';
        var json = JSON.stringify(q);
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {
                json: json
            },
            success: function(res) {
                if (res.status != 1) {
                    return;
                }
                UIAlert.success("สำเร็จ").then(() => {
                    retrieve_payment(); // ⭐ refresh หลังปิด alert
                });
            }
        });
    }
    $(document).on('click', '.btn-confirm', function() {
        const id = $(this).data('id');
        const qr_id = $(this).data('qr-id');
        console.log("click");
        UIAlert.fire({
            title: 'ยืนยันรับเงิน ?',
            icon: 'warning',
            showCancel: true
        }).then(ok => {

            if (!ok) return; // ❌ กดยกเลิก

            update_status_paid(id, qr_id); // ✅ กดยืนยัน

        });
    });
    $(document).ready(function() {
        retrieve_payment();
    });
</script>


<?php include '../components/component_footer.php'; ?>