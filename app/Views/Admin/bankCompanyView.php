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

    .alert-position {
        transform: translateY(5px);
    }
</style>

<script src="https://unpkg.com/vue@next"></script>
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
                        <div class="col-12">
                            <div class="alert alert-success " v-show="alert.success.status" style="display: none;">
                                <span v-html="alert.success.content"></span>
                            </div>
                        </div>
                        <div id="table"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAddUpdate" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdate" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddUpdateTitle">{{modal.data.title}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form class="form form-horizontal" id="formAddUpdateBankCompany">
                    <div class="modal-body">
                        <div class="alert alert-danger" v-show="alert.danger.status" style="display: none;">
                            <span v-html="alert.danger.content"></span>
                        </div>
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Jenis Bank</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select class="form-control" v-model="modal.form.bank_company_bank_id">
                                        <option value="" selected disabled>PILIH</option>
                                        <option v-for="item in bank_select" :value="item.bank_id">{{item.bank_name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Nama Akun Bank</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="text" class="form-control" onkeydown="return app.checkNamaRekening(event)" v-model="modal.form.bank_company_bank_acc_name" placeholder="Nama Akun">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Nomor Rekening</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="text" class="form-control" onkeydown="return app.checkRekening(event)" v-model="modal.form.bank_company_bank_acc_number" placeholder="No. Rekening">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tutup</span>
                    </button>
                    <button onclick="app.save()" class="btn btn-primary" :disabled="button.formBtn.disabled" id="submit">
                        <div class="d-flex align-center">{{ modal.data.btnTitle }}</div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

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
                    form: {
                        bank_company_id: '',
                        bank_company_bank_id: '',
                        bank_company_bank_acc_name: '',
                        bank_company_bank_acc_number: '',
                        bank_company_bank_is_active: '',
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
                bank_select: [],
            }
        },
        methods: {
            hideLoading() {
                $("#pageLoader").hide();
            },
            generateTable() {
                $("#table").dataTableLib({
                    url: window.location.origin + '/admin/service/bank_company/getDataBankCompany',
                    selectID: 'bank_company_id',
                    colModel: [{
                            display: 'Nama Bank',
                            name: 'bank_name',
                            sortAble: false,
                            align: 'left',
                            width: "150px",
                            export: true
                        },
                        {
                            display: 'Nama Akun Bank',
                            name: 'bank_company_bank_acc_name',
                            sortAble: false,
                            align: 'left',
                            export: false
                        },
                        {
                            display: 'Nomor Rekening',
                            name: 'bank_company_bank_acc_number',
                            sortAble: false,
                            align: 'left',
                            export: true
                        },
                        {
                            display: 'Status Rekening',
                            name: 'bank_company_bank_is_active',
                            sortAble: false,
                            align: 'center',
                            render: (params) => {
                                return params == '1' ? '<span class="badge badge-light-success badge-pill badge-round" title="Aktif" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>' : '<span class="badge badge-light-danger badge-pill badge-round" title="Non Aktif" data-toggle="tooltip"><i class="bx bx-bulb"></i></span>'
                            },
                            export: true
                        },
                        {
                            display: 'Ubah',
                            name: 'bank_company_id',
                            sortAble: false,
                            align: 'center',
                            render: (params) => {
                                return `<a onclick="app.update(${params})">  <i class="bx bx-edit-alt warning"></i> </a> `;
                            }
                        },
                    ],
                    buttonAction: [{
                            display: 'Tambah',
                            icon: 'bx bx-plus',
                            style: "info",
                            action: "add"
                        }, {
                            display: 'Aktifkan',
                            icon: 'bx bxs-bulb',
                            style: "success",
                            action: "active",
                            url: window.location.origin + "/admin/service/bank_company/activeBank"
                        },
                        {
                            display: 'Non Aktifkan',
                            icon: 'bx bx-bulb',
                            style: "warning",
                            action: "nonactive",
                            url: window.location.origin + "/admin/service/bank_company/nonactiveBank"
                        },
                        {
                            display: 'Hapus',
                            icon: 'bx bx-trash',
                            style: "danger",
                            action: "remove",
                            url: window.location.origin + "/admin/service/bank_company/removeBankCompany"
                        },
                    ],
                    options: {
                        limit: [10, 25, 50, 100],
                        currentLimit: 10,
                    },
                    search: true,
                    searchTitle: 'Pencarian Data Bank Company',
                    searchItems: [{
                            display: 'Nama Bank',
                            name: 'bank_name',
                            type: 'text'
                        },
                        {
                            display: 'Nama Akun Bank',
                            name: 'bank_company_bank_acc_name',
                            type: 'text'
                        },
                    ],
                    sortName: "bank_company_id",
                    sortOrder: "asc",
                    tableIsResponsive: true,
                    select: true,
                    multiSelect: true,
                })
            },
            add() {
                this.modal.data.title = "Tambah Data";
                this.modal.data.btnTitle = "Tambah";
                this.modal.data.btnAction = "insert";

                app.modal.form = {
                    bank_company_bank_id: '',
                    bank_company_bank_acc_name: '',
                    bank_company_bank_acc_number: '',
                };

                this.openModal();
            },
            update(bank_company_id) {
                this.modal.data.title = "Ubah Bank Company";
                this.modal.data.btnTitle = "Ubah";
                this.modal.data.btnAction = "update";

                $.ajax({
                    url: window.location.origin + '/admin/service/bank_company/detail',
                    method: 'GET',
                    data: {
                        id: bank_company_id
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            let data = response.data.results;

                            app.modal.form = data;
                        }
                    }
                });

                this.openModal();
            },
            openModal() {
                $('#modalAddUpdate').modal()
            },
            getSelectBank() {
                $.ajax({
                    url: window.location.origin + '/admin/service/bank_company/getDataBank',
                    method: 'GET',
                    success: function(response) {
                        if (response.status == 200) {
                            app.bank_select = response.data;
                        }
                    },
                });
            },
            save() {
                let actionUrl = this.modal.data.btnAction == 'insert' ? window.location.origin +
                    '/admin/service/bank_company/actAddBankCompany' : window.location.origin + '/admin/service/bank_company/actUpdateBankCompany'

                $.ajax({
                    url: actionUrl,
                    method: 'POST',
                    data: this.modal.form,
                    success: function(response) {
                        if (response.status == 200) {
                            if (app.modal.data.btnAction == 'insert') {
                                let data = response.data.results;
                                app.modal.form = {
                                    bank_company_bank_id: '',
                                    bank_company_bank_acc_name: '',
                                    bank_company_bank_acc_number: '',
                                };
                            }
                            app.alert.success.content = response.message;
                            app.alert.success.status = true;

                            $('#modalAddUpdate').modal('hide');

                            setTimeout(() => {
                                app.alert.success.status = false;
                            }, 5000);
                            app.generateTable();
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
            checkRekening(val) {
                let unicode = val.which;
                return ((unicode >= 48 && unicode <= 57) || unicode == 189 || unicode == 190 || unicode == 8 || unicode == 37 || unicode == 39)
            },
            checkNamaRekening(val) {
                let unicode = val.which;
                return ((unicode >= 65 && unicode <= 90) || unicode == 190 || unicode == 8 || unicode == 32 || unicode == 37 || unicode == 39)
            },
        },
        mounted() {
            this.getSelectBank()
        }
    }).mount('#app');

    function add() {
        app.add()
    }
</script>