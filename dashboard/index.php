<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit;
}

$cp_title      = 'Dashboard';
$cp_active     = 'dashboard';

include '../components/component_header.php';
include '../components/component_sidebar.php';
?>
<style>
    /* ===== layout card ===== */
    /* ===== SIDEBAR ===== */
    .cp-sidebar {
        width: 220px;
        min-height: 100vh;
        background: #1f2937;
        color: #fff;
        padding: 20px;
        box-sizing: border-box;
        flex-shrink: 0;
    }

    .cp-sidebar h1 {
        margin: 0 0 20px;
        font-size: 18px;
        font-weight: 600;
    }

    .cp-sidebar a {
        display: flex;
        align-items: center;
        gap: 10px;

        padding: 10px 12px;
        margin-bottom: 6px;

        color: #cbd5e1;
        text-decoration: none;
        border-radius: 6px;

        font-size: 14px;
    }

    .cp-sidebar a:hover,
    .cp-sidebar a.cp-active {
        background: #374151;
        color: #fff;
    }

    .cp-icon {
        width: 18px;
        text-align: center;
    }

    .cp-sidebar-divider {
        height: 1px;
        background: rgba(255, 255, 255, .12);
        margin: 14px 0;
    }

    .cp-sidebar a.cp-logout {
        color: #fecaca;
    }

    .cp-sidebar a.cp-logout:hover {
        background: rgba(239, 68, 68, .15);
        color: #fff;
    }

    /* ===== TOPBAR ===== */
    .cp-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .cp-title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 60%;
    }

    .cp-topbar-right {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    /* ===== LANG TOGGLE ===== */
    .cp-lang-form {
        display: inline-flex;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 999px;
        padding: 4px;
        gap: 4px;
    }

    .cp-lang-btn {
        border: none;
        background: transparent;
        padding: 6px 10px;
        border-radius: 999px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 700;
        color: #6b7280;
        transition: all .15s ease;
    }

    .cp-lang-btn:hover {
        background: #e5e7eb;
        color: #111827;
    }

    .cp-lang-btn.active {
        background: #6366f1;
        color: #fff;
    }

    /* ===== USER ===== */
    .cp-user {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cp-user>div:last-child {
        line-height: 1.2;
        white-space: nowrap;
    }
</style>

<div class="cp-box">
    <table class="cp-table">
        <thead>
            <tr>
                <th colspan="5">เพิ่มรายรับ / รายจ่าย</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select id="tx_type">
                        <option value="income">รายรับ</option>
                        <option value="expense">รายจ่าย</option>
                    </select>
                </td>
                <td>
                    <input type="text" id="tx_title" placeholder="รายการ">
                </td>
                <td>
                    <input type="number" id="tx_amount" placeholder="จำนวนเงิน">
                </td>
                <td>
                    <input type="date" id="tx_date">
                </td>
                <td>
                    <button id="btnAdd">บันทึก</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="cp-box">
    <table class="cp-table">
        <thead>
            <tr>
                <th colspan="3">สรุป</th>
            </tr>
            <tr>
                <th>รายรับ</th>
                <th>รายจ่าย</th>
                <th>คงเหลือ</th>
            </tr>
        </thead>
        <tbody id="rs_output" class="cp-summary"></tbody>

    </table>
</div>

<div class="cp-box">
    <table class="cp-table">
        <thead>
            <th>วันที่</th>
            <th>รายการ</th>
            <th>ประเภท</th>
            <th>จำนวนเงิน</th>
        </thead>
        <tbody id="rt_output" class="cp-summary"></tbody>
    </table>
</div>

<script>
    var json_request = {};
    json_request['user_id'] = <?php echo $_SESSION['user_id'] ?>;


    // retrieve summary dashboard
    function retrieve_summary() {
        var q = json_request;
        var json = JSON.stringify(q);

        $.ajax({
            url: 'api/engine-dashboard/retrieve_summary.php',
            type: 'GET',
            dataType: 'json',
            success: function(res) {

                const data = res.result[0];
                var html = '';
                html += '<tr>';
                html += '<td class="tx-income">' + data.income + '</td>';
                html += '<td class="tx-expense">' + data.expense + '</td>';
                html += '<td>' + data.balance + '</td>';
                html += '</tr>';

                $('#rs_output').html(html);
            }
        })
    }


    // retrieve summary dashboard
    function retrieve_transaction() {
        var q = json_request;
        var json = JSON.stringify(q);

        $.ajax({
            url: 'api/engine-dashboard/retrieve_transaction.php',
            type: 'GET',
            dataType: 'json',
            success: function(res) {

                var html = '';

                $.each(res.result, function(i, data) {
                    html += '<tr>';
                    html += '<td>' + data.transaction_date + '</td>';
                    html += '<td>' + data.title + '</td>';
                    html += '<td class="' + (data.type === 'income' ? 'tx-income' : 'tx-expense') + '">' +
                        (data.type === 'income' ? 'รายรับ' : 'รายจ่าย') + '</td>';
                    html += '<td>' + data.amount + '</td>';
                    html += '</tr>';
                })

                $('#rt_output').html(html);
            }
        })
    }


    //create transaction
    function save_transaction() {
        var q = json_request;
        q['type'] = $('#tx_type').val();
        q['title'] = $('#tx_title').val();
        q['amount'] = $('#tx_amount').val();
        q['date'] = $('#tx_date').val();
        var json = JSON.stringify(q);
        $.ajax({
            url: 'api/engine-dashboard/create_transaction.php',
            data: {
                'json': json
            },
            type: 'POST',
            dataType: 'json',
            success: function(res) {
                retrieve_summary();
                retrieve_transaction();
            }
        })
    }

    $('#btnAdd').click(function() {
        save_transaction();
    });

    $(document).ready(function() {
        retrieve_summary();
        retrieve_transaction();
    });
</script>

<?php include '../components/component_footer.php'; ?>