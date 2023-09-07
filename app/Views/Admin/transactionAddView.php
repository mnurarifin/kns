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
                                <div id="table"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetail" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title card-title text-primary">Detail Transaksi</h5>


                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-2 pb-2">
                            <div class="row">
                                <div class="col-6">
                                    <ul class="pl-0" style="list-style-type: none;">
                                        <li class="mb-1"><i class="bx bx-cart font-medium-5" style="margin-right: 4px;"></i>
                                            Kode : <span class="text-primary"> {{detail.transaction_code}}
                                            </span>
                                        </li>
                                        <li class="mb-1"><i class="bx bx-user font-medium-5" style="margin-right: 4px;"></i>
                                            Pembeli : <span class="text-primary">{{detail.transaction_member_name}}
                                                {{detail.transaction_member_ref_network_code !== '' ? `(${detail.transaction_member_ref_network_code})` : '' }}</span>
                                        </li>

                                    </ul>
                                </div>
                                <div class="col-6">
                                    <ul class="pl-0" style="list-style-type: none;">
                                        <li class="mb-1"><i class="bx bx-calendar font-medium-5" style="margin-right: 4px;"></i></i>
                                            Tanggal Transaksi : <span class="text-primary">{{detail.transaction_datetime_formatted}}</span></li>

                                        <li class="mb-1">
                                            <i class="bx bx-desktop font-medium-5" style="margin-right: 4px;"></i>
                                            </i>
                                            Status : <span class="text-primary">{{detail.transaction_status_formatted}}</span>
                                        </li>

                                    </ul>
                                </div>
                            </div>



                        </div>


                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-3 mt-2">
                            <!-- product details table-->
                            <div class="invoice-product-details " style="overflow: auto;">
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
                                        <tr v-for="item in detail.transaction_detail" @key="item.transaction_detail_id">
                                            <td>{{item.transaction_detail_product_name}}</td>
                                            <td>{{item.transaction_detail_product_code}}</td>
                                            <td>{{item.transaction_detail_qty}}</td>
                                            <td class="text-right">Rp. {{item.transaction_detail_unit_price_formatted}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="border-top mt-1 ">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <h6 class="card-title text-dark">Total</h6>
                                            </td>
                                            <td>
                                                <h6 class="card-title text-primary text-right">Rp. {{ detail.transaction_total_price_formatted}}</h6>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tutup</span>
                    </button>
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
                    detail: {},
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
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },
                generateTable() {
                    $("#table").dataTableLib({
                        url: window.location.origin + '/admin/service/transaction/getDataTransaction/',
                        selectID: 'transaction_code',
                        colModel: [{
                                display: 'Tanggal',
                                name: 'transaction_date_formatted',
                                sortAble: true,
                                align: 'left'
                            },
                            {
                                display: 'Kode Transaksi',
                                name: 'transaction_code',
                                sortAble: true,
                                align: 'left'
                            },
                            {
                                display: 'Kode Mitra',
                                name: 'transaction_member_network_code',
                                sortAble: true,
                                align: 'left'
                            },
                            {
                                display: 'Nama Pembeli',
                                name: 'transaction_member_name',
                                sortAble: true,
                                align: 'left'
                            },
                            {
                                display: 'Tipe Transaksi',
                                name: 'transaction_type',
                                sortAble: true,
                                align: 'left',
                                render: (params) => {
                                    return params == 'ro' ? 'Repeat Order' : 'Registrasi';
                                },
                            },
                            {
                                display: 'Status',
                                name: 'transaction_status_formatted',
                                sortAble: true,
                                align: 'left',
                                render: (params) => {
                                    return params;
                                },
                            },
                            {
                                display: 'Total',
                                name: 'transaction_total_price_formatted',
                                sortAble: true,
                                align: 'left'
                            },
                            {
                                display: 'Detail',
                                name: 'transaction_code',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    return `<a onclick="app.detailTransaction('${params}')"> <i class="bx bx-receipt warning"></i> </a>`;
                                }
                            },
                        ],
                        options: {
                            limit: [10, 15, 20],
                            currentLimit: 10,
                        },
                        search: true,
                        searchTitle: 'Pencarian Transaksi',
                        searchItems: [{
                                display: 'Tanggal',
                                name: 'transaction_datetime',
                                type: 'date'
                            },
                            {
                                display: 'Kode',
                                name: 'transaction_code',
                                type: 'text'
                            },
                            {
                                display: 'Nama Pembeli',
                                name: 'transaction_member_name',
                                type: 'text'
                            },
                            {
                                display: 'Tipe Transaksi',
                                name: 'transaction_type',
                                type: 'select',
                                option: [{
                                        title: 'Registrasi',
                                        value: 'registration'
                                    },
                                    {
                                        title: 'Repeat Order',
                                        value: 'ro'
                                    },
                                ]
                            },
                            {
                                display: 'Status Transaksi',
                                name: 'transaction_status',
                                type: 'select',
                                option: this.category
                            },
                        ],
                        sortName: "transaction_datetime",
                        sortOrder: "ASC",
                        tableIsResponsive: true,
                        select: false,
                        multiSelect: false,
                        buttonAction: [{
                                display: 'Export Excel',
                                icon: 'bx bxs-file',
                                style: 'info',
                                action: 'exportExcel',
                                url: window.location.origin + "/admin/transaction/excel/"
                            },

                        ]
                    });
                },
                detailTransaction(transaction_code) {
                    $.ajax({
                        url: window.location.origin + '/admin/service/transaction/detailTransaction',
                        method: 'GET',
                        data: {
                            transaction_code
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                app.detail = response.data.results;
                                $('#modalDetail').modal('show')
                            }
                        },

                    });
                },
                categoryTransaction() {
                    $.ajax({
                        url: window.location.origin + '/admin/service/transaction/categoryTransaction',
                        method: 'GET',
                        data: {},
                        success: function(response) {
                            if (response.status == 200) {
                                app.category = response.data.results;
                            }
                        },

                    });
                },
            },
            mounted() {
                this.categoryTransaction();
            }
        }).mount("#app");;
</script>