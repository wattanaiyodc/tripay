<script>
    var json_request = {
        user_id: <?php echo $_SESSION['user_id']; ?>,
        trip_id: <?php echo $_SESSION['trip_id']; ?>
    };

    function retrieve() {
        $.ajax({
            url: 'api/engine-transaction/retrieve.php',
            type: 'post',
            data: {
                json: JSON.stringify(json_request)
            },
            dataType: 'json',
            success: function(res) {
                if (res.success != 1) return;

                let rows = res.result || [];

                /* ===== group by date ===== */
                let groups = {};
                rows.forEach(r => {
                    if (!groups[r.tx_date]) groups[r.tx_date] = [];
                    groups[r.tx_date].push(r);
                });

                let html = '';
                let grandDR = 0;
                let grandCR = 0;
                let runningBalance = 0;

                Object.keys(groups).forEach(date => {
                    let dayDR = 0;
                    let dayCR = 0;

                    /* ===== header วันที่ ===== */
                    html += `
                <tr class="cp-date-row">
                    <td colspan="5">${date}</td>
                </tr>`;

                    /* ===== รายการ ===== */
                    groups[date].forEach(item => {
                        let dr = Number(item.debit) || 0;
                        let cr = Number(item.credit) || 0;

                        dayDR += dr;
                        dayCR += cr;
                        grandDR += dr;
                        grandCR += cr;

                        html += `
                    <tr>
                        <td></td>
                        <td>${item.description || '-'}</td>
                        <td class="text-right tx-dr">${dr ? dr.toFixed(2) : ''}</td>
                        <td class="text-right tx-cr">${cr ? cr.toFixed(2) : ''}</td>
                        <td></td>
                    </tr>`;
                    });

                    /* ===== รวมประจำวัน ===== */
                    runningBalance += (dayCR - dayDR);

                    let balClass =
                        runningBalance > 0 ? 'tx-bal-pos' :
                        runningBalance < 0 ? 'tx-bal-neg' :
                        'tx-bal-zero';

                    html += `
                <tr class="cp-day-total">
                    <td></td>
                    <td><strong>รวมประจำวัน</strong></td>
                    <td class="text-right tx-dr">${dayDR.toFixed(2)}</td>
                    <td class="text-right tx-cr">${dayCR.toFixed(2)}</td>
                    <td class="text-right ${balClass}">
                        ${runningBalance.toFixed(2)}
                    </td>
                </tr>`;
                });

                $('#rt_output').html(html);

                /* ===== รวมทั้งหมด ===== */
                let finalBalance = grandCR - grandDR;
                let finalClass =
                    finalBalance > 0 ? 'tx-bal-pos' :
                    finalBalance < 0 ? 'tx-bal-neg' :
                    'tx-bal-zero';

                $('#tf_output').html(`
            <tr class="cp-total-row">
                <td colspan="2">Total</td>
                <td class="text-right tx-dr">${grandDR.toFixed(2)}</td>
                <td class="text-right tx-cr">${grandCR.toFixed(2)}</td>
                <td class="text-right ${finalClass}">
                    ${finalBalance.toFixed(2)}
                </td>
            </tr>`);
            }
        });
    }



    $(document).ready(function() {
        retrieve();
    });
</script>