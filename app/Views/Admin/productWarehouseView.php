<section id="warehouse">
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
                                <div id="table-warehouse"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAddUpdate" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdateTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center" id="modalAddUpdateTitle"> <span>
                            {{ modal.data.title}} </span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" v-show="alert.danger.status" style="display: none;">
                        <span v-html="alert.danger.content"></span>
                    </div>

                    <form class="form form-vertical">
                        <div class="form-body">
                            <div class="row ma-5">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="warehouse_name">Nama</label>
                                        <input v-model="modal.form.warehouse_name" type="text" id="warehouse_name" class="form-control" name="warehouse_name" placeholder="Masukan Nama Gudang">
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="warehouse_address">Alamat</label>
                                        <textarea v-model="modal.form.warehouse_address" type="text" id="warehouse_address" class="form-control" name="warehouse_address" placeholder="Masukan Alamat Gudang"></textarea>
                                    </div>
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
                    <button onclick="app.saveWarehouse()" class="btn btn-success" :disabled="button.formBtn.disabled" id="draft" type="submit">
                        <div class="d-flex align-center">{{ modal.data.btnTitle }}</div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdateTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center" id="modalDetailTitle"> {{ modalDetail.data.title}}
                        <span></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form form-vertical">
                        <div class="form-body">
                            <div class="row ma-5">
                                <div class="col-sm-12 col-md-12 col-lg-">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="form-group" style="padding-top: 20px;">
                                                <h5 class="text-primary"> {{modalDetail.form.warehouse_name}}</h5>
                                                <h6> {{modalDetail.form.warehouse_address}}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

<script src="https://unpkg.com/vue@next"></script>
<script type="text/javascript" defer>
    $(document).ready(function() {
        window.history.pushState("", "", '/admin/product/warehouse');
        app.generateMessageTable('');
    });

    let app =
        Vue.createApp({
            data: function() {
                return {
                    button: {
                        formBtn: {
                            disabled: false
                        }
                    },
                    modal: {
                        data: {
                            title: "Tambah Gudang",
                            btnTitle: "Tambah",
                            btnAction: "insert",
                        },
                        form: {
                            warehouse_name: '',
                            warehouse_address: '',
                            warehouse_is_active: true,
                        },
                        action: {
                        }
                    },
                    modalDetail: {
                        data: {
                            title: "",
                            btnTitle: "",
                            btnAction: "detail",
                        },
                        form: {
                            warehouse_name: '',
                            warehouse_address: '',
                            warehouse_is_active: true,
                        },
                        action: {

                        }
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
                    tab: {
                        current: 'active'
                    }
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },

                detailWarehouse(warehouse_id) {
                    $.ajax({
                        url: window.location.origin + '/admin/service/warehouse/detailWarehouse',
                        method: 'GET',
                        data: {
                            id: warehouse_id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data.results;

                                app.modalDetail.data.title =
                                    `Detail Warehouse`
                                app.modalDetail.form = data;
                            }
                        },

                    });
                    $('#modalDetail').modal();
                },

                changeTab(type) {
                    this.tab.current = type;
                    this.generateMessageTable();
                },

                saveWarehouse() {
                    let actionUrl = this.modal.data.btnAction == 'insert' ? window.location.origin +
                        '/admin/service/warehouse/addWarehouse' : window.location.origin + '/admin/service/warehouse/updateWarehouse'

                    this.modal.form.warehouse_is_active = this.modal.form.warehouse_is_active ? 1 : 0;

                    $.ajax({
                        url: actionUrl,
                        method: 'POST',
                        data: this.modal.form,
                        success: function(response) {
                            if (response.status == 200) {
                                if (app.modal.data.btnAction == 'insert') {
                                    let data = response.data.results;
                                    app.modal.form = {
                                        warehouse_name: '',
                                        warehouse_address: '',
                                        warehouse_is_active: true,
                                    };
                                }
                                app.alert.success.content = response.message;
                                app.alert.success.status = true;

                                $('#modalAddUpdate').modal('hide');

                                setTimeout(() => {
                                    app.alert.success.status = false;
                                }, 5000);
                                $.refreshTable('table-warehouse');
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

                addWarehouse() {
                    this.modal.data.title = "Tambah Gudang";
                    this.modal.data.btnTitle = "Tambah";
                    this.modal.data.btnAction = "insert";

                    app.modal.form = {
                        warehouse_name: '',
                        warehouse_address: '',
                        warehouse_is_active: true,
                    };
                    $('#modalAddUpdate').modal();
                },

                updateWarehouse(warehouse_id) {
                    $.ajax({
                        url: window.location.origin + '/admin/service/warehouse/detailWarehouse',
                        method: 'GET',
                        data: {
                            id: warehouse_id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data.results;

                                app.modal.form = data;
                            }
                        }
                    });
                    this.modal.data.title = "Ubah Gudang";
                    this.modal.data.btnTitle = "Ubah";
                    this.modal.data.btnAction = "update";
                    $('#modalAddUpdate').modal();
                },

                generateMessageTable() {
                    let type = this.tab.current == 'active' ? 1 : 0;

                    $("#table-warehouse").dataTableLib({
                        url: window.location.origin + '/admin/service/warehouse/getDataWarehouse/' + type,
                        selectID: 'warehouse_id',
                        colModel: [{
                                display: 'Nama',
                                name: 'warehouse_name',
                                sortAble: true,
                                align: 'left'
                            },
                            {
                                display: 'Alamat',
                                name: 'warehouse_address',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Aktif',
                                name: 'warehouse_is_active',
                                render: (params) => {
                                    return params == '1' ?
                                        '<span class="badge badge-light-success badge-pill badge-round" title="Aktif" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>' :
                                        '<span class="badge badge-light-danger badge-pill badge-round" title="Non Aktif" data-toggle="tooltip"><i class="bx bx-bulb"></i></span>';
                                },
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Detail',
                                name: 'warehouse_id',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    return `<a onclick="app.detailWarehouse(${params})"> <i class="bx bx-receipt success" ></i> </a> `;
                                }
                            },
                            {
                                display: 'Ubah',
                                name: 'warehouse_id',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    return `<a onclick="app.updateWarehouse(${params})"> <i class="bx bx-edit-alt warning" ></i> </a> `;
                                }
                            },
                        ],
                        options: {
                            limit: [10, 15, 20],
                            currentLimit: 10,
                        },
                        search: true,
                        searchTitle: 'Pencarian Data Gudang',
                        searchItems: [
                            {
                                display: 'Nama Gudang',
                                name: 'warehouse_name',
                                type: 'text'
                            },
                            {
                                display: 'Alamat Gudang',
                                name: 'warehouse_address',
                                type: 'text'
                            },
                        ],
                        sortName: "warehouse_code",
                        sortOrder: "ASC",
                        tableIsResponsive: true,
                        select: true,
                        multiSelect: true,
                        buttonAction: [{
                                display: 'Tambah',
                                icon: 'bx bx-plus',
                                style: "info",
                                action: "addWarehouse"
                            },
                            {
                                display: 'Aktifkan',
                                icon: 'bx bxs-bulb',
                                style: "success",
                                action: "active",
                                url: window.location.origin + "/admin/service/warehouse/activeWarehouse"
                            },
                            {
                                display: 'Non Aktifkan',
                                icon: 'bx bx-bulb',
                                style: "warning",
                                action: "nonactive",
                                url: window.location.origin + "/admin/service/warehouse/nonActiveWarehouse"
                            },
                            {
                                display: 'Hapus',
                                icon: 'bx bx-trash',
                                style: "danger",
                                action: "remove",
                                url: window.location.origin + "/admin/service/warehouse/deleteWarehouse"
                            },

                        ]
                    });
                    $('#add-warehouse').show();
                },
            },
            mounted() {
                this.hideLoading();
            }
        }).mount("#warehouse");

    function addWarehouse() {
        app.addWarehouse();
    }
</script>