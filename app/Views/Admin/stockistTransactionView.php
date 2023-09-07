<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/app-assets/vendors/css/editors/quill/quill.snow.css">
<script src="<?php echo base_url(); ?>/app-assets/vendors/js/editors/quill/quill.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/app-assets/css/pages/app-email.css">
<script src="<?php echo base_url(); ?>/app-assets/js/scripts/pages/app-email-custom.js"></script>
<style>
    .table {
        position: relative;
    }

    .table-responsive {
        max-height: calc(100vh - 0px);
    }

    .table th,
    .table td {
        padding: 0.4rem 1rem;
        white-space: nowrap;
    }

    th {
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        background: white;
    }

    html body .content.app-content .content-area-wrapper {
        height: auto !important;
        min-height: calc(100% - 5rem);
    }

    #table-emailList>.row:first-child {
        border-bottom: 1px solid #dfe3e7;
    }

    #table-emailList .table-responsive {
        margin-top: 1rem !important;
    }

    .email-application .content-area-wrapper .sidebar .email-app-sidebar .email-app-menu {
        background-color: rgba(242, 244, 244, .7);
    }

    .ql-container.ql-snow,
    .ql-toolbar.ql-snow {
        border: 0;
    }

    .ql-container.ql-snow {
        min-height: 100px;
    }

    .email-app-details .border-primary {
        border: 2px solid #2c6de9 !important;
    }

    .email-app-details .collapse.show {
        background-color: rgba(242, 244, 244, .7);
    }

    .go-back {
        cursor: pointer;
    }
</style>


<section id="transaction">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
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
                        <div class="alert alert-success" v-show="alert.success.status" style="display: none;">
                            <span v-html="alert.success.content"></span>
                        </div>
                        <div class="alert alert-danger" v-show="alert.danger.status" style="display: none;"> <span v-html="alert.danger.content"></span> </div>
                        <div id="table-emailList" class="p-1"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailTransaksi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center" id="exampleModalCenterTitle">
                        <span>Detail Transaksi</span>
                    </h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8  pt-2 pb-2">
                            <h4 class="card-title text-primary">Transaksi</h4>
                            <ul class="pl-0" style="list-style-type: none;">
                                <li class="mb-1"><i class="bx bx-cart font-medium-5" style="margin-right: 4px"></i> Kode Transaksi : <span class="text-primary"> {{modal.detailTransaksi.data.warehouse_transaction_code}} </span></li>
                                <li class="mb-1"><i class="bx bx-user font-medium-5" style="margin-right: 4px"></i> Transaksi Dari : <span class="text-primary"> {{modal.detailTransaksi.data.warehouse_transaction_buyer_name}}</span> </li>
                                <li class="mb-1"><i class="bx bx-calendar font-medium-5" style="margin-right: 4px"></i> Tanggal Transaksi : <span class="text-primary">{{modal.detailTransaksi.data.warehouse_transaction_datetime}} </li>
                            </ul>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12  pb-2 mt-2 ">
                            <!-- product details table-->
                            <div class="invoice-product-details border-top pt-2 pl-2 pb-2" style="overflow: auto;">
                                <table class="table table-borderless mb-0 mx-70">
                                    <thead>
                                        <tr class="border-0">
                                            <th scope="col">Nama Produk</th>
                                            <th scope="col">Kode Produk</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col" class="text-right">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item,index) in modal.detailTransaksi.data.warehouse_transaction_detail" :key="item.warehouse_transaction_detail_id">
                                            <td>{{item.warehouse_transaction_detail_product_name}}</td>
                                            <td>{{item.warehouse_transaction_detail_product_code}}</td>
                                            <td>{{item.warehouse_transaction_detail_quantity}}</td>
                                            <td class="text-right">
                                                {{item.warehouse_transaction_detail_unit_nett_price_formatted}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="border-top  pt-2 pl-2 pb-2">
                                <table class="table table-borderless mb-0 mx-70">
                                    <tr>
                                        <td>
                                            <h6 class="card-title text-dark">Total</h6>
                                        </td>
                                        <td>
                                            <h6 class="card-title text-primary text-right">
                                                Rp {{modal.detailTransaksi.data.warehouse_transaction_total_nett_price_formatted}}
                                            </h6>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" v-show="modal.detailTransaksi.actions.showBtnApproved && page.actions == 'stockist'">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 my-1">
                        <button onclick="transactionView.rejectTransaksi()" type="button" class="btn btn-danger btn-block" data-dismiss="modal" id="btnCloseDetail">
                            <span class="d-none d-sm-block">Reject Transaksi</span>
                        </button>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 my-1">
                        <button onclick="transactionView.approveTransaksi()" type="button" class="btn btn-success btn-block" data-dismiss="modal" id="btnCloseDetail">
                            <span class="d-none d-sm-block">Approve Transaksi</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>




<script type="text/javascript" defer>
    let stateMenu = {
        baseUrl: window.location.origin,
        selectedID: '',
        subStateMenu: '',
    }

    let type = '';

    $(document).ready(function() {
        transactionView.generateMessageTable('');
    });


    function updateGroup(group) {
        stateMenu.selectedID = group.administrator_group_id;
        stateMenu.formAction = 'update';
    }

    let transactionView =
        Vue.createApp({
            data: function() {
                return {
                    page: {
                        title: 'Stokis',
                        actions: 'stockist'
                    },
                    modal: {

                        detailTransaksi: {
                            data: {
                                warehouse_transaction_buyer_name: '',
                                warehouse_transaction_total_nett_price: '',
                                warehouse_transaction_detail: [],
                            },
                            actions: {
                                showBtnApproved: false,
                            }
                        }
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
                openModalTransaksi(transaction_id) {
                    $.ajax({
                        url: '<?php echo site_url('admin/service/transaction/getDetailTransaction/') ?>',
                        method: 'GET',
                        data: {
                            id: transaction_id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data.results;

                                transactionView.modal.detailTransaksi.actions.showBtnApproved = data
                                    .warehouse_transaction_status == 'pending' ? true : false
                                transactionView.modal.detailTransaksi.data = data;

                                $('#detailTransaksi').modal();
                            }
                        }
                    });

                },
                approveTransaksi() {
                    $.ajax({
                        url: '<?php echo site_url('admin/service/transaction/approveTransaction') ?>',
                        method: 'POST',
                        data: {
                            warehouse_transaction_id: transactionView.modal.detailTransaksi.data.warehouse_transaction_id,
                            warehouse_transaction_status: 'complete'
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                transactionView.alert.success.content = response.message;
                                transactionView.alert.success.status = true;
                                setTimeout(() => {
                                    transactionView.alert.success.status = false;
                                    location.reload();
                                }, 1000);
                                transactionView.generateMessageTable(type);
                            }
                        }
                    });
                },
                rejectTransaksi() {
                    $.ajax({
                        url: '<?php echo site_url('admin/service/transaction/updateStatusTransaction') ?>',
                        method: 'POST',
                        data: {
                            warehouse_transaction_id: transactionView.modal.detailTransaksi.data.warehouse_transaction_id,
                            warehouse_transaction_status: 'void'
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                transactionView.alert.success.content = response.message;
                                transactionView.alert.success.status = true;
                                setTimeout(() => {
                                    transactionView.alert.success.status = false;
                                    location.reload();
                                }, 1000);
                                transactionView.generateMessageTable(type);
                            }
                        }
                    });
                },

                changePage(page_title, page_action) {
                    this.page.title = page_title;
                    this.page.actions = page_action;

                    this.generateMessageTable(stateMenu.subStateMenu);
                },
                generateMessageTable(type) {
                    stateMenu.subStateMenu = type;
                    $("#table-emailList").dataTableLib({
                        url: window.location.origin + '/admin/service/transaction/getDataTransaction/' + this.page
                            .actions + '/' +
                            type,
                        selectID: 'warehouse_transaction_id',
                        colModel: [{
                                display: 'Tanggal',
                                name: 'warehouse_transaction_datetime_formatted',
                                sortAble: true,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Kode Transaksi',
                                name: 'warehouse_transaction_code',
                                sortAble: true,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Nama Stokis',
                                name: 'warehouse_transaction_buyer_name',
                                sortAble: false,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Total Pembayaran',
                                name: 'warehouse_transaction_total_nett_price_formatted',
                                sortAble: true,
                                align: 'center',
                                export: true
                            },
                            {
                                display: 'Detail/Aksi',
                                name: 'warehouse_transaction_id',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    return `<button onclick="transactionView.openModalTransaksi(${params})" class="btn btn-sm btn-info glow px-1" > <i class="bx bx-receipt"></i> </button> `;
                                }
                            },
                            {
                                display: 'STATUS TRANSAKSI',
                                name: 'warehouse_transaction_status',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    switch (params) {
                                        case 'complete':
                                            return `<span class="btn btn-sm btn-success btn-block glow px-1" > SUKSES </span>`
                                            break;
                                        case 'pending':
                                            return `<span class="btn btn-sm btn-warning btn-block glow px-1 btn-waiting" > <span> MENUNGGU PEMBAYARAN</span> </span>`
                                            break;
                                        case 'paid':
                                            return `<span class="btn btn-sm btn-info btn-block glow px-1 btn-waiting" > <span> MENUNGGU KONFIRMASI </span> </span>`
                                            break;
                                    }
                                },
                                export: true
                            },

                        ],
                        options: {
                            limit: [10, 15, 20],
                            currentLimit: 10,
                        },
                        search: true,
                        searchTitle: 'Pencarian Data Histori Transaksi',
                        searchItems: [{
                                display: 'Tanggal',
                                name: 'warehouse_transaction_datetime',
                                type: 'date'
                            },
                            {
                                display: 'Kode Transaksi',
                                name: 'warehouse_transaction_code',
                                type: 'text'
                            },
                            {
                                display: 'Nama Stokis',
                                name: 'warehouse_transaction_buyer_name',
                                type: 'text'
                            },
                            {
                                display: 'Status Transaksi',
                                name: 'warehouse_transaction_status',
                                type: 'select',
                                option: [{
                                        title: 'Menunggu',
                                        value: 'pending'
                                    },
                                    {
                                        title: 'Sukses',
                                        value: 'complete'
                                    },
                                    {
                                        title: 'Ditolak',
                                        value: 'void'
                                    }
                                ]
                            }
                        ],
                        sortName: "warehouse_transaction_code",
                        sortOrder: "DESC",
                        tableIsResponsive: true,
                        select: false,
                        multiSelect: false,
                        buttonAction: [{
                            display: 'Export Excel',
                            icon: 'bx bxs-file',
                            style: 'info',
                            action: 'exportExcel',
                            url: window.location.origin + "/stockist/excel"
                        }, ]
                    });
                }
            },

            mounted() {
                this.hideLoading();
            }

        }).mount("#transaction");
</script>