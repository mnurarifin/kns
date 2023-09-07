<section id="receivement">
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
                                <div id="table-receivement"></div>
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
                            <div class="row ma-5 align-items-end">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="warehouse_receipt_warehouse_id">Gudang</label>
                                        <select v-model="modal.form.warehouse_receipt_warehouse_id" class="form-control" name="warehouse_receipt_warehouse_id" placeholder="Masukan Nama Gudang">
                                            <option v-for="opt in options_warehouse" :value="modal.form.warehouse_receipt_warehouse_id">{{opt.warehouse_name}}</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="warehouse_receipt_code">Kode</label>
                                        <input v-model="modal.form.warehouse_receipt_code" type="text" id="warehouse_receipt_code" class="form-control" name="warehouse_receipt_code" placeholder="Masukan Kode">
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="warehouse_receipt_supplier">Supplier</label>
                                        <input v-model="modal.form.warehouse_receipt_supplier" type="text" id="warehouse_receipt_supplier" class="form-control" name="warehouse_receipt_supplier" placeholder="Masukan Supplier">
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="warehouse_receipt_note">Catatan</label>
                                        <input v-model="modal.form.warehouse_receipt_note" type="text" id="warehouse_receipt_note" class="form-control" name="warehouse_receipt_note" placeholder="Catatan">
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                Detail
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                                    <div class="form-group">
                                        <label for="product_id">Produk</label>
                                        <select v-model="select_product_id" class="form-control" name="product_id" placeholder="Masukan Nama Produk">
                                            <option v-for="opt in options_product" :value="opt.product_id">{{opt.product_name}}</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <button type="button" @click.prevent="addProduct" class="btn btn-primary">Tambah</button>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="text-center" scope="col">Hapus</th>
                                                <th class="text-left" scope="col">Produk</th>
                                                <th scope="col">Kuantitas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="options_product.length == 0">
                                                <td class="text-center" colspan="6" style="height:200px;"> Silahkan pilih tipe product terlebih dahulu.</td>
                                            </tr>
                                            <tr v-else v-for="(item, index) in this.modal.form.warehouse_receipt_detail">
                                                <td class="text-center"><a @click="deleteNumber(index)"> <i class="bx bx-trash danger"></i> </a>
                                                </td>
                                                <td class="text-left">
                                                    <span>{{item.product_name}}</span>
                                                </td>
                                                <td><input class="form-control" type="number" @keyup="qtyChange" v-model="item.product_qty" min="1" max="100" style="max-width: 100%;"></td>
                                            </tr>
                                        </tbody>
                                    </table>
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
                    <button onclick="app.addReceivement()" class="btn btn-success" :disabled="button.formBtn.disabled" id="draft" type="submit">
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

                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-left" scope="col">Produk</th>
                                                        <th scope="col">Kuantitas</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-if="modalDetail.form.length == 0">
                                                        <td class="text-center" colspan="6" style="height:200px;"> Silahkan pilih tipe product terlebih dahulu.</td>
                                                    </tr>
                                                    <tr v-else v-for="(item, index) in this.modalDetail.form">
                                                        <td class="text-left">
                                                            <span>{{item.product_name}}</span>
                                                        </td>
                                                        <td class="text-left">
                                                            <span>{{parseInt(item.warehouse_receipt_detail_quantity)}}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

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
        window.history.pushState("", "", '/admin/product/receivement');
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
                            title: "Tambah Penerimaan",
                            btnTitle: "Tambah",
                            btnAction: "insert",
                        },
                        form: {
                            warehouse_receipt_warehouse_id: "1",
                            warehouse_receipt_code: '',
                            warehouse_receipt_supplier: '',
                            warehouse_receipt_note: '',
                            warehouse_receipt_detail: [],
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
                        form: [],
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
                    },
                    options_warehouse: [],
                    options_product: [],
                    select_product_id: 1,
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },

                getWarehouse() {
                    $.ajax({
                        url: window.location.origin + '/admin/service/stock/getWarehouse',
                        method: 'GET',
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data.results;
                                $.each(data, (i, val) => {
                                    app.options_warehouse.push({warehouse_id: val.warehouse_id, warehouse_name: val.warehouse_name})
                                })
                            }
                        },

                    });
                },

                getProduct() {
                    $.ajax({
                        url: window.location.origin + '/admin/service/stock/getProduct',
                        method: 'GET',
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data.results;
                                $.each(data, (i, val) => {
                                    app.options_product.push({product_id: val.product_id, product_name: val.product_name})
                                })
                            }
                        },

                    });
                },

                addProduct() {
                    let find_index = this.modal.form.warehouse_receipt_detail.findIndex(i => i.product_id == app.select_product_id);
                    if (find_index == -1) {
                        let find_product = this.options_product.find(i => i.product_id == app.select_product_id);

                        this.modal.form.warehouse_receipt_detail.push({
                            product_id: find_product.product_id,
                            product_name: find_product.product_name,
                            product_qty: 1,
                        })
                    } else {
                        this.modal.form.warehouse_receipt_detail[find_index].product_qty += 1;
                    }
                },
                deleteNumber(index) {
                    this.modal.form.warehouse_receipt_detail.splice(index, 1);
                },

                detailReceivement(receivement_id) {
                    $.ajax({
                        url: window.location.origin + '/admin/service/stock/detailReceivement',
                        method: 'GET',
                        data: {
                            id: receivement_id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data.results;

                                app.modalDetail.data.title =
                                    `Detail Penerimaan`
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

                qtyChange(ev) {
                    if ($(ev.target).val() <= 0 || $(ev.target).val() == "") {
                        $(ev.target).val(1)
                    }
                    if ($(ev.target).val() > 999999) {
                        $(ev.target).val(999999)
                    }
                },

                addReceivement() {
                    let actionUrl = this.modal.data.btnAction == 'insert' ? window.location.origin +
                        '/admin/service/stock/addReceivement' : window.location.origin + '/admin/service/stock/updateReceivement'

                    $.ajax({
                        url: actionUrl,
                        method: 'POST',
                        data: this.modal.form,
                        success: function(response) {
                            if (response.status == 200) {
                                if (app.modal.data.btnAction == 'insert') {
                                    let data = response.data.results;
                                    app.modal.form = {
                                        warehouse_receipt_warehouse_id: "1",
                                        warehouse_receipt_code: '',
                                        warehouse_receipt_supplier: '',
                                        warehouse_receipt_note: '',
                                        warehouse_receipt_detail: [],
                                    };
                                }
                                app.alert.success.content = response.message;
                                app.alert.success.status = true;

                                $('#modalAddUpdate').modal('hide');

                                setTimeout(() => {
                                    app.alert.success.status = false;
                                }, 5000);
                                $.refreshTable('table-receivement');
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
                },

                showReceivement() {
                    this.modal.data.title = "Tambah Penerimaan";
                    this.modal.data.btnTitle = "Tambah";
                    this.modal.data.btnAction = "insert";

                    app.modal.form = {
                        warehouse_receipt_warehouse_id: "1",
                        warehouse_receipt_code: '',
                        warehouse_receipt_supplier: '',
                        warehouse_receipt_note: '',
                        warehouse_receipt_detail: [],
                    };
                    this.modal.form.warehouse_receipt_detail = []
                    $('#modalAddUpdate').modal();
                },

                generateMessageTable() {
                    let type = this.tab.current == 'active' ? 1 : 0;

                    $("#table-receivement").dataTableLib({
                        url: window.location.origin + '/admin/service/stock/getDataReceivement/' + type,
                        selectID: 'receivement_id',
                        colModel: [{
                                display: 'Tanggal',
                                name: 'warehouse_receipt_input_datetime_formatted',
                                sortAble: true,
                                align: 'left'
                            }
                            ,{
                                display: 'Gudang',
                                name: 'warehouse_name',
                                sortAble: true,
                                align: 'left'
                            },
                            {
                                display: 'Kode',
                                name: 'warehouse_receipt_code',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Supplier',
                                name: 'warehouse_receipt_supplier',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Catatan',
                                name: 'warehouse_receipt_note',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Detail',
                                name: 'warehouse_receipt_id',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    return `<a onclick="app.detailReceivement(${params})"> <i class="bx bx-receipt success" ></i> </a> `;
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
                                display: 'Tanggal',
                                name: 'warehouse_receipt_input_datetime',
                                type: 'date'
                            },
                            {
                                display: 'Gudang',
                                name: 'warehouse_name',
                                type: 'text'
                            },
                            {
                                display: 'Kode',
                                name: 'warehouse_receipt_code',
                                type: 'text'
                            },
                            {
                                display: 'Supplier',
                                name: 'warehouse_receipt_supplier',
                                type: 'text'
                            },
                            {
                                display: 'Catatan',
                                name: 'warehouse_receipt_note',
                                type: 'text'
                            },
                        ],
                        sortName: "warehouse_code",
                        sortOrder: "ASC",
                        tableIsResponsive: true,
                        buttonAction: [{
                                display: 'Tambah',
                                icon: 'bx bx-plus',
                                style: "info",
                                action: "showReceivement"
                            },
                        ]
                    });
                    $('#add-receivement').show();
                },
            },
            mounted() {
                this.hideLoading();
                this.getWarehouse();
                this.getProduct();
            }
        }).mount("#receivement");

    function showReceivement() {
        app.showReceivement();
    }
</script>