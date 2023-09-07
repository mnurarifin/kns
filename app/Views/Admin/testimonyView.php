<style>
    .alert-position {
        transform: translateY(5px);
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
                    <div class="card-body card-dashboard">
                        <div id="response-messages"></div>
                        <div id="table-testy"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modalUpdateTesty" tabindex="-1" role="dialog" aria-labelledby="modalUpdateTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUpdateTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form class="form form-horizontal" id="formUpdateTesty">
                <div class="modal-body" style="max-height: 470px;">
                    <div id="response-message"></div>
                    <div class="form-body">
                        <div class="media d-flex align-items-center mb-1">
                            <a href="JavaScript:void(0);">
                                <img id="imgMitra" src="" class="rounded" alt="group image" height="64" width="64">
                            </a>
                            <div class="media-body ml-1">
                                <h5 class="media-heading mb-0"><small id="kodeMitra"></small></h5>
                                <span class="text-muted" id="namaMitra"></span>
                            </div>
                            <span class="text-primary d-flex align-items-center " id="tanggal"></span>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="isiTesti">Isi Testimoni</label>
                                    <textarea name="isiTesti" id="isiTesti" class="form-control" rows="6"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tutup</span>
                    </button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#table-testy").dataTableLib({
            url: window.location.origin + '/admin/service/testimony/getDataTesty',
            selectID: 'testimony_id',
            colModel: [{
                    display: 'Nama Mitra',
                    name: 'member_name',
                    sortAble: false,
                    align: 'left'
                },
                {
                    display: 'Kode Mitra',
                    name: 'network_code',
                    sortAble: false,
                    align: 'center'
                },
                {
                    display: 'Testimoni',
                    name: 'testimony_content_short',
                    sortAble: false,
                    align: 'left'
                },
                {
                    display: 'Tanggal',
                    name: 'testimony_date',
                    sortAble: false,
                    align: 'left'
                },
                {
                    display: 'Tampil',
                    name: 'testimony_is_publish',
                    sortAble: false,
                    align: 'center',
                    width: "70px",
                    render: (params) => {
                        return params == '1' ? '<span class="badge badge-light-success badge-pill badge-round" title="Ya" data-toggle="tooltip"><i class="bx bx-check-circle"></i></span>' : '<span class="badge badge-light-danger badge-pill badge-round" title="Tidak" data-toggle="tooltip"><i class="bx bx-x-circle"></i></span>'
                    }
                },
                {
                    display: 'Ubah',
                    name: '',
                    sortAble: false,
                    align: 'center',
                    width: "70px",
                    action: {
                        function: 'updateTesty',
                        icon: 'bx bx-edit-alt warning',
                        class: 'warning'
                    }
                },
            ],
            buttonAction: [{
                    display: 'Tampilkan',
                    icon: 'bx bx-check-circle',
                    style: "success",
                    action: "active",
                    url: window.location.origin + "/admin/service/testimony/activeTesty"
                },
                {
                    display: 'Sembunyikan',
                    icon: 'bx bx-x-circle',
                    style: "danger",
                    action: "nonactive",
                    url: window.location.origin + "/admin/service/testimony/nonActiveTesty"
                },
                {
                    display: 'Hapus',
                    icon: 'bx bx-trash',
                    style: "danger",
                    action: "remove",
                    url: window.location.origin + "/admin/service/testimony/removeTesty",
                    message: 'Oke'
                }
            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Testimonial',
            searchItems: [{
                    display: 'Nama Mitra',
                    name: 'testimony_member_name',
                    type: 'text'
                },
                {
                    display: 'Kode Mitra',
                    name: 'network_code',
                    type: 'text'
                },
                {
                    display: 'Tanggal',
                    name: 'testimony_date',
                    type: 'date'
                },
                {
                    display: 'Publikasi',
                    name: 'testimony_is_publish',
                    type: 'select',
                    option: [{
                        title: 'Ditampilkan',
                        value: '1'
                    }, {
                        title: 'Disembunyikan',
                        value: '0'
                    }, ]
                },
            ],
            sortName: "testimony_id",
            sortOrder: "desc",
            tableIsResponsive: true,
            select: true,
            multiSelect: true,
        });
    });

    let selectID = '';

    function updateTesty(testy) {
        $('#response-message').html('');
        $('#modalUpdateTitle').text('Form Sunting Testimoni Mitra');
        selectID = testy.testimony_id;

        $('#namaMitra').text(testy.testimony_member_name);
        $('#kodeMitra').text(testy.network_code);
        $('#imgMitra').attr('src', testy.testimony_member_image)
        $('#tanggal').text(testy.testimony_date);
        $('#isiTesti').text(testy.testimony_content);
        $('#modalUpdateTesty').modal('show');
    }

    $('#formUpdateTesty').on('submit', (e) => {
        e.preventDefault();
        $('#response-message').html('');
        $('#formUpdateTesty button[type=submit]').prop('disabled', true)
        $('#formUpdateTesty button[type=submit]').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memproses...')

        let formData = new FormData(e.target);
        formData.append('testyId', selectID);

        $.ajax({
            url: window.location.origin + '/admin/service/testimony/updateTesty',
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#formUpdateTesty button[type=submit]').prop('disabled', false)
                $('#formUpdateTesty button[type=submit]').html('Simpan')
                if (response.status == 200) {
                    $('#modalUpdateTesty').modal('hide');
                    $('#response-messages').html(response.data.message);
                    $('#response-messages').addClass('alert alert-success');
                } else {
                    $('#modalUpdateTesty').modal('hide');
                    $('#response-messages').html(response.data.message);
                    $('#response-messages').addClass('alert alert-danger');
                }
                setTimeout(function() {
                    $('#response-messages').html('');
                    $('#response-messages').removeClass();
                }, 2000);
                $.refreshTable('table-testy');
            },
            error: function(err) {
                $('#formUpdateTesty button[type=submit]').prop('disabled', false)
                $('#formUpdateTesty button[type=submit]').html('Simpan')
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
</script>