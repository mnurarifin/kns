<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/app-assets/vendors/css/editors/quill/quill.snow.css">
<script src="<?php echo base_url(); ?>/app-assets/vendors/js/editors/quill/quill.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/app-assets/css/pages/app-email.css">
<script src="<?php echo base_url(); ?>/app-assets/js/scripts/pages/app-email-custom.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<style>
    .table {
        position: relative;
    }

    .table-responsive {
        max-height: calc(100vh - 0px);
    }

    .table th,
    .table td {
        padding: 0.4rem 1rem;
        white-space: nowrap;
    }

    .position {
        transform: translateY(4px);
    }

    th {
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        background: white;
    }
</style>
<section id="horizontal-vertical">
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
                        <div id="table-village"></div>
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
                                            <div id="message_content" class="compose-editor" style="border: none; min-height: 200px"></div>
                                            <div class="d-flex justify-content-end">
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
    </div>
</section>

<script>
    $(function() {
        $("#table-village").bind("DOMSubtreeModified", function() {
            if ($("#table-village").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        var container = document.getElementById('message_content');
        var editor = new Quill(container);

        editor.enable(true)

        $('.ql-editor').attr("data-placeholder", "");
        $("#table-village").dataTableLib({
            url: window.location.origin + '/admin/service/message/getDataMessage/send',
            selectID: 'message_id',
            colModel: [{
                    display: '',
                    name: 'message_status',
                    sortAble: false,
                    align: 'left',
                    export: true,
                    render: (params) => {
                        return `<span class="btn text-info"><i class="bx bx-envelope"></i></span>`;
                    }
                },
                {
                    display: 'Tanggal',
                    name: 'message_datetime',
                    sortAble: false,
                    align: 'left',
                    export: true
                },
                {
                    display: 'Pesan',
                    name: 'message_content',
                    sortAble: false,
                    align: 'left',
                    width: '550px',
                    render: (params) => {
                        return params ? params : '-'
                    }
                },
                {
                    display: '',
                    name: '',
                    sortAble: false,
                    align: 'center',
                    action: {
                        function: 'detailMessage',
                        icon: 'bx bxs-info-circle info',
                        class: 'warning'
                    }
                },
            ],
            buttonAction: [{
                    display: 'Hapus',
                    icon: 'bx bx-trash',
                    style: "danger",
                    action: "remove",
                    url: window.location.origin + "/admin/service/message/deleteMessage"
                },
                {
                    display: 'Arsipkan',
                    icon: 'bx bx-archive',
                    style: "warning",
                    action: "active",
                    url: window.location.origin + "/admin/service/message/archiveMessage"
                },
            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Pesan',
            searchItems: [{
                display: 'Tanggal',
                name: 'message_datetime',
                type: 'date'
            }, ],
            sortName: "message_id",
            sortOrder: "desc",
            tableIsResponsive: true,
            select: true,
            multiSelect: true,
        });
    });

    function detailMessage(message) {
        $.ajax({
            url: window.location.origin + `/admin/service/message/getDataById/${message.message_id}`,
            method: 'GET',
            success: function(response) {
                if (response.status == 200) {
                    let {
                        message_sender_ref_id,
                        message_sender_ref_code,
                        message_sender_ref_name,
                        message_receiver_ref_code,
                        message_receiver_ref_name,
                        message_content,
                        message_datetime,
                        message_receiver_ref_type
                    } = response.data.results;
                    $('#pengirim').val(message_sender_ref_id);
                    $('#nama').val(message_sender_ref_name);
                    $('#kodePengirim').html(`<i class="bx bxs-id-card px-1 position"></i>Kode Mitra : <span class="text-primary">${message_sender_ref_code}</span>`)
                    $('#namaPengirim').html(`<i class="bx bxs-user-circle px-1 position"></i>Nama Mitra :  <span class="text-primary">${message_sender_ref_name}</span>`)
                    $('#kodePenerima').html(`<i class="bx bxs-id-card px-1 position"></i>Kode Mitra : <span class="text-primary">-</span>`)
                    $('#namaPenerima').html(`<i class="bx bxs-user-circle px-1 position"></i>Nama Mitra :  <span class="text-primary">${message_receiver_ref_name}</span>`)
                    $('#message_content .ql-editor p').html(message_content)
                    $('#tanggalPesan').text(toTanggal(message_datetime) + ' WIB')
                }
            }
        });
        $('#detailPesan').modal('show')
    }
</script>