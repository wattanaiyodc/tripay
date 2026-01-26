<!-- ===== Add Member Modal (Search User) ===== -->
<div id="add_member_modal" class="cp-modal" style="display:none;">
    <div class="cp-modal-backdrop"></div>

    <div class="cp-modal-card">
        <div class="cp-modal-header">
            <div>
                <div class="cp-modal-title">➕ เพิ่มสมาชิก</div>
                <div class="cp-modal-sub">เลือกสมาชิกจากรายชื่อผู้ใช้ในระบบ</div>
            </div>

            <button type="button" class="cp-modal-close" id="btn_close_modal">✕</button>
        </div>

        <div class="cp-modal-body">
            <div class="cp-search" style="margin-bottom:12px;">
                <span>🔎</span>
                <input type="text" id="user_search" placeholder="ค้นหาชื่อ / email / id...">
            </div>

            <div id="user_list_box" style="max-height:340px; overflow:auto;">
                <div class="cp-empty" style="padding:12px 0;">กำลังโหลด...</div>
            </div>
        </div>

        <div class="cp-modal-actions">
            <button type="button" class="cp-btn cp-btn-secondary" id="btn_cancel_modal">
                ปิด
            </button>
        </div>
    </div>
</div>