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
    .cp-box {
        background: #ffffff;
        padding: 20px;
        margin-bottom: 24px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    /* ===== table ===== */
    .cp-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .cp-table thead th {
        text-align: left;
        font-size: 17px;
        font-weight: 600;
        color: #374151;
        padding: 10px 8px;
        border-bottom: 2px solid #e5e7eb;
        text-align: center;
    }

    .cp-table tbody td {
        padding: 10px 8px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        
    }

    .cp-table tbody tr:hover {
        background: #f9fafb;
    }

    /* ===== form ===== */
    .cp-table input,
    .cp-table select {
        width: 100%;
        padding: 8px 10px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        font-size: 14px;
    }

    .cp-table input:focus,
    .cp-table select:focus {
        outline: none;
        border-color: #6366f1;
    }

    /* ===== button ===== */
    .cp-table button {
        padding: 10px 16px;
        border-radius: 8px;
        border: none;
        background: #6366f1;
        color: #fff;
        font-size: 14px;
        cursor: pointer;
    }

    .cp-table button:hover {
        background: #4f46e5;
    }

    /* ===== summary ===== */
    .cp-summary td {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        padding: 18px 0;
    }


    /* ===== color ===== */
    .tx-income {
        color: #16a34a;
        font-weight: bold;
    }

    .tx-expense {
        color: #dc2626;
        font-weight: bold;
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