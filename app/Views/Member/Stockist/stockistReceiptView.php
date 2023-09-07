<!-- BEGIN: Content-->
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
                    <button :class="tab == 'warehouse' ? 'btn btn-primary mr-1 mb-1' : 'btn btn-outline-primary mr-1 mb-1'" @click="changeTab('warehouse')">Pembelian Ke Perusahaan</button>
                    <button :class="tab == 'stockist' ? 'btn btn-primary mr-1 mb-1' : 'btn btn-outline-primary mr-1 mb-1'" @click="changeTab('stockist')">Pembelian Ke Stokis</button>
                    <div id="table">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade text-left" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel1"></h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-50 align-center">
                    <div class="col-12 d-flex flex-row align-center p-0">
                        <table class="table w-100" id="table-detail">
                            <thead>
                                <tr>
                                    <th class="text-left">Produk</th>
                                    <th class="text-right">Harga</th>
                                    <th class="text-right">Jumlah</th>
                                    <th class="text-right">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mb-50 align-center" id="modal_pickup">
                    <!-- <div class="col-sm-12">
                        <label for="" class="mb-1">Produk dapat diambil di
                            <a class="text-primary" onclick="window.open('<?= DELIVERY_WAREHOUSE_MAPS ?>', '_blank').focus()"><?= DELIVERY_WAREHOUSE_ADDRESS ?></a></label>
                    </div> -->
                </div>
                <div class="row mb-3 align-center" id="modal_courier">
                    <div class="col-sm-12">
                        <label for="" class="mb-1">Data Pengiriman</label>
                        <div class="row">
                            <div class="col col-6">
                                <label for="text_warehouse_transaction_delivery_receiver_name">Nama Penerima</label>
                                <div id="text_warehouse_transaction_delivery_receiver_name"></div>
                            </div>
                            <div class="col col-6">
                                <label for="text_warehouse_transaction_delivery_receiver_mobilephone">No. HP
                                    Penerima</label>
                                <div id="text_warehouse_transaction_delivery_receiver_mobilephone"></div>
                            </div>
                            <div class="col col-12">
                                <label for="text_warehouse_transaction_delivery_receiver_address">Alamat
                                    Penerima</label>
                                <div>
                                    <span id="text_warehouse_transaction_delivery_receiver_address"></span>,
                                    <span id="text_warehouse_transaction_delivery_receiver_subdistrict_name"></span>,
                                    <span id="text_warehouse_transaction_delivery_receiver_city_name"></span>,
                                    <span id="text_warehouse_transaction_delivery_receiver_province_name"></span>
                                </div>
                            </div>
                            <div class="col col-6">
                                <label for="text_warehouse_transaction_delivery_courier_code">Jasa Kurir</label>
                                <div id="text_warehouse_transaction_delivery_courier_code"></div>
                            </div>
                            <div class="col col-6">
                                <label for="text_warehouse_transaction_delivery_courier_service">Layanan</label>
                                <div id="text_warehouse_transaction_delivery_courier_service"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="showXendit();">
                    <span class="">Bayar</span>
                </button>
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <span class="">Tutup</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<!-- Modal -->
<div class="modal fade text-left" id="modal-xendit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content" style="height: 768px;">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel1"></h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <iframe src="" frameBorder="0" style="width: 100%; height: 100%;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <span class="">Tutup</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

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
                datatable: [],
                tab: "warehouse"
            }
        },
        methods: {
            hideLoading() {
                $("#pageLoader").hide();
            },
            generateTable() {
                $("#table").dataTableLib({
                    url: window.location.origin + '/member/transaction/get-transaction-payment',
                    selectID: 'warehouse_transaction_id',
                    colModel: [{
                            display: "Aksi",
                            name: "warehouse_transaction_id",
                            align: "center",
                            render: (params, data) => {
                                return `<a onclick="app.detail('${params}')" class="cstmHover" title="Bayar" data-toggle="tooltip"> <i class="bx bx-money info"></i> </a>`;
                            },
                        }, {
                            display: "Tanggal",
                            name: "warehouse_transaction_datetime_formatted",
                            align: "left",
                        },
                        {
                            display: "Keterangan",
                            name: "warehouse_transaction_notes",
                            align: "left",
                        }, {
                            display: "Total",
                            name: "warehouse_transaction_total_nett_price",
                            align: "right",
                            render: (params) => {
                                return `${app.formatCurrency(params)}`
                            }
                        },

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
                        name: "warehouse_transaction_datetime",
                        type: "date"
                    }, ],
                    sortName: "warehouse_transaction_datetime",
                    sortOrder: "desc",
                    tableIsResponsive: true,
                    select: false,
                    multiSelect: false,
                    success: (res) => {
                        this.datatable = res.data.results
                        // app.checkRedirect()
                    }
                })
            },
            generateTableStockist() {
                $("#table").dataTableLib({
                    url: window.location.origin + '/member/stockist/get-transaction-payment',
                    selectID: 'stockist_transaction_id',
                    colModel: [{
                            display: "Aksi",
                            name: "stockist_transaction_id",
                            align: "center",
                            render: (params, data) => {
                                return `<a onclick="app.detail('${params}')" class="cstmHover" title="Bayar" data-toggle="tooltip"> <i class="bx bx-money info"></i> </a>`;
                            },
                        }, {
                            display: "Tanggal",
                            name: "stockist_transaction_datetime_formatted",
                            align: "left",
                        },
                        {
                            display: "Keterangan",
                            name: "stockist_transaction_notes",
                            align: "left",
                        }, {
                            display: "Total",
                            name: "stockist_transaction_total_nett_price",
                            align: "right",
                            render: (params) => {
                                return `${app.formatCurrency(params)}`
                            }
                        },

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
                        name: "stockist_transaction_datetime",
                        type: "date"
                    }, ],
                    sortName: "stockist_transaction_datetime",
                    sortOrder: "desc",
                    tableIsResponsive: true,
                    select: false,
                    multiSelect: false,
                    success: (res) => {
                        this.datatable = res.data.results
                        // app.checkRedirect()
                    }
                })
            },
            changeTab(type) {
                this.tab = type
                if (type == "warehouse") {
                    this.generateTable()
                } else {
                    this.generateTableStockist()
                }
            },
            openModal() {
                $('#modalAddUpdate').modal()
            },
            detail(id, data = false) {
                let transaction = {}
                if (this.tab == "warehouse") {
                    transaction = data ? data[0] : this.datatable.find(o => o.warehouse_transaction_id == id)
                } else {
                    transaction = data ? data[0] : this.datatable.find(o => o.stockist_transaction_id == id)
                }

                showModalDetail(transaction, id)
            },
            checkRedirect() {
                let params = new URLSearchParams(window.location.search)
                if (params.get("id")) {
                    $.ajax({
                        url: `<?= BASEURL ?>/member/transaction/get-transaction-payment?id=${params.get("id")}`,
                        type: "GET",
                        success: (res) => {
                            data = res.data.results
                            showModalDetail(data[0], data[0].warehouse_transaction_id)
                        },
                        error: () => {
                            Swal.fire({
                                title: 'Transaksi tidak ditemukan / belum upload bukti transaksi.',
                                icon: 'error',
                                showCancelButton: true,
                                cancelButtonText: `<span style="color: #475F7B;">Tutup</span>`,
                                cancelButtonColor: '#E6EAEE',
                                showConfirmButton: false,
                            })
                        }
                    })
                }
            },
            formatCurrency(params) {
                let formatter = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                })
                return formatter.format(params)
            }
        }
    }).mount('#app');

    function showXendit(params) {
        $("#modal-detail").modal("hide")
        $("#modal-xendit").modal("show")
    }

    function showModalDetail(transaction, id) {
        $("iframe").attr("src", app.tab == "warehouse" ? transaction.warehouse_transaction_payment_invoice_url : transaction.stockist_transaction_payment_invoice_url)
        // $('#payment_url').attr('href', transaction.warehouse_transaction_payment_invoice_url)
        let total = 0
        $("#table-detail tbody").empty()
        $.each(transaction.detail, (i, val) => {
            $("#table-detail tbody").append(`
            <tr class="">
                <td class="text-left">${app.tab == "warehouse" ? val.warehouse_transaction_detail_product_name : val.stockist_transaction_detail_product_name}</td>
                <td class="text-right">${app.formatCurrency(app.tab == "warehouse" ? val.warehouse_transaction_detail_unit_price : val.stockist_transaction_detail_unit_price)}</td>
                <td class="text-right">${app.tab == "warehouse" ? val.warehouse_transaction_detail_quantity : val.stockist_transaction_detail_quantity}</td>
                <td class="text-right">${app.formatCurrency(parseInt(app.tab == "warehouse" ? val.warehouse_transaction_detail_quantity : val.stockist_transaction_detail_quantity) * parseInt(app.tab == "warehouse" ? val.warehouse_transaction_detail_unit_price : val.stockist_transaction_detail_unit_price))}</td>
            </tr>
            `)
            total += parseInt(app.tab == "warehouse" ? val.warehouse_transaction_detail_quantity : val.stockist_transaction_detail_quantity) * parseInt(app.tab == "warehouse" ? val.warehouse_transaction_detail_unit_price : val.stockist_transaction_detail_unit_price)
        })
        $("#table-detail tbody").append(`
        <tr class="">
            <th class="text-left" colspan="3">Total</th>
            <th class="text-right">${app.formatCurrency(total)}</th>
        </tr>
        <tr class="">
            <td class="text-left" colspan="3">Ongkir</td>
            <td class="text-right">${app.formatCurrency(app.tab == "warehouse" ? transaction.warehouse_transaction_delivery_cost : transaction.stockist_transaction_delivery_cost)}</td>
        </tr>
        <tr class="">
            <th class="text-left" colspan="3">Jumlah Transfer</th>
            <th class="text-right">${app.formatCurrency(parseInt(app.tab == "warehouse" ? transaction.warehouse_transaction_delivery_cost : transaction.stockist_transaction_delivery_cost) + parseInt(total))}</th>
        </tr>
        `)
        if (transaction.warehouse_transaction_delivery_method == "courier") {
            console.log(app.tab)
            $("#modal_pickup").hide()
            $("#modal_courier").show()
            $("#text_warehouse_transaction_delivery_receiver_name").html(app.tab == "warehouse" ? transaction.warehouse_transaction_delivery_receiver_name : transaction.stockist_transaction_delivery_receiver_name)
            $("#text_warehouse_transaction_delivery_receiver_mobilephone").html(app.tab == "warehouse" ? transaction.warehouse_transaction_delivery_receiver_mobilephone : transaction.stockist_transaction_delivery_receiver_mobilephone)
            $("#text_warehouse_transaction_delivery_receiver_address").html(app.tab == "warehouse" ? transaction.warehouse_transaction_delivery_receiver_address : transaction.stockist_transaction_delivery_receiver_address)
            $("#text_warehouse_transaction_delivery_receiver_province_name").html(app.tab == "warehouse" ? transaction.warehouse_transaction_delivery_receiver_province_name : transaction.stockist_transaction_delivery_receiver_province_name)
            $("#text_warehouse_transaction_delivery_receiver_city_name").html(app.tab == "warehouse" ? transaction.warehouse_transaction_delivery_receiver_city_name : transaction.stockist_transaction_delivery_receiver_city_name)
            $("#text_warehouse_transaction_delivery_receiver_subdistrict_name").html(app.tab == "warehouse" ? transaction.warehouse_transaction_delivery_receiver_subdistrict_name : transaction.stockist_transaction_delivery_receiver_subdistrict_name)
            $("#text_warehouse_transaction_delivery_courier_code").html(app.tab == "warehouse" ? transaction.warehouse_transaction_delivery_courier_code.toUpperCase() : transaction.stockist_transaction_delivery_courier_code.toUpperCase())
            $("#text_warehouse_transaction_delivery_courier_service").html(app.tab == "warehouse" ? transaction.warehouse_transaction_delivery_courier_service : transaction.stockist_transaction_delivery_courier_service)
        } else {
            $("#modal_pickup").show()
            $("#modal_courier").hide()
        }
        $("#img_transaction_payment_image").prop("src", app.tab == "warehouse" ? transaction.warehouse_transaction_payment_image : transaction.stockist_transaction_payment_image)
        $("#btn_upload").data("id", id)
        $("#modal-detail .modal-title").html(`Detail Transaksi ${app.tab == "warehouse" ? transaction.warehouse_transaction_code : transaction.stockist_transaction_code}`)
        $("#modal-detail").modal("show")
    }

    $("#input_warehouse_transaction_payment_image").on("change", (ev) => {
        const [file] = ev.target.files
        if (file) {
            $("#img_transaction_payment_image").prop("src", URL.createObjectURL(file))
            $("#btn_upload").prop("disabled", false)
        }
    })

    $("#payment_url").on("click", () => {
        app.generateTable()
        $("#modal-detail").modal("hide")
    })

    $("#btn_upload").on("click", (ev) => {
        let data = new FormData()
        data.append("warehouse_transaction_payment_image", $("#input_warehouse_transaction_payment_image").prop("files")[0])
        data.append("warehouse_transaction_id", $("#btn_upload").data("id"))
        $.ajax({
            url: "<?= BASEURL ?>/member/transaction/upload-transaction-payment",
            type: "POST",
            processData: false,
            contentType: false,
            data: data,
            success: (res) => {
                data = res.data.results
                $(".alert-input").hide()
                $("#alert-success").html(res.message)
                $("#alert-success").show()
                setTimeout(function() {
                    $("#alert-success").hide()
                }, 3000);
                $("#modal-detail").modal("hide")
                app.generateTable()
            },
            error: (err) => {
                res = err.responseJSON
                $(".alert-input").hide()
                $("#alert").html(res.message)
                $("#alert").show()
                setTimeout(function() {
                    $("#alert").hide()
                }, 3000);
                if (res.error == "validation") {
                    $.each(res.data, (i, val) => {
                        $(`#alert_input_${i}`).html(val).show()
                    })
                    setTimeout(function() {
                        $(".alert-input").hide()
                    }, 3000);
                }
            },
        })
    })
</script>