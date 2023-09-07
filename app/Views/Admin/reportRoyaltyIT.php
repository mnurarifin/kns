<script src="https://unpkg.com/vue@next"></script>

<section id="komisi">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?= isset($title) ? $title : '' ?></h4>
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
                            <div class="col-12">
                                <div class="alert alert-success " v-show="alert.success.status" style="display: none;">
                                    <span v-html="alert.success.content"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="alert alert-danger " v-show="alert.danger.status" style="display: none;">
                                    <span v-html="alert.danger.content"></span>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <select id='select-year' class="form-control">
                                        <option value="" selected disabled hidden>Pilih Tahun</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <button class="btn btn-icon btn-outline-primary btn-sm mb-1 dtable-action table-bonus-transfer-action info mr-1" type="button" id="table-bonus-transferexport_komisi" title="Rekap Komisi" data-url="undefined" data-type="export_komisi" data-message="undefined" style="align-items:center;" onclick='komisi.generateTable()'>CARI</button>
                                <button class="btn btn-icon btn-outline-primary btn-sm mb-1 dtable-action table-bonus-transfer-action info mr-1" type="button" id="table-bonus-transferexport_komisi" title="Rekap Komisi" data-url="undefined" data-type="export_komisi" data-message="undefined" data-toggle="modal" data-target="#default" style="align-items:center;">TOP
                                    UP</button>



                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="text-align:right;">
                                <button class="btn btn-icon btn-outline-primary btn-sm mb-1 dtable-action table-bonus-transfer-action info mr-1" type="button" title="Rekap Komisi" data-url="undefined" data-type="export-table" data-message="undefined" onclick="komisi.excel()" style="align-items:center;"><i class="ficon bx bxs-file" style="top:3px;"></i><span style="color:#000">Export
                                        Excel</span></button>
                            </div>

                            <div class="col-12 mt-0">
                                <div>
                                    <div class="table-responsive">
                                        <table class="table" id="table-komisi">
                                            <thead>
                                                <tr>
                                                    <th>Bulan</th>
                                                    <th>Masuk</th>
                                                    <th>Keluar</th>
                                                    <th>Saldo</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left show" id="default" tabindex="-1" aria-labelledby="myModalLabel1" style="display: hide ; padding-right: 17px;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel1">Top Up Saldo</h3>
                    <button type="button" class="close rounded-pill" data-toggle="modal" data-target="#default" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="helperText">Tanggal:</label>
                                <input type="date" v-model="form.comission_log_date" id="helperText" class="form-control datetime" placeholder="Name">
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="basicInput">Nominal :</label>
                                <input type="text" class="form-control money" id="comission_log_value" placeholder="Masukan Nominal">
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="product_description">Catatan</label>
                                <textarea class="form-control" v-model="form.comission_log_note" cols="50" rows="10"> </textarea>
                            </fieldset>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button @click="submit()" type="button" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">SUBMIT</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo base_url(); ?>/app-assets/vendors/ckeditor/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var editor = CKEDITOR.replace('editor', {
            height: 100,
            removeButtons: ''
        });
        $('.money').maskMoney({
            thousands: '.',
            decimal: '.',
            allowZero: true,
            prefix: '',
            affixesStay: true,
            allowNegative: false,
            precision: 0
        });
    });

    let komisi =
        Vue.createApp({
            data: function() {
                return {
                    data: [],
                    form: {
                        comission_log_value: '',
                        comission_log_note: '',
                        comission_log_date: '<?php echo date('Y-m-d'); ?>',
                    },
                    alert: {
                        success: {
                            status: false,
                            content: '',
                        },
                        danger: {
                            status: false,
                            content: '',
                        }
                    },
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },
                generateTable() {
                    let date = new Date();
                    let year = $('#select-year').val() ? $('#select-year').val() : date.getFullYear();

                    $.ajax({
                        url: window.location.origin + '/admin/service/report/getHistoryRoyaltyIT/' + year,
                        data: {},
                        method: 'GET',
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            let html = "";
                            $('tbody').html('')

                            if (res.status == 200) {

                                if (res.data.length > 0) {
                                    res.data.forEach((item) => {
                                        html += `
                                        <tr>
                                            <td> ${item.bulan} </td>
                                            <td> ${item.masuk} </td>
                                            <td> ${item.keluar} </td>
                                            <td> ${item.saldo} </td>
                                        </tr>
                                        `;
                                    })
                                } else {
                                    html = `
                                    <tr class="text-center">
                                        <td colspan="4"> Data tidak ditemukan </td>
                                    </tr>
                                    `;
                                }

                                $('tbody').append(html)
                            }
                        },
                        error: function(err) {
                            let response = res.responseJSON;

                            if (response.status == 400 && response.message == "validationError") {
                                let resValidation = Object.values(response.data.validationMessage);

                                if (resValidation.length > 0) {
                                    komisi.alert.danger.content = `<ul>`;
                                    resValidation.forEach((data) => {
                                        komisi.alert.danger.content +=
                                            `<li> ${data} </li>`;
                                    });
                                    komisi.alert.danger.content += `</ul>`;
                                    komisi.alert.danger.status = true;

                                    setTimeout(() => {
                                        komisi.alert.danger.status = false;
                                    }, 3000);
                                }
                            } else {
                                komisi.alert.danger.content = response.message
                                komisi.alert.danger.status = true;

                                setTimeout(() => {
                                    komisi.alert.danger.status = false;
                                }, 3000);
                            }
                        }
                    });

                },
                submit() {
                    this.form.comission_log_value = $('#comission_log_value').val().replace(/\./g, "");

                    $.ajax({
                        url: window.location.origin + '/admin/service/report/actAddRoyalty',
                        method: "POST",
                        data: this.form,
                        success: function(response) {
                            if (response.status == 200) {
                                $('#default').modal('hide');

                                komisi.alert.success.content = response.message
                                komisi.alert.success.status = true;

                                komisi.generateTable();

                                setTimeout(() => {
                                    komisi.alert.success.status = false;
                                }, 3000);

                            }
                        },
                        error: function(res) {
                            $('#default').modal('hide');

                            let response = res.responseJSON;
                            if (response.status == 400 && response.message == "validationError") {
                                let resValidation = Object.values(response.data.validationMessage);
                                console.log(resValidation);

                                if (resValidation.length > 0) {
                                    komisi.alert.danger.content = `<ul>`;

                                    resValidation.forEach((data) => {
                                        komisi.alert.danger.content +=
                                            `<li> ${data} </li>`;
                                    });
                                    komisi.alert.danger.content += `</ul>`;
                                    komisi.alert.danger.status = true;

                                    setTimeout(() => {
                                        komisi.alert.danger.status = false;
                                    }, 3000);
                                }
                            } else {
                                komisi.alert.danger.content = response.message
                                komisi.alert.danger.status = true;

                                setTimeout(() => {
                                    komisi.alert.danger.status = false;
                                }, 3000);
                            }
                        }
                    })

                    $('#comission_log_value').val(0)
                    $(".money").maskMoney('mask');

                    this.form = {
                        comission_log_value: '',
                        comission_log_note: '',
                        comission_log_date: '<?php echo date('Y-m-d'); ?>',
                    }

                    $('#default').modal('hide');


                },
                getYear() {
                    let date = new Date();
                    let year = date.getFullYear();

                    $.ajax({
                        url: '/admin/service/report/getYearByDataRoyalty',
                        type: 'GET',
                        "content_type": 'application/json',
                        success: function(res) {
                            let html = '';
                            let selected = '';

                            html += `<option value="" selected disabled hidden>Pilih Tahun</option>`;

                            res.data.forEach((item, index) => {
                                // if (item.val == year) {
                                //     selected = 'selected';
                                // } else {
                                //     selected = '';
                                // }
                                html +=
                                    `<option value='${item.val}' ${selected}>${item.name}</option>`;
                            });


                            console.log(html);

                            $('#select-year').html(html);
                        }
                    });
                },
                changeYear() {
                    this.generateTable();
                },
                excel() {
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

                    htmls = $('#table-komisi').html();

                    var ctx = {
                        worksheet: 'Worksheet',
                        table: htmls
                    }


                    var link = document.createElement("a");
                    link.download = `Laporan-Royalty-IT-${$('#select-year').val()} .xls`;
                    link.href = uri + base64(format(template, ctx));
                    link.click();
                }

            },
            mounted() {
                this.hideLoading();

            },
            created() {
                this.getYear();
                this.generateTable();
            }
        }).mount('#komisi')
</script>