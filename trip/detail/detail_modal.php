<!-- ===== MODAL QR ===== -->
<div id="qr_modal" class="cp-modal" style="display:none;">
    <div class="cp-modal-backdrop"></div>

    <div class="cp-modal-box" style="max-width:420px;">
        <div class="cp-modal-header">
            <h3 id="qr_modal_title">สร้าง QR รับเงิน</h3>
            <button class="cp-modal-close">✕</button>
        </div>

        <div class="cp-modal-body">

            <!-- ================= STEP 1 : FORM ================= -->
            <div id="qr_step_form">

                <table class="cp-table" width="100%">
                    <tr>
                        <td width="120"><strong>จำนวนเงิน:</strong></td>
                        <td>
                            <input type="number"
                                id="qr_price"
                                placeholder="0.00"
                                style="width:100%;">
                        </td>
                    </tr>

                    

                    <tr>
                        <td style="vertical-align:top;"><strong>ผู้รับ:</strong></td>
                        <td>

                            <label style="margin-right:12px;">
                                <input type="radio" name="to_mode" value="all" checked>
                                ทั้งหมด (All)
                            </label>

                            <label>
                                <input type="radio" name="to_mode" value="custom">
                                กำหนดเอง
                            </label>

                            <div id="to_custom_area" style="margin-top:10px;display:none;">
                                <div id="to_list" class="cp-check-list"></div>
                            </div>

                        </td>
                    </tr>
                </table>

            </div>


            <!-- ================= STEP 2 : PREVIEW ================= -->
            <div id="qr_step_preview" style="display:none;text-align:center;">

                <div id="qr_preview"
                    style="margin-bottom:12px;">
                </div>

                <button class="cp-btn-secondary" id="btn_back_step">
                    ← ย้อนกลับ
                </button>

            </div>

        </div>

        <div class="cp-modal-footer">

            <button class="cp-btn-secondary cp-modal-close">
                ยกเลิก
            </button>

            <button class="cp-btn-primary" id="btn_generate_qr">
                ต่อไป
            </button>

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