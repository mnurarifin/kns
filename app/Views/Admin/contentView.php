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

<div class="modal fade" id="modalKonten" tabindex="-1" role="dialog" data-backdrop="false" aria-labelledby="modalKonten" aria-hidden="true">
    <div class="modal-dialog modal-full modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKontenTitle">Form Artikel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form form-horizontal" id="formKonten">
                    <div id="response-error-content"></div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Judul Artikel</label>
                            </div>
                            <div class="col-md-9 form-group">
                                <input type="text" class="form-control" name="content_title" placeholder="Judul Artikel">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Jadikan Menu</label>
                            </div>
                            <div class="col-md-9 form-group">
                                <input type="radio" id="true" name="is_menu" value="true">
                                <label for="true">Ya</label>
                                <input type="radio" id="false" name="is_menu" value="false">
                                <label for="false">Tidak</label>
                            </div>
                        </div>
                        <div class="show-menu">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Tipe Menu</label>
                                </div>
                                <div class="col-md-9 form-group">
                                    <select name="content_menu_type" id="select-menu-type" class="form-control">
                                        <option value="" label="Pilih Tipe Menu"></option>
                                        <option value="parent" label="Parent"></option>
                                        <option value="sub-parent" label="Sub Parent"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row sub-parent-menu">
                                <div class="col-md-3">
                                    <label>Pilih Menu</label>
                                </div>
                                <div class="col-md-9 form-group">
                                    <select name="content_menu_parent" id="select-menu-parent" class="form-control">
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Menu Link</label>
                                </div>
                                <div class="col-md-9 form-group">
                                    <input type="text" class="form-control" name="content_menu_link" placeholder="Link Artikel">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Kategori Artikel</label>
                            </div>
                            <div class="col-md-9 form-group">
                                <select name="content_category_id" id="select-category" class="form-control">

                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Gambar Artikel</label>
                            </div>
                            <div class="col-md-9 form-group">
                                <input type="file" class="form-control" accept="image/*" name="content_image" placeholder="Gambar Artikel">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Isi Artikel</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <textarea id="editor"></textarea>
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
                        <div id="response-message"></div>
                        <div id="table-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo base_url(); ?>/app-assets/vendors/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    $(function() {
        $("#table-content").bind("DOMSubtreeModified", function() {
            if ($("#table-content").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        let contentOption = [];
        $.ajax({
            url: '/admin/service/content/content_category_option',
            type: 'GET',
            async: false,
            dataType: 'json',
            success: function(response) {
                contentOption = response;
            }
        });
        $("#table-content").dataTableLib({
            url: window.location.origin + '/admin/service/content/getDataContent/',
            selectID: 'content_id',
            colModel: [{
                    display: 'Judul',
                    name: 'content_title',
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
                    name: 'content_category_name',
                    sortAble: false,
                    align: 'left',
                    export: false
                },
                {
                    display: 'Tanggal Dibuat',
                    name: 'content_input_datetime',
                    sortAble: false,
                    align: 'center',
                    export: false
                },
                {
                    display: 'Status',
                    name: 'content_is_active',
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
                        function: 'updateContent',
                        icon: 'bx bx-edit-alt warning',
                        class: 'warning'
                    }
                },
            ],
            buttonAction: [{
                    display: 'Tambah',
                    icon: 'bx bx-plus',
                    style: "info",
                    action: "addContent"
                },
                {
                    display: 'Hapus',
                    icon: 'bx bx-trash',
                    style: "danger",
                    action: "remove",
                    url: window.location.origin + "/admin/service/content/deleteContent"
                },
                {
                    display: 'Aktifkan',
                    icon: 'bx bxs-bulb',
                    style: "success",
                    action: "active",
                    url: window.location.origin + "/admin/service/content/activeContent"
                },
                {
                    display: 'Non Aktifkan',
                    icon: 'bx bx-bulb',
                    style: "warning",
                    action: "nonactive",
                    url: window.location.origin + "/admin/service/content/nonactiveContent"
                },
            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Kontent',
            searchItems: [{
                    display: 'Judul Artikel',
                    name: 'content_title',
                    type: 'text'
                },
                {
                    display: 'Kategori Artikel',
                    name: 'content_category_id',
                    type: 'select',
                    option: contentOption
                },
            ],
            sortName: "content_id",
            sortOrder: "asc",
            tableIsResponsive: true,
            select: true,
            multiSelect: true,
        });

    });

    function addContent() {
        window.location.href = window.location.origin + '/admin/content/add'
    }

    function updateContent(data) {
        window.location.href = window.location.origin + '/admin/content/edit/' + data.content_id
    }
</script>