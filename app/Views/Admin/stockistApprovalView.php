<style>
    .swal2-cancel {
        color: #475F7B !important;
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
                        <div class="alert alert-success " v-show="alert.success.status" style="display: none;">
                            <span v-html="alert.success.content"></span>
                        </div>
                        <div class="alert alert-danger " v-show="alert.danger.status" style="display: none;">
                            <span v-html="alert.danger.content"></span>
                        </div>
                        <div id="response-message"></div>
                        <div id="table"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetail" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title card-title ">Detail Pengajuan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body py-1 my-0 mx-0">
                    <div class="row  py-0 my-0">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-2 pb-2">
                            <div class="row">
                                <div class="col-12">
                                    <h5> Informasi Member</h5>
                                    <ul class="ml- pl-0" style="list-style-type: none;">
                                        <li class="mt-1" style="display: flex; align-items: center; "><i class="bx bx-user mr-1 font-medium-5"></i>
                                            {{detailModal.member_name}} ({{detailModal.network_code}})
                                        </li>
                                        <li class="mt-1 ">
                                            <div style="display: flex; align-items: center; ">
                                                <i class="bx bx-calendar  font-medium-5 mr-1"></i> Terdaftar pada {{detailModal.member_join_date}}
                                            </div>
                                        </li>
                                        <li class="mt-2">Status <span class="text-warning ml-2">Menunggu Konfirmasi</span></li>
                                    </ul>
                                </div>
                                <div class="col-12 pt-2">
                                    <h5> Informasi Pengajuan</h5>

                                    <table class="mt-2" style="width: 100%;">
                                        <tr class="mt-2">
                                            <td style="width:50%;"><b>Nama Stokis</b></td>
                                            <td>{{detailModal.stockist_name}}</td>
                                        </tr>
                                        <tr class="mt-2">
                                            <td><b>Email </b></td>
                                            <td>{{detailModal.stockist_email}}</td>
                                        </tr>
                                        <tr class="mt-2">
                                            <td><b>Nomor Telepon </b></td>
                                            <td><a :href="detailModal.stockist_mobilephone_phonenumber_formatted">{{ detailModal.stockist_mobilephone_phonenumber_formatted }}</a></td>
                                        </tr>
                                        <tr class="mt-2">
                                            <td><b>Alamat</b></td>
                                            <td>{{detailModal.stockist_address}} <br>
                                                Provinsi, {{detailModal.province_name}} <br>
                                                Kota/Kabupaten, {{detailModal.city_name}} <br>
                                                Kecamatan, {{detailModal.subdistrict_name}}
                                            </td>
                                        </tr>
                                        <tr class="mt-2">
                                            <td><b>Tanggal Pengajuan</b></td>
                                            <td> {{detailModal.stockist_join_date}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button @click="approve()" type="button" class="btn btn-success">
                        <span>Setuju</span>
                    </button>
                    <button @click="reject()" type="button" class="btn btn-danger">
                        <span>Tolak</span>
                    </button>

                </div>
            </div>
        </div>
    </div>

</section>

<script src="https://unpkg.com/vue@next"></script>

<script>
    // Function That Called
    let app =
        Vue.createApp({
            data: function() {
                return {
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
                    detailModal: {}
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },

                generateTable() {
                    $("#table").dataTableLib({
                        url: window.location.origin + '/admin/service/stockist/getData/pending',
                        selectID: 'stockist_member_id',
                        colModel: [{
                                display: 'Nama Stokis',
                                name: 'stockist_name',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Provinsi',
                                name: 'province_name',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Kota',
                                name: 'city_name',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Kecamatan',
                                name: 'subdistrict_name',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Kode Mitra',
                                name: 'network_code',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Nama Mitra',
                                name: 'member_name',
                                sortAble: false,
                                align: 'left'
                            },
                            {
                                display: 'Aksi',
                                name: 'stockist_member_id',
                                sortAble: false,
                                align: 'center',
                                render: (params) => {
                                    return `<a onclick="app.detail('${params}')"> <i class="bx bx-receipt success"></i> </a>`;
                                }
                            },
                        ],
                        options: {
                            limit: [10, 15, 20],
                            currentLimit: 10,
                        },
                        search: true,
                        searchTitle: 'Pencarian Data',
                        searchItems: [{
                                display: 'Provinsi',
                                name: 'province_name',
                                type: 'text'
                            },
                            {
                                display: 'Kota',
                                name: 'city_name',
                                type: 'text'
                            },
                            {
                                display: 'Kecamatan',
                                name: 'subdistrict_name',
                                type: 'text'
                            },
                            {
                                display: 'Kode Mitra',
                                name: 'network_code',
                                type: 'text'
                            },
                            {
                                display: 'Nama Mitra',
                                name: 'member_name',
                                type: 'text'
                            },
                        ],
                        sortName: "stockist_join_date",
                        sortOrder: "ASC",
                        tableIsResponsive: true,
                        select: false,
                        multiSelect: false,
                        buttonAction: []
                    });
                },
                phoneNumberFormatter(number) {
                    let formatted = number.replace(/\D/g, '');

                    if (formatted.startsWith('0')) {
                        formatted = '+62' + formatted.substr(1);
                    }

                    return formatted;
                },
                detail(stockist_member_id) {
                    $.ajax({
                        url: window.location.origin + '/admin/service/stockist/getDetail',
                        method: 'GET',
                        data: {
                            stockist_member_id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                app.detailModal = response.data.results;
                                app.detailModal.stockist_mobilephone_phonenumber_formatted = app.detailModal.stockist_mobilephone ? 'https://wa.me/' + app.phoneNumberFormatter(app.detailModal.stockist_mobilephone) : app.detailModal.stockist_mobilephone;

                                $('#modalDetail').modal();
                            }
                        },
                    });

                },
                approve() {
                    $('#modalDetail').modal('hide');

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
                                url: window.location.origin + '/admin/service/stockist/approve',
                                method: 'POST',
                                data: {
                                    stockist_member_id: app.detailModal.stockist_member_id
                                },
                                success: function(response) {
                                    if (response.status == 200) {
                                        app.alert.success.status = true;
                                        app.alert.success.content = 'Data Berhasil Disimpan.';

                                        setTimeout(() => {
                                            app.alert.success.content = '';
                                            app.alert.success.status = false;
                                        }, 2000);
                                        app.generateTable();
                                        $('#modalDetail').modal('hide')
                                    }
                                },
                                error: function(err) {
                                    app.alert.danger.status = true;
                                    app.alert.danger.content = 'Data Gagal Disimpan.';

                                    setTimeout(() => {
                                        app.alert.danger.content = '';
                                        app.alert.danger.status = false;
                                    }, 2000);
                                    app.generateTable();
                                    $('#modalDetail').modal('hide')
                                }
                            });
                        } else {
                            $('#modalDetail').modal();
                        }
                    });
                },
                reject() {
                    $('#modalDetail').modal('hide');

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
                                url: window.location.origin + '/admin/service/stockist/rejected',
                                method: 'POST',
                                data: {
                                    stockist_member_id: app.detailModal.stockist_member_id
                                },
                                success: function(response) {
                                    if (response.status == 200) {
                                        app.alert.success.status = true;
                                        app.alert.success.content = 'Data Berhasil Disimpan.';

                                        setTimeout(() => {
                                            app.alert.success.content = '';
                                            app.alert.success.status = false;
                                        }, 2000);
                                        app.generateTable();
                                        $('#modalDetail').modal('hide')
                                    }
                                },
                                error: function(err) {
                                    app.alert.danger.status = true;
                                    app.alert.danger.content = 'Data Gagal Disimpan.';

                                    setTimeout(() => {
                                        app.alert.danger.content = '';
                                        app.alert.danger.status = false;
                                    }, 2000);
                                    app.generateTable();
                                    $('#modalDetail').modal('hide')
                                }
                            });
                        } else {
                            $('#modalDetail').modal();
                        }
                    });

                    app.generateTable();

                }
            },
            mounted() {
                this.hideLoading();
            }

        }).mount("#app");


    $(document).ready(function() {
        app.generateTable();
    });
</script>