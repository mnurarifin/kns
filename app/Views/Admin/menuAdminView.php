<style>
    .spinnerLoad {
        display: none;
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 99999;
        background: rgba(95, 82, 82, 0.58);
    }

    .center {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .alert-position {
        transform: translateY(5px);
    }
</style>

<div class="spinnerLoad">
    <div class="center">
        <div class="spinner-border spinner-border-lg text-success" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>
<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title titleHead"><?= isset($title) ? $title : '' ?></h4>
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
                        <div id="table-menu"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="modalAddUpdate" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdateTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddUpdateTitle">Vertically Centered</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form class="form form-horizontal" id="formAddUpdate">
                <div class="modal-body">
                    <div id="response-message"></div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Judul</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="titleMenu" placeholder="Nama Menu">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Link</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="linkMenu" placeholder="Link">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nama Ikon</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="iconMenu" placeholder="Nama Icon Menu">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="detailShowMenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Detail Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form form-horizontal">
                    <div class="form-body">
                        <div class="d-flex flex-row align-items-center">
                            <div id="menu-status-icon" class="mr-2"></div>
                            <div class="d-flex flex-column align-items-center w-100">
                                <label class="d-block w-100 text-left text-muted">Nama Menu</label>
                                <p id="menu-name" class="d-block text-dark font-medium-4 font-weight-normal w-100 text-left my-25"></p>
                                <p id="menu-status" class="d-block w-100 text-left mb-25"></p>
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
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    let urlParentId = new URLSearchParams(window.location.search);
    let parentId = urlParentId.get('menuPar');
    parentId = parentId == null ? 0 : parentId;
    // let parentTitle = urlParentId.get('menuPar');
    let dataTitle = urlParentId.get('menutitle');

    let stateMenu = {
        formAction: 'add',
        baseUrl: window.location.origin,
        addUrl: '/admin/service/menu/addmenu',
        updateUrl: '/admin/service/menu/updateMenu',
        selectedParID: parentId,
        selectedID: ''
    }

    $(function() {
        $("#table-menu").bind("DOMSubtreeModified", function() {
            if ($("#table-menu").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        if (parentId == 0) {
            $('.titleHead').text('Menu Admin')
            $("#table-menu").dataTableLib({
                url: window.location.origin + '/admin/service/menu/getDataMenu',
                selectID: 'administrator_menu_id',
                colModel: [{
                        display: 'Urutan Menu',
                        name: 'administrator_menu_id',
                        sortAble: false,
                        align: 'center',
                        render: (params) => {
                            return `
                                <button type="button" class="btn btn-icon btn-outline-success btn-sm mb-0" onclick="moveUp(${params})" style="padding-top:5px;padding-bottom:5px;"><i class="ficon bx bx-up-arrow-alt"></i></button>
                                <button type="button" class="btn btn-icon btn-outline-warning btn-sm mb-0" onclick="moveDown(${params})" style="padding-top:5px;padding-bottom:5px;"><i class="ficon bx bx-down-arrow-alt"></i></button>
                            `;
                        }
                    },
                    {
                        display: 'Nama menu',
                        name: 'administrator_menu_title',
                        sortAble: false
                    },
                    {
                        display: 'Sub Menu',
                        name: 'administrator_menu_id',
                        sortAble: false,
                        align: 'center',
                        action: {
                            function: 'subMenu',
                            icon: 'bx bx-sitemap',
                            class: 'btn btn-icon btn-outline-light btn-sm mb-0'
                        }
                    },
                    // {display: 'Sub Menu', name: 'administrator_menu_id', sortAble: false, align: 'center',  render: (params) => { return '<a href="'+window.location.origin+'/administrator/menu/show?menuPar='+params+'"><i class="bx bx-show"></i></a>'}},
                    {
                        display: 'link',
                        name: 'administrator_menu_link',
                        sortAble: false
                    },
                    {
                        display: 'Status',
                        name: 'administrator_menu_is_active',
                        sortAble: false,
                        align: 'center',
                        render: (params) => {
                            return params == '1' ? '<span class="badge badge-light-success badge-pill badge-round" title="Aktif" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>' : '<span class="badge badge-light-danger badge-pill badge-round" title="Non Aktif" data-toggle="tooltip"><i class="bx bx-bulb"></i></span>'
                        }
                    },
                    {
                        display: 'Detail',
                        name: '',
                        sortAble: false,
                        align: 'center',
                        action: {
                            function: 'detailMenu',
                            icon: 'bx bx-book info',
                            class: 'info'
                        }
                    },
                    {
                        display: 'Ubah',
                        name: '',
                        sortAble: false,
                        align: 'center',
                        action: {
                            function: 'updateMenu',
                            icon: 'bx bx-edit-alt warning',
                            class: 'warning'
                        }
                    },
                ],
                sortName: "administrator_menu_order_by",
                sortOrder: "asc",
                tableIsResponsive: true,
                search: true,
                searchTitle: 'Pencarian Menu Utama',
                searchItems: [{
                        display: 'Nama Menu',
                        name: 'administrator_menu_title',
                        type: 'text'
                    },
                    {
                        display: 'Status',
                        name: 'administrator_menu_is_active',
                        type: 'select',
                        option: [{
                            title: 'Aktif',
                            value: '1'
                        }, {
                            title: 'Tidak Aktif',
                            value: '0'
                        }]
                    },
                ],
                select: true,
                multiSelect: true,
                buttonAction: [{
                        display: 'Tambah',
                        icon: 'bx bx-plus',
                        style: "info",
                        action: "addData"
                    },
                    {
                        display: 'Hapus',
                        icon: 'bx bx-trash',
                        style: "danger",
                        action: "remove",
                        url: window.location.origin + "/admin/service/menu/removeMenu",
                        message: 'Oke'
                    },
                    {
                        display: 'Aktifkan',
                        icon: 'bx bxs-bulb',
                        style: "success",
                        action: "active",
                        url: window.location.origin + "/admin/service/menu/activeMenu"
                    },
                    {
                        display: 'Non Aktifkan',
                        icon: 'bx bx-bulb',
                        style: "warning",
                        action: "nonactive",
                        url: window.location.origin + "/admin/service/menu/nonActiveMenu"
                    },
                ]
            });
        } else {
            $('.titleHead').text('Data Submenu' + ' ' + dataTitle)

            $("#table-menu").dataTableLib({
                url: window.location.origin + '/admin/service/menu/getDataMenu/' + parentId,
                selectID: 'administrator_menu_id',
                colModel: [{
                        display: 'Urutan Menu',
                        name: 'administrator_menu_id',
                        sortAble: false,
                        align: 'center',
                        render: (params) => {
                            return `
                                <button type="button" class="btn btn-icon btn-outline-success btn-sm mb-0" onclick="moveUp(${params})" style="padding-top:5px;padding-bottom:5px;"><i class="ficon bx bx-up-arrow-alt"></i></button>
                                <button type="button" class="btn btn-icon btn-outline-warning btn-sm mb-0" onclick="moveDown(${params})" style="padding-top:5px;padding-bottom:5px;"><i class="ficon bx bx-down-arrow-alt"></i></button>
                            `;
                        }
                    },
                    {
                        display: 'Nama menu',
                        name: 'administrator_menu_title',
                        sortAble: false
                    },
                    {
                        display: 'link',
                        name: 'administrator_menu_link',
                        sortAble: false
                    },
                    // {display: 'ikon', name: 'administrator_menu_class', sortAble: false},
                    {
                        display: 'Status',
                        name: 'administrator_menu_is_active',
                        sortAble: false,
                        align: 'center',
                        render: (params) => {
                            return params == '1' ? '<span class="badge badge-light-success badge-pill badge-round" title="Aktif" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>' : '<span class="badge badge-light-danger badge-pill badge-round" title="Non Aktif" data-toggle="tooltip"><i class="bx bx-bulb"></i></span>'
                        }
                    },
                    {
                        display: 'Detail',
                        name: '',
                        sortAble: false,
                        align: 'center',
                        action: {
                            function: 'detailMenu',
                            icon: 'bx bx-book info',
                            class: 'info'
                        }
                    },
                    {
                        display: 'Ubah',
                        name: '',
                        sortAble: false,
                        align: 'center',
                        action: {
                            function: 'updateMenu',
                            icon: 'bx bx-edit-alt warning',
                            class: 'warning'
                        }
                    },
                ],
                sortName: "administrator_menu_order_by",
                sortOrder: "asc",
                tableIsResponsive: true,
                search: true,
                searchTitle: 'Pencarian SubMenu ',
                searchItems: [{
                        display: 'Nama Menu',
                        name: 'administrator_menu_title',
                        type: 'text'
                    },
                    {
                        display: 'Status',
                        name: 'administrator_menu_is_active',
                        type: 'select',
                        option: [{
                            title: 'Aktif',
                            value: '1'
                        }, {
                            title: 'Tidak Aktif',
                            value: '0'
                        }]
                    },
                ],
                select: true,
                multiSelect: true,
                buttonAction: [{
                        display: 'Kembali',
                        icon: 'bx bx-undo',
                        style: "primary",
                        action: "backMenu"
                    },
                    {
                        display: 'Tambah',
                        icon: 'bx bx-plus',
                        style: "info",
                        action: "addData"
                    },
                    {
                        display: 'Hapus',
                        icon: 'bx bx-trash',
                        style: "danger",
                        action: "remove",
                        url: window.location.origin + "/admin/service/menu/removeMenu",
                        message: 'Oke'
                    },
                    {
                        display: 'Aktifkan',
                        icon: 'bx bxs-bulb',
                        style: "success",
                        action: "active",
                        url: window.location.origin + "/admin/service/menu/activeMenu"
                    },
                    {
                        display: 'Non Aktifkan',
                        icon: 'bx bx-bulb',
                        style: "warning",
                        action: "nonactive",
                        url: window.location.origin + "/admin/service/menu/nonActiveMenu"
                    },
                ]
            });
        }
    });

    function subMenu(submenu) {
        window.location.href = '/admin/menu/show?menuPar=' + submenu.administrator_menu_id + '&menutitle=' + submenu.administrator_menu_title
    }

    function backMenu() {
        window.location.href = '/admin/menu/show'
    }

    function addData() {
        $('#response-message').html('');
        if (parentId == '') {
            $('#modalAddUpdateTitle').text('Form Tambah Menu');
        } else {
            $('#modalAddUpdateTitle').text('Form Tambah Sub Menu');
        }

        stateMenu.selectedID = parentId;
        stateMenu.formAction = 'add';
        $('#formAddUpdate').trigger('reset');
        $('#modalAddUpdate').modal('show');
    }

    function updateMenu(menu) {
        $('#response-message').html('');
        if (parentId == '') {
            $('#modalAddUpdateTitle').text('Form Ubah Menu');
        } else {
            $('#modalAddUpdateTitle').text('Form Ubah Sub Menu');
        }
        stateMenu.selectedID = menu.administrator_menu_id;
        stateMenu.selectedParID = parentId;
        stateMenu.formAction = 'update';
        $('#formAddUpdate input[name=titleMenu]').val(menu.administrator_menu_title);
        $('#formAddUpdate input[name=linkMenu]').val(menu.administrator_menu_link);
        $('#formAddUpdate input[name=iconMenu]').val(menu.administrator_menu_icon);
        $('#modalAddUpdate').modal('show');
    }

    function detailMenu(menu) {
        $('#menu-name').text(menu.administrator_menu_title);
        // $('#menu-status').html(menu.administrator_menu_is_active == '1' ? 'Aktif' : 'Tidak Aktif');
        if (menu.administrator_menu_is_active == '1') {
            $('#menu-status-icon').html('<i class="bx bxs-bulb font-medium-7 bx-border-circle border-success text-success" style="font-size: 48px;"></i>')
            $('#menu-status').html('<span class="text-success">Aktif</span>');
        } else {
            $('#menu-status-icon').html('<i class="bx bx-bulb font-medium-7 bx-border-circle border-danger text-danger" style="font-size: 48px;"></i>')
            $('#menu-status').html('<span class="text-danger">Tidak Aktif</span>');
        }
        $('#detailShowMenu').modal('show');
    }

    function moveUp(e) {
        sortOrderMenu(e, 'up');
    }

    function moveDown(e) {
        sortOrderMenu(e, 'down');
    }

    function sortOrderMenu(id, op) {
        $('.spinnerLoad').fadeIn('fast');
        $.ajax({
            url: window.location.origin + '/admin/service/menu/sortOrder',
            method: "POST",
            data: {
                idMenu: id,
                orderMenu: op
            },
            success: function(response) {
                $.refreshTable('table-menu');
                $('.spinnerLoad').fadeOut('fast');
            },
            error: function(err) {
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

                $('.spinnerLoad').fadeOut('fast');
            }
        });
    }

    $('#formAddUpdate').on('submit', (e) => {
        e.preventDefault();
        // $('#modalAddUpdate').modal('hide');
        $('#response-message').html('');
        $('#formAddUpdate button[type=submit]').prop('disabled', true)
        $('#formAddUpdate button[type=submit]').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')

        let formData = new FormData(e.target);
        let url = stateMenu.baseUrl + stateMenu.addUrl;
        if (stateMenu.formAction == 'update') {
            formData.append('id', stateMenu.selectedID);
            formData.append('parId', stateMenu.selectedParID);
            url = stateMenu.baseUrl + stateMenu.updateUrl;
        } else if (stateMenu.formAction == 'add') {
            formData.append('parId', stateMenu.selectedID);
        }

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#formAddUpdate button[type=submit]').prop('disabled', false)
                $('#formAddUpdate button[type=submit]').html('Simpan')
                $('#formAddUpdate button[type=submit]').prop('disabled', false)
                $('#formAddUpdate button[type=submit]').html('Simpan')
                if (response.status == 200) {
                    $('#modalAddUpdate').modal('hide');
                    $('#response-messages').html(response.data.message);
                    $('#response-messages').addClass('alert alert-success');
                } else {
                    $('#modalAddUpdate').modal('hide');
                    $('#response-messages').html(response.data.message);
                    $('#response-messages').addClass('alert alert-danger');
                }
                setTimeout(function() {
                    $('#response-messages').html('');
                    $('#response-messages').removeClass();
                }, 2000);
                $.refreshTable('table-menu');
            },
            error: function(err) {
                $('#formAddUpdate button[type=submit]').prop('disabled', false)
                $('#formAddUpdate button[type=submit]').html('Simpan')
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
        // $.refreshTable('table-menu');
    })
</script>