<style>
    .spinnerLoad {
        display: none;
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 99999;
        background: rgba(95, 82, 82, 0.58);
    }

    .center {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
</style>

<div class="spinnerLoad">
    <div class="center">
        <div class="spinner-border spinner-border-lg text-success" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>

<section id="app">
    <div class="row">

        <div class="modal fade" id="modalAddUpdateBanner" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdateBanner" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center" id="modalAddUpdateBannerTitle"> <span>{{data.title}}</span> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form form-horizontal" id="formGaleri">
                            <div class="alert alert-danger" v-show="alert.danger.status" style="display: none;">
                                <span v-html="alert.danger.content"></span>
                            </div>

                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Kode Konfigurasi</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="text" v-model="form.config_code" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Nama Konfigurasi</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="text" v-model="form.config_name" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Nilai Konfigurasi</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <textarea v-model="form.config_value" class="form-control" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                        <div id="table"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBlast" tabindex="-1" role="dialog" aria-labelledby="modalBlast" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center" id="modalBlastTitle"> <span>Blast Message</span> </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form form-horizontal" id="formGaleri">
                        <div class="alert alert-danger pb-50" v-show="alert.danger.status" style="display: none;">
                            <span v-html="alert.danger.content"></span>
                        </div>
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Isi Pesan</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <textarea class="form-control" v-model="message" rows='15'></textarea>
                                </div>
                            </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tutup</span>
                    </button>
                    <button onclick="app.send()" class="btn btn-success" id="submitBlast">
                        <div class="d-flex align-center">Kirim</div>
                    </button>
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
                        config_code: '',
                        config_name: '',
                        config_value: ''
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
                    message: ''
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },
                generateTable() {
                    $("#table").dataTableLib({
                        url: window.location.origin + '/admin/service/configuration/getDataConfig/',
                        selectID: 'config_id',
                        colModel: [{
                                display: 'Kode Konfigurasi',
                                name: 'config_code',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Nama Konfigurasi',
                                name: 'config_name',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Nilai Konfigurasi',
                                name: 'config_value',
                                sortAble: false,
                                align: 'left',
                                width: '300px'
                            },
                            {
                                display: 'Ubah',
                                name: 'config_id',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    return `<a onclick="app.update(${params})"> <i class="bx bx-edit-alt warning"></i> </a> `;
                                }
                            },
                        ],
                        options: {
                            limit: [10, 15, 20, 100],
                            currentLimit: 100,
                        },
                        search: false,
                        searchTitle: 'Pencarian',
                        searchItems: [],
                        sortName: "config_id",
                        sortOrder: "ASC",
                        tableIsResponsive: true,
                        select: false,
                        multiSelect: false,
                        buttonAction: [{
                                display: 'Blast Message',
                                icon: 'bx bx-send',
                                style: "info",
                                action: "blast"
                            },
                            // {
                            //     display: 'Tambah',
                            //     icon: 'bx bx-plus',
                            //     style: "info",
                            //     action: "add"
                            // },
                            // {
                            //     display: 'Hapus',
                            //     icon: 'bx bx-trash',
                            //     style: "danger",
                            //     action: "remove",
                            //     url: window.location.origin + "/admin/service/configuration/remove"
                            // },
                        ]
                    });
                },
                blast() {
                    $('#modalBlast').modal();
                },
                save() {
                    $('#submitModal').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')

                    let actionUrl = this.data.btnAction == 'insert' ? window.location.origin +
                        '/admin/service/configuration/add' : window.location.origin + '/admin/service/configuration/update'

                    $.ajax({
                        url: actionUrl,
                        method: 'POST',
                        data: this.form,
                        success: function(response) {
                            if (response.status == 200) {
                                if (app.data.btnAction == 'insert') {
                                    let data = response.data.results;
                                    app.form = {
                                        config_code: '',
                                        config_name: '',
                                        config_value: '',
                                    };
                                }
                                app.alert.success.content = response.message;
                                app.alert.success.status = true;

                                $('#modalAddUpdateBanner').modal('hide');
                                $('#submitModal').html('<div class="d-flex align-center">Simpan</div>')

                                setTimeout(() => {
                                    app.alert.success.status = false;
                                }, 5000);
                                $.refreshTable('table');
                            }
                        },
                        error: function(res) {
                            $('#submitModal').html('<div class="d-flex align-center">Simpan</div>')
                            let response = res.responseJSON;
                            if (response.status == 400 && response.message == "validationError") {
                                let resValidation = Object.values(response.data.validationMessage);

                                if (resValidation.length > 0) {
                                    app.alert.danger.content = `<ul>`;
                                    resValidation.forEach((data) => {
                                        app.alert.danger.content +=
                                            `<li> ${data} </li>`;
                                    });
                                    app.alert.danger.content += `</ul>`;
                                    app.alert.danger.status = true;

                                    setTimeout(() => {
                                        app.alert.danger.status = false;
                                    }, 5000);
                                }

                            }
                        },

                    })
                },
                send() {
                    $('#submitBlast').attr('disabled', true)
                    $('#submitBlast').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')

                    $.ajax({
                        url: '/admin/service/configuration/blast',
                        method: 'POST',
                        data: {
                            message: this.message
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                app.message = ''
                                app.alert.success.content = response.message;
                                app.alert.success.status = true;

                                $('#modalBlast').modal('hide');
                                $('#submitBlast').attr('disabled', false)
                                $('#submitBlast').html('<div class="d-flex align-center">Simpan</div>')

                                setTimeout(() => {
                                    app.alert.success.status = false;
                                }, 5000);
                                $.refreshTable('table');
                            }
                        },
                        error: function(res) {
                            $('#submitBlast').attr('disabled', false)
                            $('#submitBlast').html('<div class="d-flex align-center">Simpan</div>')
                            let response = res.responseJSON;
                            if (response.status == 400 && response.message == "validationError") {
                                let resValidation = Object.values(response.data.validationMessage);

                                if (resValidation.length > 0) {
                                    app.alert.danger.content = `<ul>`;
                                    resValidation.forEach((data) => {
                                        app.alert.danger.content +=
                                            `<li> ${data} </li>`;
                                    });
                                    app.alert.danger.content += `</ul>`;
                                    app.alert.danger.status = true;

                                    setTimeout(() => {
                                        app.alert.danger.status = false;
                                    }, 5000);
                                }

                            }
                        },

                    })
                },
                update(config_id) {
                    this.data.title = "Ubah Konfigurasi";
                    this.data.btnTitle = "Ubah";
                    this.data.btnAction = "update";

                    $.ajax({
                        url: window.location.origin + '/admin/service/configuration/detailData',
                        method: 'GET',
                        data: {
                            id: config_id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data.results;

                                app.form = data;
                            }
                        }
                    });

                    this.openModal();
                },

                openModal() {
                    $('#myFiles').val('');
                    $('#modalAddUpdateBanner').modal();
                },
                add() {
                    this.data.title = "Tambah Konfigurasi";
                    this.data.btnTitle = "Tambah";
                    this.data.btnAction = "insert";

                    app.form = {
                        config_code: '',
                        config_name: '',
                        config_value: '',
                    };

                    this.openModal();
                }
            },
        }).mount("#app");

    function add() {
        app.add();
    }

    function blast() {
        app.blast();
    }

    $(document).ready(function() {
        app.hideLoading();
        app.generateTable();
    });
</script>