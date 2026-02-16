<style>
    .cp-noti {
        position: relative;
        margin-right: 14px;
    }

    .cp-noti-btn {
        position: relative;
        cursor: pointer;
        font-size: 18px;
        color: #111827;
    }

    .cp-noti-btn:hover {
        color: #2563eb;
    }

    .cp-noti-badge {
        position: absolute;
        top: -6px;
        right: -8px;
        background: #ef4444;
        color: #fff;
        font-size: 11px;
        padding: 2px 6px;
        border-radius: 999px;
    }

    .cp-noti-box {
        position: absolute;
        right: 0;
        top: 34px;
        width: 300px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        box-shadow: 0 10px 24px rgba(0, 0, 0, .08);
        display: none;
        z-index: 999;
    }

    .cp-noti-header {
        padding: 10px 12px;
        font-weight: 700;
        border-bottom: 1px solid #eee;
    }

    .cp-noti-list {
        max-height: 320px;
        overflow: auto;
    }

    .cp-noti-item {
        padding: 10px 12px;
        cursor: pointer;
    }

    .cp-noti-item:hover {
        background: #f3f4f6;
    }

    .cp-noti-item.unread {
        background: #eef2ff;
    }

    .cp-noti-title {
        font-weight: 600;
        font-size: 14px;
    }

    .cp-noti-msg {
        font-size: 12px;
        color: #6b7280;
    }
</style>

<script>
    // เปิด / ปิด noti box
    $(document).on('click', '#cp_noti .cp-noti-btn', function(e) {
        e.stopPropagation();
        $('#noti_box').fadeToggle(120);
    });

    // คลิกนอกกล่อง = ปิด
    $(document).on('click', function() {
        $('#noti_box').fadeOut(120);
    });

    // กันคลิกในกล่องแล้วปิด
    $(document).on('click', '#noti_box', function(e) {
        e.stopPropagation();
    });
</script>