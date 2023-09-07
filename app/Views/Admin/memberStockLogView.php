<section id="stock">
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
                            <div class="col-12 mt-0">
                                <div id="table-stock"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://unpkg.com/vue@next"></script>
<script type="text/javascript" defer>
    $(document).ready(function() {
        app.generateMessageTable();
    });

    let app =
        Vue.createApp({
            data: function() {
                return {}
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },
                generateMessageTable() {
                    $("#table-stock").dataTableLib({
                        url: window.location.origin + '/admin/service/member/getDataStockLog/',
                        selectID: 'member_product_stock_log_id',
                        colModel: [{
                                display: 'Tanggal',
                                name: 'member_product_stock_log_datetime_formatted',
                                sortAble: true,
                                align: 'left'
                            },
                            {
                                display: 'Username',
                                name: 'member_username',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Mitra',
                                name: 'member_name',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Nama Produk',
                                name: 'product_name',
                                sortAble: true,
                                align: 'left'
                            },
                            {
                                display: 'Tipe',
                                name: 'member_product_stock_log_type',
                                sortAble: false,
                                align: 'left',
                                render: (params) => {
                                    let type = params == 'in' ? 'Masuk' : 'Keluar'
                                    let cls = params == 'in' ? 'success' : 'danger'
                                    return '<span class="badge badge-light-' + cls + ' badge-pill">' + type + '</span>'
                                }
                            },
                            {
                                display: 'Jumlah',
                                name: 'member_product_stock_log_quantity',
                                sortAble: false,
                                align: 'right'
                            },
                            {
                                display: 'Jumlah Stok Terakhir',
                                name: 'member_product_stock_log_balance',
                                sortAble: false,
                                align: 'right'
                            },
                            {
                                display: 'Keterangan',
                                name: 'member_product_stock_log_note',
                                sortAble: false,
                                align: 'left'
                            },
                        ],
                        options: {
                            limit: [10, 15, 20],
                            currentLimit: 10,
                        },
                        search: true,
                        searchTitle: 'Pencarian Data Riwayat Stok Mitra',
                        searchItems: [{
                                display: 'Tanggal',
                                name: 'member_product_stock_log_datetime',
                                type: 'date'
                            },
                            {
                                display: 'Nama Produk',
                                name: 'product_name',
                                type: 'text'
                            },
                            {
                                display: 'Mitra',
                                name: 'member_name',
                                type: 'text'
                            },
                            {
                                display: 'Tipe',
                                name: 'member_product_stock_log_type',
                                type: 'select',
                                option: [{
                                    title: 'Masuk',
                                    value: 'in'
                                }, {
                                    title: 'Keluar',
                                    value: 'out'
                                }]
                            },
                            {
                                display: 'Keterangan',
                                name: 'member_product_stock_log_note',
                                type: 'text'
                            },
                        ],
                        sortName: "member_product_stock_log_datetime",
                        sortOrder: "DESC",
                        tableIsResponsive: true,
                        select: false,
                        multiSelect: false,
                        buttonAction: []
                    });
                    $('#add-stock').show();
                },
            },
            mounted() {
                this.hideLoading();
            }
        }).mount("#stock");
</script>