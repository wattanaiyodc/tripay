<!-- ===== MODAL QR ===== -->
<div id="qr_modal" class="cp-modal" style="display:none;">
    <div class="cp-modal-backdrop"></div>

    <div class="cp-modal-box">
        <div class="cp-modal-header">
            <h3>QR Code</h3>
            <button class="cp-modal-close">✕</button>
        </div>

        <div class="cp-modal-body" id="content_qr">
            <table>
                <tr>
                    <td><strong>ราคา: </strong></td>
                    <td><input type="number" id="qr_price"></input></td>
                </tr>
                <tr>
                    <td><strong>รายละเอียด: </strong></td>
                    <td><input type="text" id="detail"></input></td>
                </tr>
                <tr>
                    <td><strong>to:</strong></td>
                    <td>

                        <div class="cp-ms" id="to_ms">

                            <!-- display -->
                            <div class="cp-ms-display">
                                <span class="cp-ms-text">เลือกผู้รับ</span>
                                <span class="cp-ms-arrow">▾</span>
                            </div>

                            <!-- dropdown -->
                            <div class="cp-ms-menu">

                                <!-- search -->
                                <input type="text" class="cp-ms-search" placeholder="ค้นหา...">

                                <!-- select all -->
                                <label class="cp-ms-item cp-ms-all">
                                    <input type="checkbox" id="to_all">
                                    All
                                </label>

                                <!-- list (JS render) -->
                                <div class="cp-ms-list" id="to_list"></div>

                            </div>

                        </div>

                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- ===== MODAL SLIP ===== -->
<div id="slip_modal" class="cp-modal" style="display:none;">
    <div class="cp-modal-backdrop"></div>

    <div class="cp-modal-box">
        <div class="cp-modal-header">
            <h3>📄 ข้อมูลจากสลิป</h3>
            <button class="cp-modal-close">✕</button>
        </div>

        <div class="cp-modal-body" id="slip_result">
            <div class="cp-loading">กำลังประมวลผลสลิป...</div>
        </div>

        <div class="cp-modal-footer">
            <button class="cp-btn-secondary" id="cancel_modal">ปิด</button>
            <button class="cp-btn-primary" id="confirm_modal">บันทึก</button>
        </div>
    </div>
</div>