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
                                            <img class="product-image" style="height: 200px; width:200px;" :src="modal.form.product_package_temp_image ? modal.form.product_package_temp_image : '<?php echo base_url(); ?>/no-image.png'" alt="Blank" class="img-fluid">
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
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label for="product_package_name">Nama Paket</label>
                                                <input v-model="modal.form.product_package_name" type="text" id="product_package_name" class="form-control" name="product_package_name" placeholder="Masukan Nama Paket Produk">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label for="">Tipe Paket</label>
                                                <select @change="changePackage()" class="form-control" v-model="type_product">
                                                    <option value="activation">Aktivasi</option>
                                                    <option value="repeatorder">Repeat Order</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label for="product_package_admin_charge">Daftar Produk</label>
                                                <div class="input-group-prepend">
                                                    <select data-placeholder="Pilih Mitra" class=" form-control" id="select_product">
                                                        <option v-for="item in product" :value="item.product_id">
                                                            {{item.product_code}} - {{item.product_name}}
                                                        </option>
                                                    </select>
                                                    <button @click.prevent="addNumber" style="margin-left:5px;" class="btn btn-primary">Tambah</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12" v-if="package.length > 0">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center">Hapus</th>
                                                <th scope="col">Kode Produk</th>
                                                <th scope="col">Nama Produk</th>
                                                <th scope="col" class="text-center">Kuantitas</th>
                                                <th scope="col" class="text-center">Harga Satuan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, index) in package">
                                                <td class="text-center"><a @click="deleteNumber(index)"> <i class="bx bx-trash danger"></i> </a>
                                                </td>
                                                <td>
                                                    <span>{{package[index].product_code}}</span>
                                                </td>
                                                <td>
                                                    <span>{{package[index].product_name}}</span>
                                                </td>
                                                <td class="text-center"><input class="form-control" type="number" v-model="package[index].product_qty" min="1" max="100" style="max-width: 100px;"></td>
                                                <td class="text-center"><input class="form-control" type="text" v-model="package[index].product_price" min="1" max="100" style="max-width: 100px;"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="product_package_description">Deskripsi</label>
                                        <textarea v-model="modal.form.product_package_description" class="form-control" name="" id="editor" cols="20" rows="5" style="height: 64px;"></textarea>
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
                                            <img class="product-image" style="width: 100%; height: auto; max-height:250px; object-fit:contain;" :src="modalDetail.form.product_package_temp_image ? modalDetail.form.product_package_temp_image : '<?php echo base_url(); ?>/no-image.png'" alt="Blank" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="form-group" style="padding-top: 20px;">
                                                <h5 class="text-primary"> {{modalDetail.form.product_package_name}}</h5>
                                                <h6>{{modalDetail.form.product_package_price}}</h6>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div style="padding-top: 20px;">
                                                <b class="text-primary">Berat</b> <br>
                                                <span v-html="modalDetail.form.product_package_weight"></span> gram
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Kode Produk</th>
                                                <th scope="col">Nama Produk</th>
                                                <th scope="col" class="text-center">Kuantitas</th>
                                                <th scope="col" class="text-center">Harga Satuan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, index) in package">
                                                </td>
                                                <td>
                                                    <span>{{package[index].product_code}}</span>
                                                </td>
                                                <td>
                                                    <span>{{package[index].product_name}}</span>
                                                </td>
                                                <td class="text-center">{{package[index].product_qty}}</td>
                                                <td class="text-center">{{package[index].product_price}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12 mt-2">
                                    <p class="text-primary"><b> Deskripsi </b></p>
                                    <p> <span v-html="modalDetail.form.product_package_description"></span></p>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<script src="<?php echo base_url(); ?>/app-assets/vendors/ckeditor/ckeditor.js"></script>

<script type="text/javascript" defer>
    $(document).ready(function() {
        window.history.pushState("", "", '/admin/product/package');
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
                            title: "Tambah Paket",
                            btnTitle: "Tambah",
                            btnAction: "insert",
                        },
                        form: {
                            product_package_name: '',
                            product_package_price: '',
                            product_package_member_price: '',
                            product_package_price: '',
                            product_package_point: 0,
                            product_package_discount: 0,
                            product_package_discount_type: 'value',
                            product_package_admin_charge: 0,
                            product_package_admin_charge_type: 'value',
                            product_package_description: '',
                            product_package_is_active: true,
                            product_package_is_ewallet: '',
                            product_package_image: '',
                            product_package_temp_image: ''
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
                            product_package_name: '',
                            product_package_price: 0,
                            product_package_description: '',
                            product_package_weight: 0,
                            product_package_discount: 0,
                            product_package_discount_type: 'value',
                            product_package_admin_charge: 0,
                            product_package_admin_charge_type: 'value',
                            product_package_is_active: true,
                            product_package_is_ewallet: '',
                            product_package_image: '',
                            product_package_temp_image: ''
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
                    },
                    package: [],
                    product: [],
                    type_product: "activation"
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },
                detailProduct(product_package_id) {
                    app.package = []
                    $.ajax({
                        url: window.location.origin + '/admin/service/package/detailPackage',
                        method: 'GET',
                        data: {
                            id: product_package_id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data.results;

                                app.modalDetail.data.title =
                                    `Detail Product`

                                data.product_package_temp_image = data.product_package_image !== '' && data
                                    .product_package_image !== null ?
                                    `<?php echo $imagePath ?>${data.product_package_image}` :
                                    `<?php echo base_url(); ?>/no-image.png`;
                                let temp_image = '';

                                if (data.name) {
                                    temp_image = `<?php echo $imagePath ?>${data.name}`;
                                }
                                app.modalDetail.form = data;

                                $.each(data.detail, (key, val) => {
                                    app.package.push({
                                        product_id: val.product_package_detail_product_id,
                                        product_qty: parseInt(val.product_package_detail_quantity),
                                        product_price: app.formatCurrency(val.product_package_detail_price),
                                        product_code: val.product_code,
                                        product_name: val.product_name,
                                    })
                                })
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
                    let actionUrl = this.modal.data.btnAction == 'insert' ? window.location.origin +
                        '/admin/service/package/addPackage' : window.location.origin + '/admin/service/package/updatePackage'

                    let product_price = app.package.map((item, index) => {
                        return item.product_price
                    })
                    let product_qty = app.package.map((item, index) => {
                        return item.product_qty
                    })
                    let product_id = app.package.map((item, index) => {
                        return item.product_id
                    })

                    $.ajax({
                        url: actionUrl,
                        method: 'POST',
                        data: {
                            package_name: app.modal.form.product_package_name,
                            package_image: app.modal.form.product_package_image,
                            package_description: app.modal.form.product_package_description,
                            package_type: app.type_product,
                            product_id: product_id,
                            product_price: product_price,
                            product_qty: product_qty,
                            product_package_id: app.modal.form.product_package_id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                if (app.modal.data.btnAction == 'insert') {
                                    let data = response.data.results;
                                    app.modal.form = {
                                        product_package_name: '',
                                        product_package_price: '',
                                        product_package_member_price: '',
                                        product_package_description: '',
                                        product_package_is_active: true,
                                        product_package_is_ewallet: ''
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
                    app.package = []
                    this.modal.data.title = "Tambah Paket";
                    this.modal.data.btnTitle = "Tambah";
                    this.modal.data.btnAction = "insert";

                    app.modal.form = {
                        product_package_name: '',
                        product_package_series_id: '',
                        product_package_price: 0,
                        product_package_distributor_price: 0,
                        product_package_agent_price: 0,
                        product_package_sales_price_min: 0,
                        product_package_sales_price_max: 0,
                        product_package_point: 0,
                        product_package_description: '',
                        product_package_weight: 0,
                        product_package_discount: 0,
                        product_package_discount_type: 'value',
                        product_package_admin_charge: 0,
                        product_package_admin_charge_type: 'value',
                        product_package_is_active: true,
                        product_package_is_ewallet: ''
                    };


                    $('#product_package_price').val(0);
                    $('#product_package_weight').val(0);

                    CKEDITOR.instances['editor'].setData('');

                    this.getProduct();
                    this.openModal();
                },
                updateProduct(product_package_id) {
                    this.package = []
                    $.ajax({
                        url: window.location.origin + '/admin/service/package/detailPackage',
                        method: 'GET',
                        data: {
                            id: product_package_id
                        },
                        success: function(response) {
                            let data = response.data.results

                            app.modal.form.product_package_id = data.product_package_id
                            app.type_product = data.product_package_type
                            app.modal.form.product_package_name = data.product_package_name
                            app.modal.form.product_package_temp_image = data.product_package_image
                            app.getProduct()

                            $.each(data.detail, (key, val) => {
                                app.package.push({
                                    product_id: val.product_package_detail_product_id,
                                    product_qty: parseInt(val.product_package_detail_quantity),
                                    product_price: val.product_package_detail_price,
                                    product_code: val.product_code,
                                    product_name: val.product_name,
                                })
                            })
                        }
                    });

                    this.modal.data.title = "Ubah Paket";
                    this.modal.data.btnTitle = "Ubah";
                    this.modal.data.btnAction = "update";

                    this.openModal();
                },
                changePackage() {
                    app.package = []
                    app.getProduct()
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
                        url: window.location.origin + '/admin/service/package/getDataPackage/' + type,
                        selectID: 'product_package_id',
                        colModel: [{
                                display: 'Nama',
                                name: 'product_package_name',
                                sortAble: true,
                                align: 'left'
                            },
                            {
                                display: 'Harga',
                                name: 'product_package_price_formatted',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Berat',
                                name: 'product_package_weight',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Tipe',
                                name: 'product_package_type',
                                sortAble: false,
                                align: 'left',
                                render: (params) => {
                                    return params == "activation" ? "Aktivasi" : "Repeat Order"
                                }
                            },
                            {
                                display: 'Aktif',
                                name: 'product_package_is_active',
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
                                name: 'product_package_id',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    return `<a onclick="app.detailProduct(${params})"> <i class="bx bx-receipt success" ></i> </a> `;
                                }
                            },
                            {
                                display: 'Ubah',
                                name: 'product_package_id',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    return `<a onclick="app.updateProduct(${params})"> <i class="bx bx-edit-alt warning" ></i> </a> `;
                                }
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
                                name: 'product_package_code',
                                type: 'text'
                            },
                            {
                                display: 'Nama Produk',
                                name: 'product_package_name',
                                type: 'text'
                            },
                        ],
                        sortName: "product_package_code",
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
                                url: window.location.origin + "/admin/service/package/activePackage"
                            },
                            {
                                display: 'Non Aktifkan',
                                icon: 'bx bx-bulb',
                                style: "warning",
                                action: "nonactive",
                                url: window.location.origin + "/admin/service/package/nonActivePackage"
                            },
                            {
                                display: 'Hapus',
                                icon: 'bx bx-trash',
                                style: "danger",
                                action: "remove",
                                url: window.location.origin + "/admin/service/package/deletePackage"
                            },

                        ]
                    });
                    $('#add-product').show();
                },
                formatCurrency(params) {
                    let formatter = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0,
                    })
                    return formatter.format(params)
                },
                previewFiles() {
                    app.button.formBtn.disabled = true;

                    let files = document.getElementById("upload-files").files[0];

                    var reader = new FileReader();
                    let temp_url = reader.readAsDataURL(files);

                    app.modal.form.product_package_temp_image = temp_url;

                    reader.onload = function(e) {
                        $('#product-image')
                            .attr('src', e.target.result)
                    };

                    let formData = new FormData();
                    formData.append("file", files);

                    $.ajax({
                        url: window.location.origin + "/admin/service/package/uploadImage",
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

                                app.modal.form.product_package_image = data.name;
                                app.modal.form.product_package_temp_image = temp_image;

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
                getProduct() {
                    $.ajax({
                        url: window.location.origin + '/admin/service/product/getOptionProduct/' + app.type_product,
                        method: 'GET',
                        success: function(response) {
                            if (response.status == 200) {
                                app.product = response.data.results;
                            }
                        },

                    });
                },
                addNumber() {
                    let find_index = this.package.findIndex(i => i.product_id === $("#select_product").val());

                    let find_product = this.product.find(el => el.product_id == $("#select_product").val())

                    if (find_index == -1) {
                        if ($("#select_product").val()) {
                            this.package.push({
                                product_id: $("#select_product").val(),
                                product_qty: 1,
                                product_price: find_product.product_price,
                                product_code: find_product.product_code,
                                product_name: find_product.product_name,
                            })
                        }
                    } else {
                        this.package[find_index].product_qty += 1;
                    }
                },
                deleteNumber(index) {
                    this.package.splice(index, 1);
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