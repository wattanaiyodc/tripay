<?php

// ตัวแปรที่ใช้ได้:
// $cp_active   (dashboard | member)
$cp_user_id    = $_SESSION['user_id'];
$cp_user_name  = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
$cp_trip_name  = $cp_trip_name ?? 'Trip';
$cp_active = $cp_active ?? '';
?>
<style>
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

    .cp-topbar-right {
        display: flex;
        align-items: center;
        gap: 14px;
    }

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

    /* ให้ user block เรียงแนวนอนชัดๆ */
    .cp-user {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* กันข้อความชื่อยาวแล้วดันทับปุ่ม */
    .cp-user>div:last-child {
        line-height: 1.2;
        white-space: nowrap;
    }

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
</style>
<?php include("notification.php"); ?>
<!-- SIDEBAR -->
<nav class="cp-sidebar">
    <h1>Tripay</h1>


    <a href="../trip/index.php" class="<?= $cp_active === 'trip' ? 'cp-active' : '' ?>">
        <span class="cp-icon">🧳</span>
        <span>Trip</span>
    </a>

    <a href="../dashboard/index.php" class="<?= $cp_active === 'dashboard' ? 'cp-active' : '' ?>">
        <span class="cp-icon">📊</span>
        <span>Dashboard</span>
    </a>

    <div class="cp-sidebar-divider"></div>

    <a href="../auth/logout.php">
        <span class="cp-icon">🚪</span>
        <span>Logout</span>
    </a>
</nav>

<!-- MAIN -->
<div class="cp-main">

    <!-- TOPBAR -->
    <header class="cp-topbar">
        <div class="cp-title">
            <?= htmlspecialchars($cp_trip_name ?? '') ?>
        </div>

        <div class="cp-topbar-right">
            <div class="cp-noti" id="cp_noti">
                <div class="cp-noti-btn">
                    <!-- MAIL ICON -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                        <path d="M3 7l9 6 9-6"></path>
                    </svg>

                    <span class="cp-noti-badge" id="noti_badge">3</span>
                </div>

                <div class="cp-noti-box" id="noti_box">
                    <div class="cp-noti-header">
                        แจ้งเตือน
                    </div>

                    <div class="cp-noti-list" id="noti_list">

                        <!-- item -->
                        <div class="cp-noti-item unread">
                            <div class="cp-noti-title">คำขอรับเงินใหม่</div>
                            <div class="cp-noti-msg">แมน ขอรับ 250 บาท • ค่าอาหาร</div>
                        </div>

                        <div class="cp-noti-item">
                            <div class="cp-noti-title">Timeline ใหม่</div>
                            <div class="cp-noti-msg">เพิ่มกิจกรรมวันที่ 12</div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- LANG TOGGLE -->
            <form method="POST" action="../trip/lang/switch_lang.php" class="cp-lang-form">
                <button type="submit" name="lang" value="th"
                    class="cp-lang-btn <?= (($_SESSION['lang'] ?? 'th') === 'th') ? 'active' : '' ?>">
                    TH
                </button>
                <button type="submit" name="lang" value="en"
                    class="cp-lang-btn <?= (($_SESSION['lang'] ?? 'th') === 'en') ? 'active' : '' ?>">
                    EN
                </button>
            </form>

            <!-- USER -->
            <div class="cp-user">
                <div class="cp-avatar">
                    <?= strtoupper(substr($cp_user_name ?? 'U', 0, 1)) ?>
                </div>
                <div>
                    <div><?= htmlspecialchars($cp_user_name ?? '') ?></div>
                    <small>ID: <?= $cp_user_id ?? '-' ?></small>
                </div>
            </div>
        </div>
    </header>

    <!-- CONTENT -->
    <div class="cp-content">