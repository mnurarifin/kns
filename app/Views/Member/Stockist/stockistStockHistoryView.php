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
                <div class="col col-4 col-md-4 mb-1">
                    <div class="card p-1">
                        <p>Total Stok Masuk :</p>
                        <h3 id="total_in" class="text-success">0</h3>
                    </div>
                </div>
                <div class="col col-4 col-md-4 mb-1">
                    <div class="card p-1">
                        <p>Total Stok Keluar :</p>
                        <h3 id="total_out" class="text-danger">0</h3>
                    </div>
                </div>
                <div class="col col-4 col-md-4 mb-1">
                    <div class="card p-1">
                        <p>Total Sisa Stok :</p>
                        <h3 id="total_balance" class="text-warning">0</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col col-12 col-md-12 mb-1">
                    <div id="table">
                    </div>
                </div>
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
                    url: window.location.origin + '/member/stockist/get-stock-log',
                    selectID: 'stockist_product_stock_log_id',
                    colModel: [{
                            display: "Tanggal",
                            name: "stockist_product_stock_log_datetime",
                            align: "left",
                        }, {
                            display: "Produk",
                            name: "stockist_product_stock_log_product_name",
                            align: "left",
                            render: (params, args) => {
                                return `<span>${args.stockist_product_stock_log_product_code} <br> ${args.stockist_product_stock_log_product_name}</span>`;
                            }
                        }, {
                            display: "Tujuan",
                            name: "network_code",
                            align: "left",
                            render: (params, columns) => {
                                let tujuan = columns.network_code == '' ? '-' : columns.network_code;

                                return `<span style="color:blue">${tujuan}</span>`;
                            },
                        }, {
                            display: "Status",
                            name: "stockist_product_stock_log_type",
                            align: "center",
                            render: (params) => {
                                return params == 'in' ? `<span class="badge badge-success rounded-pill">Masuk</span>` : `<span class="badge badge-danger rounded-pill">Keluar</span`
                            }
                        },
                        // {
                        //     display: "Jumlah",
                        //     name: "stockist_product_stock_log_quantity",
                        //     align: "center",
                        // },
                        {
                            display: "Total Stok Masuk",
                            name: "stockist_product_stock_log_type",
                            align: "center",
                            render: (params, data) => {
                                return params == 'in' ? data.stockist_product_stock_log_quantity : 0;
                            }
                        },
                        {
                            display: "Total Stok Keluar",
                            name: "stockist_product_stock_log_type",
                            align: "center",
                            render: (params, data) => {
                                return params == 'out' ? data.stockist_product_stock_log_quantity : 0;
                            }
                        },
                        {
                            display: "Sisa Stok",
                            name: "stockist_product_stock_log_balance",
                            align: "center",
                        }, {
                            display: "Keterangan",
                            name: "stockist_product_stock_log_note",
                            align: "left",
                        }
                    ],
                    buttonAction: [],
                    options: {
                        limit: [10, 25, 50, 100],
                        currentLimit: 10,
                    },
                    search: true,
                    searchTitle: 'Pencarian',
                    searchItems: [{
                        display: "Tanggal",
                        name: "stockist_product_stock_log_datetime",
                        type: "date"
                    }, {
                        display: "Kode Produk",
                        name: "stockist_product_stock_log_product_code",
                        type: "text"
                    }, {
                        display: "Nama Produk",
                        name: "stockist_product_stock_log_product_name",
                        type: "text"
                    }, {
                        display: "Status",
                        name: "stockist_product_stock_log_type",
                        type: "select",
                        option: [{
                            value: "in",
                            title: "Masuk",
                        }, {
                            value: "out",
                            title: "Keluar",
                        }],
                    }],
                    sortName: "stockist_product_stock_log_datetime",
                    sortOrder: "desc",
                    tableIsResponsive: true,
                    select: false,
                    multiSelect: false,
                    buttonAction: [{
                        display: 'Export Excel',
                        icon: 'bx bxs-file',
                        style: 'btnExcel text-white',
                        action: 'exportExcel',
                        url: window.location.origin + "/member/stockist/excelStockistStockHistory"
                    }],
                    success: (resp) => {
                        $('#total_in').html(resp.data.total_in);
                        $('#total_out').html(resp.data.total_out);
                        $('#total_balance').html(resp.data.total_in - resp.data.total_out);
                    }
                })
            },
        }
    }).mount('#app');
</script>