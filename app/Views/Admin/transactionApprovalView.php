<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.10.1/viewer.js" integrity="sha512-ivK7VQrrokmCTgtxpbqExrNaKfOhEdAFL51Ez9+UnKGT7OfCbYKogPJY++EOOvfXUDuPZaL4wkwzJbBO1kaMBQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.10.1/viewer.css" integrity="sha512-Dlf3op7L5ECYeoL6o80A2cqm4F2nLvvK4aH84DxCT690quyOZI8Z0CxVG9PQF3JHmD/aBFqN/W/8SYt7xKLi2w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    .swal2-cancel {
        color: #475F7B !important;
    }
</style>

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
                                                    <span class="text-primary ml-1 pl-1"> {{detail.warehouse_transaction_code}}
                                                    </span>
                                                </li>
                                                <li class="mb-1"><i class="bx bx-user font-medium-5" style="margin-right: 4px;"></i>
                                                    Pembeli <br>
                                                    <span class="text-primary ml-1 pl-1">{{detail.warehouse_transaction_buyer_name}}</span>
                                                </li>
                                                <li class="mb-1"><i class="bx bx-user font-medium-5" style="margin-right: 4px;"></i>
                                                    Penerima <br>
                                                    <span class="text-primary ml-1 pl-1">{{detail.warehouse_transaction_delivery_receiver_name}}</span>
                                                </li>
                                                <li class="mb-1" v-if="detail.warehouse_transaction_delivery_method == 'courier'"><i class="bx bx-map font-medium-5" style="margin-right: 4px;"></i>
                                                    Alamat Kirim <br>
                                                    <span class="text-primary ml-1 pl-1">{{detail.warehouse_transaction_delivery_receiver_address}},
                                                        {{detail.warehouse_transaction_delivery_receiver_subdistrict_name}},
                                                    </span>
                                                    <br>
                                                    <span class="text-primary ml-1 pl-1">
                                                        {{detail.warehouse_transaction_delivery_receiver_city_name}},
                                                        {{detail.warehouse_transaction_delivery_receiver_province_name}}
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-6">
                                            <ul class="pl-0" style="list-style-type: none;">
                                                <li class="mb-1"><i class="bx bx-calendar font-medium-5" style="margin-right: 4px;"></i></i>
                                                    Tanggal Transaksi <br>
                                                    <span class="text-primary pl-1 ml-1">{{detail.warehouse_transaction_datetime_formatted}}</span>
                                                </li>
                                                <li class="mb-1">
                                                    <i class="bx bx-desktop font-medium-5" style="margin-right: 4px;"></i>
                                                    </i>
                                                    Status <br>
                                                    <span class="text-primary ml-1 pl-1">{{detail.warehouse_transaction_status_formatted}}</span>
                                                </li>
                                                <li class="mb-1" v-if="detail.warehouse_transaction_delivery_method == 'courier'">
                                                    <i class="bx bx-truck font-medium-5" style="margin-right: 4px;"></i>
                                                    </i>
                                                    Ekspedisi <br>
                                                    <span class="text-primary ml-1 pl-1">{{detail.courier_name}} ({{detail.warehouse_transaction_delivery_courier_code}}) - {{detail.warehouse_transaction_delivery_courier_service}}</span>
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
                                                    <th scope="col">Kode Produk</th>
                                                    <th scope="col">Nama Produk</th>
                                                    <th scope="col">Qty</th>
                                                    <th scope="col" class="text-right">Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="item in detail.warehouse_transaction_detail" @key="item.warehouse_transaction_detail_id">
                                                    <td>{{item.warehouse_transaction_detail_product_code}}</td>
                                                    <td>{{item.warehouse_transaction_detail_product_name}}</td>
                                                    <td>{{item.warehouse_transaction_detail_quantity}}</td>
                                                    <td class="text-right">Rp. {{item.warehouse_transaction_detail_unit_nett_price_formatted}}</td>
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
                                                        <h6 class="card-title text-primary text-right">Rp {{ detail.warehouse_transaction_delivery_cost_formatted}}</h6>
                                                    </td>
                                                </tr>
                                                <tr v-if="type == 'stockist' && detail.warehouse_transaction_payment_ewallet_formatted != '0'">
                                                    <td>
                                                        <h6 class="card-title text-dark">Saldo Stokis</h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="card-title text-danger text-right">- Rp {{ detail.warehouse_transaction_payment_ewallet_formatted}}</h6>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6 class="card-title text-dark">Total</h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="card-title text-primary text-right">Rp. {{ detail.warehouse_transaction_total_nett_price_formatted}}</h6>
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
                    type: `<?= $type ?>`
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },
                generateTable() {
                    $("#table").dataTableLib({
                        url: window.location.origin + '/admin/service/transaction/getDataTransaction/<?= $type ?>/approval',
                        selectID: 'warehouse_transaction_code',
                        colModel: [{
                                display: 'Aksi',
                                name: 'warehouse_transaction_id',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    return `<a onclick="app.detailTransaction('${params}')" title="Detail" class="cstmHover" data-toggle="tooltip"> <i class="bx bx-book info"></i> </a>`;
                                }
                            }, {
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
                                display: 'Nama Pembeli',
                                name: 'warehouse_transaction_buyer_name',
                                sortAble: true,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Status',
                                name: 'warehouse_transaction_status_formatted',
                                sortAble: true,
                                align: 'center',
                                export: true,
                                render: (params) => {
                                    switch (params) {
                                        case 'Menunggu Pembayaran':
                                            return `<span class="badge badge-light-warning badge-pill">${params}</span>`
                                            break;

                                        default:
                                            return `<span class="badge badge-light-info badge-pill">${params}</span>`
                                            break;
                                    }
                                },
                            },
                            {
                                display: 'Total',
                                name: 'warehouse_transaction_total_nett_price_formatted',
                                sortAble: true,
                                align: 'right',
                                export: true
                            },
                        ],
                        options: {
                            limit: [10, 25, 50, 100],
                            currentLimit: 10,
                        },
                        search: true,
                        searchTitle: 'Pencarian Transaksi',
                        searchItems: [{
                                display: 'Tanggal',
                                name: 'warehouse_transaction_datetime',
                                type: 'date'
                            },
                            {
                                display: 'Kode',
                                name: 'warehouse_transaction_code',
                                type: 'text'
                            },
                            {
                                display: 'Nama Pembeli',
                                name: 'warehouse_transaction_buyer_name',
                                type: 'text'
                            },
                            {
                                display: 'Status Transaksi',
                                name: 'warehouse_transaction_status',
                                type: 'select',
                                option: this.category
                            },
                        ],
                        sortName: "warehouse_transaction_datetime",
                        sortOrder: "DESC",
                        tableIsResponsive: true,
                        select: false,
                        multiSelect: false,
                        buttonAction: [{
                                display: 'Export Excel',
                                icon: 'bx bxs-file',
                                style: 'info',
                                action: 'exportExcel',
                                url: window.location.origin + "/admin/transaction/excel/approval"
                            },

                        ]
                    });
                },
                detailTransaction(warehouse_transaction_id) {
                    $.ajax({
                        url: window.location.origin + '/admin/service/transaction/getDetailTransaction',
                        method: 'GET',
                        data: {
                            id: warehouse_transaction_id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                app.detail = response.data.results;

                                $('#modalDetail').modal('show')

                                $('#warehouse_transaction_payment_image').prop('src', response.data.results.warehouse_transaction_payment_image);

                                new Viewer(document.getElementById('warehouse_transaction_payment_image'), {
                                    inline: false,
                                    toolbar: false
                                });
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
                approveTransaction(warehouse_transaction_id) {
                    $('#modalDetail').modal('hide');

                    Swal.fire({
                        title: 'Perhatian!',
                        text: 'Apakah anda yakin?',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonColor: '#E6EAEE',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tutup'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: window.location.origin + '/admin/service/transaction/approveTransaction',
                                method: 'POST',
                                data: {
                                    warehouse_transaction_id
                                },
                                success: function(response) {
                                    if (response.status == 200) {
                                        $('#modalDetail').modal('hide')
                                        app.generateTable();

                                        app.alert.success.content = response.message;
                                        app.alert.success.status = true;

                                        setTimeout(() => {
                                            app.alert.success.status = false;
                                        }, 5000);
                                    }
                                },
                            });
                        } else {
                            $('#modalDetail').modal();
                        }
                    });


                },
                rejectTransaction(warehouse_transaction_id) {
                    $('#modalDetail').modal('hide');

                    Swal.fire({
                        title: 'Perhatian!',
                        text: 'Apakah anda yakin?',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonColor: '#E6EAEE',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tutup'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: window.location.origin + '/admin/service/transaction/rejectTransaction',
                                method: 'POST',
                                data: {
                                    warehouse_transaction_id
                                },
                                success: function(response) {
                                    if (response.status == 200) {
                                        $('#modalDetail').modal('hide')
                                        app.generateTable();

                                        app.alert.success.content = response.message;
                                        app.alert.success.status = true;

                                        setTimeout(() => {
                                            app.alert.success.status = false;
                                        }, 5000);
                                    }
                                },
                            });
                        } else {
                            $('#modalDetail').modal();
                        }
                    });
                },
            },
            mounted() {
                this.categoryTransaction();
            }
        }).mount("#app");;
</script>