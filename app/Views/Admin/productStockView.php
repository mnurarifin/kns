<section id="stock">
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
                                <div id="table-stock"></div>
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
                                        <label for="product_name">Nama Produk</label>
                                        <input v-model="modal.form.product_name" type="text" id="product_name" class="form-control" name="product_name" placeholder="Nama Produk" disabled>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="warehouse_name">Gudang</label>
                                        <input v-model="modal.form.warehouse_name" type="text" id="warehouse_name" class="form-control" name="warehouse_name" placeholder="Gudang" disabled>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="warehouse_product_stock_balance">Jumlah Stok Sebelumnya</label>
                                        <input v-model="modal.form.warehouse_product_stock_balance" type="text" id="warehouse_product_stock_balance" class="form-control" name="warehouse_product_stock_balance" placeholder="Stok" disabled>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="balance">Jumlah Stok Opname</label>
                                        <input @keyup="qtyChange" v-model="modal.form.balance" type="number" id="balance" class="form-control" name="balance" placeholder="Stok">
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
                    <button onclick="app.saveStock()" class="btn btn-success" :disabled="button.formBtn.disabled" id="draft" type="submit">
                        <div class="d-flex align-center">{{ modal.data.btnTitle }}</div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript" defer>
    $(document).ready(function() {
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
                            title: "",
                            btnTitle: "Tambah",
                            btnAction: "insert",
                        },
                        form: {
                            product_name: '',
                            warehouse_name: '',
                            warehouse_product_stock_balance: 0,
                            balance: 0,
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
                    tab: {
                        current: 'active'
                    }
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },

                changeTab(type) {
                    this.tab.current = type;
                    this.generateMessageTable();
                },

                qtyChange(ev) {
                    if ($(ev.target).val() < 0) {
                        $(ev.target).val(0)
                    }
                    if ($(ev.target).val() > 999999) {
                        $(ev.target).val(999999)
                    }
                },

                saveStock() {
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
                                url: window.location.origin + '/admin/service/stock/updateStock',
                                method: 'POST',
                                data: this.modal.form,
                                success: function(response) {
                                    if (response.status == 200) {
                                        if (app.modal.data.btnAction == 'insert') {
                                            let data = response.data.results;
                                            app.modal.form = {
                                                balance: 0,
                                            };
                                        }
                                        app.alert.success.content = response.message;
                                        app.alert.success.status = true;

                                        $('#modalAddUpdate').modal('hide');

                                        setTimeout(() => {
                                            app.alert.success.status = false;
                                        }, 5000);
                                        $.refreshTable('table-stock');
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
                                    } else {
                                        app.alert.danger.status = true;
                                        app.alert.danger.content = response.message

                                        setTimeout(() => {
                                            app.alert.danger.status = false;
                                        }, 5000);
                                    }
                                },
                            })
                        } else {
                            $('#modalAddUpdate').modal();
                        }
                    })
                },

                updateStock(stock_id) {
                    $.ajax({
                        url: window.location.origin + '/admin/service/stock/detailStock',
                        method: 'GET',
                        data: {
                            id: stock_id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data.results;

                                app.modal.form = data;
                                app.modal.form.balance = 0;
                            }
                        }
                    });
                    this.modal.data.title = "Stok Opname";
                    this.modal.data.btnTitle = "Ubah";
                    this.modal.data.btnAction = "update";
                    $('#modalAddUpdate').modal();
                },

                generateMessageTable() {
                    let type = this.tab.current == 'active' ? 1 : 0;

                    $("#table-stock").dataTableLib({
                        url: window.location.origin + '/admin/service/stock/getDataStock/' + type,
                        selectID: 'warehouse_product_stock_id',
                        colModel: [{
                                display: 'Aksi',
                                name: 'warehouse_product_stock_id',
                                sortAble: false,
                                align: 'center',
                                width: '50px',
                                render: (params) => {
                                    return `<a onclick="app.updateStock(${params})" title="Opname" class="cstmHover" data-toggle="tooltip"> <i class="bx bx-edit-alt warning" ></i> </a> `;
                                }
                            }, {
                                display: 'Gudang',
                                name: 'warehouse_name',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Nama Produk',
                                name: 'product_name',
                                sortAble: true,
                                align: 'left'
                            },
                            {
                                display: 'Jumlah Stok',
                                name: 'warehouse_product_stock_balance',
                                sortAble: false,
                                align: 'right'
                            },

                        ],
                        options: {
                            limit: [10, 15, 20],
                            currentLimit: 10,
                        },
                        search: true,
                        searchTitle: 'Pencarian Data Stok',
                        searchItems: [{
                                display: 'Nama Produk',
                                name: 'product_name',
                                type: 'text'
                            },
                            {
                                display: 'Gudang',
                                name: 'warehouse_name',
                                type: 'text'
                            },
                        ],
                        sortName: "warehouse_product_stock_id",
                        sortOrder: "ASC",
                        tableIsResponsive: true,
                        select: false,
                        multiSelect: false,
                        buttonAction: []
                    });
                    $('#add-stock').show();
                },
            },
            mounted() {
                this.hideLoading();
            }
        }).mount("#stock");

    function addStock() {
        app.addStock();
    }
</script>