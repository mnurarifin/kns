<style>
    .cstm-table tr td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .btn-success {
        background-color: #39DA8A !important;
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
                </button>
                <button class="btn btn-primary" onclick="reply()">Balas</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-reply" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-radius: 7px 7px 0 0; border: none">
                <h4 class="modal-title" id="modal-label">Balas Pesan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body p-0" style="overflow: scroll;">
                <div class="panel panel-default mb-1">
                    <div class="panel-body p-0">
                        <div class="card-body row" style="margin: 0">
                            <div class="outline--form col-lg-12 col-md-12" style="border-radius: 15px; border: 1px solid #DFE3E7; padding: 0">
                                <div style="margin: 10px 0; padding: 20px">
                                    <form id="formDetail">
                                        <div id="response-message"></div>
                                        <label for="">Penerima</label>
                                        <div class="input-group" style="margin-bottom: 10px">
                                            <!-- <select id="message_receive_id" class="mitra--tujuan select2" name="states[]" multiple="multiple" readonly style="width: 100%">
                                            </select> -->
                                            <input type="text" class="form-control" readonly id="message_receive_id">
                                        </div>

                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-12 form-group">
                                                    <label for="">Pesan</label>
                                                    <!-- Compose mail Quill editor -->
                                                    <div class="snow-container border rounded p-50">
                                                        <div id="balasPesan" class="compose-editor" style="border: none; min-height: 200px"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-lg-6 ml-auto text-right">
                                            <a href="#" class="btn btn-primary" onclick="sendReply()">
                                                <span style="font-weight: 700">KIRIM</span>
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border: none;">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        app.generateTable();
        app.hideLoading();
        $("#data_kosong").hide()

        // $('#message_receive_id').select2({
        //     placeholder: 'Mitra Tujuan',
        // });

        $('.ql-editor').attr("data-placeholder", "Tulis Pesan Anda....");
        var container = document.getElementById('message_content');
        var editor = new Quill(container);
        editor.enable(false)

        $('.select2').select2({
            minimumInputLength: 3,
            allowClear: true,
            placeholder: 'Mitra Tujuan',
            ajax: {
                url: window.location.origin + '/member/message/getReceiver',
                dataType: 'json',
                data: function(params) {
                    return {
                        search: params.term
                    }
                },
                processResults: function(data, page) {
                    return {
                        results: data
                    };
                }
            }
        });
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
                    url: window.location.origin + '/member/message/get-message/message/all',
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
                            display: 'Nama Pengirim',
                            name: 'message_sender_ref_name',
                            sortAble: false,
                            align: 'left',
                            export: false
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
                        display: 'Arsipkan',
                        icon: 'bx bx-archive-in',
                        style: "btn-warning",
                        action: "active",
                        message: "Arsipkan",
                        url: "<?= BASEURL ?>/member/message/archiveMessage"
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
                        display: 'Nama Penerima',
                        name: 'message_sender_ref_name',
                        type: 'text'
                    }, {
                        display: 'Kode Mitra Penerima',
                        name: 'message_sender_ref_code',
                        type: 'text'
                    }, {
                        display: 'Tanggal Pesan',
                        name: 'message_datetime',
                        type: 'date'
                    }],
                    sortName: "message_datetime",
                    sortOrder: "desc",
                    tableIsResponsive: true,
                    select: true,
                    multiSelect: true,
                })
            },
            openModal() {
                $('#modalAddUpdate').modal()
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

                        this.modal.form = data

                        $('#kodePengirim').html(`<i class="bx bxs-id-card px-1 position"></i>Kode Mitra : <span class="text-primary">${data.message_sender_ref_code}</span>`)
                        $('#namaPengirim').html(`<i class="bx bxs-user-circle px-1 position"></i>Nama Mitra :  <span class="text-primary">${data.message_sender_ref_name}</span>`)
                        $('#kodePenerima').html(`<i class="bx bxs-id-card px-1 position"></i>Kode Mitra : <span class="text-primary">${data.message_receiver_ref_code}</span>`)
                        $('#namaPenerima').html(`<i class="bx bxs-user-circle px-1 position"></i>Nama Mitra :  <span class="text-primary">${data.message_receiver_ref_name}</span>`)
                        $('#message_content .ql-editor p').html(data.message_content)
                        $('#tanggalPesan').text(data.message_datetime + ' WIB')
                    },
                })

                $('#detailPesan').modal()
            },
            reply() {
                data = this.modal.form;

                if (data.message_sender_ref_type == 'admin') {
                    $('#message_receive_id').val('Admin');
                    this.modal.form.message_sender_ref_code = 'admin';
                } else {
                    $('#message_receive_id').val(data.message_sender_ref_name + ' - ' + data.message_sender_ref_code);
                }

                $('#detailPesan').modal('hide')

                var containerReply = document.getElementById('balasPesan');
                var editorReply = new Quill(containerReply);
                editorReply.enable(true)

                $('#modal-reply').modal()
            },
            sandReply() {
                $.ajax({
                    url: "<?= BASEURL ?>/member/message/reply",
                    type: "POST",
                    async: false,
                    data: {
                        message_receiver_ref_id: this.modal.form.message_sender_ref_id,
                        message_receiver_ref_code: this.modal.form.message_sender_ref_code,
                        message_receiver_ref_name: this.modal.form.message_sender_ref_name,
                        message_receiver_ref_type: this.modal.form.message_sender_ref_type,
                        message_sender_ref_type: this.modal.form.message_receiver_ref_type,
                        message_sender_ref_id: this.modal.form.message_receiver_ref_id,
                        message_sender_ref_code: this.modal.form.message_receiver_ref_code,
                        message_sender_ref_name: this.modal.form.message_receiver_ref_name,
                        message_content: $('#balasPesan .ql-editor p').html(),
                        message_type: 'pesan'
                    },
                    success: (res) => {
                        this.modal.form = {}
                        $("#alert-success").html(res.message)
                        $("#alert-success").show()
                        $('#balasPesan .ql-editor p').html('')
                        $('#modal-reply').modal('hide')
                        setTimeout(function() {
                            $("#alert-success").hide()
                        }, 3000)
                    },
                    error: (err) => {
                        res = err.responseJSON
                        $("#alert").html(res.message)
                        $("#alert").show()
                        $('#balasPesan .ql-editor p').html('')
                        $('#modal-reply').modal('hide')
                        setTimeout(function() {
                            $("#alert").hide()
                        }, 3000);
                    }
                })
            }
        }
    }).mount('#app');

    function reply() {
        app.reply();
    }

    function sendReply() {
        app.sandReply();
    }
</script>