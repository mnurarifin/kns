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
                            <div class="col-12">
                                <div class="alert alert-success " v-show="alert.success.status" style="display: none;">
                                    <span v-html="alert.success.content"></span>
                                </div>
                            </div>
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
        window.history.pushState("", "", '/admin/member/stock');
        app.generateMessageTable('');
    });

    let app =
        Vue.createApp({
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
                            btnTitle: "Tambah",
                            btnAction: "insert",
                        },
                        form: {
                            product_name: '',
                            warehouse_name: '',
                            warehouse_product_stock_balance: 0,
                            balance: 0,
                        },
                        action: {}
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
                    tab: {
                        current: 'active'
                    }
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },

                changeTab(type) {
                    this.tab.current = type;
                    this.generateMessageTable();
                },

                generateMessageTable() {
                    let type = this.tab.current == 'active' ? 1 : 0;

                    $("#table-stock").dataTableLib({
                        url: window.location.origin + '/admin/service/member/getDataStock/' + type,
                        selectID: 'member_product_stock_id',
                        colModel: [{
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
                                display: 'Jumlah Stok',
                                name: 'member_product_stock_balance',
                                sortAble: false,
                                align: 'right'
                            },
                        ],
                        options: {
                            limit: [10, 15, 20],
                            currentLimit: 10,
                        },
                        search: true,
                        searchTitle: 'Pencarian Data Stok Mitra',
                        searchItems: [{
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
                                display: 'Username',
                                name: 'member_username',
                                type: 'text'
                            },
                        ],
                        sortName: "member_product_stock_id",
                        sortOrder: "ASC",
                        tableIsResponsive: true,
                        select: true,
                        multiSelect: true,
                        buttonAction: []
                    });
                    $('#add-stock').show();
                },
            },
            mounted() {
                this.hideLoading();
            }
        }).mount("#stock");

    function addStock() {
        app.addStock();
    }
</script>