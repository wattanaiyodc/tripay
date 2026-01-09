<?php

// ตัวแปรที่ใช้ได้:
// $cp_active   (dashboard | member)
$cp_user_id    = $_SESSION['user_id'];
$cp_user_name  = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
$cp_trip_name  = 'Trip';

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
</style>

<!-- SIDEBAR -->
<nav class="cp-sidebar">
    <h1>Tripay</h1>

    <a href="../dashboard/index.php" class="<?= $cp_active === 'dashboard' ? 'cp-active' : '' ?>">
        <span class="cp-icon">📊</span>
        <span>Dashboard</span>
    </a>

    <!-- <a href="../member/index.php" class="<?= $cp_active === 'member' ? 'cp-active' : '' ?>">
        <span class="cp-icon">👥</span>
        <span>สมาชิกทริป</span>
    </a> -->

    <a href="../trip/index.php" class="<?= $cp_active === 'trip' ? 'cp-active' : '' ?>">
        <span class="cp-icon">🧳</span>
        <span>Trip</span>
    </a>

    <a href="/auth/logout.php">
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

        <div class="cp-user">
            <div class="cp-avatar">
                <?= strtoupper(substr($cp_user_name ?? 'U', 0, 1)) ?>
            </div>
            <div>
                <div><?= htmlspecialchars($cp_user_name ?? '') ?></div>
                <small>ID: <?= $cp_user_id ?? '-' ?></small>
            </div>
        </div>
    </header>

    <!-- CONTENT -->
    <div class="cp-content">