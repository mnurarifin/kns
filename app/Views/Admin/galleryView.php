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
                <h5 class="modal-title" id="modalGaleriTitle">Form Galeri</h5>
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
                                <label>Judul</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="gallery_title" placeholder="Judul">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Kategori</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="gallery_category_id" id="select-category" class="form-control">

                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Tipe</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="gallery_type" id="select-type" class="form-control">

                                </select>
                            </div>
                        </div>
                        <div class="row" id="video">
                            <div class="col-md-4">
                                <label>Link Video</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input id="link_video" type="text" class="form-control" name="gallery_file" placeholder="Link Video">
                                <small>contoh : https://www.youtube.com/watch?v=example</small>
                            </div>
                        </div>
                        <div class="row" id="image">
                            <div class="col-md-4">
                                <label>File</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="file" class="form-control" accept="image/png, image/jpg" name="gallery_file" placeholder="File Galeri">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Deskripsi</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <textarea class="form-control" name="gallery_description" rows="5" cols="35" placeholder="Deskripsi"></textarea>
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
                    <div id="pageLoader">
                        <div class="text-center text-muted d-flex align-center justify-content-center bg-grey-light p-2">
                            <div class="spinner-border text-info spinner-border-sm" role="status" style="margin-right: 8px;margin-top: 2px;">
                                <span class="sr-only">&nbsp;</span>
                            </div>
                            <span>Sedang memuat informasi, mohon tunggu beberapa saat...</span>
                        </div>
                    </div>

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

    $(function() {
        $("#table-gallery").bind("DOMSubtreeModified", function() {
            if ($("#table-gallery").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        getGalleryCategory()
        galleryType()

        $('#video').hide()
        $('#image').hide()

        $('#select-type').on('change', function() {
            if ($(this).val() == 'image') {
                $('#video').hide()
                $('#image').show()
            } else if ($(this).val() == 'video') {
                $('#video').show()
                $('#image').hide()
            } else {
                $('#video').hide()
                $('#image').hide()
            }
        })

        let galleryOption = [];
        $.ajax({
            url: '/admin/service/gallery/gallery_category_option',
            type: 'GET',
            async: false,
            dataType: 'json',
            success: function(response) {
                galleryOption = response;
            }
        });
        $("#table-gallery").dataTableLib({
            url: window.location.origin + '/admin/service/gallery/getDataGallery/',
            selectID: 'gallery_id',
            colModel: [{
                    display: 'gambar',
                    name: '',
                    sortAble: false,
                    align: 'center',
                    action: {
                        function: 'showGallery',
                        icon: 'bx bx-show',
                        class: 'info',
                        style: 'info'
                    }
                },
                {
                    display: 'Nama',
                    name: 'gallery_title',
                    sortAble: false,
                    align: 'left',
                    render: (params) => {
                        let str = params
                        if (params.length > 45)
                            str = params.slice(0, 45) + '...'
                        return str
                    },
                    export: true
                },
                {
                    display: 'Kategori',
                    name: 'gallery_category_title',
                    sortAble: false,
                    align: 'left',
                    width: "180px",
                    export: false
                },
                {
                    display: 'Tanggal Dibuat',
                    name: 'gallery_input_datetime',
                    sortAble: false,
                    align: 'center',
                    export: false
                },
                {
                    display: 'Status',
                    name: 'gallery_is_active',
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
                    action: "addGallery"
                },
                {
                    display: 'Hapus',
                    icon: 'bx bx-trash',
                    style: "danger",
                    action: "remove",
                    url: window.location.origin + "/admin/service/gallery/deleteGallery"
                },
                {
                    display: 'Aktifkan',
                    icon: 'bx bxs-bulb',
                    style: "success",
                    action: "active",
                    url: window.location.origin + "/admin/service/gallery/activeGallery"
                },
                {
                    display: 'Non Aktifkan',
                    icon: 'bx bx-bulb',
                    style: "warning",
                    action: "nonactive",
                    url: window.location.origin + "/admin/service/gallery/nonactiveGallery"
                },
            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Galeri',
            searchItems: [{
                    display: 'Judul Galeri',
                    name: 'gallery_title',
                    type: 'text'
                },
                {
                    display: 'Kategori Galeri',
                    name: 'gallery_category_id',
                    type: 'select',
                    option: galleryOption
                },
            ],
            sortName: "gallery_id",
            sortOrder: "asc",
            tableIsResponsive: true,
            select: true,
            multiSelect: true,
        });

        $("#modalGaleri").on('hide.bs.modal', function() {
            $('#video').hide()
            $('#image').hide()
        });

    });

    function getGalleryCategory() {
        $.ajax({
            url: "<?php echo site_url('admin/service/gallery/getGalleryCategory') ?>",
            type: 'GET',
            success: function(res) {
                let html = ''
                if (res.status == 200) {
                    html += `<option value="" label="Pilih Kategori"></option>`
                    galleryCategory = res.data.results.map(item => {
                        return {
                            value: item.gallery_category_id,
                            title: item.gallery_category_title
                        }
                    })
                    res.data.results.forEach((item) => {
                        if (item.gallery_category_is_active == 1) {
                            html += `<option value="${item.gallery_category_id}" label="${item.gallery_category_title}"></option>`
                        }
                    });
                    $('#select-category').html(html);
                }
            }
        });
    }

    function galleryType() {
        let galleryCategory = [{
            "value": "image",
            "text": "Gambar"
        }, {
            "value": "video",
            "text": "Video"
        }]
        var html = '';
        html += `<option value="" label="Pilih Tipe"></option>`
        galleryCategory.forEach((item) => {
            html += `<option value="${item.value}" label="${item.text}"></option>`
        });
        $('#select-type').html(html);
    }

    function resetMessage() {
        $('#response-message').html('');
        $('#response-message').removeClass();
        $('#response-error-gallery').html('');
        $('#response-error-gallery').removeClass();
    }

    function addGallery() {
        resetMessage()
        $('#modalGaleriTitle').html('Form Tambah Galeri')
        stateGallery.selectedID = ''
        stateGallery.selectedOldFile = ''
        action = 'add';
        $('#formGaleri').trigger('reset');
        $(`#formGaleri select[name=gallery_category_id]`).val('');
        $('#modalGaleri').modal('show');
    }

    function updateGallery(data) {
        resetMessage()
        action = 'update'
        stateGallery.selectedID = data.gallery_id
        stateGallery.selectedOldFile = data.gallery_file
        $('#modalGaleriTitle').html('Form Update Galeri')
        $('#formGaleri input[name=gallery_title]').val(data.gallery_title);
        $(`#formGaleri select[name=gallery_category_id]`).val(data.gallery_category_id);
        $(`#formGaleri select[name=gallery_type]`).val(data.gallery_type);
        $(`#formGaleri textarea[name=gallery_description]`).val(data.gallery_description);

        if (data.gallery_type == 'image') {
            $('#video').hide()
            $('#image').show()
            $('#formGaleri input[name=gallery_file]').val('');
        }
        if (data.gallery_type == 'video') {
            $('#video').show()
            $('#image').hide()
            $('#link_video').val('https://www.youtube.com/watch?v=' + data.gallery_file);
        }

        var selectedCountry = $('#formGaleri select[name=gallery_category_id]').children("option:selected").val();
        $('#modalGaleri').modal('show');
    }

    var action = '';
    var stateGallery = {
        baseUrl: window.location.origin,
        updateUrl: '/admin/service/gallery/actUpdateGallery',
        addUrl: '/admin/service/gallery/actAddGallery',
        selectedID: '',
        selectedOldFile: ''
    }

    function showGallery(params) {
        $('#modalShowGaleri').modal('show');
        $('#modalShowGaleriTitle').html(params.gallery_title)
        $('#gallery-body').html('')
        var htmlBody = '';
        var urlAsset = "<?php echo $imagePath; ?>";
        if (params.gallery_type == 'video') {
            htmlBody = `
            <iframe width="400" height="450" controls src="https://www.youtube.com/embed/${params.gallery_file}">
            </iframe>
            `
        } else {
            htmlBody = `<img src="${urlAsset}${params.gallery_file}" height="400px" width="450px">`
        }
        $('#gallery-body').html(htmlBody)
    }

    $('#submitGallery').on('click', (e) => {
        e.preventDefault();
        $('#response-message').html('');
        $('#submitGallery').prop('disabled', true)
        $('#submitGallery').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')

        let myForm = document.getElementById('formGaleri');
        let formData = new FormData(myForm);
        let url = '';
        if (action == 'update') {
            url = stateGallery.baseUrl + stateGallery.updateUrl
            formData.append('id', stateGallery.selectedID);
            formData.append('old_file', stateGallery.selectedOldFile);
        } else if (action == 'add') {
            url = stateGallery.baseUrl + stateGallery.addUrl
        }

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#submitGallery').prop('disabled', false);
                $('#submitGallery').html('Simpan');
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
                $('#submitGallery').prop('disabled', false)
                $('#submitGallery').html('Simpan')
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