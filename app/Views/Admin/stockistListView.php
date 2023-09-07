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
                        <div class="alert alert-success " v-show="alert.success.status" style="display: none;">
                            <span v-html="alert.success.content"></span>
                        </div>
                        <div class="alert alert-danger " v-show="alert.danger.status" style="display: none;">
                            <span v-html="alert.danger.content"></span>
                        </div>
                        <p class="card-text"></p>
                        <div id="response-messages"></div>
                        <div id="table"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalApp" role="dialog" aria-labelledby="modalAppTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center" id="modalDetailTitle">
                        {{data.title}}
                        <span></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body" style="min-height: calc(100vh - 350px);">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label" for="basic-default-fullname">Pilih Mitra</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select id="select_stockist" data-placeholder="Pilih Mitra" class="select2" aria-hidden="true">
                                    <option value=""></option>
                                    <option v-for="item in stockist" :value="item.member_id">
                                        {{item.member_network_code}} - {{item.member_name}} ({{item.member_mobilephone}})
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Nama Mitra</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" v-model="form.member_name" class="form-control" disabled>
                                <small class="text-danger errorAlert member_name" style="display: none;"></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Tipe Stokist</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select type="text" v-model="form.stockist_type" class="form-control">
                                    <option value="master">Master Stokist</option>
                                    <option value="mobile">Stokis</option>
                                </select>
                                <small class="text-danger errorAlert stockist_type" style="display: none;"></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Nama Stokis</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" v-model="form.stockist_name" class="form-control">
                                <small class="text-danger errorAlert stockist_name" style="display: none;"></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">No. Hp</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" v-model="form.stockist_mobilephone" class="form-control">
                                <small class="text-danger errorAlert stockist_mobilephone" style="display: none;"></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Email</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" v-model="form.stockist_email" class="form-control">
                                <small class="text-danger errorAlert stockist_email" style="display: none;"></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Provinsi</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-control" v-model="form.stockist_province_id" @change="changeProvince($event)">
                                    <option v-for="item in province_select" :value="item.province_id">{{item.province_name}}</option>
                                </select>
                                <small class="text-danger errorAlert stockist_province_id" style="display: none;"></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Kota</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-control" v-model="form.stockist_city_id" @change="changeCity($event)">
                                    <option v-for="item in city_select" :value="item.city_id">{{item.city_name}}</option>
                                </select>
                                <small class="text-danger errorAlert stockist_city_id" style="display: none;"></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Kecamatan</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-control" v-model="form.stockist_subdistrict_id">
                                    <option v-for="item in subdistrict_select" :value="item.subdistrict_id">{{item.subdistrict_name}}</option>
                                </select>
                                <small class="text-danger errorAlert stockist_subdistrict_id" style="display: none;"></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Alamat</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <textarea v-model="form.stockist_address" class="form-control"></textarea>
                                <small class="text-danger errorAlert stockist_address" style="display: none;"></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tutup</span>
                    </button>
                    <button onclick="app.save()" class="btn btn-success" :disabled="button.formBtn.disabled" id="submitModal">
                        <div class="d-flex align-center">{{ data.btnTitle }}</div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript" defer>
    $(document).ready(function() {
        app.getMember()
        app.generateTable();

        // Select 2
        $('.select2').select2({
            allowClear: true,
            width: '100%',
            placeholder: 'Mitra Tujuan',
        });

        $('#select_stockist').on('select2:open', function(e) {
            $('input.select2-search__field').prop('placeholder', 'Silakan masukan kode mitra, nama mitra.');
        });
    })
    let app =
        Vue.createApp({
            data: function() {
                return {
                    button: {
                        formBtn: {
                            disabled: false
                        }
                    },
                    data: {
                        title: "",
                        btnTitle: "",
                        btnAction: "",
                    },
                    form: {
                        stockist_member_id: '',
                        member_account_username: '',
                        stockist_type: '',
                        stockist_name: '',
                        member_name: '',
                        stockist_city_id: '',
                        stockist_province_id: '',
                        stockist_mobilephone: ''
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
                    province_select: [],
                    city_select: [],
                    subdistrict_select: [],
                    stockist: []
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },
                add() {
                    this.data.title = "Tambah Stokis"
                    this.data.btnAction = 'insert'
                    this.data.btnTitle = 'Tambah'
                    $('#modalApp').modal();
                },
                update(id) {
                    this.data.title = "Ubah Stokis"
                    this.data.btnAction = 'update'
                    this.data.btnTitle = 'Ubah'

                    $.ajax({
                        url: window.location.origin + '/admin/service/stockist/detailData',
                        method: 'GET',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data.results;

                                app.form = data;

                                $('#select_stockist').val(data.stockist_member_id)
                                $('#select_stockist').trigger('change')

                                app.getSelectCity(data.stockist_province_id)
                                app.getSelectSubdistrict(data.stockist_city_id)
                            }
                        }
                    });

                    $('#modalApp').modal();
                },
                generateTable() {
                    $("#table").dataTableLib({
                        url: window.location.origin + '/admin/service/stockist/getData',
                        selectID: 'stockist_member_id',
                        colModel: [{
                                display: 'Aksi',
                                name: 'stockist_member_id',
                                sortAble: false,
                                align: 'center',
                                width: '40px',
                                render: (params) => {
                                    return `<a title="Ubah" class="cstmHover" data-toggle="tooltip" onclick="app.update(${params})"> <i class="bx bx-edit-alt warning" ></i> </a> `;
                                }
                            }, {
                                display: 'Nama Stokis',
                                name: 'stockist_name',
                                sortAble: false,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Tipe',
                                name: 'stockist_type',
                                sortAble: false,
                                align: 'left',
                                export: true,
                                render: (params) => {
                                    return params == 'master' ? 'Master Stokis' : 'Stokis'
                                }
                            },
                            {
                                display: 'Kode Mitra',
                                name: 'member_account_username',
                                sortAble: false,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Pemilik',
                                name: 'member_name',
                                sortAble: false,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Kota',
                                name: 'city_name',
                                sortAble: false,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'No. HP',
                                name: 'stockist_mobilephone',
                                sortAble: false,
                                align: 'left',
                                export: true
                            },
                            {
                                display: 'Status',
                                name: 'stockist_is_active',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    return params == '1' ? '<span class="badge badge-light-success badge-pill badge-round" title="Ya" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>' : '<span class="badge badge-light-danger badge-pill badge-round" title="Tidak" data-toggle="tooltip"><i class="bx bx-bulb"></i></span>'
                                },
                                export: true
                            },
                        ],
                        buttonAction: [{
                                display: 'Tambah',
                                icon: 'bx bx-plus',
                                style: "success",
                                action: "add"
                            }, {
                                display: 'Aktifkan',
                                icon: 'bx bxs-bulb',
                                style: "success",
                                action: "active",
                                url: window.location.origin + "/admin/service/stockist/actActive"
                            },
                            {
                                display: 'Non Aktifkan',
                                icon: 'bx bx-bulb',
                                style: "danger",
                                action: "nonactive",
                                url: window.location.origin + "/admin/service/stockist/actUnactive"
                            },
                        ],
                        options: {
                            limit: [10, 25, 50, 100],
                            currentLimit: 10,
                        },
                        search: true,
                        searchTitle: 'Pencarian Data Stokis',
                        searchItems: [{
                                display: 'Nama Stokis',
                                name: 'stockist_name',
                                type: 'text'
                            }, {
                                display: 'Tipe',
                                name: 'stockist_type',
                                type: 'select',
                                option: [{
                                    title: "Master",
                                    value: "master",
                                }, {
                                    title: "Mobile",
                                    value: "mobile",
                                }]
                            },
                            {
                                display: 'Kota',
                                name: 'city_name',
                                type: 'text'
                            },
                            {
                                display: 'Nama Pemilik',
                                name: 'member_name',
                                type: 'text'
                            },
                            {
                                display: 'Username',
                                name: 'member_account_username',
                                type: 'text'
                            },
                            {
                                display: 'Status',
                                name: 'stockist_is_active',
                                type: 'select',
                                option: [{
                                    title: 'Aktif',
                                    value: '1'
                                }, {
                                    title: 'Tidak Aktif',
                                    value: '0'
                                }]
                            },
                        ],
                        sortName: "stockist_member_id",
                        sortOrder: "asc",
                        tableIsResponsive: true,
                        select: true,
                        multiSelect: true,
                    })
                },

                save() {
                    $('#submitModal').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memproses...')
                    $('.errorAlert').hide()
                    let actionUrl = this.data.btnAction == 'insert' ? window.location.origin +
                        '/admin/service/stockist/add' : window.location.origin + '/admin/service/stockist/update'

                    $.ajax({
                        url: actionUrl,
                        method: 'POST',
                        data: this.form,
                        success: function(response) {
                            if (response.status == 200) {
                                $('#submitModal').html('<div class="d-flex align-center">Ubah</div>')
                                if (app.data.btnAction == 'insert') {
                                    $('#submitModal').html('<div class="d-flex align-center">Tambah</div>')
                                    let data = response.data.results;
                                    app.form = {
                                        running_text_description: '',
                                    };
                                }
                                app.alert.success.content = response.message;
                                app.alert.success.status = true;

                                $('#modalApp').modal('hide');

                                setTimeout(() => {
                                    app.alert.success.status = false;
                                }, 5000);
                                app.generateTable();
                            }
                        },
                        error: function(res) {
                            if (app.data.btnAction == 'insert') {
                                $('#submitModal').html('<div class="d-flex align-center">Tambah</div>')
                            } else {
                                $('#submitModal').html('<div class="d-flex align-center">Ubah</div>')
                            }
                            let response = res.responseJSON;

                            if (response.status == 400 && response.message == "validationError") {
                                $.each(response.data.validationMessage, (key, val) => {
                                    $('.' + key).show()
                                    $('.' + key).text(val)
                                })
                                let resValidation = Object.values(response.data.validationMessage);

                                setTimeout(() => {
                                    app.alert.danger.status = false;
                                }, 5000);

                            }
                        },

                    })
                },
                getMember() {
                    $.ajax({
                        url: window.location.origin + '/admin/service/member/getMemberOptions',
                        method: 'GET',
                        success: function(response) {
                            app.stockist = response.data;
                        },

                    });
                },
                getSelectProvince() {
                    $.ajax({
                        url: window.location.origin + '/admin/service/stockist/getProvince',
                        method: 'GET',
                        success: function(response) {
                            if (response.status == 200) {
                                app.province_select = response.data.arrProvince;
                            }
                        },
                    });
                },
                getSelectCity(id) {
                    $.ajax({
                        url: window.location.origin + '/admin/service/stockist/getCity/' + id,
                        method: 'GET',
                        success: function(response) {
                            if (response.status == 200) {
                                app.city_select = response.data.arrCity;
                            }
                        },
                    });
                },
                getSelectSubdistrict(id) {
                    $.ajax({
                        url: window.location.origin + '/admin/service/stockist/getSubdistrict/' + id,
                        method: 'GET',
                        success: function(response) {
                            if (response.status == 200) {
                                app.subdistrict_select = response.data.arrSubdistrict;
                            }
                        },
                    });
                },
                changeProvince(event) {
                    $.ajax({
                        url: window.location.origin + '/admin/service/stockist/getCity/' + event.target.value,
                        method: 'GET',
                        success: function(response) {
                            if (response.status == 200) {
                                app.city_select = response.data.arrCity;
                            }
                        },
                    });
                },
                changeCity(event) {
                    $.ajax({
                        url: window.location.origin + '/admin/service/stockist/getSubdistrict/' + event.target.value,
                        method: 'GET',
                        success: function(response) {
                            if (response.status == 200) {
                                app.subdistrict_select = response.data.arrSubdistrict;
                            }
                        },
                    });
                }

            },
            computed: {},
            mounted() {
                this.hideLoading();
                this.getSelectProvince();
            }

        }).mount("#modul");


    function add() {
        app.add();
    }

    $('#select_stockist').change(() => {
        let find_index = app.stockist.find(i => i.member_id === $("#select_stockist").val());

        app.form.member_name = find_index.member_name
        app.form.member_account_username = find_index.member_network_code
        app.form.member_id = $("#select_stockist").val()
    })
</script>