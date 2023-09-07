<style>
    #table-income-statement {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #table-income-statement td,
    #table-income-statement th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #table-income-statement tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #table-income-statement tr:hover {
        background-color: #ddd;
    }

    #table-income-statement th {
        padding-top: 8px;
        padding-bottom: 8px;
        text-align: left;
        background-color: #04AA6D;
        color: white;
        font-size: 10px;
    }
</style>
<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?php echo $title; ?><span id="title"></span></h4>
                </div>
                <div class="card-content">
                    <div id="pageLoader">
                        <div class="text-center text-muted d-flex align-center justify-content-center bg-grey-light p-2">
                            <div class="spinner-border text-info spinner-border-sm" role="status" style="margin-right: 8px;margin-top: 2px;">
                                <span class="sr-only">&nbsp;</span>
                            </div>
                            <span>Sedang memuat informasi, mohon tunggu beberapa saat...</span>
                        </div>
                    </div>
                    <div class="card-body card-dashboard">
                        <div class="row">
                            <div class="col-md-2 col-sm-6">
                                <div class="form-group">
                                    <select id='select-month' class="form-control"></select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <div class="form-group">
                                    <select id='select-year' class="form-control"></select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-2">
                                <button class="btn btn-icon btn-outline-primary btn-sm mb-1 dtable-action table-bonus-transfer-action info mr-1" type="button" id="table-bonus-transferexport_komisi" title="Rekap Komisi" data-url="undefined" data-type="export_komisi" data-message="undefined" style="align-items:center;" onclick='getNewData()'>CARI</button>
                            </div>
                            <div class="col-md-6 col-sm-2" style="text-align: right;">
                                <button class="btn btn-icon btn-outline-primary btn-sm mb-1 dtable-action table-bonus-transfer-action info mr-1" type="button" id="table-bonus-transferexport_komisi" title="Rekap Komisi" data-url="undefined" data-type="export_komisi" data-message="undefined" onclick="excel()" style="align-items:center;"><i class="ficon bx bxs-file" style="top:3px;"></i><span style="color:#000">Export Excel</span></button>
                            </div>
                        </div>

                        <div id="div-table-income" style="overflow-x:auto;">
                            <table id="table-income-statement" class="table" style="max-width:100%">
                                <thead>
                                    <tr>
                                        <th style='text-align:center; background:#04AA6D !important; color:white;'>Tanggal</th>
                                        <th style='text-align:center; background:#04AA6D !important; color:white;'>Registrasi</th>
                                        <th style='text-align:center; background:#04AA6D !important; color:white;'>Omset<br>Registrasi</th>
                                        <th style='text-align:center; background:#04AA6D !important; color:white;'>Komisi<br>Sponsor</th>
                                        <th style='text-align:center; background:#04AA6D !important; color:white;'>Komisi<br>Generasi</th>
                                        <th style='text-align:center; background:#04AA6D !important; color:white;'>Komisi<br>Power Leg</th>
                                        <th style='text-align:center; background:#04AA6D !important; color:white;'>Komisi<br>Matching Leg</th>
                                        <th style='text-align:center; background:#04AA6D !important; color:white;'>Komisi<br>Poin Reward</th>
                                        <th style='text-align:center; background:#04AA6D !important; color:white;'>Total<br>Payout</th>
                                        <th style='text-align:center; background:#04AA6D !important; color:white;'>Laba<br>Kotor</th>
                                        <th style='text-align:center; background:#04AA6D !important; color:white;'>Total<br>Komisi Menunggu</th>
                                        <th style='text-align:center; background:#04AA6D !important; color:white;'>Laba<br>Bersih</th>
                                    </tr>
                                </thead>
                                <tbody id="body-income-statement"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    let month = '<?php echo $month ?>'
    let year = '<?php echo $year ?>'
    $(document).ready(function() {
        getData(month, year);
        getMonth();
        getYear();
    });

    function getMonth() {
        $.ajax({
            url: window.location.origin + '/admin/service/income_statement/get_data_month',
            type: 'GET',
            "content_type": 'application/json',
            success: function(res) {
                let html = ``;
                let selected = '';
                res.data.forEach((item, index) => {
                    if (item.val == month) {
                        selected = 'selected';
                    } else {
                        selected = '';
                    }
                    html += `<option value='${item.val}' ${selected}>${item.name}</option>`
                });
                $('#select-month').html(html);
            }
        });
    }

    function getYear() {
        $.ajax({
            url: window.location.origin + '/admin/service/income_statement/get_data_year',
            type: 'GET',
            "content_type": 'application/json',
            success: function(res) {
                let html = ``;
                let selected = '';
                res.data.forEach((item, index) => {
                    if (item.val == year) {
                        selected = 'selected';
                    } else {
                        selected = '';
                    }
                    html += `<option value='${item.val}' ${selected}>${item.name}</option>`
                });
                $('#select-year').html(html);
            }
        });
    }

    function getNewData() {
        month = $('#select-month').val();
        year = $('#select-year').val();
        getData(month, year);
    }

    function getData(month = '', year = '') {
        let url = window.location.origin + '/admin/service/income_statement/get_data/' + month + '/' + year;
        $.ajax({
            url: url,
            type: 'GET',
            "content_type": 'application/json',
            success: function(res) {
                let html = ``;

                if (res.data.results.length > 0) {
                    res.data.results.forEach((item, index) => {
                        html += `
                        <tr style="font-size:12px; padding:6px;">
                            <td style="text-align:center;">${item.bonus_log_date}</td>
                            <td style='text-align:right;'>${numberFormat(item.total_reg)}</td>
                            <td style='text-align:right;'>${numberFormat(item.total_activation)}</td>
                            <td style='text-align:right;'>${numberFormat(item.total_sponsor)}</td>
                            <td style='text-align:right;'>${numberFormat(item.total_gen_node)}</td>
                            <td style='text-align:right;'>${numberFormat(item.total_power_leg)}</td>
                            <td style='text-align:right;'>${numberFormat(item.total_matching_leg)}</td>
                            <td style='text-align:right;'>${numberFormat(item.total_cash_reward)}</td>
                            <td style='text-align:right;'>${numberFormat(item.total_payout)}</td>
                            <td style='text-align:right;'>${numberFormat(item.total_bruto)}</td>
                            <td style='text-align:right;'>${numberFormat(item.total_diff)}</td>
                            <td style='text-align:right;'>${numberFormat(item.total_profit)}</td>
                        </tr>
                        `;
                    });

                    html += `<tr style="font-size:12px; padding:6px;">`;
                    res.data.total.forEach((item, index) => {
                        if (index >= 1) {
                            html += `
                             <td style='text-align:right; background:#04AA6D !important; color:white;'>${numberFormat(item)}</td>
                            `;
                        } else {
                            html += `
                                <td style='text-align:center; background:#04AA6D !important; color:white;'>${item}</td>
                            `;
                        }
                    });
                    html += `</tr>`;


                } else {
                    html = `<tr style="text-align:center">
                    <td colspan="9">Data Kosong</td>
                    </tr>`;
                }

                $('#body-income-statement').html(html)
                $("#pageLoader").hide();

            }
        });
    }

    function excel() {
        let htmls = '';
        console.log('test');

        var uri = 'data:application/vnd.ms-excel;base64,';
        var template =
            '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>';
        var base64 = function(s) {
            return window.btoa(unescape(encodeURIComponent(s)))
        };

        var format = function(s, c) {
            return s.replace(/{(\w+)}/g, function(m, p) {
                return c[p];
            })
        };

        htmls = $('#table-income-statement').html();

        var ctx = {
            worksheet: 'Worksheet',
            table: htmls
        }


        var link = document.createElement("a");
        link.download = `Laporan-Laba-Rugi-${$('#select-month').val()}-${$('#select-year').val()} .xls`;
        link.href = uri + base64(format(template, ctx));
        link.click();
    }


    function numberFormat(value) {
        var formatter = new Intl.NumberFormat('id', {
            minimumFractionDigits: 0
        });

        return formatter.format(parseInt(value));
    }
</script>