<script src="https://unpkg.com/vue@next"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    #table-appaddSerial {
        height: 30px;
    }
</style>

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
                        <div class="col-12">
                            <div id="table-app"> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalApp" role="dialog" aria-labelledby="modalAddUpdateTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center" id="modalDetailTitle">
                            Kirim Serial
                            <span></span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- <form class="form form-vertical">
                            <div class="col-12">
                                <div class="alert alert-danger " v-show="alert.danger.status" style="display: none;">
                                    <span v-html="alert.danger.content"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="basicInput">Pilih Mitra</label>
                                    <select id="select_member" data-placeholder="Pilih Mitra"
                                        class="select2 form-control select2-hidden-accessible" aria-hidden="true">
                                        <option value=""></option>
                                        <option v-for="item in member" :value="item.member_id">
                                            {{item.member_network_code !== '' ? `(${item.member_network_code})` : ''}}
                                            {{item.member_name}} [{{item.member_mobilephone}}] </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="basicInput">Masukan Tipe</label>
                                    <select class="form-control"  v-model ="form.serial_type_id" name="" id="select_type" >
                                     <option value="">Pilih tipe serial</option>
                                     <option v-for="item in serial" :value="item.serial_type_id"> {{item.serial_type_label}} </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="basicInput">Masukan QTY</label>
                                    <input v-model="form.serial_qty" class="form-control" type="NUMBER">
                                </div>
                            </div>
                        </form> -->

                        <form class="form form-vertical">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="alert alert-danger " v-show="alert.danger.status" style="display: none;">
                                        <span v-html="alert.danger.content"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="basicInput">Tambah Nomor Handphone</label>
                                    <div style="display:flex;">

                                        <select id="select_member" data-placeholder="Pilih Mitra" class="select2 form-control select2-hidden-accessible" aria-hidden="true">
                                            <option value=""></option>
                                            <option v-for="item in member" :value="item.member_mobilephone">
                                                {{item.member_mobilephone}}
                                                {{item.member_network_code !== '' ? `(${item.member_network_code})` : ''}}
                                            </option>

                                        </select>
                                        <button @click.prevent="addNumber" style="margin-left:5px;" class="btn btn-primary">Tambah</button>

                                    </div>
                                    <small class="text-muted">Pastikan pengguna mempunyai/aktif whatsapp. (Ketik lalu tekan enter pada form untuk menambah nomor baru)</small>
                                </div>

                            </div>
                            <div class="col-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Hapus</th>
                                            <th scope="col">Nomor Handphone</th>
                                            <!-- <th scope="col">Jenis Serial</th> -->
                                            <th scope="col">Kuantitas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="form.length  == 0">
                                            <td class="text-center" colspan="5"> Silahkan untuk memasukan nomor.</td>
                                        </tr>
                                        <tr v-else v-for="(item, index) in form">
                                            <td><a @click="deleteNumber(index)"> <i class="bx bx-trash danger"></i> </a>
                                            </td>
                                            <td>

                                                <input @keypress="isNumber($event)" class="form-control" type="string" v-model="form[index].member_phonenumber">
                                            </td>
                                            <!-- <td>
                                                <select class="form-control" v-model="form[index].member_serial_type_id"
                                                    name="" id="select_type">
                                                    <option v-for="item in serial" :value="item.serial_type_id">
                                                        {{item.serial_type_label}} </option>
                                                </select>
                                            </td> -->
                                            <td><input class="form-control" min='1' max='100' type="number" v-model="form[index].member_serial_qty" min="1" max="100" style="max-width: 150px;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" :disabled="loading" @click.prevent="save" class="btn btn-primary">
                            Kirim
                        </button>
                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    let app =
        Vue.createApp({
            data: function() {
                return {
                    loading: false,
                    member: [],
                    number: '',
                    serial: [{
                            serial_type_id: 1,
                            serial_type_label: 'Khasanah'
                        },
                        {
                            serial_type_id: 2,
                            serial_type_label: 'Barokah'
                        },

                    ],
                    form: [],
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
                generateTable() {
                    $("#table-app").dataTableLib({
                        url: window.location.origin + '/admin/service/serial/getDataInputSerial',
                        selectID: 'serial_id',
                        colModel: [{
                                display: 'Serial',
                                name: 'serial_id',
                                sortAble: false,
                                align: 'center',
                                export: true
                            },
                            {
                                display: 'Pin',
                                name: 'serial_pin',
                                sortAble: false,
                                align: 'center',
                                export: true
                            },
                        ],
                        options: {
                            limit: [10, 15, 20],
                            currentLimit: 10,
                        },
                        search: false,
                        searchTitle: 'Pencarian Data Serial',
                        searchItems: [],
                        sortName: "serial_id",
                        sortOrder: "asc",
                        tableIsResponsive: true,
                        select: false,
                        multiSelect: false,
                        buttonAction: [{
                            display: 'Kirim Serial',
                            icon: 'bx bx-plus',
                            style: "info",
                            action: "addSerial"
                        }, ]
                    });
                },
                hideLoading() {
                    $("#pageLoader").hide();
                },
                addNumber() {
                    let find_index = this.form.findIndex(i => i.member_phonenumber === $("#select_member").val().replace(/\+/g, ""));

                    if (find_index == -1) {
                        if ($("#select_member").val().replace(/\+/g, "")) {
                            this.form.push({
                                member_phonenumber: $("#select_member").val().replace(/\+/g, ""),
                                member_serial_qty: 1,
                            })

                        }
                        $('#select_member').val(null).trigger('change');
                        // this.number = '';
                    } else {
                        this.form[find_index].member_serial_qty += 1;
                        $('#select_member').val(null).trigger('change');
                        // this.number = '';
                    }
                },
                deleteNumber(index) {
                    this.form.splice(index, 1);
                },
                save() {
                    app.loading = true;
                    let member_phonenumber = app.form.map((item, index) => {
                        return String(item.member_phonenumber)
                    })
                    let member_serial_qty = app.form.map((item, index) => {
                        return item.member_serial_qty
                    })
                    $.ajax({
                        url: window.location.origin + '/admin/service/serial/addRepeatOrderSerial',
                        method: 'POST',
                        data: {
                            member_phonenumber,
                            member_serial_qty
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                app.alert.success.content = response.message;
                                app.alert.success.status = true;

                                $('#modalApp').modal('hide');

                                setTimeout(() => {
                                    app.alert.success.status = false;
                                }, 5000);
                                app.generateTable();
                            }
                            app.loading = false;
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
                                    console.log(app.alert.danger);
                                    setTimeout(() => {
                                        app.alert.danger.status = false;
                                    }, 3000);
                                }
                            } else {
                                app.alert.danger.content = `<ul>`;
                                app.alert.danger.content = `<li> ${response.message} </li>`;
                                app.alert.danger.content += `</ul>`;
                                app.alert.danger.status = true;

                                console.log('tests');

                                setTimeout(() => {
                                    app.alert.danger.status = false;
                                }, 3000);
                            }

                            app.loading = false;
                        },
                    });

                },
                getMember() {
                    $.ajax({
                        url: window.location.origin + '/admin/service/member/getOptionMember',
                        method: 'GET',
                        success: function(response) {
                            if (response.status == 200) {
                                app.member = response.data.results;
                            }
                        },

                    });
                },
                isNumber: function(evt) {
                    evt = (evt) ? evt : window.event;
                    var charCode = (evt.which) ? evt.which : evt.keyCode;
                    if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                        evt.preventDefault();;
                    } else {
                        return true;
                    }
                }
            },
            mounted() {
                this.getMember();
                this.hideLoading();
            },
        }).mount("#app");

    function addSerial() {
        $('#modalApp').modal();
        app.form = [];
    }


    $(document).ready(function() {
        // Initiate Function
        app.generateTable();

        // Select 2
        $('.select2').select2({
            width: '100%',
            tags: true,
            maximumSelectionLength: 3,
            allowClear: true,
            placeholder: function() {
                $(this).data('placeholder');
            }
        });

        $('#select_member').one('select2:open', function(e) {
            $('input.select2-search__field').prop('placeholder', 'Masukan nomor lalu tekan enter untuk menambah nomor yang tidak terdaftar.');
        });
    });
</script>