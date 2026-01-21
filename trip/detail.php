<?php
session_start();
include('lang/lang.php');
$lang = $_SESSION["lang"] ?? 'th';
$resource = load_lang_csv("trip_lang.csv", $lang);
if (!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit;
}

$trip_id = (int)($_GET['trip_id'] ?? 0);
if ($trip_id === 0) {
    exit('invalid trip');
}
$_SESSION['trip_id'] = $trip_id;
$cp_trip_name = $resource["detail"];
$cp_title  = 'Detail';
$cp_active = 'detail';

include 'components/component_header.php';
include 'components/component_sidebar.php';
?>

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
</style>


<!-- ================= กล่องรายละเอียดทริป ================= -->
<!-- ===== Trip Info ===== -->
<div class="cp-box">
    <a href="index.php" id="btn_back" class="cp-back-btn">← กลับ</a>

    <h2 id="trip_name" class="cp-trip-title"></h2>

    <div class="cp-trip-meta">
        <div class="cp-meta-item">
            <div class="cp-meta-label">วันออกเดินทาง</div>
            <div class="cp-meta-value" id="date_from">-</div>
        </div>

        <div class="cp-meta-item">
            <div class="cp-meta-label">วันสิ้นสุด</div>
            <div class="cp-meta-value" id="date_to">-</div>
        </div>

        <div class="cp-meta-item cp-meta-item-full">
            <div class="cp-meta-label">📍 สถานที่</div>

            <a id="trip_location_link" class="cp-map-card2" href="#" target="_blank" style="display:none;">
                <div class="cp-map-card2-icon">📍</div>
                <div class="cp-map-card2-body">
                    <div class="cp-map-card2-title" id="trip_location_text">-</div>
                    <div class="cp-map-card2-sub">เปิดใน Google Maps</div>
                </div>
                <div class="cp-map-card2-action">เปิดแผนที่ ↗</div>
            </a>

            <div id="trip_location_plain" class="cp-meta-empty" style="display:none;">-</div>
        </div>
    </div>
</div>

<!-- ===== Timeline + Member ===== -->
<div class="cp-grid-2">

    <!-- Timeline -->
    <div class="cp-box">
        <div class="cp-box-header">
            <h3>📅 Timeline</h3>
            <button id="add_timeline" class="cp-icon-btn">+</button>
        </div>

        <div id="timeline_form" class="cp-inline-form" style="display:none;">
            <div class="cp-form-group">
                <label>วันที่ <span style="color:red">*</span></label>
                <input type="date" id="tl_date" required>
            </div>

            <div class="cp-form-row">
                <div class="cp-form-group">
                    <label>เวลาเริ่ม <span style="color:red">*</span></label>
                    <input type="time" id="tl_starttime" required>
                </div>
                <div class="cp-form-group">
                    <label>เวลาสิ้นสุด <span style="color:red">*</span></label>
                    <input type="time" id="tl_endtime" required>
                </div>
            </div>

            <div class="cp-form-group">
                <label>กิจกรรม <span style="color:red">*</span></label>
                <input type="text" id="tl_title" required>
            </div>

            <div class="cp-form-group">
                <label class="cp-label">สถานที่ (Google Maps) </label>
                <input type="text"
                    id="tl_location"
                    placeholder="วางลิงก์จาก Google Maps ที่นี่">
            </div>

            <div class="cp-form-group">
                <label>รายละเอียด</label>
                <textarea id="tl_detail"></textarea>
            </div>

            <div class="cp-form-actions">
                <button type="button" class="cp-btn cp-btn-secondary" id="cancel_timeline">
                    ยกเลิก
                </button>
                <button type="button" class="cp-btn cp-btn-primary" id="save_timeline">
                    บันทึก
                </button>
            </div>

        </div>
        <div id="timeline_tabs" class="cp-tabs"></div>
        <table class="cp-timeline-table">
            <tbody id="timeline">
            </tbody>
        </table>
    </div>

    <div class="cp-box">
        <div class="cp-box-header">
            <h3>👥 สมาชิกทริป</h3>
            <button id="btn_edit_member" class="cp-edit-btn" type="button" title="จัดการสมาชิก">
                ✏️
            </button>
        </div>
        <table class="member-table" width="100%">
            <thead>
                <tr>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody id="member_list">
            </tbody>
        </table>
    </div>

</div>




<script>
    var json_request = {
        user_id: <?php echo $_SESSION['user_id']; ?>,
        trip_id: <?php echo $trip_id; ?>
    };
    let timeline_all = [];
    let selected_date = '';

    let trip_date_from = '';
    let trip_date_to = '';

    $(document).on("click", ".cp-toggle-btn", function() {
        let $mainRow = $(this).closest("tr");
        let $detailRow = $mainRow.next(".cp-timeline-detail-row");
        let $box = $detailRow.find(".cp-detail-box");

        $mainRow.toggleClass("open");
        $box.stop(true, true).slideToggle(200);
    });
    $(document).on("click", ".cp-tab-btn", function() {
        $(".cp-tab-btn").removeClass("active");
        $(this).addClass("active");

        $("#tl_date").val(selected_date);

        selected_date = $(this).data("date");
        $("#tl_date").val(selected_date);
        renderTimelineByDate(selected_date);
    });

    $('#btn_back').click(function() {
        unset($_SESSION['trip_id']);
    })
    $('#btn_edit_member').click(function() {
        location.href = `manage_member.php?trip_id=<?php echo $trip_id ?>`;
    });
    $('#add_timeline').click(function() {
        $('#timeline_form').slideToggle();
        $("#tl_date").val(selected_date || trip_date_from);
    });

    $('#cancel_timeline').click(function() {
        $('#timeline_form').slideUp();
    });

    $('#save_timeline').click(function() {

        let valid = true;

        $('#timeline_form [required]').each(function() {
            if (!$(this).val()) {
                $(this).css('border-color', '#dc2626');
                valid = false;
            } else {
                $(this).css('border-color', '#d1d5db');
            }
        });

        if (!valid) {
            alert('กรุณากรอกข้อมูลที่จำเป็นให้ครบ');
            return;
        }

        create_timeline();
    });



    function retrieve_trip() {
        $.ajax({
            url: "api/engine-trip/retrieve_detail_trip.php",
            type: "POST",
            dataType: "json",
            data: {
                json: JSON.stringify(json_request)
            },
            success: function(res) {
                if (res.status !== 'success') {
                    alert(res.message);
                    return;
                }

                let trip = res.result;

                let loc = (trip.location || '').trim();

                $("#trip_location_link").hide();
                $("#trip_location_plain").hide();

                if (!loc) {
                    $("#trip_location_plain").text("-").show();
                } else {
                    let href = loc.startsWith("http") ?
                        loc :
                        "https://www.google.com/maps/search/?api=1&query=" + encodeURIComponent(loc);

                    $("#trip_location_text").text(loc.startsWith("http") ? "Google Maps" : loc);
                    $("#trip_location_link").attr("href", href).show();
                }



                // --- trip info ---
                $('#trip_name').text(trip.trip_name);
                $('#date_from').text(trip.date_from);
                $('#date_to').text(trip.date_to);

                trip_date_from = trip.date_from;
                trip_date_to = trip.date_to;

                // จำกัดวันใน input date
                $("#tl_date").attr({
                    min: trip_date_from,
                    max: trip_date_to
                });

                $("#tl_date").val(selected_date || trip_date_from);

                buildTimelineTabs(trip_date_from, trip_date_to);
            }
        });
    }



    function buildTimelineTabs(dateFrom, dateTo) {
        if (!dateFrom || !dateTo) return;

        let start = new Date(dateFrom);
        let end = new Date(dateTo);

        let html = '';
        let firstDate = '';

        for (let dt = new Date(start); dt <= end; dt.setDate(dt.getDate() + 1)) {
            let yyyy = dt.getFullYear();
            let mm = String(dt.getMonth() + 1).padStart(2, '0');
            let dd = String(dt.getDate()).padStart(2, '0');

            let dateStr = `${yyyy}-${mm}-${dd}`;

            if (!firstDate) firstDate = dateStr;

            html += `<button type="button" class="cp-tab-btn" data-date="${dateStr}">${dateStr}</button>`;
        }

        $('#timeline_tabs').html(html);

        // default เลือกวันแรก
        selected_date = firstDate;
        $("#timeline_tabs .cp-tab-btn").first().addClass("active");

        renderTimelineByDate(selected_date);
    }

    function renderTimelineByDate(dateStr) {
        let html = '';

        let list = timeline_all.filter(r => {
            let d = (r.start_time || '').substr(0, 10);
            return d === dateStr;
        });

        if (list.length === 0) {
            html = `<tr><td colspan="3" style="text-align:center;color:#6b7280;">ไม่มี timeline ของวันนี้</td></tr>`;
            $("#timeline").html(html);
            return;
        }

        list.forEach((row, i) => {
            let timeText = formatTimeRange(row.start_time, row.end_time);
            let titleText = row.title ?? '';
            let detailText = row.detail ?? '';

            let loc = (row.location || '').trim();
            let mapHtml = '';

            if (loc) {
                let href = loc.startsWith("http") ?
                    loc :
                    "https://www.google.com/maps/search/?api=1&query=" + encodeURIComponent(loc);

                mapHtml = `
                <a class="cp-map-card2" href="${href}" target="_blank">
                    <div class="cp-map-card2-icon">📍</div>
                    <div class="cp-map-card2-body">
                        <div class="cp-map-card2-title">${loc.startsWith("http") ? "Google Maps" : loc}</div>
                        <div class="cp-map-card2-sub">เปิดใน Google Maps</div>
                    </div>
                    <div class="cp-map-card2-action">เปิดแผนที่ ↗</div>
                </a>
            `;
            }

            html += `
            <tr class="cp-timeline-row" data-index="${i}">
                <td class="cp-time">${timeText}</td>
                <td class="cp-title">📍 ${titleText}</td>
                <td class="cp-action">
                    <button class="cp-toggle-btn" type="button">▾</button>
                </td>
            </tr>

            <tr class="cp-timeline-detail-row">
                <td colspan="3">
                    <div class="cp-detail-box">
                        <div style="font-size:12px;color:#6b7280;margin-bottom:6px;">รายละเอียด</div>

                        ${detailText ? `<div style="margin-bottom:10px;color:#111827;">${detailText}</div>` : '<div style="color:#9ca3af;">-</div>'}

                        ${mapHtml}
                    </div>
                </td>
            </tr>
        `;
        });

        $("#timeline").html(html);
    }


    function create_timeline() {
        var q = json_request;
        q["tl_date"] = $('#tl_date').val();
        q["tl_starttime"] = $('#tl_starttime').val();
        q["tl_endtime"] = $('#tl_endtime').val();
        q["tl_title"] = $('#tl_title').val();
        q["tl_location"] = $('#tl_location').val();
        q["tl_detail"] = $('#tl_detail').val();

        var json = JSON.stringify(q);
        $.ajax({
            url: 'api/engine-timeline/create_timeline.php',
            type: 'POST',
            data: {
                'json': json
            },
            dataType: 'json',
            success: function(res) {
                if (res.status !== 'success') {
                    alert(res.message);
                    return;
                }

                $('#timeline_form').slideUp();

                selected_date = $('#tl_date').val();

                retrieve_timeline();

                // set active tab ให้ตรงวัน
                $(".cp-tab-btn").removeClass("active");
                $(`.cp-tab-btn[data-date="${selected_date}"]`).addClass("active");
            }
        });
    }

    function retrieve_timeline() {
        var json = JSON.stringify(json_request);

        $.ajax({
            url: 'api/engine-timeline/retrieve_timeline.php',
            type: 'POST',
            data: {
                json: json
            },
            dataType: 'json',
            success: function(res) {
                if (res.status !== 'success') {
                    alert(res.message);
                    return;
                }

                timeline_all = Array.isArray(res.result) ? res.result : [];

                if (!selected_date && trip_date_from) {
                    selected_date = trip_date_from;
                }

                renderTimelineByDate(selected_date);
            }
        });
    }

    function retrieve_member() {
        var q = json_request;
        var json = JSON.stringify(q);
        $.ajax({
            url: 'api/engine-member/retrieve_member.php',
            type: 'post',
            data: {
                json: json
            },
            dataType: 'json',
            success: function(res) {
                if (res.status !== 'success') {
                    alert(res.message);
                    return;
                }
                var result = res.result
                var html = '';
                if (!result || result.length === 0) {
                    $("#member_list").html(`<tr><td colspan="2">ยังไม่มีสมาชิก</td></tr>`);
                    return;
                }

                $.each(result, function(index, item) {
                    let fullName = `${item.first_name ?? ''} ${item.last_name ?? ''}`.trim();
                    let avatar = item.avatar_url || item.profile_image || ''; // แล้วแต่ field ของคุณ

                    // fallback เอาตัวอักษรแรกของชื่อ
                    let firstChar = (fullName ? fullName.charAt(0).toUpperCase() : '?');

                    let avatarHtml = avatar ?
                        `<div class="cp-avatar"><img src="${avatar}" alt=""></div>` :
                        `<div class="cp-avatar">${firstChar}</div>`;

                    html += `
                        <tr>
                            <td>${avatarHtml}</td>
                            <td>${fullName || '-'}</td>
                        </tr>
                    `;
                });

                $("#member_list").html(html);
            }
        });
    }

    function check_permission_config() {
        var q = json_request;
        var json = JSON.stringify(q);
        $.ajax({
            url: 'api/authentication.php',
            type: 'post',
            data: {
                json: json
            },
            dataType: 'json',
            success: function(res) {
                if (res.status !== 'success') {
                    alert(res.message);
                    return;
                }
                var role = res.role.role;
                if (role !== 'master') {
                    $('#btn_edit_member').hide();
                    $('#add_timeline').hide();
                } else {
                    $('#btn_edit_member').show();
                    $('#add_timeline').show();
                }
            }

        })
    }

    function formatTimeRange(start_time, end_time) {

        if (!start_time || !end_time) return '';

        let start = start_time.substr(11, 5);
        let end = end_time.substr(11, 5);

        return `${start} - ${end}`;
    }

    $(document).ready(function() {
        retrieve_trip();
        retrieve_timeline();
        retrieve_member();
        check_permission_config();
    });
</script>

<?php include '../components/component_footer.php'; ?>