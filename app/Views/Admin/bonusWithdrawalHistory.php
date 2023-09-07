<section id="app">
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
                            <div class="col-12 mt-0">
                                <p>
                                    <b>Total Potongan Admin :</b> <span id="charge">Rp 0</span> <br>
                                    <b>Total Potongan Pajak :</b> <span id="tax">Rp 0</span> <br>
                                    <b>Total Komisi :</b> <span id="komisi">Rp 0</span>
                                </p>
                                <div id="table"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    $(document).ready(function() {
        app.generateTable();
        app.hideLoading();
    });

    // Function That Called 
    let app =
        Vue.createApp({
            data: function() {
                return {
                    modal_detail: {},
                    category: [],
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
                    form: {
                        transaction_code: '',
                        transaction_receipt: '',
                    }
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },
                generateTable() {
                    $("#table").dataTableLib({
                        url: window.location.origin + '/admin/service/bonus/getListWithdrawal',
                        selectID: 'ewallet_withdrawal_id',
                        colModel: [{
                                display: 'Tanggal',
                                name: 'ewallet_withdrawal_datetime',
                                sortAble: true,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Nama Stokis',
                                name: 'stockist_name',
                                sortAble: true,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Nama Bank',
                                name: 'ewallet_withdrawal_bank_name',
                                sortAble: true,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Bank Atas Nama',
                                name: 'ewallet_withdrawal_bank_account_name',
                                sortAble: true,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Nomor Rekening',
                                name: 'ewallet_withdrawal_bank_account_no',
                                sortAble: false,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Potongan Admin',
                                name: 'ewallet_withdrawal_adm_charge_value',
                                sortAble: false,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Pajak',
                                name: 'ewallet_withdrawal_tax_value',
                                sortAble: false,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Jumlah Saldo',
                                name: 'ewallet_withdrawal_nett',
                                sortAble: false,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Tanggal Update',
                                name: 'ewallet_withdrawal_status_datetime',
                                sortAble: false,
                                align: 'left',
                                // export: true
                            },
                            {
                                display: 'Status',
                                name: 'ewallet_withdrawal_status',
                                sortAble: false,
                                align: 'left',
                                // export: true

                            }
                        ],
                        options: {
                            limit: [10, 25, 50, 100],
                            currentLimit: 10,
                        },
                        search: true,
                        searchTitle: 'Pencarian List Withdrawal',
                        searchItems: [{
                                display: 'Tanggal',
                                name: 'ewallet_withdrawal_datetime',
                                type: 'date'
                            },
                            {
                                display: 'Username',
                                name: 'member_account_username',
                                type: 'text'
                            },
                            {
                                display: 'Nama Stokis',
                                name: 'ewallet_withdrawal_member_name',
                                type: 'text'
                            },
                            {
                                display: 'Nomor Rekening',
                                name: 'ewallet_withdrawal_bank_account_no',
                                type: 'text'
                            },
                        ],
                        sortName: "ewallet_withdrawal_datetime",
                        sortOrder: "DESC",
                        tableIsResponsive: true,
                        select: false,
                        multiSelect: false,
                        buttonAction: [
                            // {
                            //     display: 'Export Excel',
                            //     icon: 'bx bxs-file',
                            //     style: 'info',
                            //     action: 'exportExcel',
                            //     url: window.location.origin + "/admin/bonus/excel"
                            // },
                        ],
                        advancedOption: [{
                            id: 'charge',
                            value: 'charge'
                        }, {
                            id: 'komisi',
                            value: 'komisi'
                        }, {
                            id: 'tax',
                            value: 'tax'
                        }]
                    });
                },
            },
            mounted() {}
        }).mount("#app");
</script>