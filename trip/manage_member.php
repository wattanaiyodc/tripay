<?php
session_start();
include('lang/lang.php');
$lang = $_SESSION["lang"] ?? 'th';
$resource = load_lang_csv("trip_lang.csv", $lang);
if (!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit;
}
$trip_id = (int)($_SESSION['trip_id'] ?? 0);
if ($trip_id === 0) {
    exit('invalid trip');
}

$cp_trip_name = $resource['edit_member'];
$cp_title  = 'Manage_member';
$cp_active = 'manage_member';

include 'components/component_header.php';
include 'components/component_sidebar.php';
?>
<?php include("manage_member_headerscript.php"); ?>
<?php include("manage_member_modal.php"); ?>
<div class="cp-box">
    <div class="cp-box-header">
        <div>
            <div class="cp-page-sub">
                <?= htmlspecialchars($resource['manage_member_desc'] ?? 'จัดการสมาชิกในทริป เพิ่ม/ลบ/กำหนดสิทธิ์') ?>
            </div>
        </div>

        <a href="detail.php" id="btn_back" class="cp-back-btn">← <?= htmlspecialchars($resource['back'] ?? 'กลับ') ?></a>
    </div>

    <!-- ===== Toolbar ===== -->
    <div class="cp-toolbar">
        <div class="cp-search">
            <span>🔎</span>
            <input type="text" id="member_search" placeholder="<?= htmlspecialchars($resource['search_member'] ?? 'ค้นหาสมาชิก...') ?>">
        </div>

        <div style="display:flex; gap:10px;">
            <!-- <button type="button" class="cp-btn cp-btn-secondary" id="btn_refresh_member">
                🔄 <?= htmlspecialchars($resource['refresh'] ?? 'รีเฟรช') ?>
            </button> -->

            <button type="button" class="cp-btn cp-btn-primary" id="btn_add_member">
                ➕ <?= htmlspecialchars($resource['add_member'] ?? 'เพิ่มสมาชิก') ?>
            </button>
        </div>
    </div>

    <!-- ===== Member Table ===== -->
    <table class="cp-table">
        <thead>
            <tr>
                <th style="width:70px;"></th>
                <th style="width:55%;"><?= htmlspecialchars($resource['name'] ?? 'ชื่อ') ?></th>
                <th style="width:20%;"><?= htmlspecialchars($resource['role'] ?? 'สิทธิ์') ?></th>
                <th style="width:25%; text-align:right;"><?= htmlspecialchars($resource['action'] ?? 'จัดการ') ?></th>
            </tr>
        </thead>

        <tbody id="member_list">
            <tr class="cp-empty-row">
                <td colspan="4" class="cp-empty"><?= htmlspecialchars($resource['no_member'] ?? 'ยังไม่มีสมาชิก') ?></td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    var json_request = {
        user_id: <?php echo $_SESSION['user_id']; ?>,
        trip_id: <?php echo $trip_id; ?>
    };

    // ====== GLOBAL ======
    let all_users = []; // user list จาก retrieve_user
    let selected_user_id = 0;

    // ====== OPEN MODAL ======
    $('#btn_add_member').on('click', function() {
        $('#add_member_modal').fadeIn(120);

        // reset
        $('#user_search').val('').focus();
        $('#user_list_box').html(`<div class="cp-empty" style="padding:12px 0;">กำลังโหลด...</div>`);
        selected_user_id = 0;

        retrieve_user();
    });

    // ====== CLOSE MODAL ======
    function closeAddMemberModal() {
        $('#add_member_modal').fadeOut(120);
    }

    $('#btn_close_modal, #btn_cancel_modal').on('click', function() {
        closeAddMemberModal();
    });

    $(document).on('click', '.cp-modal-backdrop', function() {
        closeAddMemberModal();
    });

    $(document).on('keydown', function(e) {
        if (e.key === "Escape") {
            closeAddMemberModal();
        }
    });

    // ====== SEARCH USER IN MODAL ======
    $('#user_search').on('input', function() {
        render_user_list($(this).val());
    });

    // ====== CLICK PICK USER ======
    $(document).on('click', '.btn-pick-user', function() {
        selected_user_id = parseInt($(this).data('user-id') || 0);

        if (!selected_user_id) {
            alert('เลือกสมาชิกไม่สำเร็จ');
            return;
        }

        if (!confirm("ต้องการเพิ่มสมาชิกคนนี้เข้าทริปใช่ไหม?")) {
            return;
        }

        add_member(selected_user_id);
    });

    $(document).on("click", ".btn-del", function() {
        let member_id = $(this).data("member-id");
        if (!member_id) return;

        if (!confirm("ต้องการลบสมาชิกคนนี้ออกจากทริปใช่ไหม?")) {
            return;
        }

        delete_member(member_id);
    });
    $(document).on("click", ".btn-edit", function() {
        let member_id = $(this).data("member-id");
        if (!member_id) return;

        edit_member(member_id);
    })
    // ====== RENDER USER LIST ======
    function render_user_list(keyword = '') {
        keyword = (keyword || '').toLowerCase().trim();

        let list = all_users;

        if (keyword) {
            list = all_users.filter(u => {
                let fullName = ((u.first_name ?? '') + ' ' + (u.last_name ?? '')).toLowerCase();
                let email = (u.email ?? '').toLowerCase();
                let id = String(u.user_id ?? '').toLowerCase();
                return fullName.includes(keyword) || email.includes(keyword) || id.includes(keyword);
            });
        }

        if (!Array.isArray(list) || list.length === 0) {
            $('#user_list_box').html(`<div class="cp-empty" style="padding:12px 0;">ไม่พบผู้ใช้</div>`);
            return;
        }

        let html = '';

        list.forEach(u => {
            let fullName = ((u.first_name ?? '') + ' ' + (u.last_name ?? '')).trim() || '-';
            let avatarUrl = (u.avatar_url ?? '').trim();

            let avatarHtml = avatarUrl ?
                `<img class="cp-avatar-img" src="${avatarUrl}" alt="avatar">` :
                `<div class="cp-avatar-fallback">${(fullName || 'U').substr(0,1).toUpperCase()}</div>`;

            let subText = u.email ? u.email : ('ID: ' + (u.user_id ?? '-'));

            html += `
                <div class="cp-user-item">
                    <div class="cp-user-left">
                        ${avatarHtml}
                        <div class="cp-user-info">
                            <div class="cp-user-name">${fullName}</div>
                            <div class="cp-user-sub">${subText}</div>
                        </div>
                    </div>

                    <button type="button"
                        class="cp-user-addbtn btn-pick-user"
                        data-user-id="${u.user_id}">
                        เลือก
                    </button>
                </div>
            `;
        });

        $('#user_list_box').html(html);
    }

    // ====== ADD MEMBER ======
    function add_member(target_user_id) {
        let q = Object.assign({}, json_request);
        q.target_user_id = target_user_id;
        q.trip_id = <?= $trip_id ?>;

        console.log(q);
        $.ajax({
            url: 'api/engine-member/create_member.php',
            type: 'post',
            data: {
                json: JSON.stringify(q)
            },
            dataType: 'json',
            success: function(res) {
                if (res.status !== 'success') {
                    alert(res.message || 'เพิ่มสมาชิกไม่สำเร็จ');
                    return;
                }

                closeAddMemberModal();
                retrieve_member();
            }
        });
    }

    // ====== RETRIEVE USER (สำหรับ popup) ======
    function retrieve_user() {
        var json = JSON.stringify(json_request);

        $.ajax({
            url: 'api/engine-user/retrieve_user.php',
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

                all_users = Array.isArray(res.result) ? res.result : [];
                render_user_list($('#user_search').val());
            }
        });
    }

    // ====== RETRIEVE MEMBER (ตารางหลัก) ======
    function retrieve_member() {
        var json = JSON.stringify(json_request);

        $.ajax({
            url: 'api/engine-member/retrieve_trip_member.php',
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

                let html = '';
                let list = Array.isArray(res.result) ? res.result : [];

                if (list.length === 0) {
                    $('#member_list').html(`
                        <tr class="cp-empty-row">
                            <td colspan="4" class="cp-empty">ยังไม่มีสมาชิก</td>
                        </tr>
                    `);
                    return;
                }

                $.each(list, function(idx, user) {
                    let fullName = ((user.first_name ?? '') + ' ' + (user.last_name ?? '')).trim();
                    let avatarUrl = (user.avatar_url ?? '').trim();
                    let role = (user.role ?? '').toLowerCase();

                    let avatarHtml = avatarUrl ?
                        `<img class="cp-avatar-img" src="${avatarUrl}" alt="avatar">` :
                        `<div class="cp-avatar-fallback">${(fullName || 'U').substr(0,1).toUpperCase()}</div>`;

                    let badgeClass = (role === 'master') ? 'cp-badge-master' : 'cp-badge-member';
                    let roleText = (role === 'master') ? 'Master' : (user.role ?? '-');

                    html += `
                        <tr>
                            <td style="width:70px;">${avatarHtml}</td>

                            <td>
                                <div class="cp-name">${fullName || '-'}</div>
                                <div class="cp-email">${user.email ?? ''}</div>
                            </td>

                            <td>
                                <span class="cp-badge ${badgeClass}">${roleText}</span>
                            </td>

                            <td>
                                <div class="cp-actions">
                                    <button type="button" class="cp-icon-action cp-icon-danger btn-del" title="ลบ" data-member-id="${user.member_id ?? ''}">🗑️</button>
                                </div>
                            </td>
                        </tr>
                    `;
                    // "<button type="button" class="cp-icon-action btn-edit" title="แก้ไข" data-member-id="${user.member_id ?? ''}">✏️</button>"
                });

                $('#member_list').html(html);
            }
        });
    }

    function delete_member(member_id) {
        var q = json_request;
        q["member_id"] = member_id;
        var json = JSON.stringify(q);
        $.ajax({
            url: 'api/engine-member/delete_member.php',
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
                retrieve_member();
            }
        })
    }

    function edit_member(member_id){
        var q = json_request;
            q["member_id"] = member_id

            $.ajax({
                url: 'api/engine-member/edit_member.php'
            });
    }
    // ====== READY ======
    $(document).ready(function() {
        retrieve_member();
    });
</script>