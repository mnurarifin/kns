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
                    url: window.location.origin + '/member/stockist/get-stock',
                    selectID: 'stockist_product_stock_id',
                    colModel: [{
                        display: "Kode Produk",
                        name: "stockist_product_stock_product_code",
                        align: "left",
                    }, {
                        display: "Nama Produk",
                        name: "stockist_product_stock_product_name",
                        align: "left",
                    }, {
                        display: "Harga / Produk",
                        name: "stockist_product_stock_product_price_formatted",
                        align: "right",
                    }, {
                        display: "Jumlah Stok",
                        name: "stockist_product_stock_balance",
                        align: "center",
                    }],
                    buttonAction: [],
                    options: {
                        limit: [10, 25, 50, 100],
                        currentLimit: 10,
                    },
                    search: true,
                    searchTitle: 'Pencarian',
                    searchItems: [{
                        display: "Kode Produk",
                        name: "stockist_product_stock_product_code",
                        type: "text"
                    }, {
                        display: "Nama Produk",
                        name: "stockist_product_stock_product_name",
                        type: "text"
                    }],
                    sortName: "stockist_product_stock_id",
                    sortOrder: "desc",
                    tableIsResponsive: true,
                    select: false,
                    multiSelect: false,
                })
            },
        }
    }).mount('#app');
</script>