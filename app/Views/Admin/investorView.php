<section id="investor">
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
                                <div id="table-investor"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAddUpdate" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdateTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
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
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <strong>DATA INVESTOR</strong>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="investor_title">Nama Investor</label>
                                                <input v-model="modal.form.investor_title" type="text" id="investor_title" class="form-control" name="investor_title" placeholder="Masukan Nama Investor">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="investor_email">Email Investor</label>
                                                <input v-model="modal.form.investor_email" type="email" id="investor_email" class="form-control" name="investor_email" placeholder="Masukan Email Investor">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label for="investor_join_date">Tanggal Mulai Investasi</label>
                                                <input v-model="modal.form.investor_join_date" type="date" id="investor_join_date" class="form-control" name="investor_join_date" placeholder="Masukan Tanggal Investasi">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label for="investor_administrator_group_id">Group Administrator</label>
                                                <select v-model="modal.form.investor_administrator_group_id" id="investor_administrator_group_id" class="form-control" name="investor_administrator_group_id" placeholder="Masukan Nama Investor">
                                                    <option value="0">------ Pilih Group Administrator ---------</option>
                                                    <option v-for="data in administratorGroupOptions.responseJSON" :value="data.value">{{data.title}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <fieldset>
                                                    <label for="investor_percentage">Persentase</label>
                                                    <div class="input-group">
                                                        <input type="number" name="investor_percentage" id="investor_percentage" class="form-control money" placeholder="Masukan Contoh: 0.5">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">%</span>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <strong>DATA BANK</strong>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label>Nama Bank</label>
                                                <select v-model="modal.form.investor_bank_id" name="member_bank_id" class="form-control" id="select-bank">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <label>Kota Bank</label>
                                            <input v-model="modal.form.investor_bank_city" type="text" name="member_bank_city" value="" size="30" class="form-control" />

                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <label>Cabang Bank</label>
                                            <input v-model="modal.form.investor_bank_branch" type="text" name="member_bank_branch" value="" size="30" class="form-control" />

                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <label>Nama Rekening</label>
                                            <input v-model="modal.form.investor_bank_account_name" type="text" name="member_bank_account_name" value="" size="30" class="form-control" />
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <label>No. Rekening</label>
                                            <input v-model="modal.form.investor_bank_account_no" type="text" name="member_bank_account_name" value="" size="30" class="form-control" />
                                        </div>

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
                    <button onclick="app.saveInvestor()" class="btn btn-success" :disabled="button.formBtn.disabled" id="draft" type="submit">
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
                        title: "Tambah Investor",
                        btnTitle: "Tambah",
                        btnAction: "insert",
                    },
                    form: {
                        investor_title: '',
                        investor_percentage: 0.5,
                        investor_administrator_group_id: 0,
                        investor_join_date: '',
                    },
                    action: {}
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
                administratorGroupOptions: $.ajax({
                    url: '/admin/service/investor/administrator_group_option',
                    type: 'GET',
                    async: false,
                    dataType: 'json',
                    success: function(response) {
                        return response;
                    }
                })
            }
        },
        methods: {
            getAdministratorGroupOtions() {},
            hideLoading() {
                $("#pageLoader").hide();
            },
            saveInvestor() {
                $('#draft').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memproses...')
                let actionUrl = this.modal.data.btnAction == 'insert' ? window.location.origin +
                    '/admin/service/investor/addInvestor' : window.location.origin + '/admin/service/investor/updateInvestor'

                this.modal.form.investor_title = $('#investor_title').val();
                this.modal.form.investor_percentage = $('#investor_percentage').val();
                this.modal.form.investor_administrator_group_id = $('#investor_administrator_group_id').val();

                $.ajax({
                    url: actionUrl,
                    method: 'POST',
                    data: this.modal.form,
                    success: function(response) {
                        if (response.status == 200) {
                            $('#draft').html('<div class="d-flex align-center">Ubah</div>')
                            if (app.modal.data.btnAction == 'insert') {
                                $('#draft').html('<div class="d-flex align-center">Tambah</div>')
                                let data = response.data.results;
                                app.modal.form = {
                                    investor_title: '',
                                    investor_percentage: 0,
                                    investor_administrator_group_id: 0,
                                    investor_join_date: '',
                                };
                            }
                            app.alert.success.content = response.message;
                            app.alert.success.status = true;

                            $('#modalAddUpdate').modal('hide');

                            setTimeout(() => {
                                app.alert.success.status = false;
                            }, 5000);
                            $.refreshTable('table-investor');
                        }
                    },
                    error: function(res) {
                        if (app.modal.data.btnAction == 'insert') {
                            $('#draft').html('<div class="d-flex align-center">Tambah</div>')
                        } else {
                            $('#draft').html('<div class="d-flex align-center">Ubah</div>')
                        }
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
            addInvestor() {
                this.modal.data.title = "Tambah Investor";
                this.modal.data.btnTitle = "Tambah";
                this.modal.data.btnAction = "insert";

                app.modal.form = {
                    investor_title: '',
                    investor_percentage: 0.5,
                    investor_bank_id: "",
                    investor_administrator_group_id: 0,
                    investor_join_date: '',
                };

                $('#investor_title').val('');
                $('#investor_percentage').val('');
                $('#investor_administrator_group_id').val(0);

                $('#modalAddUpdate').modal();
            },
            updateInvestor(investor_id) {
                $.ajax({
                    url: window.location.origin + '/admin/service/investor/detailInvestor',
                    method: 'GET',
                    data: {
                        id: investor_id
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            let data = response.data.results;

                            $('#investor_title').val(data.investor_title);
                            $('#investor_percentage').val(data.investor_percentage);
                            $('#investor_administrator_group_id').val(data.investor_administrator_group_id);
                            $('#investor_join_date').val(data.investor_join_date);

                            app.modal.form = data;

                        }
                    }
                });

                this.modal.data.title = "Ubah Investor";
                this.modal.data.btnTitle = "Ubah";
                this.modal.data.btnAction = "update";

                $('#modalAddUpdate').modal();
            },
            generateTable() {
                $("#table-investor").dataTableLib({
                    url: window.location.origin + '/admin/service/investor/getDataInvestor',
                    selectID: 'investor_id',
                    colModel: [{
                            display: 'Aksi',
                            name: 'investor_id',
                            sortAble: false,
                            align: 'center',
                            width: '80px',
                            render: (params) => {
                                return `
                                    <span class="cstmHover" onclick='app.updateInvestor(${params})' title="Ubah" data-toggle="tooltip"><i class="bx bx-edit-alt warning"></i></span>
                                `
                            }
                        },
                        {
                            display: 'Nama',
                            name: 'investor_title',
                            sortAble: true,
                            align: 'left'
                        },
                        {
                            display: 'Persentase (%)',
                            name: 'investor_percentage',
                            sortAble: false,
                            align: 'right'
                        },
                        {
                            display: 'Group Administrator',
                            name: 'administrator_group_title',
                            sortAble: false,
                            align: 'right'
                        },
                        {
                            display: 'Tanggal Investasi',
                            name: 'investor_join_date_formatted',
                            sortAble: false,
                            align: 'right'
                        },
                        {
                            display: 'Tanggal Terakhir Update',
                            name: 'investor_update_datetime_formatted',
                            sortAble: false,
                            align: 'right'
                        },
                        {
                            display: 'Aktif',
                            name: 'investor_is_active',
                            render: (params) => {
                                return params == '1' ?
                                    '<span class="badge badge-light-success badge-pill badge-round" title="Aktif" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>' :
                                    '<span class="badge badge-light-danger badge-pill badge-round" title="Non Aktif" data-toggle="tooltip"><i class="bx bx-bulb"></i></span>';
                            },
                            sortAble: false,
                            align: 'left'
                        },
                    ],
                    options: {
                        limit: [10, 15, 20],
                        currentLimit: 10,
                    },
                    search: true,
                    searchTitle: 'Pencarian Data Investor',
                    searchItems: [{
                        display: 'Nama Investor',
                        name: 'investor_title',
                        type: 'text'
                    }, ],
                    sortName: "investor_id",
                    sortOrder: "ASC",
                    tableIsResponsive: true,
                    select: true,
                    multiSelect: true,
                    buttonAction: [{
                            display: 'Tambah',
                            icon: 'bx bx-plus',
                            style: "info",
                            action: "addInvestor"
                        },
                        {
                            display: 'Aktifkan',
                            icon: 'bx bxs-bulb',
                            style: "success",
                            action: "active",
                            url: window.location.origin + "/admin/service/investor/activeInvestor"
                        },
                        {
                            display: 'Non Aktifkan',
                            icon: 'bx bx-bulb',
                            style: "warning",
                            action: "nonactive",
                            url: window.location.origin + "/admin/service/investor/nonActiveInvestor"
                        },
                        {
                            display: 'Hapus',
                            icon: 'bx bx-trash',
                            style: "danger",
                            action: "remove",
                            url: window.location.origin + "/admin/service/investor/deleteInvestor"
                        },

                    ]
                });
                $('#add-investor').show();
            },
            getDataForm() {
                $.ajax({
                    url: "<?php echo site_url('admin/service/member/getDataForm') ?>",
                    type: "GET",
                    content_type: "application/json",
                    success: function(res) {
                        let html = ""
                        let arrData = []

                        if (res.status == 200) {
                            let arrDataBank = res.data.bank_options
                            let htmlBank = ""
                            htmlBank += `<option value="" disabled>N/A</option>`
                            arrDataBank.forEach((item, index) => {
                                htmlBank += `<option value="${item.bank_id}">${item.bank_name}</option>`
                            })
                            $('#select-bank').html(htmlBank)
                        }
                    }
                })
            }
        },
        mounted() {
            this.getAdministratorGroupOtions();
            this.hideLoading();
            this.getDataForm();
        }
    }).mount("#investor");

    function addInvestor() {
        app.addInvestor();
    }
</script>