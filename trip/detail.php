<?php
session_start();
include('lang/lang.php');
$lang = $_SESSION["lang"] ?? 'th';
$resource = load_lang_csv("trip_lang.csv", $lang);
if (!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit;
}

$trip_id = (int)($_GET['trip_id'] ?? $_SESSION['trip_id'] ?? 0);
if ($trip_id <= 0) {
    exit('invalid trip');
}
$_SESSION['trip_id'] = $trip_id;
$cp_trip_name = $resource["detail"];
$cp_title  = 'Detail';
$cp_active = 'detail';

include 'components/component_header.php';
include 'components/component_sidebar.php';
?>

<?php include('detail/detail_headerscript.php'); ?>


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


<div class="cp-box">
    <button id="import_slip" class="cp-btn-import">
        📷 <?php echo $resource["import_transaction"] ?>
    </button>

    <!-- input แนบรูป (ซ่อนไว้) -->
    <input type="file" id="slip_file" accept="image/*" hidden>
    <div id="qr-temp" style="display:none;"></div>
</div>

<!-- ===== MODAL ===== -->
<div id="slip_modal" class="cp-modal">
    <div class="cp-modal-backdrop"></div>

    <div class="cp-modal-box">
        <div class="cp-modal-header">
            <h3>📄 ข้อมูลจากสลิป</h3>
            <button class="cp-modal-close" id="close_modal">✕</button>
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



<script src="https://unpkg.com/html5-qrcode"></script>
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
        location.href = 'index.php';
    });
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

    $('#import_slip').on('click', function() {
        $('#slip_file').click();
    });

    $('#slip_file').on('change', function() {
        if (!this.files.length) return;

        openModal();
        showLoading();

        const file = this.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            const img = new Image();
            img.src = e.target.result;

            img.onload = function() {
                const html5QrCode = new Html5Qrcode("qr-temp");

                html5QrCode.scanFile(file, true)
                    .then(qrText => {
                        console.log('QR STRING:', qrText);

                        verifySlipByQR(qrText);
                    })
                    .catch(err => {
                        $('#slip_result').html(`
                        <div style="color:red;font-weight:700;">
                            ไม่สามารถอ่าน QR Code จากรูปได้
                        </div>
                    `);
                    });
            };
        };

        reader.readAsDataURL(file);
    });

    function verifySlipByQR(qrString) {
        $.ajax({
            url: 'api/engine-slip/verify.php',
            type: 'POST',
            dataType: 'json',
            data: {
                json: JSON.stringify({
                    qr: qrString,
                    trip_id: json_request.trip_id
                })
            },
            success: function(res) {
                if (res.status !== 'success') {
                    $('#slip_result').html(`
                    <div style="color:red;font-weight:700;">
                        ${res.message || 'ตรวจสอบสลิปล้มเหลว'}
                    </div>
                `);
                    return;
                }

                renderSlipData(res.data);
            },
            error: function() {
                $('#slip_result').html(`
                <div style="color:red;font-weight:700;">
                    ไม่สามารถติดต่อ API ได้
                </div>
            `);
            }
        });
    }


    function openModal() {
        $('#slip_modal').fadeIn(120);
    }

    function closeModal() {
        $('#slip_modal').fadeOut(120);
    }

    $('#close_modal, #cancel_modal, .cp-modal-backdrop').on('click', closeModal);

    function showLoading() {
        $('#slip_result').html('<div class="cp-loading">กำลังประมวลผลสลิป...</div>');
    }

    function renderSlipData(d) {
        $('#slip_result').html(`
        <table class="cp-table">
            <tr>
                <td><strong>จากธนาคาร</strong></td>
                <td id="slip_bank_from">
                    ${d.bank_from ?? '-'}
                </td>
            </tr>

            <tr>
                <td><strong>ถึงธนาคาร</strong></td>
                <td id="slip_bank_to">
                    ${d.bank_to ?? '-'}
                </td>
            </tr>

            <tr>
                <td><strong>วันที่</strong></td>
                <td>
                    <span id="slip_tx_date">${d.tx_date ?? '-'}</span>
                    <span id="slip_tx_time">${d.tx_time ?? ''}</span>
                </td>
            </tr>

            <tr>
                <td><strong>จาก</strong></td>
                <td id="slip_from">
                    ${d.from ?? '-'}
                </td>
            </tr>

            <tr>
                <td><strong>ถึง</strong></td>
                <td id="slip_to">
                    ${d.to ?? '-'}
                </td>
            </tr>

            <tr>
                <td><strong>จำนวนเงิน</strong></td>
                <td style="color:#16a34a;font-weight:800;">
                    <span id="slip_amount">
                        ${Number(d.amount || 0).toFixed(2)}
                    </span>
                    บาท
                </td>
            </tr>

            <tr>
                <td><strong>เลขอ้างอิง</strong></td>
                <td id="slip_ref">
                    ${d.reference_id ?? '-'}
                </td>
            </tr>

            <!-- ===== เลือก DR / CR ===== -->
            <tr>
                <td><strong>ประเภท</strong></td>
                <td>
                    <label style="margin-right:12px;">
                        <input type="radio"
                            name="tx_type"
                            id="tx_type_dr"
                            value="DR"
                            checked>
                        รายจ่าย (DR)
                    </label>

                    <label>
                        <input type="radio"
                            name="tx_type"
                            id="tx_type_cr"
                            value="CR">
                        รายรับ (CR)
                    </label>
                </td>
            </tr>
        </table>

    `);
    }


    $('#confirm_modal').on('click', function() {
        save_confirm();
        closeModal();
    });

    function save_confirm() {
        let msg = `คุณต้องการบันทึกรายการนี้ใช่หรือไม่?`;
        if (!confirm(msg)) {
            return false; // ผู้ใช้กดยกเลิก
        }
        save_function();
    }

    function save_function() {
        let q = Object.assign({}, json_request);
        q.detail = {};
        q["detail"]["slip_bank_from"] = $('#slip_bank_from').text().trim();
        q["detail"]["slip_bank_to"] = $('#slip_bank_to').text().trim();
        q["detail"]["slip_tx_date"] = $('#slip_tx_date').text().trim();
        q["detail"]["slip_tx_time"] = $('#slip_tx_time').text().trim();
        q["detail"]["slip_from"] = $('#slip_from').text().trim();
        q["detail"]["slip_to"] = $('#slip_to').text().trim();
        q["detail"]["slip_amount"] = Number($('#slip_amount').text().replace(/,/g, '')) || 0;
        q["detail"]["slip_ref"] = $('#slip_ref').text().trim();
        q["type"] = $('input[name="tx_type"]:checked').val();
        q["source"] = 'slip';

        var json = JSON.stringify(q);
        $.ajax({
            url: 'api/engine-transaction/create_transaction.php',
            type: 'post',
            data: {
                json: json
            },
            dataType: 'json',
            success: function(res) {
                if (res.success != 1) {
                    alert(res.message);
                    return false;
                }
            }
        });

    }

    function buildTransactionPayload() {
        let type = $('input[name="tx_type"]:checked').val(); // DR | CR
        let amount = Number($('#tx_amount').val()) || 0;

        let debit = 0;
        let credit = 0;

        if (type === 'DR') {
            debit = amount;
        } else {
            credit = amount;
        }

        return {
            debit: debit,
            credit: credit
        };
    }

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