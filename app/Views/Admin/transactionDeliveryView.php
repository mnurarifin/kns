<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>

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
                                                    <th scope="col" class="text-right">Harga Satuan</th>
                                                    <th scope="col" class="text-right">Harga Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="item in detail.warehouse_transaction_detail" @key="item.warehouse_transaction_detail_id">
                                                    <td>{{item.warehouse_transaction_detail_product_code}}</td>
                                                    <td>{{item.warehouse_transaction_detail_product_name}}</td>
                                                    <td>{{item.warehouse_transaction_detail_quantity}}</td>
                                                    <td class="text-right">{{formatCurrency(parseInt(item.warehouse_transaction_detail_unit_nett_price))}}</td>
                                                    <td class="text-right">{{formatCurrency(parseInt(item.warehouse_transaction_detail_unit_nett_price) * parseInt(item.warehouse_transaction_detail_quantity))}}</td>
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
                                                        <h6 class="card-title text-primary text-right">{{formatCurrency(detail.warehouse_transaction_total_nett_price)}}</h6>
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

    <div id="modal-print" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                </div>
                <div class="modal-body p-2" style="min-height: 550px;">
                    <div id="preview"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModalPrint" data-dismiss="modal-print">Close</button>
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
                    modal_detail: {},
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
                    form: {},
                    detail: {},
                    type: `<?= $type ?>`
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },
                generateTable() {
                    $("#table").dataTableLib({
                        url: window.location.origin +
                            '/admin/service/transaction/getDataTransaction/<?= $type ?>/delivery',
                        selectID: 'warehouse_transaction_id',
                        colModel: [{
                                display: 'Aksi',
                                name: 'warehouse_transaction_id',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    return `
                                    <a onclick="app.printAlamat('${params}')" title="Print" data-toggle="tooltip" class="cstmHover d-none d-lg-inline-block"> <i class="bx bx-receipt warning"></i> </a>
                                    <a onclick="app.printAlamatJS('${params}')" title="PrintJS" data-toggle="tooltip" class="cstmHover d-inline-block d-lg-none"> <i class="bx bx-receipt warning"></i> </a>
                                    <a onclick="app.detailTransaction('${params}')" class="cstmHover" title="Detail" data-toggle="tooltip"> <i class="bx bx-book info"></i> </a>`;
                                }
                            }, {
                                display: 'Tanggal',
                                name: 'warehouse_transaction_datetime_formatted',
                                sortAble: true,
                                align: 'left'
                            },
                            {
                                display: 'Kode',
                                name: 'warehouse_transaction_code',
                                sortAble: true,
                                align: 'left'
                            },
                            {
                                display: 'Nama Pembeli',
                                name: 'warehouse_transaction_buyer_name',
                                sortAble: true,
                                align: 'left'
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

                                        case 'Dibayar':
                                            return `<span class="badge badge-light-info badge-pill">${params}</span>`
                                            break;

                                        case 'Selesai':
                                            return `<span class="badge badge-light-success badge-pill">${params}</span>`
                                            break;

                                        default:
                                            return `<span class="badge badge-light-info badge-pill">${params}</span>`
                                            break;
                                    }
                                },
                            },
                            {
                                display: 'Keterangan',
                                name: 'warehouse_transaction_buyer_name',
                                sortAble: true,
                                align: 'center',
                                export: true,
                                render: (params, args) => {
                                    return args.warehouse_transaction_delivery_method == 'pickup' ? '-' : args.warehouse_transaction_delivery_awb == '' ? '-' : 'No Resi: ' + args.warehouse_transaction_delivery_awb
                                }
                            },
                            {
                                display: 'Total',
                                name: 'warehouse_transaction_total_nett_price_formatted',
                                sortAble: true,
                                align: 'right'
                            },
                            {
                                display: 'Resi',
                                name: 'warehouse_transaction_awb',
                                sortAble: false,
                                align: 'center',
                                render: (params, args) => {
                                    return args.warehouse_transaction_delivery_method == 'pickup' ? `<span>-</span>` : `<span>${params}</span>`
                                }
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
                                display: 'Kode Transaksi',
                                name: 'warehouse_transaction_code',
                                type: 'text'
                            },
                            {
                                display: 'Nama Pembeli',
                                name: 'warehouse_transaction_buyer_name',
                                type: 'text'
                            },
                        ],
                        sortName: "warehouse_transaction_code",
                        sortOrder: "ASC",
                        tableIsResponsive: true,
                        select: true,
                        multiSelect: true,
                        buttonAction: [{
                            display: 'Diterima',
                            icon: 'bx bx-check',
                            style: "success",
                            action: "accept",
                            message: 'menerima',
                            url: window.location.origin + "/admin/service/transaction/approved"
                        }, ]
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

                                if (response.data.results.transaction_bank_transfer_attachment) {
                                    $('#transaction_bank_transfer_attachment').prop('src', response.data
                                        .results.transaction_bank_transfer_attachment);
                                } else {
                                    $('#transaction_bank_transfer_attachment').prop('src',
                                        '<?php echo base_url(); ?>/no-image.png');
                                }
                            }
                        },

                    });
                },
                printAlamat(warehouse_transaction_id) {
                    if (!warehouse_transaction_id) {
                        warehouse_transaction_id = app.detail.warehouse_transaction_id
                    }
                    $.ajax({
                        url: window.location.origin + '/admin/service/transaction/getDetailTransaction',
                        method: 'GET',
                        data: {
                            id: warehouse_transaction_id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data.results
                                let detail = '';
                                let total = 0;

                                $.each(data.warehouse_transaction_detail, (key, val) => {
                                    detail += `
                                    <tr>
                                        <td style="padding-left: 10px;">${val.warehouse_transaction_detail_product_code}</td>
                                        <td style="padding-left: 10px; width: 200px;">${val.warehouse_transaction_detail_product_name}</td>
                                        <td align="center">${val.warehouse_transaction_detail_quantity}</td>
                                        <td align="right" style="padding-right: 10px;">Rp ${val.warehouse_transaction_detail_unit_price_formatted}</td>
                                        <td align="right" style="padding-right: 10px;">Rp ${val.warehouse_transaction_detail_unit_nett_price_formatted}</td>
                                    </tr>
                                    `
                                    total += parseInt(val.warehouse_transaction_detail_unit_price) * parseInt(val.warehouse_transaction_detail_quantity);
                                })

                                detail = `<table width="100%" border="1">
                                    <thead>
                                        <tr>
                                            <th style="text-align: left;padding: 10px;">Kode Produk</th>
                                            <th align="left" style="padding-left: 10px;">Nama Produk</th>
                                            <th>Qty</th>
                                            <th align="right" style="padding-right: 10px;">Harga Satuan</th>
                                            <th align="right" style="padding-right: 10px;">Harga Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${detail}
                                        <tr>
                                            <th align="right" style="padding-right: 10px;" colspan="4">Ongkir</th>
                                            <td align="right" style="padding-right: 10px;">Rp ${data.warehouse_transaction_delivery_cost_formatted}</td>
                                        </tr>
                                        <tr>
                                            <th align="right" style="padding-right: 10px;" colspan="4">Total</th>
                                            <td align="right" style="padding-right: 10px;">Rp ${app.formatDecimal(parseInt(data.warehouse_transaction_delivery_cost) + parseInt(total))}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                `

                                let html_print = `
                                <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: 210mm 210mm;
            margin: 0mm;
        }
        @media print {
            body {
                width: 768;
                height: 768;
            }
        }
    </style>
</head>

<body style="font-family: 'Open Sans', sans-serif; border: 3px dashed #000; margin: 8px; padding: 8px; height: calc(100vh - 38px);">
    <div id="divName" style="">
    <div style="
    align-items: center;
    justify-content: space-between;
    display: flex;
">
        <img class="logo logo-expanded" src="<?= BASEURL ?>/logo.png" style="width :100px; height:auto;opacity: .7;display: block; left: 45px; top: -20px;">
        <span>Tanggal Transaksi : <b>${data.warehouse_transaction_datetime_formatted}</b></span>
    </div>
        <table style="width:100%">
            <colgroup>
                <col style="width: 15%;">
                <col style="width: 4%;">
                <col style="width: 30%;">
                <col style="width: 2%;">
                <col style="width: 15%;">
                <col style="width: 4%;">
                <col style="width: 30%;">
            </colgroup>
            <thead>
                <tr>
                    <th style="font-weight: 700; font-size: 18px; text-align: left; padding-bottom: 24px; vertical-align: top;" colspan="3">Data Pengirim</th>
                    <th></th>
                    <th style="font-weight: 700; font-size: 18px; text-align: left; padding-bottom: 24px; vertical-align: top;" colspan="3">Data Penerima</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="font-weight: 400; font-size: 18px; text-align: left; padding-bottom: 24px; vertical-align: top;">Nama Pengirim</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px; vertical-align: top;">:</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px; vertical-align: top;"><?= COMPANY_NAME ?></th>
                    <th></th>
                    <th style="font-weight: 400; font-size: 18px; text-align: left; padding-bottom: 24px; vertical-align: top;">Nama Penerima</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px; vertical-align: top;">:</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px; vertical-align: top;">${data.warehouse_transaction_delivery_receiver_name}${(data.network_code) ? '<br>( ' + data.network_code + ' )' : ''}</th>
                </tr>
                <tr>
                    <th style="font-weight: 400; font-size: 18px; text-align: left; padding-bottom: 24px; vertical-align: top;">No. HP Pengirim</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px; vertical-align: top;">:</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px; vertical-align: top;"><?= WA_CS_NUMBER ?></th>
                    <th></th>
                    <th style="font-weight: 400; font-size: 18px; text-align: left; padding-bottom: 24px; vertical-align: top;">No. HP Penerima</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px; vertical-align: top;">:</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px; vertical-align: top;">${data.warehouse_transaction_delivery_receiver_mobilephone}</th>
                </tr>
                <tr>
                    <th style="font-weight: 400; font-size: 18px; text-align: left; padding-bottom: 24px; vertical-align: top;">Alamat Pengirim</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px; vertical-align: top;">:</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px; vertical-align: top;"><?= DELIVERY_WAREHOUSE_ADDRESS ?></th>
                    <th></th>
                    <th style="font-weight: 400; font-size: 18px; text-align: left; padding-bottom: 24px; vertical-align: top;">Alamat Penerima</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px; vertical-align: top;">:</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px; vertical-align: top;">${data.warehouse_transaction_delivery_receiver_address},
                        ${data.warehouse_transaction_delivery_receiver_province_name},
                        ${data.warehouse_transaction_delivery_receiver_city_name},
                        ${data.warehouse_transaction_delivery_receiver_subdistrict_name}
                    </th>
                </tr>
                <tr>
                    <th style="font-weight: 400; font-size: 18px; text-align: left; padding-bottom: 24px; vertical-align: top;">Kurir</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px; vertical-align: top;">:</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px; vertical-align: top;">${data.warehouse_transaction_delivery_courier_code ? data.warehouse_transaction_delivery_courier_code+` (`+data.warehouse_transaction_delivery_courier_service+`)` : "COD"}</th>
                    <th></th>
                    <th style="font-weight: 400; font-size: 20px; text-align: left; padding-bottom: 20px; vertical-align: top;">No. Resi</th>
                    <th style="font-weight: 400; text-align: left; font-size: 20px; padding-bottom: 20px; vertical-align: top;">:</th>
                    <th style="font-weight: 400; text-align: left; font-size: 20px; padding-bottom: 20px; vertical-align: top;"><b>${data.warehouse_transaction_delivery_method == 'pickup' ? '-' : data.warehouse_transaction_awb}</b></th>
                </tr>
            </tbody>
        </table>
    </div>
</body>
<footer style="padding-top: 20px;">
${detail}
</footer>

</html>
                                `;

                                var printWindow = window.open('', '', 'height=768,width=768');

                                printWindow.document.write(html_print);
                                printWindow.print();
                                printWindow.close();
                            }
                        },
                    });
                },

                printAlamatJS(warehouse_transaction_id) {
                    const currentDate = new Date();
                    const formattedDate = currentDate.toLocaleDateString('en-US');

                    const {
                        jsPDF
                    } = window.jspdf;
                    const doc = new jsPDF();

                    if (!warehouse_transaction_id) {
                        warehouse_transaction_id = app.detail.warehouse_transaction_id
                    }

                    $.ajax({
                        url: window.location.origin + '/admin/service/transaction/getDetailTransaction',
                        method: 'GET',
                        data: {
                            id: warehouse_transaction_id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data.results
                                // console.log('data', data)
                                let detail = '';
                                let total = 0;

                                $.each(data.warehouse_transaction_detail, (key, val) => {
                                    total += parseInt(val.warehouse_transaction_detail_unit_price) * parseInt(val.warehouse_transaction_detail_quantity);
                                })

                                // Transaction Details
                                let compText = "<?= COMPANY_NAME ?>";
                                let receiverText = data.warehouse_transaction_delivery_receiver_name;
                                let companyText = (compText.length > receiverText.length) ? compText : receiverText;
                                let addressCompText = "<?= DELIVERY_WAREHOUSE_ADDRESS ?>";
                                let addressRcvText = app.generateAddress(data.warehouse_transaction_delivery_receiver_address, data.warehouse_transaction_delivery_receiver_province_name, data.warehouse_transaction_delivery_receiver_city_name, data.warehouse_transaction_delivery_receiver_subdistrict_name);
                                let addressText = (addressCompText.length > addressRcvText.length) ? addressCompText : addressRcvText

                                let companyLineHeight = doc.getLineHeight(companyText) / doc.internal.scaleFactor
                                let companySplittedText = doc.splitTextToSize(companyText, 50)
                                let companyLines = companySplittedText.length  // splitted text is a string array
                                let companyBlockHeight = (compText.length > receiverText.length) ? parseFloat(companyLines * companyLineHeight) : parseFloat(companyLines * companyLineHeight) + 10

                                let receiverLineHeight = doc.getLineHeight(receiverText) / doc.internal.scaleFactor
                                let receiverSplittedText = doc.splitTextToSize(receiverText, 50)
                                let receiverLines = receiverSplittedText.length  // splitted text is a string array
                                let receiverBlockHeight = (receiverText.length > 20) ? parseFloat(receiverLines * receiverLineHeight) : receiverLineHeight

                                let addrsLineHeight = doc.getLineHeight(addressText) / doc.internal.scaleFactor
                                let addrsSplittedText = doc.splitTextToSize(addressText, 50)
                                let addrsLines = addrsSplittedText.length  // splitted text is a string array
                                let addrsBlockHeight = addrsLines * addrsLineHeight

                                doc.setFontSize(12);
                                doc.text(`Tanggal Transaksi: ${data.warehouse_transaction_datetime_formatted}`, 155, 10, { align : "center" });
                                doc.setFontSize(13).setFont(undefined, 'bold').text("Data Pengirim", 10, 20);
                                doc.setFontSize(13).setFont(undefined, 'bold').text("Data Penerima", 112, 20);

                                doc.setFontSize(13).setFont(undefined, 'normal').text("Nama", 10, 30);
                                doc.setFontSize(13).setFont(undefined, 'normal').text(":", 40, 30);
                                doc.setFontSize(13).setFont(undefined, 'bold').text("<?= COMPANY_NAME ?>", 45, 30, { maxWidth: 50 });

                                doc.setFontSize(13).setFont(undefined, 'normal').text("Nama", 112, 30);
                                doc.setFontSize(13).setFont(undefined, 'normal').text(":", 142, 30);
                                doc.setFontSize(13).setFont(undefined, 'bold').text(data.warehouse_transaction_delivery_receiver_name, 147, 30, { maxWidth: 50 });
                                doc.setFontSize(12).setFont(undefined, 'normal').text((data.network_code) ? '( ' + data.network_code + ' )' : '', 147, 30 + parseInt(receiverBlockHeight));

                                doc.setFontSize(13).setFont(undefined, 'normal').text("No. HP", 10, (30 + parseInt(companyBlockHeight)));
                                doc.setFontSize(13).setFont(undefined, 'normal').text(":", 40, (30 + parseInt(companyBlockHeight)));
                                doc.setFontSize(13).setFont(undefined, 'bold').text("<?= WA_CS_NUMBER ?>", 45, (30 + parseInt(companyBlockHeight)), { maxWidth: 50 });

                                doc.setFontSize(13).setFont(undefined, 'normal').text("No. HP", 112, (30 + parseInt(companyBlockHeight)));
                                doc.setFontSize(13).setFont(undefined, 'normal').text(":", 142, (30 + parseInt(companyBlockHeight)));
                                doc.setFontSize(13).setFont(undefined, 'bold').text(data.warehouse_transaction_delivery_receiver_mobilephone, 147, (30 + parseInt(companyBlockHeight)), { maxWidth: 50 });

                                let lastPos = (37.5 + parseInt(companyBlockHeight))
                                doc.setFontSize(13).setFont(undefined, 'normal').text("Alamat", 10, lastPos);
                                doc.setFontSize(13).setFont(undefined, 'normal').text(":", 40, lastPos);
                                doc.setFontSize(13).setFont(undefined, 'normal').text("<?= DELIVERY_WAREHOUSE_ADDRESS ?>", 45, lastPos, { maxWidth: 50 });

                                doc.setFontSize(13).setFont(undefined, 'normal').text("Alamat", 112, lastPos);
                                doc.setFontSize(13).setFont(undefined, 'normal').text(":", 142, lastPos);
                                doc.setFontSize(13).setFont(undefined, 'normal').text(addressRcvText, 147, lastPos, { maxWidth: 50 });

                                let lastAddrsPos = (parseInt(lastPos) + parseInt(addrsBlockHeight) - 7.5)
                                doc.setFontSize(13).setFont(undefined, 'normal').text("Kurir", 10, lastAddrsPos);
                                doc.setFontSize(13).setFont(undefined, 'normal').text(":", 40, lastAddrsPos);
                                doc.setFontSize(13).setFont(undefined, 'normal').text(data.warehouse_transaction_delivery_courier_code ? data.warehouse_transaction_delivery_courier_code+` (`+data.warehouse_transaction_delivery_courier_service+`)` : "COD", 45, lastAddrsPos, { maxWidth: 50 });

                                doc.setFontSize(13).setFont(undefined, 'normal').text("No. Resi", 112, lastAddrsPos);
                                doc.setFontSize(13).setFont(undefined, 'normal').text(":", 142, lastAddrsPos);
                                doc.setFontSize(13).setFont(undefined, 'normal').text((data.warehouse_transaction_awb ? data.warehouse_transaction_awb : '-'), 147, lastAddrsPos, { maxWidth: 50 });
                                
                                // Table Headers
                                const tableHeaders = [
                                    { title: 'Kode Produk', styles: { halign: 'left' } },
                                    { title: 'Nama Produk', styles: { halign: 'left' } },
                                    { title: 'Qty', styles: { halign: 'center' } },
                                    { title: 'Satuan Harga', styles: { halign: 'right' } },
                                    { title: 'Subtotal', styles: { halign: 'right' } },
                                ]
                                doc.autoTable({
                                    startX: -50,
                                    startY: lastAddrsPos + 10,
                                    theme: 'grid',
                                    tableWidth: 190,
                                    margin: { top: 0, right: 0, bottom: 0, left: 10 },
                                    columnStyles: {
                                        0: {
                                            halign: 'left',
                                            tableWidth: 100,
                                            minCellHeight: 10,
                                            valign: 'middle',
                                            },
                                        1: {
                                            tableWidth: 100,
                                            valign: 'middle',
                                        },
                                        2: {
                                            halign: 'center',
                                            tableWidth: 100,
                                            valign: 'middle',
                                        },
                                        3: {
                                            halign: 'right',
                                            tableWidth: 100,
                                            valign: 'middle',
                                        },
                                        4: {
                                            halign: 'right',
                                            tableWidth: 100,
                                            valign: 'middle',
                                        }
                                    },
                                    head: [tableHeaders],
                                    headStyles :{
                                        lineWidth: 0.25, 
                                        minCellHeight: 10, 
                                        valign: 'middle', 
                                        fillColor: '#f5f5f5', 
                                        textColor: [0,0,0]
                                    },
                                    body: data.warehouse_transaction_detail.map(detail => [
                                        detail.warehouse_transaction_detail_product_code,
                                        detail.warehouse_transaction_detail_product_name,
                                        detail.warehouse_transaction_detail_quantity,
                                        app.formatCurrency(detail.warehouse_transaction_detail_unit_price),
                                        app.formatCurrency((detail.warehouse_transaction_detail_quantity * detail.warehouse_transaction_detail_unit_price).toFixed(0))
                                    ]),
                                    foot: [
                                        [
                                            {
                                                content: 'Ongkir',
                                                colSpan: 4,
                                                styles: { halign: 'right' },
                                            },
                                            {
                                                content: `Rp ${data.warehouse_transaction_delivery_cost_formatted}`,
                                                styles: { halign: 'right' },
                                            }
                                        ],
                                        [
                                            {
                                                title: 'Total',
                                                colSpan: 4,
                                                styles: { halign: 'right' },
                                            },
                                            {
                                                content: `Rp ` + app.formatDecimal(parseInt(data.warehouse_transaction_delivery_cost) + parseInt(total)),
                                                styles: { halign: 'right' },
                                            }
                                        ]
                                    ],
                                    footStyles :{
                                        lineWidth: 0.25, 
                                        minCellHeight: 10, 
                                        valign: 'middle', 
                                        fillColor: '#f5f5f5', 
                                        textColor: [0,0,0]
                                    },
                                })

                                // const pdfOutput = doc.output('datauristring');

                                // // Create a preview container and embed the PDF
                                // const previewContainer = document.createElement('iframe');
                                // previewContainer.src = pdfOutput;
                                // previewContainer.width = '100%';
                                // previewContainer.height = '500px';

                                // const previewElement = document.getElementById('preview');
                                // previewElement.innerHTML = ''; // Clear any previous content
                                // previewElement.appendChild(previewContainer);

                                // $('#modal-print').modal('show');
                                window.open(doc.output('bloburl'), '_blank');
                                // doc.save('pengemasan-' + data.warehouse_transaction_code + '.pdf');
                            }
                        }
                    })

                },

                generateAddress(address, province, city, subdist) {
                    let textAddress = ''
                    if (address) {
                        textAddress = address + (province ?? '') + (city ?? '') + subdist ?? ''
                    } else {
                        textAddress = '-'
                    }

                    return textAddress
                },

                closeModalPrint() {
                    $('#modal-print').modal('hide');
                },

                formatCurrency($params) {
                    let formatter = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0,
                    })
                    return formatter.format($params)
                },
                formatDecimal($params) {
                    let formatter = new Intl.NumberFormat('id-ID', {
                        style: 'decimal',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0,
                    })
                    return formatter.format($params)
                },
            },
            mounted() {}
        }).mount("#app");
</script>