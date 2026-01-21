<?php
session_start();
include('lang/lang.php');
$lang = $_SESSION["lang"] ?? 'th';
$resource = load_lang_csv("trip_lang.csv", $lang);
if (!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit;
}

$cp_active     = 'trip';
$cp_user_id    = $_SESSION['user_id'];
$cp_user_name  = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
$cp_trip_name  = $resource['trip'];

include '../components/component_header.php';
include '../components/component_sidebar.php';
?>

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

    /* ===== button ===== */
    .cp-btn {
        padding: 10px 16px;
        border-radius: 8px;
        border: none;
        background: #6366f1;
        color: #fff;
        cursor: pointer;
    }

    .cp-btn:hover {
        background: #4f46e5;
    }

    /* ===== form ===== */
    .cp-form {
        display: none;
        margin-top: 16px;
    }

    .cp-form input {
        width: 100%;
        padding: 10px;
        margin-bottom: 12px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
    }

    .cp-form-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 12px 10px;
    }

    .cp-form-table td {
        padding: 6px;
    }

    /* ===== trip grid ===== */
    .cp-trip-grid {
        width: 100%;
        display: grid;
        /* ⭐ สำคัญ */
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 20px;
    }

    /* ===== trip card ===== */
    .cp-trip-card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
        cursor: pointer;
        transition: .2s;
    }

    .cp-trip-card:hover {
        transform: translateY(-4px);
    }

    .cp-trip-image {
        height: 140px;
        background: #e5e7eb;
    }

    .cp-trip-body {
        padding: 14px;
    }

    .cp-trip-title {
        font-size: 16px;
        font-weight: bold;
        text-align: center;
    }

    .cp-trip-date {
        font-size: 13px;
        text-align: center;
        padding: 10px;
        color: #6b7280;
    }

    .cp-trip-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }
</style>

<div class="cp-box">

    <div style="display:flex;justify-content:space-between;align-items:center;">
        <h3>🧳 ทริปของฉัน</h3>
        <button class="cp-btn" id="btnAddTrip">+ เพิ่มทริป</button>
    </div>

    <!-- ===== form ===== -->
    <div class="cp-form" id="tripForm">
        <table class="cp-form-table">
            <tr>
                <td colspan="2">
                    <label class="cp-label">ชื่อทริป <span style="color:red">*</span></label>
                    <input type="text" id="trip_name" placeholder="เช่น ทริปปีใหม่ 2026" required>
                </td>
            </tr>

            <tr>
                <td>
                    <label class="cp-label">วันที่เริ่ม <span style="color:red">*</span></label>
                    <input type="date" id="date_from">
                </td>
                <td>
                    <label class="cp-label">วันที่สิ้นสุด <span style="color:red">*</span></label>
                    <input type="date" id="date_to" required>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <label class="cp-label">สถานที่ (Google Maps) <span style="color:red">*</span></label>
                    <input type="text"
                        id="trip_location"
                        placeholder="วางลิงก์จาก Google Maps ที่นี่" required>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <label class="cp-label">รูปปกทริป</label>
                    <input type="file" id="trip_image" accept="image/*" required>

                    <img id="image_preview"
                        style="display:none;
                       margin-top:12px;
                       width:100%;
                       max-height:220px;
                       object-fit:cover;
                       border-radius:12px;">
                </td>
            </tr>
        </table>

        <button class="cp-btn" id="btnSaveTrip">บันทึกทริป</button>
    </div>

    <!-- ===== trip list ===== -->
    <div class="cp-trip-grid" id="tripList"></div>

</div>

<script>
    var json_request = {
        user_id: <?php echo $_SESSION['user_id'] ?>
    };

    $('#btnAddTrip').click(() => $('#tripForm').slideToggle());

    $('#trip_image').on('change', function() {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => $('#image_preview').attr('src', e.target.result).show();
        reader.readAsDataURL(file);
    });

    $('#btnSaveTrip').click(function() {
        create_trip();
    });

    function retrieve_trip() {
        $.ajax({
            url: "api/engine-trip/retrieve_trip.php",
            type: "POST",
            dataType: "json",
            data: {
                json: JSON.stringify(json_request)
            },
            success: function(res) {
                if (res.status !== 'success') return;

                let html = '';
                res.result.forEach(t => {
                    const img = t.trip_image ?
                        `/tripay/${t.trip_image}` :
                        '/tripay/assets/no-image.png';

                    html += `
                    <a href="detail.php?trip_id=${t.trip_id}" class="cp-trip-link">
                        <div class="cp-trip-card">
                            <div class="cp-trip-image"
                                style="background-image:url('${img}');
                                    background-size:cover;
                                    background-position:center;">
                            </div>

                            <div class="cp-trip-body">
                                <div class="cp-trip-title">
                                    ${t.trip_name ?? ''}
                                </div>

                                <div class="cp-trip-date">
                                    📅 ${t.date_from ?? '-'} - ${t.date_to ?? '-'}
                                </div>
                            </div>
                        </div>
                    </a>
                    `;
                });
                $('#tripList').html(html);
            }
        });
    }

    function create_trip() {
        const q = {
            trip_name: $('#trip_name').val(),
            date_from: $('#date_from').val(),
            date_to: $('#date_to').val(),
            trip_location: $('#trip_location').val()
        };

        const formData = new FormData();
        formData.append('json', JSON.stringify(q));

        const file = $('#trip_image')[0].files[0];
        if (file) formData.append('trip_image', file);

        $.ajax({
            url: "api/engine-trip/create_trip.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(res) {
                if (res.status === 'success') {
                    retrieve_trip();
                    $('#tripForm').slideUp();
                    $('#trip_name,#date_from,#date_to,#trip_image').val('');
                    $('#image_preview').hide();
                }
            }
        });
    }

    $(document).ready(retrieve_trip);
</script>

<?php include '../components/component_footer.php'; ?>