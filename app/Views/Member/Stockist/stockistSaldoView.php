<style>
    @media (max-width: 767.98px) {
        #saldoMitra {
            margin: 1rem 0;
        }

        #saldoMitra>.col-12 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        #btn-withdraw {
            margin-top: 1rem;
        }
    }
</style>

<div class="app-content content" id="app">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0"><?= $title ?></h5>

                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="bx bx-home-alt"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Saldo Anda</a></li>
                                <li class="breadcrumb-item active"><?= $title ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row justify-content-center">
                <div class="col-md-12 col-12">
                    <div class="card mb-1">
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <div class="row align-items-center justify-content-between justify-sm-content-between justify-md-content-start">
                                        <div class="col-8 col-sm-8 col-md-12">
                                            <div class="d-flex">
                                                <i class="bx bx-user-circle my-auto" style="font-size: 3rem;"></i>
                                                <div class="pl-1">
                                                    <p class="card-text mb-0 dark" style="padding-bottom: 5px;"><?= session('member')['member_name'] ?></p>
                                                    <h6 class="mb-0 dark font-weight-bold"><?= session('member')['network_code'] ?></h6>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-4 col-sm-4 col-md-2 my-auto text-right d-inline d-sm-inline d-md-none">
                                            <button class="btn btn-link px-0 py-25 text-primary" id="showCollapse" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                <div class="btn btn-light btn-collapse" id="chevronUp">
                                                    <i class="ficon bx bx-chevron-down bx-md mr-0"></i>
                                                </div>
                                                <div class="btn btn-light btn-collapse" id="chevronDown" style="display: none;">
                                                    <i class="ficon bx bx-chevron-up bx-md mr-0"></i>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-12">
                                    <div class="row py-auto" id="saldoMitra">
                                        <div class="col-md-12 col-12">
                                            <div class="d-flex">
                                                <img src="<?php echo base_url(); ?>/app-assets/images/saldo.png" class="img-fluid filter-white pt-50" style="width: auto; max-width: none; filter: grayscale(1);">
                                                <div class="pl-1">
                                                    <h5 class="mb-0 dark font-weight-bold pt-75">Rp {{saldoMember}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button class="ml-0 h-100 w-100 btn round d-none d-sm-none d-md-inline-block" style="background-color: #6f2abb; color: white;">
                                        <span class="d-sm-block" onclick="app.openClaim()">Withdraw</span>
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button onclick="location.href = '<?= BASEURL; ?>/member/stockist/buy';" class="ml-0 h-100 w-100 btn round d-none d-sm-none d-md-inline-block" style="background-color: #6f2abb; color: white;">
                                        <span class="d-sm-block">Belanja Ulang</span>
                                    </button>
                                </div>
                                <div class="col-md-2 col-12 my-auto text-right d-none d-sm-none d-md-flex justify-content-end">
                                    <button class="btn btn-link px-0 py-25 text-primary" id="showCollapseMobile" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                        <div class="btn btn-light btn-collapse" id="chevronUpMobile">
                                            <i class="ficon bx bx-chevron-down bx-md mr-0"></i>
                                        </div>
                                        <div class="btn btn-light btn-collapse" id="chevronDownMobile" style="display: none;">
                                            <i class="ficon bx bx-chevron-up bx-md mr-0"></i>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseExample">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 col-12 mb-75">
                                            <p class="card-text mb-50 d-flex align-items-center"><i class="ficon bx bxs-bank my-auto mr-50"></i>Bank</p>
                                            <div class="d-flex card-text font-weight-bold secondary">
                                                <div style="padding-left: 25px;">
                                                    <div><span>{{bank_name}}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12 mb-75">
                                            <p class="card-text mb-50 d-flex align-items-center"><i class="ficon bx bx-credit-card my-auto mr-50"></i>Akun Bank</p>
                                            <div class="d-flex card-text font-weight-bold secondary">
                                                <div style="padding-left: 25px;">
                                                    <div id="text_member_bank_name"></div>
                                                    <div class="d-flex flex-row align-items-center">
                                                        <div class="mr-25">{{account_name}}</div> /
                                                        <div class="ml-25">{{account_no}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-block d-sm-block d-md-none mt-2">
                                <button class="ml-0 h-100 btn round mr-1" style="background-color: #6f2abb; color: white;">
                                    <span class="d-sm-block" onclick="app.openClaim()">Withdraw</span>
                                </button>
                                <button onclick="location.href = '<?= BASEURL; ?>/member/stockist/buy';" class="ml-0 h-100 btn round" style="background-color: #6f2abb; color: white;">
                                    <span class="d-sm-block">Belanja Ulang</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-12">
                    <div id="table"></div>
                    <div class='card p-2' id="data_kosong">
                        <div class='row'>
                            <div class='col-md-12 d-flex justify-content-center'>
                                <img src="<?= base_url() ?>/app-assets/images/no-data-green.svg" alt='' style="filter: grayscale(100%);">
                            </div>
                            <div class='col-md-12 d-flex justify-content-center mt-3'>
                                <label>Tidak ada informasi yang ditampilkan</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="modal-wd" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-label">Withdraw Saldo</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-50 align-center align-items-end">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="input_amount">Jumlah (Rp.)</label>
                            <input type="text" class="form-control" id="input_amount" placeholder="" value="">
                            <small class="text-danger alert-input" id="alert_input_amount" style="display: none;"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="app.claim();">
                    <span class="">Withdraw</span>
                </button>
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <span class="">Tutup</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#claimBtn').attr('disabled', true)
        $('.filter-white').css('filter', 'grayscale(1)')

        $('#data_kosong').hide()
        $('#alertMin').hide()
        $('#alertMax').hide()
        $('#alertLimit').hide()

        app.generateTable();
        app.saldo();
    })

    let app = Vue.createApp({
        data: function() {
            return {
                button: {
                    formBtn: {
                        disabled: true
                    }
                },
                modal: {
                    data: {
                        title: "",
                        btnTitle: "",
                        btnAction: "",
                    },
                    form: {
                        ewallet_withdrawal_value: ''
                    },
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
                saldoMember: '0',
                saldoOriginal: 0,
                taxCharge: '0',
                nettValue: '0',
                account_name: '',
                account_no: '',
                value: 0,
                bank_name: ''
            }
        },
        methods: {
            generateTable() {
                $("#table").dataTableLib({
                    url: window.location.origin + '/member/stockist/get-saldo-summary',
                    selectID: 'ewallet_log_id',
                    colModel: [{
                            display: 'Tanggal',
                            name: 'ewallet_log_datetime_formatted',
                            align: 'left',
                        },
                        {
                            display: 'Jenis Saldo',
                            name: 'ewallet_log_type',
                            align: 'center',
                            render: (params) => {
                                return params == 'in' ? `<div class="badge badge-pill badge-success">Masuk</div>` : `<div class="badge badge-pill badge-warning">Keluar</div>`
                            }
                        },
                        {
                            display: 'Info',
                            name: 'ewallet_log_note',
                            align: 'left',
                        },
                        {
                            display: 'Jumlah',
                            name: 'ewallet_log_value_formatted',
                            align: 'right',
                        },
                    ],
                    options: {
                        limit: [10, 15, 20, 50, 100],
                        currentLimit: 10,
                    },
                    search: true,
                    searchTitle: 'Pencarian',
                    searchItems: [{
                            display: 'Tanggal',
                            name: 'ewallet_log_datetime',
                            type: 'date'
                        },
                        {
                            display: 'Jenis Saldo',
                            name: 'ewallet_log_type',
                            type: 'select',
                            option: [{
                                    title: 'Masuk',
                                    value: 'in'
                                },
                                {
                                    title: 'Keluar',
                                    value: 'out'
                                },
                            ]
                        },

                    ],
                    sortName: "ewallet_log_datetime",
                    sortOrder: "DESC",
                    tableIsResponsive: true,
                    select: false,
                    multiSelect: false,
                    buttonAction: [],
                    success: (res) => {
                        $("#table-mobile").empty();
                        let dataTb = res.data.results
                        let dataUI = "<div class='row'>";
                        if (dataTb.length > 0) {
                            for (let idx = 0; idx < dataTb.length; idx++) {
                                dataUI += `
									<div class="col-12 wrapper-row mb-1 px-1">
										<div class="col-12 heading-row d-flex flex-row w-100">
											<div class="d-flex align-items-center justify-content-between py-50 w-100">
												<p style="font-size: 1rem!important;" class="card-text mb-0 dark d-flex align-items-center font-weight-bold"><i class="bx bx-calendar mr-50"></i>${dataTb[idx].ewallet_log_datetime}</p>
												<div style="font-size: 1rem!important;" class="card-text mb-0 dark d-flex align-items-center font-weight-bold">${(dataTb[idx].ewallet_log_type == 'in') ? `<div class="badge badge-pill badge-success">Masuk</div>` : `<div class="badge badge-pill badge-warning">Keluar</div>`}</div>
											</div>
										</div>
										<div class="col-12 data-row">
											<div class="d-flex flex-row mb-50 justify-content-between align-center">
												<span>Jumlah</span>
												<span class="mb-0 text-dark">${dataTb[idx].ewallet_log_value}</span>
											</div>

											<div class="d-flex flex-row mb-50 justify-content-between align-center p-50 mt-1 mb-0" style="line-height: 150%; background: rgba(0,0,0,0.05);">
												<span class="mb-0">${dataTb[idx].ewallet_log_note}</span>
											</div>

										</div>
									</div>
								`
                            }
                        } else {
                            dataUI += `
									<div class="col-12 wrapper-row mb-1 px-1">
										<div class="col-12 heading-row text-center">
											<div class="d-block p-1 text-center">Tidak ada data yang ditampilkan.</div>
										</div>
										<div class="col-12 data-row"></div>
									</div>
								`
                        }
                        dataUI += "</div>";

                        $("#table-mobile").append(dataUI);
                    },
                });
            },
            saldo() {
                $.ajax({
                    url: "<?= BASEURL ?>/member/stockist/get-saldo",
                    type: "GET",
                    data: {},
                    success: (res) => {
                        app.saldoMember = res.data.results.saldo_formatted
                        app.saldoOriginal = res.data.results.saldo
                        app.account_name = res.data.results.bank_account_name
                        app.account_no = res.data.results.bank_account_no
                        app.bank_name = res.data.results.bank_name
                    },
                    error: (err) => {
                        res = err.responseJSON
                    },
                })
            },
            openClaim() {
                $("#modal-wd").modal("show")
            },
            claim() {
                if (parseInt($("#input_amount").val().replaceAll(".", "")) < 100000) {
                    Swal.fire({
                        title: 'Gagal',
                        text: 'Minimal withdraw saldo Rp 100.000',
                        icon: 'error'
                    });

                    return false
                } else {
                    Swal.fire({
                        title: 'Perhatian!',
                        html: `Apakah anda yakin akan withdraw <b>Rp ${$("#input_amount").val()}</b> saldo anda?`,
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.value) {
                            showLoadProcess();
                            setTimeout(() => {
                                $.ajax({
                                    url: "<?= BASEURL ?>/member/stockist/claim-saldo",
                                    method: "POST",
                                    data: {
                                        amount: $("#input_amount").val().replaceAll(".", "")
                                    },
                                    success: function(response) {
                                        app.generateTable()
                                        app.saldo()
                                        Swal.fire({
                                            title: 'Berhasil!',
                                            text: response.message,
                                            icon: 'success'
                                        });
                                        $("#modal-wd").modal("hide")
                                    },
                                    error: function(err) {
                                        Swal.fire({
                                            title: 'Gagal!',
                                            text: 'Gagal withdraw saldo',
                                            icon: 'error'
                                        });
                                    }
                                })
                            }, 300);
                        }
                    });
                }
            },
            openModal() {
                $('#modalKlaim').modal()
            },
        }
    }).mount('#app');

    $('#showCollapseMobile').on('click', function() {
        if ($('#collapseExample').hasClass('show')) {
            $('#saldoMitra').show()
            $('#chevronDownMobile').hide()
            $('#chevronUpMobile').show()
        } else {
            $('#saldoMitraMobile').hide()
            $('#chevronUpMobile').hide()
            $('#chevronDownMobile').show()
        }
    })

    $('#showCollapse').on('click', function() {
        if ($('#collapseExample').hasClass('show')) {
            $('#saldoMitra').show()
            $('#chevronDown').hide()
            $('#chevronUp').show()
        } else {
            $('#saldoMitra').hide()
            $('#chevronUp').hide()
            $('#chevronDown').show()
        }
    })

    $("#input_amount").on("keyup", (el) => {
        $(el.target).val(formatRupiah($(el.target).val()))
    })

    function save() {
        app.save()
    }

    function formatRupiah(bilangan) {
        var number_string = bilangan.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi bilangan ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah
    }
</script>