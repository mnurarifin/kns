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
            <div class="modal-dialog modal-dialog-scrollable" role="document">
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
                                        <label>Deskripsi</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <textarea v-model="form.running_text_description" class="form-control" placeholder="Deskripsi"></textarea>
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
                        <button onclick="app.save()" class="btn btn-primary" :disabled="button.formBtn.disabled" id="submitModal">
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
</section>

<script src="https://unpkg.com/vue@next"></script>

<script>
    $(document).ready(function() {
        app.hideLoading();
        app.generateTable();
    });

    function add() {
        app.add();
    }

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
                        running_text_id: '',
                        running_text_description: '',
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
                        url: window.location.origin + '/admin/service/announcement/data/',
                        selectID: 'running_text_id',
                        colModel: [{
                                display: 'Deskripsi',
                                name: 'running_text_description',
                                sortAble: false,
                                align: 'left',
                                width: '180px'
                            },
                            {
                                display: 'Aktif',
                                name: 'running_text_is_active',
                                sortAble: false,
                                align: 'left',
                                render: (params) => {
                                    var status;
                                    switch (params) {
                                        case '1':
                                            status = '<span class="badge badge-light-success badge-pill badge-round" title="Aktif" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>';
                                            break;
                                        default:
                                            status = '<span class="badge badge-light-danger badge-pill badge-round" title="Non Aktif" data-toggle="tooltip"><i class="bx bx-bulb"></i></span>';
                                            break;
                                    }
                                    return status
                                },
                            },

                            {
                                display: 'Update',
                                name: 'running_text_id',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    return `<a onclick="app.update(${params})"> <i class="bx bx-edit-alt warning"></i> </a> `;
                                }
                            },
                        ],
                        options: {
                            limit: [10, 15, 20],
                            currentLimit: 10,
                        },
                        search: true,
                        searchTitle: 'Pencarian',
                        searchItems: [],
                        sortName: "running_text_id",
                        sortOrder: "ASC",
                        tableIsResponsive: true,
                        select: true,
                        multiSelect: true,
                        buttonAction: [{
                                display: 'Tambah',
                                icon: 'bx bx-plus',
                                style: "info",
                                action: "add"
                            },
                            {
                                display: 'Aktifkan',
                                icon: 'bx bxs-bulb',
                                style: "success",
                                action: "active",
                                url: window.location.origin + "/admin/service/announcement/active"
                            },
                            {
                                display: 'Non Aktifkan',
                                icon: 'bx bx-bulb',
                                style: "warning",
                                action: "nonactive",
                                url: window.location.origin + "/admin/service/announcement/nonactive"
                            },
                            {
                                display: 'Hapus',
                                icon: 'bx bx-trash',
                                style: "danger",
                                action: "remove",
                                url: window.location.origin + "/admin/service/announcement/remove"
                            },
                        ]
                    });
                },
                modalAddUpdate() {
                    $('#modalAddUpdateBanner').modal();
                },
                add() {
                    this.data.title = "Tambah Data";
                    this.data.btnTitle = "Tambah";
                    this.data.btnAction = "insert";

                    app.form = {
                        running_text_description: ''
                    };

                    this.modalAddUpdate();
                },
                update(id) {
                    this.data.title = "Ubah Data";
                    this.data.btnTitle = "Simpan";
                    this.data.btnAction = "update";

                    $.ajax({
                        url: window.location.origin + '/admin/service/announcement/detail',
                        method: 'GET',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data.results;

                                app.form = data;
                            }
                        }
                    });

                    this.modalAddUpdate();
                },
                save() {
                    let actionUrl = this.data.btnAction == 'insert' ? window.location.origin +
                        '/admin/service/announcement/add' : window.location.origin + '/admin/service/announcement/update'

                    $.ajax({
                        url: actionUrl,
                        method: 'POST',
                        data: this.form,
                        success: function(response) {
                            if (response.status == 200) {
                                if (app.data.btnAction == 'insert') {
                                    let data = response.data.results;
                                    app.form = {
                                        running_text_description: '',
                                    };
                                }
                                app.alert.success.content = response.message;
                                app.alert.success.status = true;

                                $('#modalAddUpdateBanner').modal('hide');

                                setTimeout(() => {
                                    app.alert.success.status = false;
                                }, 5000);
                                $.refreshTable('table');
                            }
                        },
                        error: function(res) {
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
                }
            }
        }).mount("#app");
</script>