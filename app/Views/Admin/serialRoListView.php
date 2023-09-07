<script src="https://unpkg.com/vue@next"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    .table {
        position: relative;
    }

    .table-responsive {
        max-height: calc(100vh - 0px);
    }

    .table th,
    .table td {
        padding: 0.4rem 1rem;
        white-space: nowrap;
    }

    th {
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        background: white;
    }

    .activMenu {
        background-color: #719df0 !important;
        color: #fff !important;
        border-color: #5A8DEE !important;
    }

    .swal2-cancel {
        color: #475F7B !important;
        background-color: rgb(230, 234, 238) !important;
    }
</style>

<section id="modul">
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
                        <div class="alert border-warning alert-dismissible mb-2 informSerial" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-error-circle"></i>
                                <span class="text-info">
                                    Serial yang bisa diaktifkan atau dinonaktifkan adalah serial yang sudah dijual.
                                </span>
                            </div>
                        </div>
                        <div class="alert alert-success " v-show="alert.success.status" style="display: none;">
                            <span v-html="alert.success.content"></span>
                        </div>
                        <div class="alert alert-danger " v-show="alert.danger.status" style="display: none;">
                            <span v-html="alert.danger.content"></span>
                        </div>

                        <div>
                            <button class="btn btn-icon btn-sm btn-outline-primary mb-1 mr-1 activMenu" id="premium" onclick="app.changeType(2)">Premium</button>
                            <button class="btn btn-icon btn-sm btn-outline-primary mb-1 mr-1" id="business" onclick="app.changeType(3)">Business</button>
                            <button class="btn btn-icon btn-sm btn-outline-primary mb-1 mr-1" id="vip" onclick="app.changeType(4)">VIP Business</button>
                            <button class="btn btn-icon btn-sm btn-outline-primary mb-1 mr-1" id="executive" onclick="app.changeType(5)">Executive Business</button>
                        </div>
                        <p class="card-text"></p>
                        <div id="response-messages"></div>
                        <div id="table-basic"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="border-radius: 7px 7px 0 0; border: 1px solid #ccc; background-color: #f5f5f5;">
                    <i class="bx bx-credit-card font-large-2"></i>
                    <h4 class="modal-title" id="detailserialId"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="panel panel-default mb-2">
                        <div class="panel-body p-0">
                            <div class="card widget-state-multi-radial">

                                <div class="card-body py-1">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="biodata" aria-labelledby="biodata-tab" role="tabpanel">
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <ul class="list-group list-group-flush d-flex flex-row">
                                                        <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                            <div class="list-left d-flex">
                                                                <div class="list-icon mr-1">
                                                                    <div class="avatar bg-rgba-primary m-0 p-25">
                                                                        <div class="avatar-content" id="detailisUsed">
                                                                            <i class="bx bxs-check-circle text-success font-size-base"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="list-content">
                                                                    <small class="text-muted d-block">Digunakan</small>
                                                                    <div class="list-title">
                                                                        <span id="detailused" class="d-block"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>

                                                        <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                            <div class="list-left d-flex">
                                                                <div class="list-icon mr-1">
                                                                    <div class="avatar bg-rgba-primary m-0 p-25">
                                                                        <div class="avatar-content" id="detailisSold">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="list-content">
                                                                    <small class="text-muted d-block">Terjual</small>
                                                                    <div class="list-title">
                                                                        <span id="detailbuy" class="d-block"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="col-md-12">
                                                    <ul class="list-group list-group-flush d-flex flex-row">
                                                        <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                            <div class="list-left d-flex">
                                                                <div class="list-icon mr-1">
                                                                    <div class="avatar bg-rgba-success m-0 p-25">
                                                                        <div class="avatar-content" id="detailisActive">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="list-content">
                                                                    <small class="text-muted d-block">Diaktifkan</small>
                                                                    <div class="list-title">
                                                                        <span id="detailactive" class="d-block"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>

                                                        <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                            <div class="list-left d-flex">
                                                                <div class="list-icon mr-1">
                                                                    <div class="avatar bg-rgba-success m-0 p-25">
                                                                        <div class="avatar-content" id="avatar-create">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="list-content">
                                                                    <small class="text-muted d-block">Dibuat</small>
                                                                    <div class="list-title">
                                                                        <span id="detailcreate" class="d-block"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="col-md-12">
                                                    <ul class="list-group list-group-flush d-flex flex-row">
                                                        <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                            <div class="list-left d-flex">
                                                                <div class="list-icon mr-1">
                                                                    <div class="avatar bg-rgba-success m-0 p-25">
                                                                        <div class="avatar-content" id="detail-seller">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="list-content">
                                                                    <small class="text-muted d-block">Dijual Oleh</small>
                                                                    <div class="list-title">
                                                                        <span id="detailadmin" class="d-block"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>

                                                        <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                            <div class="list-left d-flex">
                                                                <div class="list-icon mr-1">
                                                                    <div class="avatar bg-rgba-success m-0 p-25">
                                                                        <div class="avatar-content" id="detail-buyer-name">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="list-content">
                                                                    <small class="text-muted d-block">Dibeli Oleh</small>
                                                                    <div class="list-title">
                                                                        <span id="detailbuyerName" class="d-block"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>

                                                    </ul>
                                                </div>
                                                <div class="col-md-12">
                                                    <ul class="list-group list-group-flush d-flex flex-row">
                                                        <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                            <div class="list-left d-flex">
                                                                <div class="list-icon mr-1">
                                                                    <div class="avatar bg-rgba-success m-0 p-25">
                                                                        <div class="avatar-content" id="detail-user-name">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="list-content">
                                                                    <small class="text-muted d-block">Digunakan Oleh</small>
                                                                    <div class="list-title">
                                                                        <span id="detailuserName" class="d-block"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="bank" aria-labelledby="bank-tab" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card border mb-50">
                                                        <div class="card-header p-1 d-flex justify-content-start align-items-center">
                                                            <i class="bx bx-receipt bx-lg bx-border-circle text-muted font-weight-light mr-2"></i>
                                                            <div class="card-title-content">
                                                                <h4 class="card-title text-dark mb-50">Rekening</h4>
                                                                <h5 id="data-member-bank-account-number" class="d-block text-primary mb-0"></h5>
                                                                <span id="data-member-bank-account-name" class="d-block text-muted"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="card border mb-50">
                                                        <div class="card-header p-1 d-flex justify-content-start align-items-center">
                                                            <i class="bx bxs-bank bx-lg bx-border-circle text-muted font-weight-light mr-2"></i>
                                                            <div class="card-title-content">
                                                                <span id="data-member-bank-name" class="d-block text-primary font-weight-bolder"></span>
                                                                <span id="data-member-bank-city" class="d-block text-muted"></span>
                                                                <span id="data-member-bank-branch" class="d-block text-muted"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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

    <div class="modal fade" id="modalApp" role="dialog" aria-labelledby="modalAddUpdateTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center" id="modalDetailTitle">
                        Kirim Serial
                        <span></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body" style="min-height: calc(100vh - 350px);">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label" for="basic-default-fullname">Pilih Stokis</label>
                                <select id="select_stockist" data-placeholder="Pilih Stokis" class="select2" aria-hidden="true">
                                </select>
                                <hr>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="basic-default-fullname">Pilih Tipe Serial</label>
                            </div>
                            <div class="col col-xs-8 col-sm-8 col-md-10 col-lg-10">
                                <select v-model="select_type_id" class="form-control">
                                    <option v-for="(item, index) in serial_type" :value="item.serial_type_id">{{item.serial_type_name}}</option>
                                </select>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-2 col-lg-2">
                                <button type="button" :disabled="addBtn" @click.prevent="addSerialType" class="btn btn-primary">
                                    Tambah
                                </button>
                            </div>
                            <div class="col-12 pt-2">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col">Hapus</th>
                                            <th class="text-left" scope="col">Tipe</th>
                                            <th scope="col">Kuantitas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="serial.length  == 0">
                                            <td class="text-center" colspan="6" style="height:200px;"> Silahkan pilih tipe serial terlebih dahulu.</td>
                                        </tr>
                                        <tr v-else v-for="(item, index) in serial">
                                            <td class="text-center"><a @click="deleteNumber(index)"> <i class="bx bx-trash danger"></i> </a>
                                            </td>
                                            <td class="text-left">
                                                <span>{{serial[index].serial_type_name}}</span>
                                            </td>
                                            <td><input class="form-control" type="number" v-model="serial[index].serial_qty" min="1" max="100" style="max-width: 100%;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        Tutup
                    </button>
                    <button type="button" :disabled="serial.length == 0" @click.prevent="save" class="btn btn-primary">
                        Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript" defer>
    $(document).ready(function() {
        app.generateTable();

        // Select 2
        $('.select2').select2({
            allowClear: true,
            width: '100%',
            placeholder: 'Mitra Tujuan',
            ajax: {
                url: window.location.origin + '/admin/service/serial/getStockistOption',
                dataType: 'json',
                data: function(params) {
                    return {
                        search: params.term
                    }
                },
                processResults: function(data, page) {
                    app.stockist = data;
                    return {
                        results: data
                    };
                }
            }
        });

        $('#select_stockist').on('select2:open', function(e) {
            $('input.select2-search__field').prop('placeholder', 'Silakan masukan kode mitra, nama mitra, atau nama stokis.');
        });
    })
    let app =
        Vue.createApp({
            data: function() {
                return {
                    loading: false,
                    button: {
                        formBtn: {
                            disabled: false
                        }
                    },
                    stockist: [],
                    serial_type: [{
                        serial_type_id: 1,
                        serial_type_name: 'Basic'
                    }, {
                        serial_type_id: 2,
                        serial_type_name: 'Premium'
                    }],

                    number: '',
                    select_type_id: '1',
                    serial: [],
                    addBtn: true,
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
                    typeId: 2
                }
            },
            methods: {
                changeType(type) {
                    $('#premium').removeClass('activMenu')
                    $('#business').removeClass('activMenu')
                    $('#vip').removeClass('activMenu')
                    $('#executive').removeClass('activMenu')

                    if (type == 2) {
                        $('#premium').addClass('activMenu')
                    } else if (type == 3) {
                        $('#business').addClass('activMenu')
                    } else if (type == 4) {
                        $('#vip').addClass('activMenu')
                    } else if (type == 5) {
                        $('#executive').addClass('activMenu')
                    }

                    this.typeId = type
                    app.generateTable()
                },
                hideLoading() {
                    $("#pageLoader").hide();
                },

                detailSerial(serial) {
                    $.ajax({
                        url: window.location.origin + '/admin/service/serial/getDetailSerial/<?= $tipeSerial; ?>',
                        method: 'GET',
                        dataType: 'json',
                        data: {
                            data: serial.serial_id
                        },
                        success: function(res) {

                            $('#detailserialId').text(res.data.serial_id + ' | ' + res.data.serial_pin)
                            $('#detailserialCode').text(res.data.serial_network_code != null ? res.data.serial_network_code : '')
                            $('#detailadmin').text(res.data.administrator_name != null ? res.data.administrator_name : '')
                            // $('#detailuserName').text(res.data.user != null ? res.data.user.member_name : '')
                            // $('#detailuserCode').text(res.data.user != null ? res.data.user.member_ref_network_code : '');

                            $('#avatar-create').html('<span class="badge badge-light-success badge-pill badge-round" data-toggle="tooltip"><i class="bx bxs-plus-circle font-medium-5"></i></span>');
                            $('#detailcreate').text(res.data.serial_create_datetime);
                            $('#detailactive').text(res.data.serial_active_datetime != null ? res.data.serial_active_datetime : '-')
                            $('#detailbuy').text(res.data.serial_buy_datetime != null ? res.data.serial_buy_datetime : '-')
                            $('#detailused').text(res.data.serial_used_datetime != null ? res.data.serial_used_datetime : '-');
                            $('#detailexp').text(res.data.serial_expired_date != null ? res.data.serial_expired_date : '-');
                            $('#detailisActive').html(res.data.serial_is_active == '1' ? '<span class="badge badge-light-success badge-pill badge-round" title="Ya" data-toggle="tooltip"><i class="bx bxs-bulb font-medium-5"></i></span>' : '<span class="badge badge-light-danger badge-pill badge-round" title="Tidak" data-toggle="tooltip"><i class="bx bxs-bulb font-medium-5"></i></span>');
                            $('#detailisUsed').html(res.data.serial_is_used == '1' ? '<i class="bx bxs-check-circle success font-medium-5" title="Ya" data-toggle="tooltip"></i>' : '<i class="bx bxs-x-circle danger font-medium-5" title="Tidak" data-toggle="tooltip"></i>');
                            $('#detailisSold').html(res.data.serial_is_sold == '1' ? '<i class="bx bxs-check-circle success font-medium-5" title="Ya" data-toggle="tooltip"></i>' : '<i class="bx bxs-x-circle danger font-medium-5" title="Belum" data-toggle="tooltip"></i>');
                            $('#detail-seller').html('<i class="bx bxs-x-circle danger font-medium-5" data-toggle="tooltip"></i>');
                            $('#detail-buyer-name').html(res.data.buyer_network_code != null ? '<i class="bx bxs-user success font-medium-5" data-toggle="tooltip"></i>' : '<i class="bx bxs-user danger font-medium-5" data-toggle="tooltip"></i>');
                            let codeBuyer = res.data.buyer_network_code ? res.data.buyer_network_code : "-";
                            let nameBuyer = res.data.buyer_name ? res.data.buyer_name : "-";

                            $('#detailbuyerName').text(nameBuyer + '( ' + codeBuyer + ' )');

                            $('#detail-user-name').html(res.data.user_network_code != null ? '<i class="bx bxs-user-check success font-medium-5" data-toggle="tooltip"></i>' : '<i class="bx bxs-user-check danger font-medium-5" data-toggle="tooltip"></i>');

                            let codeUser = res.data.user_network_code ? res.data.user_network_code : "-";
                            let nameUser = res.data.user_name ? res.data.user_name : "-";
                            $('#detailuserName').text(nameUser + '( ' + codeUser + ' )');

                            $('#detailModal').modal('show')
                        }
                    })
                },
                addSerialType() {
                    let find_index = this.serial.findIndex(i => i.serial_type_id == app.select_type_id);
                    if (find_index == -1) {
                        let find_serial_type = this.serial_type.find(i => i.serial_type_id == app.select_type_id);

                        this.serial.push({
                            serial_type_id: app.select_type_id,
                            serial_type_name: find_serial_type.serial_type_name,
                            serial_qty: 1,
                        })
                    } else {
                        this.serial[find_index].serial_qty += 1;
                    }
                },
                addSerial() {
                    app.stockist = []
                    app.serial = []
                    $("#select_stockist").val('')
                    $('#select_stockist').val(null).trigger('change');
                    $('#modalApp').modal();
                },

                addNumber() {
                    if (this.form.length > 4) {
                        this.alert.danger.content = `<ul>`;
                        this.alert.danger.content = `<li> Maksimal hanya 5 nomor yang diinputkan.</li>`;
                        this.alert.danger.content += `</ul>`;
                        this.alert.danger.status = true;

                        setTimeout(() => {
                            this.alert.danger.status = false;
                        }, 3000);
                        return true
                    }
                    let find_index = this.form.findIndex(i => i.member_id === $("#select_stockist").val());

                    let find_stockist = app.stockist.find(el => el.id == $("#select_stockist").val())

                    if (find_index == -1) {
                        if ($("#select_stockist").val()) {
                            this.form.push({
                                member_id: $('#select_stockist').val(),
                                member_name: find_stockist.member_name,
                                network_code: find_stockist.network_code,
                                stockist_name: find_stockist.stockist_name,
                                member_serial_type_id: 1,
                                serial_qty: 1,
                            })
                        }
                        $('#select_stockist').val(null).trigger('change');
                    } else {
                        this.form[find_index].serial_qty += 1;
                        $('#select_stockist').val(null).trigger('change');
                    }
                },
                deleteNumber(index) {
                    this.serial.splice(index, 1);
                },

                generateTable() {
                    $("#table-basic").dataTableLib({
                        url: window.location.origin + '/admin/service/serial/getDataSerial/<?= $tipeSerial; ?>/' + this.typeId,
                        selectID: 'serial_id',
                        colModel: [{
                                display: 'Serial',
                                name: 'serial_id',
                                sortAble: false,
                                align: 'center',
                                export: true
                            },
                            {
                                display: 'Pin',
                                name: 'serial_pin',
                                sortAble: false,
                                align: 'center',
                                export: true
                            },
                            {
                                display: 'Aktif',
                                name: 'serial_is_active',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    return params == '1' ? '<span class="badge badge-light-success badge-pill badge-round" title="Ya" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>' : '<span class="badge badge-light-danger badge-pill badge-round" title="Tidak" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>'
                                },
                                export: true
                            },
                            {
                                display: 'Digunakan',
                                name: 'serial_is_used',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    return params == '1' ? '<i class="bx bxs-check-circle success" title="Ya" data-toggle="tooltip"></i>' : '<i class="bx bxs-x-circle danger" title="Belum" data-toggle="tooltip"></i>'
                                },
                                export: true
                            },
                            {
                                display: 'Tgl Digunakan',
                                name: 'serial_used_datetime',
                                sortAble: false,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Detail',
                                name: '',
                                sortAble: false,
                                align: 'center',
                                action: {
                                    function: 'app.detailSerial',
                                    icon: 'bx bx-book info',
                                    class: 'warning'
                                }
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
                                display: 'Aktif',
                                name: 'serial_is_active',
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
                                display: 'Terjual',
                                name: 'serial_is_sold',
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
                                display: 'Terpakai',
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
                        ],
                        sortName: "serial_id",
                        sortOrder: "asc",
                        tableIsResponsive: true,
                        select: true,
                        multiSelect: true,
                        buttonAction: [
                            // {
                            //     display: 'Kirim',
                            //     icon: 'bx bxs-send',
                            //     style: "success",
                            //     action: "addSerial",
                            // },
                            {
                                display: 'Aktifkan',
                                icon: 'bx bxs-bulb',
                                style: "success",
                                action: "active",
                                url: window.location.origin + "/admin/service/serial/activeSerial/<?= $tipeSerial; ?>"
                            },
                            {
                                display: 'Non-Aktifkan',
                                icon: 'bx bxs-bulb',
                                style: "danger",
                                action: "nonactive",
                                url: window.location.origin + "/admin/service/serial/nonActiveSerial/<?= $tipeSerial; ?>"
                            },
                        ]
                    })
                },

                save() {
                    $('#modalApp').modal('hide');
                    let serial = this.serial.map((elem) => ({
                        member_id: $("#select_stockist").val(),
                        ...elem
                    }))

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
                                url: window.location.origin + '/admin/service/serial/addRegistrationSerial',
                                method: 'POST',
                                data: {
                                    serial: serial,
                                },
                                success: function(response) {
                                    if (response.status == 200) {
                                        app.alert.success.status = true;
                                        app.alert.success.content = 'Serial berhasil dikirim.';

                                        setTimeout(() => {
                                            app.alert.success.content = '';
                                            app.alert.success.status = false;
                                        }, 4000);
                                        app.generateTable();

                                        $('#modalApp').modal('hide');
                                    }
                                },
                                error: function(err) {
                                    app.alert.danger.status = true;
                                    app.alert.danger.content = 'Serial gagal dikirim.';

                                    setTimeout(() => {
                                        app.alert.danger.content = '';
                                        app.alert.danger.status = false;
                                    }, 4000);

                                    app.generateTable();

                                    $('#modalApp').modal('hide')
                                }
                            });
                        } else {
                            $('#modalApp').modal()
                        }
                    });

                    $("#select_stockist").val('')
                    $('#select_stockist').val(null).trigger('change');
                },
                isNumber: function(evt) {
                    evt = (evt) ? evt : window.event;
                    var charCode = (evt.which) ? evt.which : evt.keyCode;
                    if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                        evt.preventDefault();;
                    } else {
                        return true;
                    }
                }

            },
            computed: {},
            mounted() {
                this.hideLoading();
            }

        }).mount("#modul");


    function addSerial() {
        app.addSerial();
    }

    $('#select_stockist').change(() => {
        if ($('#select_stockist').val()) {
            app.addBtn = false;
        } else {
            app.addBtn = true;
        }
    })
</script>