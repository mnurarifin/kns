<script src="https://unpkg.com/vue@next"></script>


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
                                    <b>Total Potongan Keseluruhan :</b> <span id="charge">Rp 0</span> <br>
                                    <b>Total Potongan Bulan Ini :</b> <span id="charge_month">Rp 0</span>
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
                    },
                    bonus_charge: {
                        charge: 0,
                        charge_month: 0
                    }
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },
                generateTable() {
                    $("#table").dataTableLib({
                        url: window.location.origin + '/admin/service/bonus/getListWithdrawal/success',
                        selectID: 'ewallet_withdrawal_id',
                        colModel: [{
                                display: 'Tanggal',
                                name: 'ewallet_withdrawal_datetime',
                                sortAble: true,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Username',
                                name: 'member_account_username',
                                sortAble: true,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Nama Mitra',
                                name: 'member_name',
                                sortAble: true,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Potongan',
                                name: 'ewallet_withdrawal_adm_charge_value',
                                sortAble: false,
                                align: 'left',
                                export: true
                            },

                        ],
                        options: {
                            limit: [10, 15, 20],
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
                                display: 'Nama Mitra',
                                name: 'member_name',
                                type: 'text'
                            },
                        ],
                        sortName: "bonus_withdrawal_datetime",
                        sortOrder: "DESC",
                        tableIsResponsive: true,
                        select: false,
                        multiSelect: false,
                        buttonAction: [
                            // {
                            //     display: 'Transfer',
                            //     icon: 'bx bx-check',
                            //     style: "success",
                            //     action:'active',
                            //     message:"melakukan persetujuan transfer dari",
                            //     url: window.location.origin + "/admin/service/bonus/addWithdrawal"
                            // },
                            {
                                display: 'Export Excel',
                                icon: 'bx bxs-file',
                                style: 'info',
                                action: 'exportExcel',
                                url: window.location.origin + "/admin/bonus/excel_charge"
                            },
                        ],
                        advancedOption: [{
                            id: 'charge',
                            value: 'charge'
                        }, {
                            id: 'charge_month',
                            value: 'charge_month'
                        }]
                    });
                },
                getChargeWithdrawal() {
                    $.ajax({
                        url: window.location.origin + '/admin/service/bonus/getChargeWithdrawalTotal',
                        method: 'GET',
                        data: {},
                        success: function(response) {
                            if (response.status == 200) {
                                app.bonus_charge = response.data.result;
                            }
                        },

                    });
                }
            },
            mounted() {
                this.getChargeWithdrawal();
            }
        }).mount("#app");
</script>