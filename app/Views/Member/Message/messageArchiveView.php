<style>
    .cstm-table tr td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
<!-- BEGIN: Content-->
<div class="app-content content" id="app">
    <div class="content-overlay">
    </div>
    <div class="content-wrapper">
        <div class="alert alert-danger" role="alert" id="alert" style="position: absolute; top: 84px; right: 32px; display: none;"></div>
        <div class="alert alert-success" role="alert" id="alert-success" style="position: absolute; top: 84px; right: 32px; display: none;"></div>

        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0"><?= $title ?></h5>

                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="<?= BASEURL ?>/member/dashboard"><i class="bx bx-home-alt"></i></a></li>
                                <li class="breadcrumb-item"><a href="#"><?= $breadcrumbs[0] ?></a></li>
                                <li class="breadcrumb-item active"><?= $breadcrumbs[1] ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="mb-1" id="table">
                    </div>
                    <div class="card p-2" id="data_kosong">
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <img src="<?= BASEURL ?>/app-assets/images/no-data-green.svg" alt="" style=" filter: grayscale(100%);">
                            </div>
                            <div class="col-md-12 d-flex justify-content-center mt-3">
                                <label>Tidak ada informasi yang ditampilkan</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailPesan" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdateTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center">
                    <span>Detail Pesan</span>
                </h5>
                <h4 class="modal-title d-flex align-items-center" id="tanggalPesan"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="card widget-state-multi-radial">
                    <div class="card-body py-1">
                        <div class="row">
                            <input type="hidden" id="pengirim">
                            <input type="hidden" id="nama">
                            <div class="col-md-12">
                                <ul class="list-group list-group-flush d-flex flex-row">
                                    <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                        <div class="list-left d-flex">
                                            <div class="list-icon mr-1">
                                                <h4 class="text-primary">Pengirim</h4>
                                                <span class="text-muted d-block" id="kodePengirim"></span>
                                                <span class="text-muted d-block" id="namaPengirim"></span>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                        <div class="list-left d-flex">
                                            <div class="list-icon mr-1">
                                                <h4 class="text-primary">Penerima</h4>
                                                <span class="text-muted d-block" id="kodePenerima"></span>
                                                <span class="text-muted d-block" id="namaPenerima"></span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-top mx-2">
                    <form class="form form-vertical py-2">
                        <div class="form-body">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="product_description">Isi Pesan</label>
                                    <div class="snow-container border rounded p-50">
                                        <div id="message_content" class="" style="border: none; min-height: 200px"></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        app.generateTable();
        app.hideLoading();
        $("#data_kosong").hide()

        var container = document.getElementById('message_content');
        var editor = new Quill(container);
        editor.enable(false)
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
                    form: {},
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
            generateTable() {
                $("#table").dataTableLib({
                    url: window.location.origin + '/member/message/get-message/archive/all',
                    selectID: 'message_id',
                    colModel: [{
                            display: '',
                            name: 'message_status',
                            sortAble: false,
                            align: 'left',
                            export: false,
                            render: (params) => {
                                return params == 0 ? `<span class="btn text-info"><i class='bx bx-envelope'></i></span>` : `<span class="btn text-info"><i class='bx bx-envelope'></i></span>`
                            }
                        },
                        {
                            display: 'Pesan',
                            name: 'message_content',
                            sortAble: false,
                            align: 'left',
                            export: false,
                            width: '250px'
                        },
                        {
                            display: 'Tanggal',
                            name: 'message_datetime',
                            sortAble: false,
                            align: 'left',
                            export: false
                        },
                        {
                            display: 'Info',
                            name: 'message_id',
                            sortAble: false,
                            align: 'center',
                            render: (params) => {
                                return `<a href='#' onclick="app.detailMessage(${params})" class="btn text-info"><i class='bx bx-info-circle'></i></a>`
                            }
                        },
                    ],
                    buttonAction: [{
                        display: 'Buka Arsip',
                        icon: 'bx bx-archive-out',
                        style: "btn-warning",
                        action: "active",
                        message: "Buka Arsip",
                        url: "<?= BASEURL ?>/member/message/unarchiveMessage"
                    }, {
                        display: 'Hapus',
                        icon: 'bx bx-trash',
                        style: "btn-danger",
                        action: "remove",
                        url: "<?= BASEURL ?>/member/message/remove"
                    }],
                    options: {
                        limit: [10, 25, 50, 100],
                        currentLimit: 10,
                    },
                    search: true,
                    searchTitle: 'Pencarian Data',
                    searchItems: [{
                        display: 'Tanggal Pesan',
                        name: 'message_datetime',
                        type: 'date'
                    }, ],
                    sortName: "message_datetime",
                    sortOrder: "desc",
                    tableIsResponsive: true,
                    select: true,
                    multiSelect: true,
                })
            },
            detailMessage(message_id) {
                $.ajax({
                    url: "<?= BASEURL ?>/member/message/get-detail",
                    type: "GET",
                    async: false,
                    data: {
                        message_id: message_id
                    },
                    success: (res) => {
                        data = res.data.results

                        $('#kodePengirim').html(`<i class="bx bxs-id-card px-1 position"></i>Kode Mitra : <span class="text-primary">${data.message_sender_ref_code}</span>`)
                        $('#namaPengirim').html(`<i class="bx bxs-user-circle px-1 position"></i>Nama Mitra :  <span class="text-primary">${data.message_sender_ref_name}</span>`)
                        $('#kodePenerima').html(`<i class="bx bxs-id-card px-1 position"></i>Kode Mitra : <span class="text-primary">${data.message_receiver_ref_code}</span>`)
                        $('#namaPenerima').html(`<i class="bx bxs-user-circle px-1 position"></i>Nama Mitra :  <span class="text-primary">${data.message_receiver_ref_name}</span>`)
                        $('#message_content .ql-editor p').html(data.message_content)
                        $('#tanggalPesan').text(data.message_datetime + ' WIB')
                    },
                })

                $('#detailPesan').modal()
            }
        }
    }).mount('#app');
</script>