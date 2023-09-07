<!-- BEGIN: Content-->
<div class="app-content content" id="app">
    <div class="content-overlay">
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
                <div class="col-md-12 col-12">
                    <div class="mb-1" id="table-penjualan">
                    </div>

                    <div class="card p-2" id="data_kosong">
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <img src="<?= BASEURL ?>/app-assets/images/no-data-green.svg" alt="style=" filter: grayscale(100%);">
                            </div>
                            <div class="col-md-12 d-flex justify-content-center mt-3">
                                <label>Tidak ada informasi yang ditampilkan</label>
                            </div>
                        </div>
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
                        <table class="table w-100 d-none" id="table-detail">
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
                        <table class="table w-100 d-none" id="table-detail-seller">
                            <thead>
                                <tr>
                                    <th class="text-left">Produk</th>
                                    <th class="text-right">Harga</th>
                                    <th class="text-right">Jumlah</th>
                                    <th class="text-right">Poin BV</th>
                                    <th class="text-right">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mb-50 align-center d-none" id="modal_pickup">
                    <div class="col-sm-12">
                        <label for="" class="mb-1">Produk dapat diambil di
                            <a class="text-primary" onclick="window.open('<?= DELIVERY_WAREHOUSE_MAPS ?>', '_blank').focus()"><?= DELIVERY_WAREHOUSE_ADDRESS ?></a></label>
                    </div>
                </div>
                <div class="row mb-3 align-center d-none" id="modal_courier">
                    <div class="col-sm-12">
                        <label for="" class="mb-1">Data Penerima</label>
                        <div class="row">
                            <div class="col col-6">
                                <label for="text_warehouse_transaction_delivery_receiver_name">Nama</label>
                                <div id="text_warehouse_transaction_delivery_receiver_name"></div>
                            </div>
                            <div class="col col-6">
                                <label for="text_warehouse_transaction_delivery_receiver_mobilephone">No. HP</label>
                                <div id="text_warehouse_transaction_delivery_receiver_mobilephone"></div>
                            </div>
                            <div class="col col-6">
                                <label for="text_warehouse_transaction_delivery_receiver_address">Alamat</label>
                                <div>
                                    <span id="text_warehouse_transaction_delivery_receiver_address"></span>,
                                    <span id="text_warehouse_transaction_delivery_receiver_subdistrict_name"></span>,
                                    <span id="text_warehouse_transaction_delivery_receiver_city_name"></span>,
                                    <span id="text_warehouse_transaction_delivery_receiver_province_name"></span>
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
<!-- End Modal -->

<script>
    $(function() {
        let datatableSeller = []

        $("#data_kosong").hide()

        getTransactionSeller = () => {
            $("#table-penjualan").dataTableLib({
                url: window.location.origin + "/member/transaction/get-transaction-seller-history/",
                selectID: "stockist_transaction_id",
                colModel: [{
                    display: "Aksi",
                    name: "stockist_transaction_id",
                    align: "center",
                    render: (params, data) => {
                        return `<a onclick="showModalDetailSeller(${params})" title="Detail" data-toggle="tooltip" class="cstmHover"> <i class="bx bx-book info"></i> </a>`
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
                    display: "Status",
                    name: "stockist_transaction_status",
                    align: "center",
                    render: (params) => {
                        switch (params) {
                            case 'pending':
                                return `<span class="badge badge-warning rounded-pill">Menunggu Pembayaran</span>`
                                break;

                            case 'paid':
                                return `<span class="badge badge-info rounded-pill">Menunggu Diambil</span>`
                                break;

                            case 'void':
                                return `<span class="badge badge-danger rounded-pill">Ditolak</span>`
                                break;

                            case 'expired':
                                return `<span class="badge badge-danger rounded-pill">Kedaluwarsa</span>`
                                break;

                            case 'complete':
                                return `<span class="badge badge-success rounded-pill">Transaksi Selesai</span>`
                                break;

                            case 'delivered':
                                return `<span class="badge badge-info rounded-pill">Dikirim</span>`
                                break;
                        }
                    }
                }, {
                    display: "Nama Pembeli",
                    name: "stockist_transaction_buyer_name",
                    align: "left",
                }, {
                    display: "No. Telpon Penerima",
                    name: "stockist_transaction_delivery_receiver_mobilephone",
                    align: "left",
                }, {
                    display: "Keterangan",
                    name: "stockist_transaction_notes",
                    align: "left",
                }],
                options: {
                    limit: [10, 15, 20, 50, 100],
                    currentLimit: 10,
                },
                search: true,
                searchTitle: "Pencarian",
                searchItems: [{
                    display: "Tanggal",
                    name: "stockist_transaction_datetime",
                    type: "date"
                }],
                sortName: "stockist_transaction_datetime",
                sortOrder: "DESC",
                tableIsResponsive: true,
                select: false,
                multiSelect: false,
                buttonAction: [],
                success: (res) => {
                    datatableSeller = res.data.results
                }
            })
        }

        showModalDetailSeller = (id) => {
            let transaction = datatableSeller.find(o => o.stockist_transaction_id == id)
            let total = 0
            $("#table-detail-seller tbody").empty()
            $.each(transaction.detail, (i, val) => {
                $("#table-detail-seller tbody").append(`
                <tr class="">
                    <td class="text-left">${val.stockist_transaction_detail_product_name}</td>
                    <td class="text-right">${formatCurrency(val.stockist_transaction_detail_unit_price)}</td>
                    <td class="text-right">${val.stockist_transaction_detail_quantity}</td>
                    <td class="text-right">${val.product_bv * val.stockist_transaction_detail_quantity}</td>
                    <td class="text-right">${formatCurrency(parseInt(val.stockist_transaction_detail_quantity) * parseInt(val.stockist_transaction_detail_unit_price))}</td>
                </tr>
                `)
                total += parseInt(val.stockist_transaction_detail_quantity) * parseInt(val.stockist_transaction_detail_unit_price)
            })
            $("#table-detail-seller tbody").append(`
            <tr class="">
                <th class="text-left" colspan="4">Total Transaksi</th>
                <th class="text-right">${formatCurrency(total)}</th>
            </tr>
            `)

            $("#modal_courier").show()
            $("#text_warehouse_transaction_delivery_receiver_name").html(transaction.stockist_transaction_buyer_name + ` (${transaction.stockist_transaction_buyer_member_username})`)
            $("#text_warehouse_transaction_delivery_receiver_mobilephone").html(transaction.stockist_transaction_buyer_member_mobilephone)
            $("#text_warehouse_transaction_delivery_receiver_address").html(transaction.stockist_transaction_buyer_member_address)
            $("#text_warehouse_transaction_delivery_receiver_province_name").html(transaction.stockist_transaction_buyer_member_province_name)
            $("#text_warehouse_transaction_delivery_receiver_city_name").html(transaction.stockist_transaction_buyer_member_city_name)
            $("#text_warehouse_transaction_delivery_receiver_subdistrict_name").html(transaction.stockist_transaction_buyer_member_subdistrict_name)

            $("#transfer-prove").hide()
            $("#modal-detail .modal-title").html(`Detail Transaksi ${transaction.stockist_transaction_code}`)
            $("#table-detail").addClass('d-none')
            $("#table-detail-seller").removeClass('d-none')
            $("#modal-detail").modal("show")
        }

        formatCurrency = ($params) => {
            let formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            })
            return formatter.format($params)
        }

        getTransactionSeller()
    })
</script>
<!-- END: Content-->