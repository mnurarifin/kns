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
<div class="modal fade" id="modalGaleri" tabindex="-1" role="dialog" aria-labelledby="modalGaleri" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGaleriKategoriTitle">Form Galeri</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form class="form form-horizontal" id="formGaleri">
                <div class="modal-body">
                    <div id="response-error-gallery"></div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Kategori</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="gallery_category_title" placeholder="Judul">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Gambar</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="file" class="form-control" accept="image/*" name="gallery_category_image" placeholder="File Galeri">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Deskripsi</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <textarea class="form-control" name="gallery_category_description" rows="5" cols="35" placeholder="Deskripsi"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tutup</span>
                    </button>
                    <button class="btn btn-primary" id="submitGallery" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalShowGaleri" tabindex="-1" role="dialog" aria-labelledby="modalShowGaleri" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalShowGaleriTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body text-center" id="gallery-body"></div>
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
                    <div class="card-body card-dashboard">
                        <div id="response-message"></div>
                        <div id="table-gallery"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    var galleryCategory;

    $(document).ready(function() {
        $("#table-gallery").dataTableLib({
            url: window.location.origin + '/admin/service/gallery/getGalleryCategory',
            selectID: 'gallery_category_id',
            colModel: [{
                    display: 'Kategori',
                    name: 'gallery_category_title',
                    sortAble: false,
                    align: 'left',
                    width: "180px",
                    export: false
                },
                {
                    display: 'Status',
                    name: 'gallery_category_is_active',
                    sortAble: false,
                    align: 'center',
                    render: (params) => {
                        return params == '1' ? '<span class="badge badge-light-success badge-pill badge-round" title="Ya" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>' : '<span class="badge badge-light-danger badge-pill badge-round" title="Tidak" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>'
                    },
                    export: true
                },
                {
                    display: 'Tanggal Dibuat',
                    name: 'gallery_category_input_datetime',
                    sortAble: false,
                    align: 'center',
                    width: "180px",
                    export: false
                },
                {
                    display: 'Ubah',
                    name: '',
                    sortAble: false,
                    align: 'center',
                    action: {
                        function: 'updateGallery',
                        icon: 'bx bx-edit-alt warning',
                        class: 'warning'
                    }
                },
            ],
            buttonAction: [{
                    display: 'Tambah',
                    icon: 'bx bx-plus',
                    style: "info",
                    action: "addGalleryCategory"
                },
                {
                    display: 'Hapus',
                    icon: 'bx bx-trash',
                    style: "danger",
                    action: "remove",
                    url: window.location.origin + "/admin/service/gallery/deleteGalleryCategory"
                },
                {
                    display: 'Aktifkan',
                    icon: 'bx bxs-bulb',
                    style: "success",
                    action: "active",
                    url: window.location.origin + "/admin/service/gallery/activeGalleryCategory"
                },
                {
                    display: 'Non Aktifkan',
                    icon: 'bx bx-bulb',
                    style: "warning",
                    action: "nonactive",
                    url: window.location.origin + "/admin/service/gallery/nonactiveGalleryCategory"
                },
                // {display: 'Export Excel', icon: 'bx bxs-file',style: 'info', action: 'exportExcel', url: window.location.origin+"/gallery/excel"},
            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Galeri',
            searchItems: [{
                display: 'Judul Kategori Galeri',
                name: 'gallery_category_title',
                type: 'text'
            }, ],
            sortName: "gallery_category_id",
            sortOrder: "asc",
            tableIsResponsive: true,
            select: true,
            multiSelect: true,
        });

    });

    function resetMessage() {
        $('#response-message').html('');
        $('#response-message').removeClass();
        $('#response-error-gallery').html('');
        $('#response-error-gallery').removeClass();
    }

    function addGalleryCategory() {
        resetMessage()
        $('#modalGaleriKategoriTitle').html('Form Tambah Kategori Galeri')
        stateGalleryCategory.selectedID = ''
        stateGalleryCategory.selectedOldFile = ''
        action = 'add';
        $('#formGaleri').trigger('reset');
        $('#modalGaleri').modal('show');
    }

    function updateGallery(data) {
        resetMessage()
        action = 'update'
        stateGalleryCategory.selectedID = data.gallery_category_id
        stateGalleryCategory.selectedOldFile = data.gallery_category_image
        $('#modalGaleriKategoriTitle').html('Form Update Kategori Galeri')
        $('#formGaleri input[name=gallery_category_image]').val('');
        $('#formGaleri input[name=gallery_category_title]').val(data.gallery_category_title);
        $(`#formGaleri textarea[name=gallery_category_description]`).val(data.gallery_category_description);

        $('#modalGaleri').modal('show');
    }

    var action = '';
    var stateGalleryCategory = {
        baseUrl: window.location.origin,
        updateUrl: '/admin/service/gallery/actUpdateGalleryCategory',
        addUrl: '/admin/service/gallery/actAddGalleryCategory',
        selectedID: '',
        selectedOldFile: ''
    }

    function showGallery(params) {
        $('#modalShowGaleri').modal('show');
        $('#modalShowGaleriTitle').html(params.gallery_category_title)
        $('#gallery-body').html('')
        var htmlBody = '';
        var urlAsset = "<?php echo $imagePath; ?>";
        htmlBody = `<img src="${urlAsset}${params.gallery_category_image}" height="400px" width="450px">`
        $('#gallery-body').html(htmlBody)
    }

    $('#submitGallery').on('click', (e) => {
        e.preventDefault();
        $('#response-message').html('');
        $('#formGaleri button[type=submit]').prop('disabled', true)
        $('#formGaleri button[type=submit]').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')

        let myForm = document.getElementById('formGaleri');
        let formData = new FormData(myForm);
        let url = '';
        if (action == 'update') {
            url = stateGalleryCategory.baseUrl + stateGalleryCategory.updateUrl
            formData.append('id', stateGalleryCategory.selectedID);
            formData.append('old_file', stateGalleryCategory.selectedOldFile);
        } else if (action == 'add') {
            url = stateGalleryCategory.baseUrl + stateGalleryCategory.addUrl
        }

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#formGaleri button[type=submit]').prop('disabled', false);
                $('#formGaleri button[type=submit]').html('Simpan');
                if (response.message == 'OK') {
                    $('#modalGaleri').modal('hide');
                    $('#response-message').html(response.data);
                    $('#response-message').addClass('alert alert-success');
                } else {
                    $('#modalGaleri').modal('hide');
                    $('#response-error-gallery').html(response.data.message);
                    $('#response-error-gallery').addClass('alert alert-danger');
                }
                setTimeout(function() {
                    $('#response-message').html('');
                    $('#response-message').removeClass();
                    $('#response-error-gallery').html('');
                    $('#response-error-gallery').removeClass();
                }, 2000);
                $.refreshTable('table-gallery');
            },
            error: function(err) {
                $('#formGaleri button[type=submit]').prop('disabled', false)
                $('#formGaleri button[type=submit]').html('Simpan')
                let response = err.responseJSON
                $('#response-error-gallery').show()
                if (response.message == "validationError") {
                    let message = '<ul>';
                    for (let key in response.data.validationMessage) {
                        message += `<li>${response.data.validationMessage[key]}</li>`
                    }
                    message += '</ul>'
                    $('#response-error-gallery').html(`
                        <div class="alert border-danger alert-dismissible mb-2" role="alert">
                        <div class="d-flex align-items-center">
                        <span class="alert-position">
                        ${message}
                        </span>
                        </div>
                        </div>
                        `);
                    setTimeout(function() {
                        $('#response-error-gallery').hide('blind', {}, 500)
                    }, 5000);
                } else if (response.message == 'Unauthorized' || response.status == 403) {
                    location.reload();
                } else {
                    $('#response-error-gallery').html(response.message);
                    $('#response-error-gallery').addClass('alert alert-danger');
                    setTimeout(function() {
                        resetMessage()
                    }, 2000);
                }
            }
        });
    })
</script>