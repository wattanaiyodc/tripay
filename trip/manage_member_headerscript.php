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

    .cp-box-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
        padding-right: 20px;
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

    /* ===== buttons ===== */
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

    /* ทำให้แถวเป็น card และไม่มีเส้นคั่นดำๆ */
    .cp-table td {
        background: #f9fafb;
        padding: 12px;
        vertical-align: middle;
        border: none;
        /* สำคัญ: เอาเส้นคั่นออก */
    }

    /* กรอบรอบแถวแบบเดียว */
    .cp-table tbody tr td:first-child {
        border: 1px solid #e5e7eb;
        border-right: none;
        border-radius: 12px 0 0 12px;
    }

    .cp-table tbody tr td:not(:first-child):not(:last-child) {
        border-top: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
    }

    .cp-table tbody tr td:last-child {
        border: 1px solid #e5e7eb;
        border-left: none;
        border-radius: 0 12px 12px 0;
    }

    .cp-table tbody tr:hover td {
        background: #f3f4f6;
    }

    /* ===== name / email ===== */
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

    /* ===== badge ===== */
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

    /* ===== actions ===== */
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
        background: #eef2ff;
        border-color: #c7d2fe;
        transform: translateY(-1px);
    }

    .cp-icon-danger:hover {
        background: rgba(239, 68, 68, .12);
        border-color: rgba(239, 68, 68, .35);
    }

    /* ===== avatar ===== */
    .cp-avatar-img {
        width: 42px;
        height: 42px;
        border-radius: 999px;
        object-fit: cover;
        border: 2px solid #e5e7eb;
        background: #f3f4f6;
        display: block;
    }

    .cp-avatar-fallback {
        width: 42px;
        height: 42px;
        border-radius: 999px;
        background: #eef2ff;
        border: 2px solid #c7d2fe;
        color: #3730a3;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
    }

    /* ===== empty row ===== */
    .cp-empty {
        text-align: center;
        color: #6b7280;
        padding: 18px 0;
        font-weight: 600;
    }

    .cp-table .cp-empty {
        background: transparent !important;
        border: none !important;
        padding: 22px 0 !important;
    }

    .cp-table tr.cp-empty-row td {
        background: transparent !important;
        border: none !important;
    }

    /* ===== modal ===== */
    .cp-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
    }

    .cp-modal-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(17, 24, 39, .45);
        backdrop-filter: blur(4px);
    }

    .cp-modal-card {
        position: relative;
        width: min(520px, 92vw);
        margin: 10vh auto 0;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 18px 50px rgba(0, 0, 0, .18);
        overflow: hidden;
        animation: cpPop .18s ease;
    }

    @keyframes cpPop {
        from {
            transform: translateY(10px);
            opacity: .6;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .cp-modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 18px;
        border-bottom: 1px solid #e5e7eb;
    }

    .cp-modal-title {
        font-size: 16px;
        font-weight: 800;
        color: #111827;
    }

    .cp-modal-sub {
        font-size: 12px;
        color: #6b7280;
        margin-top: 2px;
    }

    .cp-modal-close {
        border: none;
        background: #f3f4f6;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 16px;
    }

    .cp-modal-close:hover {
        background: #e5e7eb;
    }

    .cp-modal-body {
        padding: 16px 18px;
    }

    .cp-form-group {
        margin-bottom: 12px;
    }

    .cp-form-group label {
        display: block;
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 6px;
        font-weight: 700;
    }

    .cp-form-group input,
    .cp-form-group select {
        width: 100%;
        padding: 10px 12px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        outline: none;
        font-size: 14px;
        background: #fff;
    }

    .cp-form-group input:focus,
    .cp-form-group select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .15);
    }

    .cp-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 14px 18px;
        border-top: 1px solid #e5e7eb;
    }

    .cp-user-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        margin-bottom: 10px;
    }

    .cp-user-left {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .cp-user-info {
        min-width: 0;
    }

    .cp-user-name {
        font-weight: 800;
        color: #111827;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 280px;
    }

    .cp-user-sub {
        font-size: 12px;
        color: #6b7280;
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 280px;
    }

    .cp-user-addbtn {
        border: none;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 999px;
        font-weight: 800;
        font-size: 13px;
        background: #6366f1;
        color: #fff;
    }

    .cp-user-addbtn:hover {
        background: #4f46e5;
    }

    .cp-member-link {
        text-decoration: none;
        color: #111827;
        font-weight: 800;
    }

    .cp-member-link:hover {
        color: #6366f1;
        text-decoration: underline;
    }
</style>