<script src="https://unpkg.com/vue@next"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .activMenu {
        background-color: #719df0 !important;
        color: #fff !important;
        border-color: #5A8DEE !important;
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
                        <div class="col-12">
                            <div class="alert alert-success " v-show="alert.success.status" style="display: none;">
                                <span v-html="alert.success.content"></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div id="table-app"> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    let app =
        Vue.createApp({
            data: function() {
                return {
                    loading: false,
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
                generateTable() {
                    $("#table-app").dataTableLib({
                        url: window.location.origin + '/admin/service/serial/getDataSalesSerial/sys/',
                        selectID: 'serial_id',
                        colModel: [{
                                display: 'Serial',
                                name: 'serial_id',
                                sortAble: false,
                                export: true
                            },
                            {
                                display: 'Pin',
                                name: 'serial_pin',
                                sortAble: false,
                                export: true
                            },
                            {
                                display: 'Digunakan',
                                name: 'serial_is_used',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    return params == '1' ? '<i class="bx bxs-check-circle success" title="Ya" data-toggle="tooltip"></i>' : '<i class="bx bxs-x-circle danger" title="Tidak" data-toggle="tooltip"></i>'
                                },
                                export: true
                            },
                            {
                                display: 'Kode Pembeli',
                                name: 'buyer_network_code',
                                sortAble: true,
                                export: true
                            },
                            {
                                display: 'Nama Pembeli',
                                name: 'buyer_name',
                                sortAble: true,
                                export: true
                            },
                            {
                                display: 'Tanggal Pembelian',
                                name: 'buy_datetime',
                                sortAble: false,
                                export: true
                            },
                            {
                                display: 'Kode Pemilik',
                                name: 'owner_network_code',
                                sortAble: true,
                                export: true
                            },
                            {
                                display: 'Nama Pemilik',
                                name: 'owner_name',
                                sortAble: true,
                                export: true
                            },
                        ],
                        options: {
                            limit: [10, 15, 20],
                            currentLimit: 10,
                        },
                        search: true,
                        searchTitle: 'Pencarian Data Serial',
                        searchItems: [{
                                display: 'Serial',
                                name: 'serial_id',
                                type: 'text'
                            },
                            {
                                display: 'Digunakan',
                                name: 'serial_is_used',
                                type: 'select',
                                option: [{
                                    title: 'YA',
                                    value: '1'
                                }, {
                                    title: 'TIDAK',
                                    value: '0'
                                }, ]
                            },
                            {
                                display: 'Kode Pembeli',
                                name: 'buyer_network_code',
                                type: 'text'
                            },
                            {
                                display: 'Nama Pembeli',
                                name: 'buyer_name',
                                type: 'text'
                            },
                            {
                                display: 'Kode Pemilik',
                                name: 'owner_network_code',
                                type: 'text'
                            },
                            {
                                display: 'Nama Pemilik',
                                name: 'owner_name',
                                type: 'text'
                            },
                        ],
                        sortName: "serial_sold_datetime",
                        sortOrder: "desc",
                        tableIsResponsive: true,
                        select: false,
                        multiSelect: false,
                        buttonAction: []
                    });
                },
                hideLoading() {
                    $("#pageLoader").hide();
                },
            },
            mounted() {
                this.hideLoading();
            },
        }).mount("#app");

    $(document).ready(function() {
        // Initiate Function
        app.generateTable();
    });
</script>