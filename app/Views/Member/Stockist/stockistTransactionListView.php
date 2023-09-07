<div class="app-content content" id="app">
    <div class="content-overlay">
    </div>
    <div class="content-loading">
        <i class="bx bx-loader bx-spin"></i>
    </div>
    <div class="content-wrapper">
        <div class="alert alert-danger" role="alert" id="alert" style="position: absolute; top: 84px; right: 32px; display: none;"></div>
        <div class="alert alert-success" role="alert" id="alert-success" style="position: absolute; top: 84px; right: 32px; display: none;"></div>

        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0"><?= $title ?></h5>

                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="<?= BASEURL ?>/member/dashboard"><i class="bx bx-home-alt"></i></a></li>
                                <li class="breadcrumb-item"><a href="#"><?= $breadcrumbs[0] ?></a></li>
                                <li class="breadcrumb-item active"><?= $breadcrumbs[1] ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col col-12 col-md-12 mb-1">
                    <div id="table">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetailTrx" tabindex="-1" role="dialog" aria-labelledby="modalDetailTrx" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title card-title text-primary">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-2 pb-2">
                                <div class="row">
                                    <div class="col-6">
                                        <ul class="pl-0" style="list-style-type: none;">
                                            <li class="mb-1"><i class="bx bx-cart font-medium-5" style="margin-right: 4px;"></i>
                                                Kode <br>
                                                <span class="text-primary ml-1 pl-1"> {{detail.stockist_transaction_code}}
                                                </span>
                                            </li>
                                            <li class="mb-1"><i class="bx bx-user font-medium-5" style="margin-right: 4px;"></i>
                                                Pembeli <br>
                                                <span class="text-primary ml-1 pl-1">{{detail.stockist_transaction_buyer_name}}
                                                    {{detail.network_code !== '' ? `(${detail.network_code})` : '' }}</span>
                                            </li>
                                            <li class="mb-1"><i class="bx bx-user font-medium-5" style="margin-right: 4px;"></i>
                                                Penerima <br>
                                                <span class="text-primary ml-1 pl-1">{{detail.stockist_transaction_delivery_receiver_name}}</span>
                                            </li>
                                            <li class="mb-1" v-if="detail.stockist_transaction_delivery_method == 'courier'"><i class="bx bx-map font-medium-5" style="margin-right: 4px;"></i>
                                                Alamat Kirim <br>
                                                <span class="text-primary ml-1 pl-1">{{detail.stockist_transaction_delivery_receiver_address}},
                                                    {{detail.stockist_transaction_delivery_receiver_subdistrict_name}},
                                                </span>
                                                <br>
                                                <span class="text-primary ml-1 pl-1">
                                                    {{detail.stockist_transaction_delivery_receiver_city_name}},
                                                    {{detail.stockist_transaction_delivery_receiver_province_name}}
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <ul class="pl-0" style="list-style-type: none;">
                                            <li class="mb-1"><i class="bx bx-calendar font-medium-5" style="margin-right: 4px;"></i></i>
                                                Tanggal Transaksi <br>
                                                <span class="text-primary pl-1 ml-1">{{detail.stockist_transaction_datetime_formatted}}</span>
                                            </li>
                                            <li class="mb-1">
                                                <i class="bx bx-desktop font-medium-5" style="margin-right: 4px;"></i>
                                                </i>
                                                Status <br>
                                                <span class="text-primary ml-1 pl-1">{{detail.stockist_transaction_status_formatted}}</span>
                                            </li>
                                            <li class="mb-1" v-if="detail.stockist_transaction_delivery_method == 'courier'">
                                                <i class="bx bx-truck font-medium-5" style="margin-right: 4px;"></i>
                                                </i>
                                                Ekspedisi <br>
                                                <span class="text-primary ml-1 pl-1">{{detail.courier_name}} ({{detail.stockist_transaction_delivery_courier_code}}) - {{detail.stockist_transaction_delivery_courier_service}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-3 mt-2">
                                <div class="invoice-product-details " style="overflow: auto;">
                                    <table class="table table-borderless mb-0 mx-70">
                                        <thead>
                                            <tr class="border-0">
                                                <th scope="col">Kode Produk</th>
                                                <th scope="col">Nama Produk</th>
                                                <th scope="col">Qty</th>
                                                <th scope="col" class="text-right">Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in detail.stockist_transaction_detail" @key="item.stockist_transaction_detail_id">
                                                <td>{{item.stockist_transaction_detail_product_code}}</td>
                                                <td>{{item.stockist_transaction_detail_product_name}}</td>
                                                <td>{{item.stockist_transaction_detail_quantity}}</td>
                                                <td class="text-right">Rp {{item.stockist_transaction_detail_unit_nett_price_formatted}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="border-top mt-1 ">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <h6 class="card-title text-dark">Ongkir</h6>
                                                </td>
                                                <td>
                                                    <h6 class="card-title text-primary text-right">Rp {{ detail.stockist_transaction_delivery_cost_formatted}}</h6>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class="card-title text-dark">Total</h6>
                                                </td>
                                                <td>
                                                    <h6 class="card-title text-primary text-right">Rp {{ detail.stockist_transaction_total_nett_price_formatted}}</h6>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <span class="">Tutup</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        app.generateTable();
        app.hideLoading();
    })

    let app = Vue.createApp({
        data: function() {
            return {
                button: {
                    formBtn: {
                        disabled: false
                    }
                },
                category: [],
                modal: {
                    data: {
                        title: "",
                        btnTitle: "",
                        btnAction: "",
                    },
                    form: {},
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
                $("#table").dataTableLib({
                    url: window.location.origin + '/member/stockist/get-member-transaction',
                    selectID: 'stockist_transaction_id',
                    colModel: [{
                        display: "Aksi",
                        name: "stockist_transaction_id",
                        align: "center",
                        render: (params, data) => {
                            return `<a onclick="app.detailTrx('${params}')" class="cstmHover" title="Detail" data-toggle="tooltip"> <i class="bx bx-book info"></i> </a>`;
                        },
                    }, {
                        display: "Tanggal",
                        name: "stockist_transaction_datetime_formatted",
                        align: "left",
                    }, {
                        display: "Kode Transaksi",
                        name: "stockist_transaction_code",
                        align: "left",
                    }, {
                        display: "Nama Pembeli",
                        name: "stockist_transaction_buyer_name",
                        align: "left",
                    }, {
                        display: "No. Telpon Penerima",
                        name: "stockist_transaction_delivery_receiver_mobilephone",
                        align: "left",
                    }, {
                        display: "Status",
                        name: "stockist_transaction_status",
                        align: "center",
                        render: (params, args) => {
                            switch (args.stockist_transaction_status_formatted) {
                                case 'Menunggu Pembayaran':
                                    return `<span class="badge badge-light-warning badge-pill">${args.stockist_transaction_status_formatted}</span>`
                                    break;

                                case 'Dibayar':
                                    return `<span class="badge badge-light-info badge-pill">${args.stockist_transaction_status_formatted}</span>`
                                    break;

                                case 'Selesai':
                                    return `<span class="badge badge-light-success badge-pill">${args.stockist_transaction_status_formatted}</span>`
                                    break;

                                case 'Ditolak':
                                    return `<span class="badge badge-light-danger badge-pill">${args.stockist_transaction_status_formatted}</span>`
                                    break;

                                case 'Kedaluarsa':
                                    return `<span class="badge badge-light-danger badge-pill">${args.stockist_transaction_status_formatted}</span>`
                                    break;

                                default:
                                    return `<span class="badge badge-light-info badge-pill">${args.stockist_transaction_status_formatted}</span>`
                                    break;
                            }
                        }
                    }, {
                        display: "Total",
                        name: "stockist_transaction_total_nett_price_formatted",
                        align: "right"
                    }],
                    buttonAction: [],
                    options: {
                        limit: [10, 25, 50, 100],
                        currentLimit: 10,
                    },
                    search: true,
                    searchTitle: 'Pencarian Transaksi',
                    searchItems: [{
                            display: 'Tanggal',
                            name: 'stockist_transaction_datetime',
                            type: 'date'
                        },
                        {
                            display: 'Kode Transaksi',
                            name: 'stockist_transaction_code',
                            type: 'text'
                        },
                        {
                            display: 'Nama Pembeli',
                            name: 'stockist_transaction_buyer_name',
                            type: 'text'
                        },
                        {
                            display: 'Status Transaksi',
                            name: 'stockist_transaction_status',
                            type: 'select',
                            option: this.category
                        },
                    ],
                    sortName: "stockist_product_stock_id",
                    sortOrder: "desc",
                    tableIsResponsive: true,
                    select: false,
                    multiSelect: false,
                })
            },
            detailTrx(transaction_id) {
                $.ajax({
                    url: window.location.origin + '/member/transaction/getDetailTransaction',
                    method: 'GET',
                    data: {
                        id: transaction_id
                    },
                    success: function(response) {
                        detailTrx(response.data.results)
                    },
                });
            },
            categoryTransaction() {
                $.ajax({
                    url: window.location.origin + '/member/transaction/categoryTransaction',
                    method: 'GET',
                    data: {},
                    success: function(response) {
                        app.category = response.data.results;
                    },
                });
            },
        },
        mounted() {
            this.categoryTransaction()
        }
    }).mount('#app');

    function detailTrx(data) {
        detail.showDetail(data)
    }

    const detail = Vue.createApp({
        data() {
            return {
                detail: {},
            }
        },
        methods: {
            showDetail(data) {
                this.detail = data
                $('#modalDetailTrx').modal()
            }
        }
    }).mount("#modalDetailTrx")
</script>