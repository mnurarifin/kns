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

        <div class="modal fade" id="modalAddUpdateBusinessPlan" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdateBusinessPlan" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center" id="modalAddUpdateBusinessPlanTitle"> <span>{{data.title}}</span> </h5>
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
                                        <label>Judul Bisnis Plan</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="text" v-model="form.page_content" class="form-control" placeholder="Judul">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Gambar</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input ref="myFiles" @change="uploadImage('')" type="file" class="form-control" name="rewardImageFilename" placeholder="" id="myFiles">
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
</section>

<div id="modalDetailImage" class="modal" tabindex="-1" role="dialog" aria-labelledby="modalDetailImageTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalDetailImageTitle">Detail Gambar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body text-center" id="business-body">
            </div>
        </div>
    </div>
</div>

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
                        page_id: '',
                        page_content: '',
                        page_image: ''
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
                        url: window.location.origin + '/admin/service/businessplan/getDataBusinessPlan/',
                        selectID: 'page_id',
                        colModel: [{
                                display: 'Gambar',
                                name: 'page_image',
                                sortAble: false,
                                align: 'center',
                                action: {
                                    function: 'preview',
                                    icon: 'bx bx-show',
                                    class: 'info',
                                    style: 'info'
                                }
                            },
                            {
                                display: 'Urutan',
                                name: 'page_order_by',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    return `
                                        <button type="button" class="btn btn-icon btn-outline-success btn-sm mb-0" onclick="move(${params},'up')" style="padding-top:5px;padding-bottom:5px;"><i class="ficon bx bx-up-arrow-alt"></i></button>
                                        <button type="button" class="btn btn-icon btn-outline-warning btn-sm mb-0" onclick="move(${params},'down')" style="padding-top:5px;padding-bottom:5px;"><i class="ficon bx bx-down-arrow-alt"></i></button>
                                    `;
                                }
                            },
                            {
                                display: 'Judul',
                                name: 'page_content',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Aktif',
                                name: 'page_is_active',
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
                                name: 'page_id',
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
                        sortName: "page_order_by",
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
                                url: window.location.origin + "/admin/service/businessplan/activeBusinessPlan"
                            },
                            {
                                display: 'Non Aktifkan',
                                icon: 'bx bx-bulb',
                                style: "warning",
                                action: "nonactive",
                                url: window.location.origin + "/admin/service/businessplan/nonActiveBusinessPlan"
                            },
                            {
                                display: 'Hapus',
                                icon: 'bx bx-trash',
                                style: "danger",
                                action: "remove",
                                url: window.location.origin + "/admin/service/businessplan/deleteBusinessPlan"
                            },

                        ]
                    });
                },
                save() {
                    let actionUrl = this.data.btnAction == 'insert' ? window.location.origin +
                        '/admin/service/businessplan/actAddBusinessPlan' : window.location.origin + '/admin/service/businessplan/actUpdateBusinessPlan'

                    console.log(app.form)
                    $.ajax({
                        url: actionUrl,
                        method: 'POST',
                        data: this.form,
                        success: function(response) {
                            if (response.status == 200) {
                                if (app.data.btnAction == 'insert') {
                                    let data = response.data.results;
                                    app.form = {
                                        page_content: '',
                                        page_image: ''
                                    };
                                }
                                app.alert.success.content = response.message;
                                app.alert.success.status = true;

                                $('#modalAddUpdateBusinessPlan').modal('hide');

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
                },

                update(page_id) {
                    this.data.title = "Ubah Data";
                    this.data.btnTitle = "Ubah";
                    this.data.btnAction = "update";

                    $.ajax({
                        url: window.location.origin + '/admin/service/businessplan/detailData',
                        method: 'GET',
                        data: {
                            id: page_id
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

                uploadImage(type) {
                    $('#submitModal').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')

                    let formData = new FormData();
                    let image = this.$refs.myFiles.files[0]

                    formData.append('file', image);
                    formData.append('type', type);

                    $.ajax({
                        url: window.location.origin + '/admin/service/businessplan/uploadImage',
                        method: 'POST',
                        data: formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data;

                                app.form.page_image = data.name;

                                setTimeout(function() {
                                    app.button.formBtn.disabled = false;
                                    $('#submitModal').html('Simpan');
                                }, 3000);
                            }
                        },
                        error: function(res) {
                            let response = res.responseJSON;
                            app.button.formBtn.disabled = false;
                            $('#submitModal').html('Simpan');
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
                                    }, 3000);
                                }

                            }
                        },
                    });
                },

                openModal() {
                    $('#myFiles').val('');
                    $('#modalAddUpdateBusinessPlan').modal();
                },
                add() {
                    this.data.title = "Tambah Bisnis Plan";
                    this.data.btnTitle = "Tambah";
                    this.data.btnAction = "insert";

                    app.form = {
                        page_content: '',
                        page_image: ''
                    };

                    this.openModal();
                }
            },
        }).mount("#app");

    function move(order, operator) {
        $('.spinnerLoad').fadeIn('fast');
        $.ajax({
            url: window.location.origin + '/admin/service/businessplan/setOrder',
            method: 'POST',
            data: {
                page_order_by: order,
                page_operator: operator
            },
            success: function(response) {
                if (response.status == 200) {
                    app.generateTable();
                    $('.spinnerLoad').fadeOut('fast');

                }
            },
            error: function(err) {
                location.reload();
                $('.spinnerLoad').fadeOut('fast');
            }
        })
    }

    function add() {
        app.add();
    }


    $(document).ready(function() {
        app.hideLoading();
        app.generateTable();
    });

    function preview(params) {
        $('#modalDetailImage').modal('show');
        $('#partner-body').html('')
        var htmlBody = '';
        var urlAsset = "<?php echo $imagePath; ?>";

        htmlBody = `<img src="${urlAsset}${params.page_img}" width="450px">`

        $('#business-body').html(htmlBody)
    }
</script>