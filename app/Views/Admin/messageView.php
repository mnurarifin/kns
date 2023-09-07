<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/app-assets/vendors/css/editors/quill/quill.snow.css">
<script src="<?php echo base_url(); ?>/app-assets/vendors/js/editors/quill/quill.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/app-assets/css/pages/app-email.css">
<script src="<?php echo base_url(); ?>/app-assets/js/scripts/pages/app-email-custom.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #DFE3E7 !important;
        padding: 2px 8px;
    }

    .select2.select2-container.select2-container--default {
        width: calc(100% - 45px) !important;
    }

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
                    <button class="btn btn-primary" id="replyMessage">Balas</button>
                </div>
            </div>
        </div>
    </div>
</section>

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
                                        <div class="input-group" style="margin-bottom: 10px">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-primary d-flex justify-content-center" style="width: 45px">
                                                    <img src="<?php echo base_url(); ?>/app-assets/images/buat_pesan/tujuan-pesan.svg" alt="">
                                                </div>
                                            </div>
                                            <select id="message_receive_id" class="mitra--tujuan select2" name="states[]" multiple="multiple" readonly style="width: calc(100% - 45px); padding: 0.47rem 0.8rem">
                                            </select>
                                        </div>

                                        <div class="input-group" style="margin-bottom: 10px">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-warning d-flex justify-content-center" style="width: 45px">
                                                    <img src="<?php echo base_url(); ?>/app-assets/images/buat_pesan/subjek.svg" alt="">
                                                </div>
                                            </div>
                                            <input type="text" placeholder="Subjek" class="form-control" aria-describedby="subjek" id="subject">
                                        </div>

                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-12 form-group">
                                                    <!-- Compose mail Quill editor -->
                                                    <div class="snow-container border rounded p-50">
                                                        <div id="balasPesan" class="compose-editor" style="border: none; min-height: 200px"></div>
                                                        <div class="d-flex justify-content-end">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-lg-6 ml-auto text-right">
                                            <button type="submit" class="btn btn-success" id="submit">
                                                <img class="mr-50" src="<?php echo base_url(); ?>/app-assets/images/buat_pesan/send.svg" alt="">
                                                <span style="font-weight: 700">KIRIM</span>
                                            </button>
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

<script type="text/javascript">
    $(function() {
        $("#table-village").bind("DOMSubtreeModified", function() {
            if ($("#table-village").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        let id = getUrlParameter('id');

        if (id) {
            getDetailMessage(id);
            readNotification(id);
        }

        $('.ql-editor').attr("data-placeholder", "Tulis Pesan Anda....");
        var container = document.getElementById('message_content');
        var editor = new Quill(container);
        editor.enable(false)

        var container_ = document.getElementById('balasPesan');
        var editor_ = new Quill(container_);
        editor_.enable(true)

        $("#table-village").dataTableLib({
            url: window.location.origin + '/admin/service/message/getDataMessage/inbox',
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
            sortName: "message_datetime",
            sortOrder: "desc",
            tableIsResponsive: true,
            select: true,
            multiSelect: true,
        });
    });

    $('.select2').select2({
        minimumInputLength: 3,
        allowClear: true,
        placeholder: 'Mitra Tujuan',
        ajax: {
            url: window.location.origin + '/admin/service/message/getPenerima',
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

    $(window).on('hashchange', function(e) {
        var hash = window.location.hash.substring(1);
        if (hash) {
            getDetailMessage(hash);
            readNotification(hash);
            history.replaceState(null, null, ' ');

        }
    });

    if (window.location.hash) {
        var hash = window.location.hash.substring(1);

        if (hash) {
            getDetailMessage(hash);
            readNotification(hash);
            history.replaceState(null, null, ' ');

        }
    }

    $('#replyMessage').on('click', function() {
        $('#detailPesan').modal('hide');
        $('#modal-reply').modal('show');
        var penerima = $('#pengirim').val()
        var nama = $('#nama').val();
        var getResults = new Option(nama, penerima, false, false);
        $('#message_receive_id').html(getResults).trigger('change');
        $('#message_receive_id').val(penerima);

    })

    function getDetailMessage(id) {
        $.ajax({
            url: window.location.origin + `/admin/service/message/getDataById/${id}`,
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
        readNotification(message.message_id)
    }

    function readNotification(id = 0) {
        let data = [];

        if (id) {
            data.push(id);
        }

        $.ajax({
            url: window.location.origin + '/admin/service/message/readMessage',
            method: 'POST',
            data: {
                data
            },
            success: function(response) {
                if (response.status == 200) {
                    getNotification();
                }
            }
        })
    }

    $('#submit').on('click', (e) => {
        e.preventDefault();

        $('#response-message').html('');
        $('#submit').prop('disabled', true)
        $('#submit').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')

        let message = '';
        let message_content = $('#balasPesan .ql-editor p').html();
        let subject = $('#subject').val();

        message += subject ? subject + '<br>' : ''
        message += message_content == '<br>' ? '' : message_content

        let receive_id = $('#message_receive_id').val()

        $.ajax({
            url: window.location.origin + '/admin/service/message/addMessage',
            method: "POST",
            data: {
                message_receive_id: receive_id,
                message_content: message,
                message_type: 'pesan'
            },
            success: function(response) {
                $('#submit').prop('disabled', false)
                $('#submit').html(
                    `<div class="d-flex align-center"><i class='bx bx-send d-flex align-items-center mr-25'></i> Kirim</div>`
                )

                if (response.status == 200) {
                    $('#response-message').html(
                        `<div class="alert alert-success"> ${response.message} </div>`)
                    location.reload();
                }

                setTimeout(function() {
                    $('#response-messages').html('');
                    $('#response-messages').removeClass();
                }, 2000);

            },
            error: function(err) {
                $('#submit').prop('disabled', false)
                $('#submit').html(
                    `<div class="d-flex align-center"><i class='bx bx-send d-flex align-items-center mr-25 mb-50'></i> Kirim</div>`
                )
                let response = err.responseJSON
                $('#response-message').show()
                if (response.message == "validationError") {
                    let message = '<ul>';
                    for (let key in response.data.validationMessage) {
                        message += `<li>${response.data.validationMessage[key]}</li>`
                    }
                    message += '</ul>'
                    $('#response-message').html(`
                        <div class="alert border-danger alert-dismissible mb-2" role="alert">
                            <div class="d-flex align-items-center">
                                <span class="alert-position">
                                    ${message}
                                </span>
                            </div>
                        </div>
                    `);
                    setTimeout(function() {
                        $('#response-message').hide('blind', {}, 500)
                    }, 5000);
                } else if (response.message == 'Unauthorized' && response.status == 403) {
                    location.reload();
                }
            }
        });
    });

    function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
        return false;
    };
</script>