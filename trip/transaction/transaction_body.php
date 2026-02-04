<div class="cp-box">
    <div class="cp-box-header">
        <div>
            <div class="cp-page-sub">
                <?= htmlspecialchars($resource['dr_cr'] ?? 'รายรับ/รายจ่าย') ?>
            </div>
        </div>

        <a href="index.php" id="btn_back" class="cp-back-btn">← <?= htmlspecialchars($resource['back'] ?? 'กลับ') ?></a>
    </div>
    <table class="cp-table">
        <thead>
            <tr>
                <th style="width:120px;">วันที่</th>
                <th>รายการ</th>
                <th class="text-right" style="width:120px;">DR</th>
                <th class="text-right" style="width:120px;">CR</th>
                <th class="text-right" style="width:140px;">Balance</th>
            </tr>
        </thead>

        <tbody id="rt_output"></tbody>
        <tfoot id="tf_output"></tfoot>
    </table>
</div>