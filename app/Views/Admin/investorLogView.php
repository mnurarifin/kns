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
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="investor_title">Nama Investor</label>
                                                <input v-model="modal.form.investor_title" type="text" id="investor_title" class="form-control" name="investor_title" placeholder="Masukan Nama Investor">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <fieldset>
                                                    <label for="investor_percentage">Persentase</label>
                                                    <div class="input-group">
                                                        <input type="number" value="0" name="investor_percentage" id="investor_percentage" class="form-control money" placeholder="Masukan Persentase">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">%</span>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
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

<script type="text/javascript" defer>
    $(document).ready(function() {
        window.history.pushState("", "", '/admin/investor/log');
        app.generateInvestorTable();
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
                            title: "Tambah Investor",
                            btnTitle: "Tambah",
                            btnAction: "insert",
                        },
                        form: {
                            investor_title: '',
                            investor_percentage: 0,
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
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },
                generateInvestorTable() {
                    $("#table-investor").dataTableLib({
                        url: window.location.origin + '/admin/service/investor/getDataLogInvestor/',
                        selectID: 'investor_id',
                        colModel: [{
                                display: 'Tanggal Update',
                                name: 'investor_log_datetime_formatted',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Nama',
                                name: 'investor_title',
                                sortAble: true,
                                align: 'left'
                            },
                            {
                                display: 'Nama Diubah',
                                name: 'investor_log_investor_title',
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
                                display: 'Persentase Diubah (%)',
                                name: 'investor_log_investor_percentage',
                                sortAble: false,
                                align: 'right'
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
                        }, {
                            display: 'Tanggal',
                            name: 'investor_update_datetime',
                            type: 'date'
                        }, ],
                        sortName: "investor_log_id",
                        sortOrder: "DESC",
                        tableIsResponsive: true,
                        select: false,
                        multiSelect: false,
                        buttonAction: []
                    });
                    $('#add-investor').show();
                },
            },
            mounted() {
                this.hideLoading();
            }
        }).mount("#investor");
</script>