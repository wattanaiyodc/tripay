<?php
/* 
ตัวแปรที่ใช้ได้ (ถ้ามี):
$cp_title
*/
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title><?= $cp_title ?? 'Tripay' ?></title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        /* ===== RESET ===== */
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        /* ===== APP LAYOUT ===== */
        .cp-app {
            display: flex;
            min-height: 100vh;
            background: #f4f6f8;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        /* ===== MAIN AREA ===== */
        .cp-main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* ===== TOPBAR ===== */
        .cp-topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 14px 24px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cp-topbar .cp-title {
            font-size: 16px;
            font-weight: 600;
        }

        .cp-topbar .cp-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cp-topbar .cp-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #2563eb;
            color: #fff;

            display: flex;
            align-items: center;
            justify-content: center;

            font-weight: bold;
        }

        /* ===== CONTENT ===== */
        .cp-content {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
        }
    </style>
</head>

<body>

    <div class="cp-app">