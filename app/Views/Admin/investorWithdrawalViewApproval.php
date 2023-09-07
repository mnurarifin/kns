<section id="investor">
    <div class="row">



        <div class="col-12">
            <div class="alert alert-danger alert-floating-right mb-0" role="alert">{{messageError}}</div>
            <div class="alert alert-success alert-floating-right mb-0" role="alert">aa</div>
        </div>
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
                            <div class="col-12 mt-2">

                            </div>
                            <div class="col-12 mt-0">
                                <div id="table-investor"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalAddUpdate" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdateTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center" id="modalAddUpdateTitle"> <span>
                            {{ modal.data.title}} </span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" v-show="alert.danger.status" style="display: none;">
                        <span v-html="alert.danger.content"></span>
                    </div>

                    <form class="form form-vertical">
                        <div class="form-body">
                            <div class="row ma-5">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="">
                                            Masukan Jumlah
                                        </label>
                                        <input type="text" id="amount" class="form-control money" name="amount" placeholder="Masukan Jumlah Withdrawal">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <button @click="saveWithdraw" :disabled="modal.form.amount  == 0 || modal.form.amount == '' " class="btn custom-btn btn-primary btn-block mt-1" disabled>Request Withdraw</button>

                </div>

            </div>
        </div>
    </div>

</section>

<style>
    .card-rounded {
        border-radius: 10px;
    }

    .custom-btn {
        border-radius: 10px;
        padding: 10px 20px;
        z-index: 999;
    }

    .alert-floating-right {
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 999;

    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

<script type="text/javascript" defer>
    let app =
        Vue.createApp({
            data: function() {
                return {
                    messageError: "",
                    messageSuccess: "",
                    button: {
                        formBtn: {
                            disabled: false
                        }
                    },
                    modal: {
                        data: {
                            title: "Withdraw",
                            btnTitle: "Withdraw",
                            btnAction: "insert",
                        },
                        form: {
                            amount: 0,
                        },
                        action: {}
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
                    type: "daily",
                    investor_id: "",
                }
            },
            methods: {
                changeTab(type) {
                    this.type = type
                    this.generateInvestorTable()
                },
                hideLoading() {
                    $("#pageLoader").hide();
                },
                generateInvestorTable() {
                    $("#table-investor").dataTableLib({
                        url: window.location.origin + '/admin/service/investor/getWithdrawalAdmin/',
                        selectID: 'investor_withdrawal_id',
                        colModel: [{
                                display: 'Tanggal',
                                name: 'investor_withdrawal_datetime',
                                sortAble: false,
                                align: 'center'
                            },
                            {
                                display: 'Jumlah',
                                name: 'investor_withdrawal_value',
                                sortAble: false,
                                align: 'center'
                            },
                            {
                                display: 'Nama Bank',
                                name: 'investor_withdrawal_bank_name',
                                sortAble: false,
                                align: 'center'
                            },
                            {
                                display: 'Nama Pemilik Rekening',
                                name: 'investor_withdrawal_account_name',
                                sortAble: false,
                                align: 'center'
                            },
                            {
                                display: 'No. Rekening',
                                name: 'investor_withdrawal_account_no',
                                sortAble: false,
                                align: 'center'
                            },

                            {
                                display: 'Status',
                                name: 'investor_withdrawal_status',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    switch (params) {
                                        case 'pending':
                                            return '<span class="badge badge-warning">Menunggu</span>'
                                            break;
                                        case 'verification':
                                            return '<span class="badge badge-warning">Verifikasi</span>'
                                            break;
                                        case 'approve':
                                            return '<span class="badge badge-success">Sukses</span>'
                                            break;
                                        case 'failed':
                                            return '<span class="badge badge-danger">Gagal</span>'
                                            break;
                                    }
                                }
                            },
                        ],
                        options: {
                            limit: [10, 15, 20],
                            currentLimit: 10,
                        },
                        search: true,
                        searchTitle: 'Pencarian Data',
                        searchItems: [{
                                display: 'Tanggal',
                                name: 'investor_withdrawal_datetime',
                                type: 'date'
                            },
                            {
                                display: 'Jumlah',
                                name: 'investor_withdrawal_status',
                                type: 'select',
                                option: [{
                                        value: 'pending',
                                        title: 'Menunggu'
                                    },
                                    {
                                        value: 'success',
                                        title: 'Sukses'
                                    },
                                    {
                                        value: 'failed',
                                        title: 'Gagal'
                                    },
                                    {
                                        value: 'verification',
                                        title: 'Verifikasi'
                                    }

                                ]
                            }

                        ],
                        sortName: "transaction_status_date",
                        sortOrder: "DESC",
                        tableIsResponsive: true,
                        select: true,
                        multiSelect: true,
                        buttonAction: [{
                            display: 'Approve',
                            icon: 'bx bx-check-circle',
                            style: "info",
                            action: 'accept',
                            url: window.location.origin + "/admin/service/investor/approveWithdrawalInvestor"

                        }, ]
                    });
                    this.getTotal();

                    $('#add-investor').show();
                },
                openModalWithdrawal() {
                    $('#modalAddUpdate').modal('show');
                },
                getTotal() {
                    $.ajax({
                        url: window.location.origin + '/admin/service/investor/getTotalInvestor/' + app.investor_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            console.log(data);
                            $('#total').html(data.data.total_price);
                            $('#total-price-percent').html(data.data.total_price_percent);
                            $('#total-registrasi').html(data.data.count);
                            $('#total-saldo').html(data.data.balance);
                            $('#total-paid').html(data.data.paid);
                            $('#total-pending').html(data.data.pending);
                        }
                    });
                },
                saveWithdraw() {
                    let url = window.location.origin + '/admin/service/investor/withdraw/' + app.investor_id;

                    $('#modalAddUpdate').modal('hide');

                    Swal.fire({
                        title: 'Perhatian!',
                        text: "Anda yakin akan mengubah password user ini?",
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: url,
                                data: {
                                    amount: app.modal.form.amount,
                                },
                                method: 'POST',
                                dataType: 'json',
                                success: function(res) {
                                    let messages = ``;

                                    $.refreshTable("table-investor");
                                    app.getTotal();
                                },
                                error: function(err) {}
                            });

                        } else {
                            $('#modalAddUpdate').modal('show');
                        }
                    });
                }
            },
            mounted() {
                this.hideLoading();
            }
        }).mount("#investor");

    $(document).ready(function() {
        window.history.pushState("", "", '/admin/investor/approval');
        app.generateInvestorTable(app.type);

        $('#select-investor').select2({
            allowClear: true,
            placeholder: 'Cari Investor',
            ajax: {
                url: window.location.origin + '/admin/service/investor/getAllInvestor',
                dataType: 'json',
                data: function(params) {
                    return {
                        search: params.term
                    }
                },
                processResults: function(data, page) {
                    return {
                        results: data
                    };
                }
            }
        });

        $('#select-investor').on('select2:select', function(e) {
            var data = e.params.data;
            app.investor_id = data.id;
            app.generateInvestorTable();
        });

        $('#amount').on('keydown', function() {
            app.modal.form.amount = $(this).val().replace(/\./g, '');
            console.log(app.modal.form.amount);
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
</script>