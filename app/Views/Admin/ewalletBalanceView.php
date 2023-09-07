<style>
    @media (min-width: 576px) {
        #detailTransaksi .modal-dialog {
            max-width: none;
        }
    }

    #detailTransaksi .modal-dialog {
        width: 98%;
        height: 92%;
        padding: 0;
    }

    #detailTransaksi .modal-content {
        height: 99%;
    }

    #table-report-transfer-searchModal .modal-dialog,
    #table-report-all-searchModal .modal-dialog {
        width: 500px;
    }

    #table-report-transfer-searchModal .modal-content,
    #table-report-all-searchModal .modal-dialog {
        height: auto;
    }
</style>
<section id="ewallet">
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
                                <div class="row" style="margin-bottom: 20px; font-weight:bold;">
                                    <div class="col-md-4">
                                        <caption>Total Diterima : <span id="receiver">Rp. 0</span></caption>
                                    </div>
                                    <div class="col-md-4">
                                        <caption>Total Dibayarkan : <span id="paid">Rp. 0</span></caption>
                                    </div>
                                    <div class="col-md-4">
                                        <caption>Saldo : <span id="saldo">Rp. 0</span></caption>
                                    </div>
                                </div>
                                <div id="table-product"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailTransaksi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center" id="exampleModalCenterTitle">
                        <span>Detail E-Wallet</span>
                    </h5>

                    <h4 class="modal-title d-flex align-items-center"></h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body" style="min-height: 500px;">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6  pt-2 pb-2">
                            <ul style="list-style-type: none;">

                                <li><i class="bx bx-user font-medium-5"></i> Nama Mitra : &nbsp; <span class="text-primary">
                                        {{detail.ewallet_product_member_name}}
                                    </span>
                                </li>
                                <li><i class="bx bx-user font-medium-5"></i> Saldo Terakhir : &nbsp; <span class="text-primary">
                                        {{detail.ewallet_product_balance_formated}}
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6  pt-2 pb-2" v-show="detail.ewallet_product_member_network_code !== ''">
                            <ul style="list-style-type: none;">
                                <li><i class="bx bx-cart font-medium-5"></i> Kode Mitra : &nbsp;<span class="text-primary"> {{detail.ewallet_product_member_network_code}} </span>
                                </li>

                            </ul>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12  pb-2 mt-2 ">
                            <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab-fill" data-toggle="tab" href="#home-fill" role="tab" aria-controls="home-fill" aria-selected="true">
                                        Semua Histori
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab-fill" data-toggle="tab" href="#profile-fill" role="tab" aria-controls="profile-fill" aria-selected="false">
                                        Histori Transfer
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content pt-1">
                                <div @click="ewalletView.generateDetailTable(this.detail.ewallet_product_member_id)" class="tab-pane active" id="home-fill" role="tabpanel" aria-labelledby="home-tab-fill">
                                    <div id="table-report-all"></div>
                                </div>
                                <div @click="ewalletView.generateTableTransfer(this.detail.ewallet_product_member_id)" class="tab-pane " id="profile-fill" role="tabpanel" aria-labelledby="profile-tab-fill">
                                    <div id="table-report-transfer"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

</section>
<script type="text/javascript">
    function generateTable() {
        $("#table-product").dataTableLib({
            url: window.location.origin + '/admin/service/ewallet/getWalletBalance/',
            selectID: 'bonus_network_id',
            colModel: [{
                    display: 'Nama Stokis',
                    name: 'stockist_name',
                    sortAble: false,
                    align: 'left'
                },
                {
                    display: 'Total Diterima (Rp)',
                    name: 'ewallet_acc',
                    sortAble: true,
                    align: 'right'
                },
                {
                    display: 'Total Dibayar (Rp)',
                    name: 'ewallet_paid',
                    sortAble: true,
                    align: 'right'
                },
                {
                    display: 'Saldo (Rp)',
                    name: 'saldo',
                    sortAble: true,
                    align: 'right'
                },
            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Saldo',
            searchItems: [{
                display: 'Nama Stokis',
                name: 'stockist_name',
                type: 'text'
            }, ],
            sortName: "saldo",
            sortOrder: "desc",
            tableIsResponsive: true,
            select: false,
            multiSelect: false,
            buttonAction: [],
            advancedOption: [{
                id: 'receiver',
                value: 'totalKomisi'
            }, {
                id: 'paid',
                value: 'totalPaid'
            }, {
                id: 'saldo',
                value: 'totalSaldo'
            }]
        });
    }

    function generateDetailTable(member_id) {
        $("#table-report-all").dataTableLib({
            url: window.location.origin + '/admin/service/ewallet/getLogWalletBalance/' + member_id,
            selectID: 'ewallet_product_log_id',
            colModel: [{
                    display: 'Tanggal',
                    name: 'ewallet_product_log_datetime_formatted',
                    sortAble: false,
                    align: 'left'
                },
                {
                    display: 'Saldo',
                    name: 'ewallet_product_log_value_formatted',
                    sortAble: true,
                    align: 'left'
                },
                {
                    display: 'Tipe',
                    name: 'ewallet_product_log_type_formatted',
                    sortAble: true,
                    align: 'left'
                },
                {
                    display: 'Catatan',
                    name: 'ewallet_product_log_note',
                    sortAble: false,
                    align: 'left'
                },

            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian',
            searchItems: [{
                    display: 'Tanggal',
                    name: 'ewallet_product_log_datetime',
                    type: 'date'
                },
                {
                    display: 'Tipe',
                    name: 'ewallet_product_log_type',
                    type: 'select',
                    option: [{
                            title: 'Masuk',
                            value: 'in'
                        },
                        {
                            title: 'Keluar',
                            value: 'out'
                        }
                    ]
                },

            ],
            sortName: "ewallet_product_balance",
            sortOrder: "DESC",
            tableIsResponsive: false,
            select: false,
            multiSelect: false,
            buttonAction: [{
                display: 'Export Excel',
                icon: 'bx bxs-file',
                style: 'info',
                action: 'exportExcel',
                url: window.location.origin + "/admin/ewallet/excelHistoryBalance/" + member_id
            }, ]
        });
    }

    function generateTableTransfer(member_id) {
        $("#table-report-transfer").dataTableLib({
            url: window.location.origin + '/admin/service/ewallet/getTransferLogWalletBalance/' + member_id,
            selectID: 'ewallet_product_transfer_id',
            colModel: [{
                    display: 'Tanggal',
                    name: 'ewallet_product_transfer_datetime_formatted',
                    sortAble: false,
                    align: 'left'
                },
                {
                    display: 'Nama Pengirim',
                    name: 'ewallet_product_transfer_sender_member_name',
                    sortAble: true,
                    align: 'left'
                },
                {
                    display: 'Nama Penerima',
                    name: 'ewallet_product_transfer_receiver_member_name',
                    sortAble: true,
                    align: 'left'
                },
                {
                    display: 'Saldo',
                    name: 'ewallet_product_transfer_value_formatted',
                    sortAble: true,
                    align: 'left'
                },
                {
                    display: 'Tipe',
                    name: 'ewallet_product_transfer_type',
                    sortAble: true,
                    align: 'left'
                },
                {
                    display: 'Catatan',
                    name: 'ewallet_product_transfer_note',
                    sortAble: false,
                    align: 'left'
                },

            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian',
            searchItems: [{
                    display: 'Tanggal',
                    name: 'ewallet_product_transfer_datetime',
                    type: 'date'
                },
                // Filter Doesnt Work  Cause The JS FILE
                // {
                //     display: 'Kode Pengirim',
                //     name: 'ewallet_product_transfer_sender_network_code',
                //     type: 'text'
                // },
                // {
                //     display: 'Kode Penerima',
                //     name: 'ewallet_product_transfer_receiver_member_name',
                //     type: 'text'
                // },
                // {
                //     display: 'Tipe',
                //     name: `IF(ewallet_product_transfer_sender_member_id = ${member_id}, 'Keluar', 'Masuk')`,
                //     type:'select', 
                //     option: [
                //         {title: 'Masuk', value: 'Masuk'}, 
                //         {title: 'Keluar', value: 'Keluar'}
                //     ]
                // },
                // {
                //     display: 'Catatan',
                //     name: 'ewallet_product_transfer_note',
                //     type:'text',
                // },

            ],
            sortName: "ewallet_product_balance",
            sortOrder: "DESC",
            tableIsResponsive: false,
            select: false,
            multiSelect: false,
            buttonAction: [{
                    display: 'Export Excel',
                    icon: 'bx bxs-file',
                    style: 'info',
                    action: 'exportExcel',
                    url: window.location.origin + "/admin/ewallet/excelTransferHistoryBalance/" + member_id
                },

            ]
        });
    }

    let ewalletView =
        Vue.createApp({
            data: function() {
                return {
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
                    detail: {}
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },
                detailWallet(value) {
                    this.detail = value;
                    generateTableTransfer(value.ewallet_product_member_id);
                    generateDetailTable(value.ewallet_product_member_id);
                    $('#detailTransaksi').modal();
                },

            },
            computed: {

            },
            mounted() {
                this.hideLoading();

            }

        }).mount("#ewallet");

    $(document).ready(function() {
        generateTable();
    });
</script>