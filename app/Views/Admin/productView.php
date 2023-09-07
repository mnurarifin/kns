<section id="product">
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
                                <div id="table-product"></div>
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

                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group " style=" border-radius:5px;">
                                        <label>Gambar Produk</label>

                                        <div style="display: flex; align-items:center; justify-content:center; border:1px solid #DFE3E7; padding:10px;">
                                            <img class="product-image" style="height: 200px; width:200px;" :src="modal.form.product_temp_image ? modal.form.product_temp_image : '<?php echo base_url(); ?>/no-image.png'" alt="Blank" class="img-fluid">
                                        </div>
                                        <div class="custom-file">
                                            <input @change="previewFiles()" type="file" class="custom-file-input" id="upload-files" name="customFile">
                                            <label id="image-label" class="custom-file-label" for="customFile">Tambah
                                                Gambar</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="product_name">Nama</label>
                                                <input v-model="modal.form.product_name" type="text" id="product_name" class="form-control" name="product_name" placeholder="Masukan Nama Produk">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <fieldset>
                                                    <label for="product_weight">Berat Dalam Gram</label>
                                                    <div class="input-group">
                                                        <input value="0" name="product_weight" id="product_weight" class="form-control money" placeholder="Masukan Berat">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">g </span>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <fieldset>
                                                    <label for="product_member_price">Harga Produk </label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp </span>
                                                        </div>
                                                        <input value="0" name="product_price" id="product_price" class="form-control money" placeholder="Masukan Harga Produk">
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <fieldset>
                                                    <label for="product_member_price">Harga Master Stokis </label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp </span>
                                                        </div>
                                                        <input value="0" name="product_master_stockist_price" id="product_master_stockist_price" class="form-control money" placeholder="Masukan Harga Master Stokis">
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <fieldset>
                                                    <label for="product_member_price">Harga Stokis </label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp </span>
                                                        </div>
                                                        <input value="0" name="product_mobile_stockist_price" id="product_mobile_stockist_price" class="form-control money" placeholder="Masukan Mobile Master Stokis">
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 d-none">
                                            <div class="form-group">
                                                <fieldset>
                                                    <label for="product_admin_charge">Charge Admin</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <select v-model="modal.form.product_admin_charge_type" class="custom-select" id="product_series">
                                                                <option v-for="item in type" :value="item.value">
                                                                    {{item.label}}
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <input v-model="modal.form.product_admin_charge" name="product_admin_charge" type="number" class="form-control" placeholder="Masukan Charge Admin">
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="display: none;">
                                            <div class="form-group">
                                                <fieldset>
                                                    <label for="product_admin_charge">Diskon</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <select v-model="modal.form.product_discount_type" class="custom-select" id="product_series">
                                                                <option v-for="item in type" :value="item.value">
                                                                    {{item.label}}
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <input v-model="modal.form.product_discount" name="product_admin_charge" type="number" class="form-control" placeholder="Masukan Diskon">
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="product_description">Deskripsi</label>
                                        <textarea v-model="modal.form.product_description" class="form-control" name="" id="editor" cols="20" rows="5" style="height: 64px;"></textarea>
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
                    <button onclick="app.saveProduct()" class="btn btn-success" :disabled="button.formBtn.disabled" id="draft" type="submit">
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
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group " style=" border-radius:5px;">
                                        <div style="display: flex; align-items:center; justify-content:center; border:1px solid #DFE3E7; padding:10px;">
                                            <img class="product-image" style="width: 100%; height: auto; max-height:250px; object-fit:contain;" :src="modalDetail.form.product_temp_image ? modalDetail.form.product_temp_image : '<?php echo base_url(); ?>/no-image.png'" alt="Blank" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="form-group" style="padding-top: 20px;">
                                                <h5 class="text-primary"> {{modalDetail.form.product_name}} ({{modalDetail.form.product_code}})</h5>
                                                <h6> Rp. {{modalDetail.form.product_price_formatted}} (Harga Produk)</h6>
                                                <h6> Rp. {{modalDetail.form.product_master_stockist_price_formatted}} (Harga Master Stokis)</h6>
                                                <h6> Rp. {{modalDetail.form.product_mobile_stockist_price_formatted}} (Harga Stokis)</h6>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div style="padding-top: 20px;">
                                                <b class="text-primary mr-3">Berat</b>
                                                <span v-html="modalDetail.form.product_weight"></span> gram
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <p class="text-primary"><b> Deskripsi </b></p>
                                    <p> <span v-html="modalDetail.form.product_description"></span></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

</section>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<script src="<?php echo base_url(); ?>/app-assets/vendors/ckeditor/ckeditor.js"></script>

<script type="text/javascript" defer>
    $(document).ready(function() {
        window.history.pushState("", "", '/admin/product/show');
        app.generateMessageTable('');
        $('.money').maskMoney({
            thousands: '.',
            decimal: '.',
            allowZero: true,
            prefix: '',
            affixesStay: true,
            allowNegative: false,
            precision: 0
        });
        var editor = CKEDITOR.replace('editor', {
            height: 200,
            removeButtons: ''
        });
        // var editor = CKEDITOR.replace('editor1', {
        //     height: 200,
        //     removeButtons: ''
        // });
    });


    // Function That Called
    let app =
        Vue.createApp({
            data: function() {
                return {
                    button: {
                        formBtn: {
                            disabled: false
                        }
                    },
                    modalQR: {
                        step: 0,
                        table: [],
                        loading: false,
                        form: {
                            serial_qty: "",
                        }
                    },
                    modal: {
                        data: {
                            title: "Tambah Produk",
                            btnTitle: "Tambah",
                            btnAction: "insert",
                        },
                        form: {
                            product_name: '',
                            product_price: '',
                            product_member_price: '',
                            product_price: '',
                            product_discount: 0,
                            product_discount_type: 'value',
                            product_admin_charge: 0,
                            product_admin_charge_type: 'value',
                            product_description: '',
                            product_is_active: true,
                            product_is_ewallet: '',
                            product_image: '',
                            product_temp_image: ''
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
                            product_name: '',
                            product_price: 0,
                            product_member_price: 0,
                            product_description: '',
                            product_weight: 0,
                            product_type: '',
                            product_discount: 0,
                            product_discount_type: 'value',
                            product_admin_charge: 0,
                            product_admin_charge_type: 'value',
                            product_is_active: true,
                            product_is_ewallet: '',
                            product_image: '',
                            product_temp_image: ''
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
                    type: [{
                            value: 'percent',
                            label: 'Persen (%)',
                        },
                        {
                            value: 'value',
                            label: 'Nominal'
                        }
                    ],
                    tab: {
                        current: 'active'
                    }
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },
                detailProduct(product_id) {
                    $.ajax({
                        url: window.location.origin + '/admin/service/product/detailProduct',
                        method: 'GET',
                        data: {
                            id: product_id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data.results;

                                app.modalDetail.data.title =
                                    `Detail Product`

                                data.product_temp_image = data.product_image_url
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
                saveProduct() {
                    $('#draft').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memproses...')
                    let actionUrl = this.modal.data.btnAction == 'insert' ? window.location.origin +
                        '/admin/service/product/addProduct' : window.location.origin + '/admin/service/product/updateProduct'

                    this.modal.form.product_is_active = this.modal.form.product_is_active ? 1 : 0;

                    this.modal.form.product_description = CKEDITOR.instances['editor'].getData();

                    this.modal.form.product_price = $('#product_price').val().replace(/\./g, "");
                    this.modal.form.product_master_stockist_price = $('#product_master_stockist_price').val().replace(/\./g, "");
                    this.modal.form.product_mobile_stockist_price = $('#product_mobile_stockist_price').val().replace(/\./g, "");
                    this.modal.form.product_weight = $('#product_weight').val().replace(/\./g, "");
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
                                        product_name: '',
                                        product_price: '',
                                        product_member_price: '',
                                        product_master_stockist_price: '',
                                        product_mobile_stockist_price: '',
                                        product_description: '',
                                        product_is_active: true,
                                    };
                                }
                                app.alert.success.content = response.message;
                                app.alert.success.status = true;

                                $('#modalAddUpdate').modal('hide');

                                setTimeout(() => {
                                    app.alert.success.status = false;
                                }, 5000);
                                $.refreshTable('table-product');
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
                addProduct() {
                    this.modal.data.title = "Tambah Produk";
                    this.modal.data.btnTitle = "Tambah";
                    this.modal.data.btnAction = "insert";

                    app.modal.form = {
                        product_name: '',
                        product_price: '',
                        product_member_price: '',
                        product_master_stockist_price: '',
                        product_mobile_stockist_price: '',
                        product_description: '',
                        product_is_active: true,
                    };


                    $('#product_price').val(0);
                    $('#product_master_stockist_price').val(0);
                    $('#product_mobile_stockist_price').val(0);
                    $('#product_weight').val(0);

                    CKEDITOR.instances['editor'].setData('');

                    this.openModal();
                },
                updateProduct(product_id) {
                    $.ajax({
                        url: window.location.origin + '/admin/service/product/detailProduct',
                        method: 'GET',
                        data: {
                            id: product_id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data.results;
                                data.product_temp_image = data.product_image_url

                                CKEDITOR.instances['editor'].setData(data.product_description);

                                $('#product_price').val(data.product_price);
                                $('#product_master_stockist_price').val(data.product_master_stockist_price);
                                $('#product_mobile_stockist_price').val(data.product_mobile_stockist_price);
                                $('#product_weight').val(data.product_weight);

                                $(".money").maskMoney('mask');

                                app.modal.form = data;

                            }
                        }
                    });

                    this.modal.data.title = "Ubah Produk";
                    this.modal.data.btnTitle = "Ubah";
                    this.modal.data.btnAction = "update";

                    this.openModal();
                },
                openModal() {
                    if (this.modal.data.btnAction == "update") {
                        $('#image-label').html('UBAH GAMBAR')
                    } else {
                        $('#image-label').html('TAMBAH GAMBAR')
                    }
                    $('#modalAddUpdate').modal();
                },
                generateMessageTable() {
                    let type = this.tab.current == 'active' ? 1 : 0;

                    $("#table-product").dataTableLib({
                        url: window.location.origin + '/admin/service/product/getDataProduct/' + type,
                        selectID: 'product_id',
                        colModel: [{
                                display: 'Aksi',
                                name: 'product_id',
                                sortAble: false,
                                align: 'center',
                                width: '80px',
                                render: (params) => {
                                    return `
                                    <span class="cstmHover px-25" onclick='app.detailProduct(${params})' title="Detail" data-toggle="tooltip"><i class="bx bx-receipt success"></i></span>
                                    <span class="cstmHover" onclick='app.updateProduct(${params})' title="Ubah" data-toggle="tooltip"><i class="bx bx-edit-alt warning"></i></span>
                                `
                                }
                            }, {
                                display: 'Kode',
                                name: 'product_code',
                                sortAble: true,
                                align: 'left'
                            },
                            {
                                display: 'Nama',
                                name: 'product_name',
                                sortAble: true,
                                align: 'left'
                            },
                            {
                                display: 'Aktif',
                                name: 'product_is_active',
                                render: (params) => {
                                    return params == '1' ?
                                        '<span class="badge badge-light-success badge-pill badge-round" title="Aktif" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>' :
                                        '<span class="badge badge-light-danger badge-pill badge-round" title="Non Aktif" data-toggle="tooltip"><i class="bx bx-bulb"></i></span>';
                                },
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Harga',
                                name: 'product_price_formatted',
                                sortAble: false,
                                align: 'right'
                            },
                            {
                                display: 'Harga Master Stokis',
                                name: 'product_master_stockist_price_formatted',
                                sortAble: false,
                                align: 'right'
                            },
                            {
                                display: 'Harga Stokis',
                                name: 'product_mobile_stockist_price_formatted',
                                sortAble: false,
                                align: 'right'
                            },
                        ],
                        options: {
                            limit: [10, 15, 20],
                            currentLimit: 10,
                        },
                        search: true,
                        searchTitle: 'Pencarian Data Produk',
                        searchItems: [{
                                display: 'Kode Produk',
                                name: 'product_code',
                                type: 'text'
                            },
                            {
                                display: 'Nama Produk',
                                name: 'product_name',
                                type: 'text'
                            },
                        ],
                        sortName: "product_code",
                        sortOrder: "ASC",
                        tableIsResponsive: true,
                        select: true,
                        multiSelect: true,
                        buttonAction: [{
                                display: 'Tambah',
                                icon: 'bx bx-plus',
                                style: "info",
                                action: "addProduct"
                            },
                            {
                                display: 'Aktifkan',
                                icon: 'bx bxs-bulb',
                                style: "success",
                                action: "active",
                                url: window.location.origin + "/admin/service/product/activeProduct"
                            },
                            {
                                display: 'Non Aktifkan',
                                icon: 'bx bx-bulb',
                                style: "warning",
                                action: "nonactive",
                                url: window.location.origin + "/admin/service/product/nonActiveProduct"
                            },
                            {
                                display: 'Hapus',
                                icon: 'bx bx-trash',
                                style: "danger",
                                action: "remove",
                                url: window.location.origin + "/admin/service/product/deleteProduct"
                            },

                        ]
                    });
                    $('#add-product').show();
                },

                previewFiles() {
                    app.button.formBtn.disabled = true;

                    let files = document.getElementById("upload-files").files[0];

                    var reader = new FileReader();
                    let temp_url = reader.readAsDataURL(files);

                    app.modal.form.product_temp_image = temp_url;

                    reader.onload = function(e) {
                        $('#product-image')
                            .attr('src', e.target.result)
                    };

                    let formData = new FormData();
                    formData.append("file", files);

                    $.ajax({
                        url: window.location.origin + "/admin/service/product/uploadImage",
                        method: 'POST',
                        data: formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data;
                                let temp_image = '';

                                if (data.name) {
                                    temp_image = `<?php echo $imagePath ?>${data.name}`;
                                }

                                app.modal.form.product_image = data.name;
                                app.modal.form.product_temp_image = temp_image;

                                app.button.formBtn.disabled = false;


                            }
                        },
                        error: function(res) {
                            let response = res.responseJSON;
                            app.button.formBtn.disabled = false;

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
            },
            mounted() {
                this.hideLoading();
            }

        }).mount("#product");



    function addProduct() {
        app.addProduct();
    }
</script>