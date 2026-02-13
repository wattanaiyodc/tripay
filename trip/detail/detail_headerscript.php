<style>
    /* ===== base card ===== */
    .cp-box {
        background: #fff;
        padding: 20px;
        margin-bottom: 24px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
    }

    /* ===== back button ===== */
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
        font-weight: 500;
    }

    .cp-back-btn:hover {
        background: #e5e7eb;
    }

    /* ===== 2 column layout ===== */
    .cp-grid-2 {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        align-items: flex-start;
    }

    @media (max-width: 900px) {
        .cp-grid-2 {
            grid-template-columns: 1fr;
        }
    }

    /* ===== labels ===== */
    .cp-label {
        font-size: 15px;
        color: #000000ff;
    }

    /* ===== header row ===== */
    .cp-box-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    /* ===== icon button ===== */
    .cp-icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        background: #6366f1;
        color: #fff;
        font-size: 20px;
        cursor: pointer;
    }

    .cp-icon-btn:hover {
        background: #4f46e5;
    }

    /* ===== inline form ===== */
    .cp-inline-form {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 16px;
        margin-right: 5px;
    }

    .cp-form-group {
        margin-bottom: 12px;
    }

    .cp-form-group label {
        display: block;
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 4px;
    }

    .cp-form-group input,
    .cp-form-group textarea {
        width: 100%;
        padding: 8px 10px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
    }

    .cp-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .cp-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 10px;
    }

    /* ===== timeline table ===== */
    .cp-timeline-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .cp-timeline-row td {
        background: #f9fafb;
        padding: 12px 14px;
        border: 1px solid #e5e7eb;
        vertical-align: middle;
    }

    .cp-time {
        width: 160px;
        font-size: 13px;
        color: #6b7280;
        white-space: nowrap;
        border-radius: 10px 0 0 10px;
    }

    .cp-title {
        font-weight: 500;
    }

    .cp-action {
        width: 40px;
        text-align: right;
        border-radius: 0 10px 10px 0;
    }

    /* detail row */
    .cp-timeline-detail-row td {
        padding: 0;
        border: none;
        background: transparent;
    }

    .cp-detail-box {
        display: none;
        overflow: hidden;
        background: #fff;
        padding: 12px 14px;
        border: 1px solid #e5e7eb;
        border-top: none;
        border-radius: 0 0 10px 10px;
        font-size: 13px;
        color: #4b5563;
    }

    /* toggle btn */
    .cp-toggle-btn {
        border: none;
        background: none;
        cursor: pointer;
        font-size: 16px;
        color: #6b7280;
    }

    .cp-timeline-row.open .cp-toggle-btn {
        transform: rotate(180deg);
    }

    /* ===== action area ===== */
    .cp-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 20px;
    }

    /* base button */
    .cp-btn {
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all .15s ease;
    }

    /* primary */
    .cp-btn-primary {
        background: #6366f1;
        color: #fff;
        box-shadow: 0 6px 14px rgba(99, 102, 241, .25);
    }

    .cp-btn-primary:hover {
        background: #4f46e5;
        transform: translateY(-1px);
    }

    .cp-btn-primary:active {
        transform: translateY(0);
    }

    /* secondary */
    .cp-btn-secondary {
        background: #f9fafb;
        color: #374151;
        border: 1px solid #e5e7eb;
    }

    .cp-btn-secondary:hover {
        background: #f3f4f6;
    }

    /* disabled (เผื่อใช้) */
    .cp-btn:disabled {
        opacity: .6;
        cursor: not-allowed;
    }

    /* ===== timeline tabs ===== */
    .cp-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .cp-tab-btn {
        padding: 8px 12px;
        border-radius: 999px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        cursor: pointer;
        font-size: 13px;
        color: #374151;
    }

    .cp-tab-btn:hover {
        background: #f3f4f6;
    }

    .cp-tab-btn.active {
        background: #6366f1;
        color: #fff;
        border-color: #6366f1;
    }

    #trip_location {
        font-weight: 500;
        color: #111827;
    }

    .cp-map-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        text-decoration: none;
        color: #111827;
        font-weight: 500;
        transition: all .15s ease;
    }

    .cp-map-link:hover {
        background: #eef2ff;
        border-color: #c7d2fe;
        transform: translateY(-1px);
    }

    .cp-map-icon {
        font-size: 12px;
        color: #6b7280;
    }

    .cp-map-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        background: #fff;
        box-shadow: 0 6px 16px rgba(0, 0, 0, .06);
        transition: all .15s ease;
    }

    .cp-map-card:hover {
        transform: translateY(-1px);
        border-color: #c7d2fe;
        box-shadow: 0 10px 22px rgba(0, 0, 0, .08);
    }

    .cp-map-pin {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: #eef2ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .cp-map-title {
        font-weight: 600;
        color: #111827;
        line-height: 1.2;
    }

    .cp-map-sub {
        font-size: 12px;
        color: #6b7280;
        margin-top: 2px;
    }

    .cp-map-open {
        text-decoration: none;
        background: #6366f1;
        color: #fff;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .cp-map-open:hover {
        background: #4f46e5;
    }

    .cp-map-empty {
        color: #6b7280;
        font-weight: 500;
    }

    .cp-trip-title {
        font-size: 28px;
        font-weight: 800;
        margin: 10px 0 14px;
        color: #111827;
    }

    .cp-trip-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px 18px;
        margin-top: 10px;
    }

    @media (max-width: 900px) {
        .cp-trip-meta {
            grid-template-columns: 1fr;
        }
    }

    .cp-meta-item {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 12px 14px;
    }

    .cp-meta-item-full {
        grid-column: 1 / -1;
    }

    .cp-meta-label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 6px;
    }

    .cp-meta-value {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
    }

    /* ===== map card (new) ===== */
    .cp-map-card2 {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        text-decoration: none;
        transition: all .18s ease;
    }

    .cp-map-card2:hover {
        border-color: #c7d2fe;
        box-shadow: 0 10px 22px rgba(0, 0, 0, .08);
        transform: translateY(-1px);
    }

    .cp-map-card2-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: #eef2ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex: 0 0 auto;
    }

    .cp-map-card2-body {
        min-width: 0;
        flex: 1 1 auto;
    }

    .cp-map-card2-title {
        font-weight: 800;
        color: #111827;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cp-map-card2-sub {
        font-size: 12px;
        color: #6b7280;
        margin-top: 2px;
    }

    .cp-map-card2-action {
        background: #6366f1;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        padding: 8px 12px;
        border-radius: 999px;
        flex: 0 0 auto;
    }

    .cp-meta-empty {
        color: #6b7280;
        font-weight: 600;
    }

    .member-table {
        width: 100%;
        border-collapse: collapse;
    }

    .member-table th,
    .member-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        vertical-align: middle;
    }

    .cp-avatar {
        width: 40px;
        height: 40px;
        border-radius: 999px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #6b7280;
        font-size: 14px;
    }

    .cp-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .td-avatar {
        width: 60px;
        position: relative;
    }

    .td-avatar::after {
        content: "";
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 1px;
        height: 26px;
        background: #e5e7eb;
    }

    .cp-edit-btn {
        width: 36px;
        height: 36px;
        border-radius: 999px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        color: #111827;
        cursor: pointer;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        font-size: 16px;
        line-height: 1;

        transition: all .15s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, .06);
    }

    .cp-edit-btn:hover {
        background: #eef2ff;
        border-color: #c7d2fe;
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(0, 0, 0, .10);
    }

    .cp-edit-btn:active {
        transform: translateY(0px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, .06);
    }

    .cp-edit-btn:focus {
        outline: none;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, .25), 0 4px 10px rgba(0, 0, 0, .06);
    }

    .cp-edit-btn:disabled {
        opacity: .5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .cp-import-btn {
        width: 100%;
        height: 90px;

        border: none;
        border-radius: 16px;

        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;

        font-size: 20px;
        font-weight: 800;

        cursor: pointer;

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;

        box-shadow: 0 12px 28px rgba(99, 102, 241, 0.35);
        transition: all .2s ease;
    }

    .cp-import-btn small {
        font-size: 13px;
        font-weight: 500;
        opacity: 0.9;
    }

    .cp-import-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 36px rgba(99, 102, 241, 0.45);
    }

    .cp-import-btn:active {
        transform: translateY(0);
        box-shadow: 0 8px 18px rgba(99, 102, 241, 0.35);
    }

    /* ===== MODAL ===== */
    .cp-modal {
        position: fixed;
        inset: 0;
        z-index: 999;
        display: none;
    }

    .cp-modal-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, .45);
    }

    .cp-modal-box {
        position: relative;
        background: #fff;
        width: 520px;
        max-width: calc(100% - 32px);
        margin: 8vh auto;
        border-radius: 16px;
        box-shadow: 0 30px 80px rgba(0, 0, 0, .25);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .cp-modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cp-modal-header h3 {
        margin: 0;
        font-size: 18px;
    }

    .cp-modal-close {
        border: none;
        background: none;
        font-size: 20px;
        cursor: pointer;
    }

    .cp-modal-body {
        padding: 20px;
    }

    .cp-modal-footer {
        padding: 16px 20px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    /* loading */
    .cp-loading {
        text-align: center;
        font-weight: 700;
        color: #6b7280;
    }

    /* buttons */
    .cp-btn-primary {
        background: #6366f1;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 16px;
        font-weight: 700;
        cursor: pointer;
    }

    .cp-btn-secondary {
        background: #f3f4f6;
        border: none;
        border-radius: 10px;
        padding: 10px 16px;
        font-weight: 700;
        cursor: pointer;
    }

    /* กล่อง */
    .cp-box {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 24px;
        margin: 16px 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
    }

    /* ปุ่ม Import Slip */
    .cp-btn-import {
        width: 100%;
        height: 64px;

        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;

        border: none;
        border-radius: 14px;

        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;

        font-size: 18px;
        font-weight: 800;
        letter-spacing: .3px;

        cursor: pointer;
        transition: all .2s ease;
        box-shadow: 0 12px 28px rgba(99, 102, 241, .35);
    }

    /* hover */
    .cp-btn-import:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 36px rgba(99, 102, 241, .45);
        filter: brightness(1.05);
    }

    /* active */
    .cp-btn-import:active {
        transform: translateY(0);
        box-shadow: 0 8px 18px rgba(99, 102, 241, .35);
    }

    /* focus */
    .cp-btn-import:focus {
        outline: none;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, .25);
    }

    /* ปุ่ม Import Slip */
    .cp-btn-create {
        width: 100%;
        height: 64px;

        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;

        border: none;
        border-radius: 14px;

        background: linear-gradient(135deg, #f16363, #4f46e5);
        color: #fff;

        margin-top: 20px;

        font-size: 18px;
        font-weight: 800;
        letter-spacing: .3px;

        cursor: pointer;
        transition: all .2s ease;
        box-shadow: 0 12px 28px rgba(99, 102, 241, .35);
    }

    .cp-dropdown {
        position: relative;
        width: 220px;
    }

    .cp-dropdown-display {
        border: 1px solid #d1d5db;
        padding: 6px 10px;
        cursor: pointer;
        background: #fff;
    }

    .cp-dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #e5e7eb;
        max-height: 220px;
        overflow: auto;
        display: none;
        z-index: 1000;
    }

    .cp-dd-item {
        display: block;
        padding: 6px 10px;
        cursor: pointer;
    }

    .cp-ms {
        position: relative;
        width: 260px;
        font-size: 14px;
    }

    .cp-ms-display {
        border: 1px solid #d1d5db;
        padding: 7px 10px;
        background: #fff;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 6px;
    }

    .cp-ms-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-top: 4px;
        display: none;
        z-index: 999;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }

    .cp-ms-search {
        width: 100%;
        border: none;
        border-bottom: 1px solid #eee;
        padding: 8px;
        outline: none;
    }

    .cp-ms-list {
        max-height: 220px;
        overflow: auto;
    }

    .cp-ms-item {
        display: block;
        padding: 6px 10px;
        cursor: pointer;
    }

    .cp-ms-item:hover {
        background: #f3f4f6;
    }
</style>