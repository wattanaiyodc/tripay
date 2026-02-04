<style>
    /* ===== PAGE BASE ===== */
    body {
        background: #f9fafb;
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Noto Sans Thai", sans-serif;
    }

    .cp-box {
        background: #fff;
        width: 100%;
        padding: 20px;
        margin-bottom: 24px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
    }

    .cp-page-sub {
        font-size: 13px;
        color: #6b7280;
        margin-top: 4px;
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


    .cp-box-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
        padding-right: 20px;
    }

    /* ===== TABLE ===== */
    .cp-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .cp-table thead th {
        background: #f3f4f6;
        padding: 12px 10px;
        border-bottom: 1px solid #e5e7eb;
        font-weight: 700;
        text-align: left;
    }

    .cp-table tbody td,
    .cp-table tfoot td {
        padding: 10px;
        border-bottom: 1px solid #f1f5f9;
    }

    .cp-table tbody tr:hover td {
        background: #f9fafb;
    }

    /* ===== DATE ROW ===== */
    .cp-date-row td {
        background: #eef2ff;
        font-weight: 700;
        border-top: 2px solid #6366f1;
    }

    /* ===== DAY TOTAL ===== */
    .cp-day-total td {
        background: #f9fafb;
        font-weight: 700;
        border-top: 1px dashed #cbd5e1;
    }

    /* ===== GRAND TOTAL ===== */
    .cp-total-row td {
        background: #eef2ff;
        font-weight: 800;
        border-top: 2px solid #6366f1;
    }

    /* ===== COLORS ===== */
    .tx-dr {
        /* เงินออก */
        color: #dc2626;
        font-weight: 700;
    }

    .tx-cr {
        /* เงินเข้า */
        color: #16a34a;
        font-weight: 700;
    }

    .tx-bal-pos {
        /* เงินเหลือ */
        color: #16a34a;
        font-weight: 800;
    }

    .tx-bal-neg {
        /* ติดลบ */
        color: #dc2626;
        font-weight: 800;
    }

    .tx-bal-zero {
        color: #6b7280;
        font-weight: 700;
    }

    /* ===== ALIGN ===== */
    .text-right {
        text-align: right;
    }
</style>