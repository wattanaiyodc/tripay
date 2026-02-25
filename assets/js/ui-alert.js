/* ===== UIAlert System (SweetAlert Style) ===== */

window.UIAlert = (function () {


    function fire(options = {}) {

        const root = document.getElementById("ui-alert-root");
        
        return new Promise(resolve => {

            const opt = Object.assign({
                title: "",
                text: "",
                confirmText: "ตกลง",
                cancelText: "ยกเลิก",
                showCancel: false,
                closeOnBg: true
            }, options);

            const overlay = document.createElement("div");
            overlay.className = "ui-alert-overlay";

            overlay.innerHTML = `
                <div class="ui-alert-box">
                    ${opt.title ? `<div class="ui-alert-title">${opt.title}</div>` : ""}
                    ${opt.text ? `<div class="ui-alert-text">${opt.text}</div>` : ""}
                    <div class="ui-alert-actions">
                        ${opt.showCancel ? `<button class="ui-btn ui-btn-cancel" data-act="cancel">${opt.cancelText}</button>` : ""}
                        <button class="ui-btn ui-btn-confirm" data-act="confirm">${opt.confirmText}</button>
                    </div>
                </div>
            `;

            root.appendChild(overlay);

            setTimeout(() => overlay.classList.add("show"), 10);

            function close(result) {
                overlay.classList.remove("show");
                setTimeout(() => {
                    overlay.remove();
                    document.removeEventListener("keydown", escHandler);
                    resolve(result);
                }, 200);
            }

            overlay.addEventListener("click", (e) => {
                if (e.target.dataset.act === "confirm") close(true);
                if (e.target.dataset.act === "cancel") close(false);
                if (opt.closeOnBg && e.target === overlay) close(false);
            });

            function escHandler(e) {
                if (e.key === "Escape") close(false);
            }

            document.addEventListener("keydown", escHandler);

        });
    }

    return {

        fire,

        success(text) {
            return fire({
                title: "สำเร็จ",
                text: text
            });
        },

        error(text) {
            return fire({
                title: "เกิดข้อผิดพลาด",
                text: text
            });
        },

        confirm(text) {
            return fire({
                title: "ยืนยันรายการ",
                text: text,
                showCancel: true
            });
        }

    };

})();