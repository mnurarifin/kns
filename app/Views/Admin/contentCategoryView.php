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

    .alert-position {
        transform: translateY(5px);
    }
</style>
<div class="modal fade" id="modalkategori" tabindex="-1" role="dialog" aria-labelledby="modalkategori" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalkategoriTitle">Form Kategori Artikel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="response-error-content"></div>
                <form class="form form-horizontal" id="formKonten">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nama Kategori Artikel</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="category_name" placeholder="Nama Kategori Artikel">
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
                <button class="btn btn-primary" id="submitContent" type="submit">Simpan</button>
            </div>

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
                        <p class="card-text"></p>
                        <div id="response-message"></div>
                        <div id="table-content"></div>
                        <div id="table-xo"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(function() {
        $("#table-content").bind("DOMSubtreeModified", function() {
            if ($("#table-content").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        $("#table-content").dataTableLib({
            url: window.location.origin + '/admin/service/content/categoryList/',
            selectID: 'content_category_id',
            colModel: [{
                    display: 'Judul Kategori',
                    name: 'content_category_name',
                    sortAble: false,
                    align: 'left',
                    width: "180px",
                    export: true
                },
                {
                    display: 'Status',
                    name: 'content_category_is_active',
                    sortAble: false,
                    align: 'center',
                    render: (params) => {
                        var status;
                        switch (params) {
                            case '1':
                                status = '<span class="badge badge-light-success badge-pill badge-round" title="Aktif" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>';
                                break;
                            default:
                                status = '<span class="badge badge-light-danger badge-pill badge-round" title="Non Aktif" data-toggle="tooltip"><i class="bx bx-bulb"></i></span>';
                                break;
                        }
                        return status
                    },
                    export: true
                },
                {
                    display: 'Ubah',
                    name: '',
                    sortAble: false,
                    align: 'center',
                    action: {
                        function: 'editCategory',
                        icon: 'bx bx-edit-alt warning',
                        class: 'warning'
                    }
                },
            ],
            buttonAction: [{
                    display: 'Tambah',
                    icon: 'bx bx-plus',
                    style: "info",
                    action: "addCategory"
                },
                {
                    display: 'Hapus',
                    icon: 'bx bx-trash',
                    style: "danger",
                    action: "remove",
                    url: window.location.origin + "/admin/service/content/deleteCategory"
                },
                {
                    display: 'Aktifkan',
                    icon: 'bx bxs-bulb',
                    style: "success",
                    action: "active",
                    url: window.location.origin + "/admin/service/content/activeCategory"
                },
                {
                    display: 'Non Aktifkan',
                    icon: 'bx bx-bulb',
                    style: "warning",
                    action: "nonactive",
                    url: window.location.origin + "/admin/service/content/nonActiveCategory"
                },
            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Kategori',
            searchItems: [{
                display: 'Judul Kategori',
                name: 'content_category_name',
                type: 'text'
            }, ],
            sortName: "content_category_id",
            sortOrder: "asc",
            tableIsResponsive: true,
            select: true,
            multiSelect: true,
        });

    });

    var action = '';
    var stateContent = {
        baseUrl: window.location.origin,
        updateUrl: '/admin/service/content/editCategory',
        addUrl: '/admin/service/content/addCategory',
        selectedID: '',
        selectedOldImage: ''
    }

    function addCategory() {
        $('#response-message').html('');
        $('#modalkategoriTitle').text('Form Tambah Kategori Artikel')
        stateContent.selectedID = ''
        action = 'add';
        $('#formKonten').trigger('reset');
        $('#modalkategori').modal('show');
    }

    function editCategory(data) {
        $('#response-message').html('');
        $('#modalkategoriTitle').text('Form Edit Kategori Artikel')
        stateContent.selectedID = data.content_category_id
        action = 'update';
        $('#formKonten input[name=category_name]').val(data.content_category_name);
        $('#modalkategori').modal('show');
    }

    $('#submitContent').on('click', (e) => {
        e.preventDefault();
        $('#response-message').html('');
        $('#submitContent').prop('disabled', true)
        $('#submitContent').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')

        let myForm = document.getElementById('formKonten');
        let formData = new FormData(myForm);
        let url = '';
        formData.append('is_one', 0);
        if (action == 'update') {
            url = stateContent.baseUrl + stateContent.updateUrl
            formData.append('id', stateContent.selectedID);
        } else if (action == 'add') {
            url = stateContent.baseUrl + stateContent.addUrl
        }

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#submitContent').prop('disabled', false);
                $('#submitContent').html('Simpan');
                if (response.message == 'OK') {
                    $('#modalkategori').modal('hide');
                    $('#response-message').html(response.data);
                    $('#response-message').addClass('alert alert-success');
                } else {
                    $('#modalkategori').modal('hide');
                    $('#response-error-content').html(response.data.message);
                    $('#response-error-content').addClass('alert alert-danger');
                }
                setTimeout(function() {
                    $('#response-message').html('');
                    $('#response-message').removeClass();
                    $('#response-error-content').html('');
                    $('#response-error-content').removeClass();
                }, 2000);
                $.refreshTable('table-content');
            },
            error: function(err) {
                $('#submitContent').prop('disabled', false)
                $('#submitContent').html('Simpan')
                let response = err.responseJSON
                $('#response-error-content').show()
                if (response.message == "validationError") {
                    let message = '<ul>';
                    for (let key in response.data.validationMessage) {
                        message += `<li>${response.data.validationMessage[key]}</li>`
                    }
                    message += '</ul>'
                    $('#response-error-content').html(`
                        <div class="alert border-danger alert-dismissible mb-2" role="alert">
                        <div class="d-flex align-items-center">
                        <span class="alert-position">
                        ${message}
                        </span>
                        </div>
                        </div>
                        `);

                    setTimeout(function() {
                        $('#response-error-content').hide('blind', {}, 500)
                    }, 5000);
                } else if (response.message == 'Unauthorized' || response.status == 403) {
                    location.reload();
                } else {
                    $('#response-error-content').html(response.message);
                    $('#response-error-content').addClass('alert alert-danger');
                    setTimeout(function() {
                        $('#response-message').html('');
                        $('#response-message').removeClass();
                        $('#response-error-content').html('');
                        $('#response-error-content').removeClass();
                    }, 2000);
                }
            }
        });
    })
</script>