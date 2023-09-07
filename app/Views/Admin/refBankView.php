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

    th {
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        background: white;
    }
</style>
<div class="modal fade" id="modalAddUpdateBank" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdateTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddUpdateTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form class="form form-horizontal" id="formAddUpdateBank">
                <div class="modal-body" style="max-height: 470px;">
                    <div id="response-message"></div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nama Bank</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="bankName" placeholder="Nama Bank">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Logo Bank</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="file" class="form-control" name="bankImage" placeholder="">
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
                        <div id="response-messages"></div>
                        <div id="table-bank"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(function() {
        $("#table-bank").bind("DOMSubtreeModified", function() {
            if ($("#table-bank").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        $("#table-bank").dataTableLib({
            url: window.location.origin + '/admin/service/ref-bank/getDataBank',
            selectID: 'bank_id',
            colModel: [{
                    display: 'Nama Bank',
                    name: 'bank_name',
                    sortAble: false,
                    align: 'left',
                    width: "150px",
                    export: true
                },
                {
                    display: 'Gambar Bank',
                    name: 'bank_logo',
                    sortAble: false,
                    align: 'center',
                    width: "180px",
                    render: (params) => {
                        let image = params !== '' ? `<?php echo $imagePath ?>${params}` : `<?php echo base_url(); ?>/no-image.png`;
                        return `<img src="${image}" height="60" width="60" class="img-fluid"/>`
                    },
                    export: false
                },
                {
                    display: 'Status',
                    name: 'bank_is_active',
                    sortAble: false,
                    align: 'center',
                    render: (params) => {
                        return params == '1' ? '<span class="badge badge-light-success badge-pill badge-round" title="Aktif" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>' : '<span class="badge badge-light-danger badge-pill badge-round" title="Non Aktif" data-toggle="tooltip"><i class="bx bx-bulb"></i></span>'
                    },
                    export: true
                },
                {
                    display: 'Ubah',
                    name: 'edit',
                    sortAble: false,
                    align: 'center',
                    action: {
                        function: 'updateBank',
                        icon: 'bx bx-edit-alt warning',
                        class: 'warning'
                    }
                },
            ],
            buttonAction: [{
                    display: 'Tambah',
                    icon: 'bx bx-plus',
                    style: "info",
                    action: "addBank"
                }, {
                    display: 'Aktifkan',
                    icon: 'bx bxs-bulb',
                    style: "success",
                    action: "active",
                    url: window.location.origin + "/admin/service/ref-bank/activeBank"
                },
                {
                    display: 'Non Aktifkan',
                    icon: 'bx bx-bulb',
                    style: "warning",
                    action: "nonactive",
                    url: window.location.origin + "/admin/service/ref-bank/nonactiveBank"
                },
                {
                    display: 'Excel',
                    icon: 'bx bxs-file',
                    style: 'info',
                    action: 'exportExcel',
                    url: window.location.origin + "/admin/ref-bank/excel"
                },
                {
                    display: 'Hapus',
                    icon: 'bx bx-trash',
                    style: "danger",
                    action: "remove",
                    url: window.location.origin + "/admin/service/ref-bank/removeBank",
                    message: 'Hapus'
                }
            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Bank',
            searchItems: [{
                display: 'Nama Bank',
                name: 'bank_name',
                type: 'text'
            }, ],
            sortName: "bank_id",
            sortOrder: "asc",
            tableIsResponsive: true,
            select: true,
            multiSelect: true,
        });
    });

    let stateBank = {
        baseUrl: window.location.origin,
        addUrl: '/admin/service/ref-bank/addBank',
        updateUrl: '/admin/service/ref-bank/updateBank',
        selectID: '',
        selectedOldImage: '',
        formAction: ''
    }

    function addBank() {
        $('#response-message').html('')
        $('#modalAddUpdateTitle').text('Form Tambah Data Bank')
        stateBank.selectID = '';
        stateBank.formAction = 'Add'
        $('#formAddUpdateBank').trigger('reset');
        $('#modalAddUpdateBank').modal('show')
    }

    function updateBank(data) {
        $('#response-message').html('');
        $('#modalAddUpdateTitle').text('Form Ubah Data Bank');
        stateBank.selectID = data.bank_id;
        stateBank.selectedOldImage = data.bank_logo;
        stateBank.formAction = 'update';

        $('#formAddUpdateBank input[name=bankName]').val(data.bank_name);
        $('#modalAddUpdateBank').modal('show');
    }

    $('#formAddUpdateBank').on('submit', (e) => {
        e.preventDefault();
        $('#response-message').html('');
        $('#formAddUpdateBank button[type=submit]').prop('disabled', true)
        $('#formAddUpdateBank button[type=submit]').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')

        let formData = new FormData(e.target);
        let url = stateBank.baseUrl + stateBank.addUrl;
        if (stateBank.formAction == 'update') {
            formData.append('bankId', stateBank.selectID);
            formData.append('oldLogo', stateBank.selectedOldImage);
            url = stateBank.baseUrl + stateBank.updateUrl;
        }

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#formAddUpdateBank button[type=submit]').prop('disabled', false)
                $('#formAddUpdateBank button[type=submit]').html('Simpan')
                if (response.status == 200) {
                    $('#modalAddUpdateBank').modal('hide');
                    $('#response-messages').html(response.data.message);
                    $('#response-messages').addClass('alert alert-success');
                } else {
                    $('#modalAddUpdateBank').modal('hide');
                    $('#response-messages').html(response.data.message);
                    $('#response-messages').addClass('alert alert-danger');
                }
                setTimeout(function() {
                    $('#response-messages').html('');
                    $('#response-messages').removeClass();
                }, 2000);
                $.refreshTable('table-bank');
            },
            error: function(err) {
                $('#formAddUpdateBank button[type=submit]').prop('disabled', false)
                $('#formAddUpdateBank button[type=submit]').html('Simpan')
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