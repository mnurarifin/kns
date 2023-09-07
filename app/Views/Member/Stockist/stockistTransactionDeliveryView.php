<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>

<div class="app-content content" id="app">
    <div class="content-overlay">
    </div>
    <div class="content-loading">
        <i class="bx bx-loader bx-spin"></i>
    </div>
    <div class="content-wrapper">
        <div class="alert alert-danger" role="alert" id="alert" style="position: absolute; top: 84px; right: 32px; display: none;"></div>
        <div class="alert alert-success d-none" role="alert" id="alert-success" style="position: absolute; top: 84px; right: 32px;"></div>

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

<div class="modal fade" id="modalDetailTrx" tabindex="-1" role="dialog" aria-labelledby="modalDetailTrx" aria-hidden="true">
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
                                                <span class="text-primary ml-1 pl-1"> {{detail.stockist_transaction_code}}
                                                </span>
                                            </li>
                                            <li class="mb-1"><i class="bx bx-user font-medium-5" style="margin-right: 4px;"></i>
                                                Pembeli <br>
                                                <span class="text-primary ml-1 pl-1">{{detail.stockist_transaction_buyer_name}}
                                                    {{detail.network_code !== '' ? `(${detail.network_code})` : '' }}</span>
                                            </li>
                                            <li class="mb-1"><i class="bx bx-user font-medium-5" style="margin-right: 4px;"></i>
                                                Penerima <br>
                                                <span class="text-primary ml-1 pl-1">{{detail.stockist_transaction_delivery_receiver_name}}</span>
                                            </li>
                                            <li class="mb-1" v-if="detail.stockist_transaction_delivery_method == 'courier'"><i class="bx bx-map font-medium-5" style="margin-right: 4px;"></i>
                                                Alamat Kirim <br>
                                                <span class="text-primary ml-1 pl-1">{{detail.stockist_transaction_delivery_receiver_address}},
                                                    {{detail.stockist_transaction_delivery_receiver_subdistrict_name}},
                                                </span>
                                                <br>
                                                <span class="text-primary ml-1 pl-1">
                                                    {{detail.stockist_transaction_delivery_receiver_city_name}},
                                                    {{detail.stockist_transaction_delivery_receiver_province_name}}
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <ul class="pl-0" style="list-style-type: none;">
                                            <li class="mb-1"><i class="bx bx-calendar font-medium-5" style="margin-right: 4px;"></i></i>
                                                Tanggal Transaksi <br>
                                                <span class="text-primary pl-1 ml-1">{{detail.stockist_transaction_datetime_formatted}}</span>
                                            </li>
                                            <li class="mb-1">
                                                <i class="bx bx-desktop font-medium-5" style="margin-right: 4px;"></i>
                                                </i>
                                                Status <br>
                                                <span class="text-primary ml-1 pl-1">{{detail.stockist_transaction_status_formatted}}</span>
                                            </li>
                                            <li class="mb-1" v-if="detail.stockist_transaction_delivery_method == 'courier'">
                                                <i class="bx bx-truck font-medium-5" style="margin-right: 4px;"></i>
                                                </i>
                                                Ekspedisi <br>
                                                <span class="text-primary ml-1 pl-1">{{detail.courier_name}} ({{detail.stockist_transaction_delivery_courier_code}}) - {{detail.stockist_transaction_delivery_courier_service}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-3 mt-2">
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
                                            <tr v-for="item in detail.stockist_transaction_detail" @key="item.stockist_transaction_detail_id">
                                                <td>{{item.stockist_transaction_detail_product_code}}</td>
                                                <td>{{item.stockist_transaction_detail_product_name}}</td>
                                                <td>{{item.stockist_transaction_detail_quantity}}</td>
                                                <td class="text-right">Rp {{item.stockist_transaction_detail_unit_nett_price_formatted}}</td>
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
                                                    <h6 class="card-title text-primary text-right">Rp {{ detail.stockist_transaction_delivery_cost_formatted}}</h6>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class="card-title text-dark">Total</h6>
                                                </td>
                                                <td>
                                                    <h6 class="card-title text-primary text-right">Rp {{ detail.stockist_transaction_total_nett_price_formatted}}</h6>
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
                    <span class="">Tutup</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDelivery" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdateTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center" id="modalDeliveryTitle">
                    <span>Form Input Resi</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form form-vertical">
                    <div class="form-body">
                        <div class="row ma-5">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="stockist_transaction_notes">Masukan Nomor Resi</label>
                                    <input v-model="form.stockist_transaction_awb" class="form-control">
                                    </input>
                                    <small class="text-danger d-none" id="stockist_transaction_awb"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" @click="save" id="btnUpdateAwb">
                    <span>Update Resi</span>
                </button>
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
                category: [],
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
                    url: window.location.origin + '/member/stockist/get-member-transaction/delivered',
                    selectID: 'stockist_transaction_id',
                    colModel: [{
                        display: "Aksi",
                        name: "stockist_transaction_id",
                        align: "center",
                        render: (params, args) => {
                            return `<a onclick="app.detailTrx('${params}')" title="Detail" data-toggle="tooltip" class="cstmHover"> <i class="bx bx-book info"></i> </a>`;
                        }
                    }, {
                        display: "Tanggal",
                        name: "stockist_transaction_datetime_formatted",
                        align: "left",
                    }, {
                        display: "Kode Transaksi",
                        name: "stockist_transaction_code",
                        align: "left",
                    }, {
                        display: "Nama Pembeli",
                        name: "stockist_transaction_buyer_name",
                        align: "left",
                    }, {
                        display: "No. Telpon Penerima",
                        name: "stockist_transaction_delivery_receiver_mobilephone",
                        align: "left",
                    }, {
                        display: "Status",
                        name: "stockist_transaction_status",
                        align: "center",
                        render: (params, args) => {
                            switch (args.stockist_transaction_status_formatted) {
                                case 'Menunggu Pembayaran':
                                    return `<span class="badge badge-light-warning badge-pill">${args.stockist_transaction_status_formatted}</span>`
                                    break;

                                case 'Dibayar':
                                    return `<span class="badge badge-light-info badge-pill">${args.stockist_transaction_status_formatted}</span>`
                                    break;

                                case 'Selesai':
                                    return `<span class="badge badge-light-success badge-pill">${args.stockist_transaction_status_formatted}</span>`
                                    break;

                                default:
                                    return `<span class="badge badge-light-info badge-pill">${args.stockist_transaction_status_formatted}</span>`
                                    break;
                            }
                        }
                    }, {
                        display: "Total",
                        name: "stockist_transaction_total_nett_price_formatted",
                        align: "right"
                    }, {
                        display: "Resi",
                        name: "stockist_transaction_awb",
                        align: "left"
                    }],
                    buttonAction: [{
                        display: 'Diterima',
                        icon: 'bx bx-check',
                        style: "btn btn-success",
                        action: "accept",
                        message: 'menerima',
                        url: window.location.origin + "/member/stockist/member-approved"
                    }],
                    options: {
                        limit: [10, 25, 50, 100],
                        currentLimit: 10,
                    },
                    search: true,
                    searchTitle: 'Pencarian Transaksi',
                    searchItems: [{
                            display: 'Tanggal',
                            name: 'stockist_transaction_datetime',
                            type: 'date'
                        },
                        {
                            display: 'Kode Transaksi',
                            name: 'stockist_transaction_code',
                            type: 'text'
                        },
                        {
                            display: 'Nama Pembeli',
                            name: 'stockist_transaction_buyer_name',
                            type: 'text'
                        },
                        {
                            display: 'Status Transaksi',
                            name: 'stockist_transaction_status',
                            type: 'select',
                            option: this.category
                        },
                    ],
                    sortName: "stockist_product_stock_id",
                    sortOrder: "desc",
                    tableIsResponsive: true,
                    select: true,
                    multiSelect: true,
                })
            },
            detailTrx(transaction_id) {
                $.ajax({
                    url: window.location.origin + '/member/transaction/getDetailTransaction',
                    method: 'GET',
                    data: {
                        id: transaction_id
                    },
                    success: function(response) {
                        detailTrx(response.data.results)
                    },
                });
            },
            receive(stockist_transaction_id) {
                let id = []
                id.push(stockist_transaction_id)

                Swal.fire({
                    title: 'Perhatian!',
                    text: "Anda yakin barang sudah diterima?",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'YA',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
                        showLoadProcess();
                        $.ajax({
                            url: window.location.origin + '/member/stockist/approved',
                            method: 'POST',
                            data: {
                                data: id
                            },
                            success: function(response) {
                                swal.hideLoading();
                                app.generateTable();
                                Swal.fire({
                                    title: 'Berhasil',
                                    html: 'Paket berhasil diterima',
                                    icon: 'success'
                                });
                            },

                        });
                    }
                })
            },
            printAlamat(data) {
                let detail_ = '';
                let detail = '';

                $.each(data.stockist_transaction_detail, (key, val) => {
                    detail_ += `
                                                <tr>
                                                    <td style="padding-left: 10px;">${val.stockist_transaction_detail_product_code}</td>
                                                    <td style="padding-left: 10px;">${val.stockist_transaction_detail_product_name}</td>
                                                    <td align="center">${val.stockist_transaction_detail_quantity}</td>
                                                    <td align="right" style="padding-right: 10px;">Rp ${val.stockist_transaction_detail_unit_price_formatted}</td>
                                                    <td align="right" style="padding-right: 10px;">Rp ${val.stockist_transaction_detail_unit_nett_price_formatted}</td>
                                                </tr>
                                    `
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
                                                ${detail_}
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
            size: auto;
            size: A3;
            margin: 0mm;
        }
    </style>
</head>

<body style="">
    <div id="divName" style="font-family: 'Open Sans', sans-serif; border: 3px dashed #000; padding: 24px">
        <table style="width:100%">
            <colgroup>
                <col style="width: 15%;">
                <col style="width: 34%;">
                <col style="width: 2%;">
                <col style="width: 15%;">
                <col style="width: 34%;">
            </colgroup>
            <thead>
                <tr>
                    <th style="font-weight: 700; font-size: 18px; text-align: left; padding-bottom: 24px;" colspan="2">
                        Data Pengirim</th>
                    <th></th>
                    <th style="font-weight: 700; font-size: 18px; text-align: left; padding-bottom: 24px;" colspan="2">
                        Data Penerima</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="font-weight: 400; font-size: 18px; text-align: left; padding-bottom: 24px;">
                        Nama Pengirim</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px;">
                        : <?= COMPANY_NAME ?></th>
                    <th></th>
                    <th style="font-weight: 400; font-size: 18px; text-align: left; padding-bottom: 24px;">
                        Nama Penerima</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px;">
                        : ${data.stockist_transaction_delivery_receiver_name}</th>
                </tr>
                <tr>
                    <th style="font-weight: 400; font-size: 18px; text-align: left; padding-bottom: 24px;">
                        No. HP Pengirim</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px;">
                        : <?= WA_CS_NUMBER ?></th>
                    <th></th>
                    <th style="font-weight: 400; font-size: 18px; text-align: left; padding-bottom: 24px;">
                        No. HP Penerima</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px;">
                        : ${data.stockist_transaction_delivery_receiver_mobilephone}</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="font-weight: 400; font-size: 18px; text-align: left; padding-bottom: 24px;">
                        Alamat Penerima</th>
                    <th style="font-weight: 400; text-align: left; padding-bottom: 24px;">
                        : ${data.stockist_transaction_delivery_receiver_address},
                        ${data.stockist_transaction_delivery_receiver_subdistrict_name},
                        <br>
                        <span style="padding-left: 8px;">${data.stockist_transaction_delivery_receiver_city_name},
                        ${data.stockist_transaction_delivery_receiver_province_name}</span>
                        </th>
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

                var printWindow = window.open('', '', 'height=400,width=1366');

                printWindow.document.write(html_print);
                printWindow.print();
                printWindow.close();

            },

            printAlamatJS(data) {
                const currentDate = new Date();
                const formattedDate = currentDate.toLocaleDateString('en-US');

                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF();

                // console.log('data', data)
                let detail = '';
                let total = 0;

                $.each(data.stockist_transaction_detail, (key, val) => {
                    total += parseInt(val.stockist_transaction_detail_unit_price) * parseInt(val.stockist_transaction_detail_quantity);
                })

                // Transaction Details
                let compText = "<?= COMPANY_NAME ?>";
                let receiverText = data.stockist_transaction_delivery_receiver_name;
                let companyText = (compText.length > receiverText.length) ? compText : receiverText;
                let addressCompText = "<?= DELIVERY_WAREHOUSE_ADDRESS ?>";
                let addressRcvText = app.generateAddress(data.stockist_transaction_delivery_receiver_address, data.stockist_transaction_delivery_receiver_province_name, data.stockist_transaction_delivery_receiver_city_name, data.stockist_transaction_delivery_receiver_subdistrict_name);
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
                doc.text(`Tanggal Transaksi: ${data.stockist_transaction_datetime_formatted}`, 155, 10, { align : "center" });
                doc.setFontSize(13).setFont(undefined, 'bold').text("Data Pengirim", 10, 20);
                doc.setFontSize(13).setFont(undefined, 'bold').text("Data Penerima", 112, 20);

                doc.setFontSize(13).setFont(undefined, 'normal').text("Nama", 10, 30);
                doc.setFontSize(13).setFont(undefined, 'normal').text(":", 40, 30);
                doc.setFontSize(13).setFont(undefined, 'bold').text("<?= COMPANY_NAME ?>", 45, 30, { maxWidth: 50 });

                doc.setFontSize(13).setFont(undefined, 'normal').text("Nama", 112, 30);
                doc.setFontSize(13).setFont(undefined, 'normal').text(":", 142, 30);
                doc.setFontSize(13).setFont(undefined, 'bold').text(data.stockist_transaction_delivery_receiver_name, 147, 30, { maxWidth: 50 });
                doc.setFontSize(12).setFont(undefined, 'normal').text((data.network_code) ? '( ' + data.network_code + ' )' : '', 147, 30 + parseInt(receiverBlockHeight));

                doc.setFontSize(13).setFont(undefined, 'normal').text("No. HP", 10, (30 + parseInt(companyBlockHeight)));
                doc.setFontSize(13).setFont(undefined, 'normal').text(":", 40, (30 + parseInt(companyBlockHeight)));
                doc.setFontSize(13).setFont(undefined, 'bold').text("<?= WA_CS_NUMBER ?>", 45, (30 + parseInt(companyBlockHeight)), { maxWidth: 50 });

                doc.setFontSize(13).setFont(undefined, 'normal').text("No. HP", 112, (30 + parseInt(companyBlockHeight)));
                doc.setFontSize(13).setFont(undefined, 'normal').text(":", 142, (30 + parseInt(companyBlockHeight)));
                doc.setFontSize(13).setFont(undefined, 'bold').text(data.stockist_transaction_delivery_receiver_mobilephone, 147, (30 + parseInt(companyBlockHeight)), { maxWidth: 50 });

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
                doc.setFontSize(13).setFont(undefined, 'normal').text(data.stockist_transaction_delivery_courier_code ? data.stockist_transaction_delivery_courier_code+` (`+data.stockist_transaction_delivery_courier_service+`)` : "COD", 45, lastAddrsPos, { maxWidth: 50 });

                doc.setFontSize(13).setFont(undefined, 'normal').text("No. Resi", 112, lastAddrsPos);
                doc.setFontSize(13).setFont(undefined, 'normal').text(":", 142, lastAddrsPos);
                doc.setFontSize(13).setFont(undefined, 'normal').text((data.stockist_transaction_awb ? data.stockist_transaction_awb : '-'), 147, lastAddrsPos, { maxWidth: 50 });
                
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
                    body: data.stockist_transaction_detail.map(detail => [
                        detail.stockist_transaction_detail_product_code,
                        detail.stockist_transaction_detail_product_name,
                        detail.stockist_transaction_detail_quantity,
                        app.formatCurrency(detail.stockist_transaction_detail_unit_price),
                        app.formatCurrency((detail.stockist_transaction_detail_quantity * detail.stockist_transaction_detail_unit_price).toFixed(0))
                    ]),
                    foot: [
                        [
                            {
                                content: 'Ongkir',
                                colSpan: 4,
                                styles: { halign: 'right' },
                            },
                            {
                                content: `${data.stockist_transaction_delivery_cost_formatted}`,
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
                                content: `Rp ` + app.formatDecimal(parseInt(data.stockist_transaction_delivery_cost) + parseInt(total)),
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
                // doc.save('pengemasan-' + data.stockist_transaction_code + '.pdf');

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

            formatCurrency($params) {
                let formatter = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                })
                return formatter.format($params)
            },

            categoryTransaction() {
                $.ajax({
                    url: window.location.origin + '/member/transaction/categoryTransaction',
                    method: 'GET',
                    data: {},
                    success: function(response) {
                        app.category = response.data.results;
                    },
                });
            },
        },
        mounted() {
            this.categoryTransaction()
        }
    }).mount('#app');

    function detailTrx(data) {
        detail.showDetail(data)
    }

    function delivery(trx_id, trx_awb) {
        deliveryVue.updateAwb(trx_id, trx_awb);
    }

    const deliveryVue = Vue.createApp({
        data() {
            return {
                form: {}
            }
        },
        methods: {
            updateAwb(trx_id, trx_awb) {
                this.form.stockist_transaction_awb = trx_awb
                this.form.stockist_transaction_id = trx_id
                $('#modalDelivery').modal()
            },
            save() {
                $('#btnUpdateAwb').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memproses...')
                $('#stockist_transaction_awb').addClass('d-none')
                $('#stockist_transaction_awb').text('')

                let url = window.location.origin + '/member/stockist/updateAwb';
                $.ajax({
                    url,
                    method: 'POST',
                    data: this.form,
                    success: function(response) {
                        $('#btnUpdateAwb').html('Update Resi')
                        $('#modalDelivery').modal('hide');
                        $('#alert-success').html(`<span>${response.message}</span>`)
                        $('#alert-success').removeClass('d-none')
                        app.generateTable();

                        setTimeout(() => {
                            $('#alert-success').html(``)
                            $('#alert-success').addClass('d-none')
                        }, 2000);
                    },
                    error: function(res) {
                        $('#btnUpdateAwb').html('Update Resi')
                        let response = res.responseJSON;

                        if (response.error == "validation") {
                            $('#stockist_transaction_awb').removeClass('d-none')
                            $('#stockist_transaction_awb').text(response.data.stockist_transaction_awb)
                        }
                    },

                });
            }
        },
    }).mount("#modalDelivery")

    const detail = Vue.createApp({
        data() {
            return {
                detail: {},
            }
        },
        methods: {
            showDetail(data) {
                this.detail = data
                $('#modalDetailTrx').modal()
            }
        }
    }).mount("#modalDetailTrx")
</script>