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

    .cp-modal{
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.6);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    }

    .cp-modal-box{
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        width: 400px;
    }

    .cp-modal-title{
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    /* ===== Overlay ===== */
.cp-modal{
    position: fixed;
    inset: 0;
    background: rgba(17,24,39,.55);   /* ⭐ ดำอมฟ้า ดู modern */
    backdrop-filter: blur(4px);       /* ⭐ เบลอพื้นหลัง */
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

/* ===== Modal Box ===== */
.cp-modal-box{
    background: #ffffff;
    width: 420px;
    max-width: calc(100% - 40px);
    border-radius: 14px;
    padding: 22px 20px 18px;

    box-shadow:
        0 20px 45px rgba(0,0,0,.12),
        0 4px 12px rgba(0,0,0,.08);

    transform: scale(.92);
    opacity: 0;
    transition: all .18s ease;
}

/* ⭐ ตอนเปิด modal */
.cp-modal.show .cp-modal-box{
    transform: scale(1);
    opacity: 1;
}

/* ===== Title ===== */
.cp-modal-title{
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 6px;
}

/* ===== Message ===== */
.cp-modal-body{
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 14px;
}

/* ===== QR Preview ===== */
#qr_preview{
    display:flex;
    justify-content:center;
    align-items:center;
    padding:12px;
    background:#f9fafb;
    border-radius:10px;
    margin-bottom:14px;
}

/* ===== Close Button ===== */
.cp-modal-close{
    width:100%;
    border:none;
    background:#2563eb;
    color:#fff;
    padding:10px;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
    transition:.15s;
}

.cp-modal-close:hover{
    background:#1d4ed8;
}
</style>
<div class="cp-topbar-right">
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

                    <span class="cp-noti-badge" id="noti_badge"></span>
                </div>

                <div class="cp-noti-box" id="noti_box">
                    <div class="cp-noti-header">
                        แจ้งเตือน
                    </div>

                    <div class="cp-noti-list" id="noti_list">

                    </div>
                </div>
            </div>


            <div id="cpModal" class="cp-modal">
    <div class="cp-modal-box">
        <div class="cp-modal-title"></div>
        <div class="cp-modal-body"></div>
         <div id="qr_preview"
                    style="margin-bottom:12px;">
                </div>
        <div id="fullname"></div>
        <button class="cp-modal-close">ปิด</button>
    </div>
</div>

<script>
    var json_request = {
        user_id: <?php echo $_SESSION['user_id']; ?>,
        trip_id: <?php echo $_SESSION['trip_id']; ?>
    };
   // ===== เปิด / ปิด noti box =====
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


// ===== modal =====
$(document).on("click",".cp-modal-close",function(e){
    e.stopPropagation();
    $("#cpModal").removeClass("show").fadeOut(120);
});

// คลิกพื้นหลัง modal แล้วปิด
$(document).on("click","#cpModal",function(e){
    if(e.target.id === "cpModal"){
        $(this).fadeOut(150);
    }
});


// ===== click noti item =====
$(document).on("click", ".cp-noti-item", function (e) {

    e.stopPropagation(); // ⭐ สำคัญ กัน document click

    const notice_id = $(this).data("id");
    const ref_type  = $(this).data("ref_type");
    const ref_id    = $(this).data("ref_id");
    const title     = $(this).find(".cp-noti-title").text();
    const message   = $(this).find(".cp-noti-msg").text();

    if ($(this).hasClass("unread")) {
        update_read(notice_id);
        $(this).removeClass("unread");
    }
    if(ref_type == "qr"){
        $(".cp-modal-title").text(title);
        $(".cp-modal-body").text(message);
        gen_qr_preview(ref_id);
        retrieve_notice_item();
        $("#cpModal").fadeIn(120).addClass("show");
    }
});

    function retrieve_notice_item(){
        var q = json_request;
        var url = 'api/engine-member/retrieve_host_trip.php';
        var json = JSON.stringify(q);
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {json: json},
            success: function(res) {
                if(res.status != 1){
                    return;
                }
                var name = res.result;
                $('#fullname').text(name);
            }
        });
    }
    function update_read(notice_id){
        var q = json_request;
        q["notice_id"] = notice_id;
        var url = 'api/engine-notice/update_notice_read.php';
        var json = JSON.stringify(q);
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {json: json},
            success: function(res) {
                
            }
        });
    }
    // ================ FUNCTION ===================
   function retrieve_notice() {

    $.ajax({
        url: 'api/engine-notice/retrieve_notice.php',
        type: 'POST',
        dataType: 'json',
        data: { json: JSON.stringify(json_request) },

        success: function(res) {

            if(res.success != 1){
                return;
            }
            var count = res.count;
            var items = res.result;  
            var html = '';
            $('#noti_badge').html(count);
            items.forEach(function(item){

                html += `
                     <div class="cp-noti-item ${item.is_read === "0"?"unread":""}"
                            data-id="${item.notice_id}"
                            data-ref_type="${item.ref_type}"
                            data-ref_id="${item.ref_id}">
                        <div class="cp-noti-title">${item.title}</div>
                        <div class="cp-noti-msg">${item.message}</div>
                    </div>
                `;

            });

            $('#noti_list').html(html);
        }
    });
    }
    $(document).ready(function() {
        retrieve_notice();
    })
</script>
