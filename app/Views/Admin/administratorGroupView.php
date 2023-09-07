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
                        <div id="table-group"></div>
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
                <h5 class="modal-title" id="modalAddUpdateTitle">Form Add Grup</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form form-horizontal" id="formAddUpdate">
                    <div id="response-message"></div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="control-label">Tipe Grup</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <div class="input-group" id="defaultrange">
                                    <select name="type" id="type" class="form-control" onchange="typeOption()">
                                        <option value="administrator">Administrator</option>
                                        <?php if($administrator_group_type == 'superuser') {?>
                                        <option value="superuser">Superuser</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nama Grup</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="title" size="40" class="form-control" />
                            </div>
                        </div>
                        <div id="privilege-menu">
                            <div class="mb-1">
                                <label>Hak Akses</label>
                            </div>
                            <div class="ml-5">
                                <div class="form-check">
                                    <div class="d-block" style="margin-bottom: 7px;">
                                        <label class="d-flext align-center">
                                            <input id="checkAll" type="checkbox" class="mr-1">
                                            <strong>Pilih Semua</strong>
                                        </label>
                                    </div>
                                    <div id="menu"></div>
                                </div>
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
                <button class="btn btn-primary" id="submit" type="submit">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Detail Grup</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body p-2">
                <form class="form form-horizontal">
                    <div class="form-body">
                        <div class="card border">
                            <div class="card-body p-2">
                                <div class="row">
                                    <div class="col-md-6 d-flex flex-row align-items-center">
                                        <i class='bx bxs-user bx-md bx-border-circle border-primary text-primary'></i>
                                        <div class="d-flex flex-column ml-1">
                                            <label class="mb-0">Nama Grup</label>
                                            <p id="menu-name" class="text-primary mb-0"></p>
                                        </div>
                                    </div>

                                    <div class="col-md-6 d-flex flex-row align-items-center">
                                        <i class='bx bxs-user bx-md bx-border-circle border-primary text-primary'></i>
                                        <div class="d-flex flex-column ml-1">
                                            <label class="mb-0">Tipe Grup</label>
                                            <p id="menu-type" class="text-primary mb-0"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6>Hak Akses</h6>
                        <div id="menu-access" class="d-block py-1"></div>

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
    let stateMenu = {
        formAction: 'add',
        baseUrl: window.location.origin,
        addUrl: '/admin/service/administrator/addGroup',
        updateUrl: '/admin/service/administrator/updateGroup',
        selectedID: ''
    }

    $(function() {
        $("#table-group").bind("DOMSubtreeModified", function() {
            if ($("#table-group").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {

        typeOption()
        getSuperuserMenu()

        $("#table-group").dataTableLib({
            url: window.location.origin + '/admin/service/administrator/getDataGroup',
            selectID: 'administrator_group_id',
            colModel: [{
                    display: 'Nama Grup',
                    name: 'administrator_group_title',
                    sortAble: false
                },
                {
                    display: 'Tipe Grup',
                    name: 'administrator_group_type',
                    sortAble: false
                },
                {
                    display: 'Status',
                    name: 'administrator_group_is_active',
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
                        function: 'detailGroup',
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
                        function: 'updateGroup',
                        icon: 'bx bx-edit-alt warning',
                        class: 'warning'
                    }
                },
            ],
            sortName: "administrator_menu_title",
            sortOrder: "asc",
            tableIsResponsive: true,
            search: true,
            searchTitle: 'Pencarian Data Administrator Grup',
            searchItems: [{
                    display: 'Nama Grup',
                    name: 'administrator_group_title',
                    type: 'text'
                },
                {
                    display: 'Tipe Grup',
                    name: 'administrator_group_type',
                    type: 'select',
                    option: [
                        {
                            title: 'Administrator',
                            value: 'administrator'
                        },
                    ]
                },
                {
                    display: 'Status',
                    name: 'administrator_group_is_active',
                    type: 'select',
                    option: [{
                            title: 'Aktif',
                            value: '1'
                        },
                        {
                            title: 'Tidak Aktif',
                            value: '0'
                        }
                    ]
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
                    url: window.location.origin + "/admin/service/administrator/removeGroup",
                    message: 'Oke'
                },
                {
                    display: 'Aktifkan',
                    icon: 'bx bxs-bulb',
                    style: "success",
                    action: "active",
                    url: window.location.origin + "/admin/service/administrator/activeGroup"
                },
                {
                    display: 'Non Aktifkan',
                    icon: 'bx bx-bulb',
                    style: "warning",
                    action: "nonactive",
                    url: window.location.origin + "/admin/service/administrator/NonActiveGroup"
                },
            ]
        });

    });

    function getSuperuserMenu() {

        $.ajax({
            url: "<?php echo site_url('admin/service/administrator/getSuperuserMenu') ?>",
            type: 'GET',
            "content_type": 'application/json',
            success: function(res) {
                let html = ``
                let arrData = [];

                if (res.status == 200) {
                    let arrData = res.data.result

                    arrData.forEach((item, index) => {
                        html += `   <div class="d-block ml-1" style="margin-bottom: 7px;">
                                    <label>
                                        <input class="checkParent arrParent${index}" name="item" data-id="${index}" value="${item.administratorMenuId}" type="checkbox"> <strong class="ml-1">${item.administratorMenuTitle}</strong>
                                    </label>
                                </div>
                                `
                        item.SubMenu.forEach((subitem, subindex) => {

                            html += `<div class="d-block ml-3" style="margin-bottom: 5px;">
                                                 <label>
                                                    <input class="checkChild arr${index}" name="item" data-id="${index}" value="${subitem.administrator_menu_id}" type="checkbox"><span class="ml-1">${subitem.administrator_menu_title}</span>
                                                 </label>
                                          </div>`
                        })
                    });
                    $('#menu').html(html);
                    EventCheckbox();
                }
            }
        });
    }

    function typeOption() {
        let tipe = $('#type').val();

        if (tipe == 'superuser') {
            $("#privilege-menu").hide();
        } else {
            $("#privilege-menu").show();
        }
    }

    function EventCheckbox() {
        $('#checkAll').on('change', function() {
            if ($(this).is(':checked')) {
                $('.checkParent').prop('checked', true);
                $('.checkChild').prop('checked', true);
            } else {
                $('.checkParent').prop('checked', false);
                $('.checkChild').prop('checked', false);
            }
        })

        $('.checkParent').on('change', function() {
            let arrId = $(this).attr('data-id');
            if ($(this).is(':checked')) {
                $('.arr' + arrId).prop('checked', true);
            } else {
                $('.arr' + arrId).prop('checked', false);
            }
        })

        $('.checkChild').on('change', function() {
            let arrId = $(this).attr('data-id');
            if ($(this).is(':checked')) {
                $('.arrParent' + arrId).prop('checked', true);
            } else {
                var len = $('input.arr' + arrId + ':checked').length;
                if (len == 0) {
                    $('.arrParent' + arrId).prop('checked', false);
                }
            }
        })
    }

    function addData() {
        $('#response-message').html('');
        $('#modalAddUpdateTitle').text('Form Tambah Grup');
        stateMenu.selectedID = '';
        stateMenu.formAction = 'add';
        $('#formAddUpdate').trigger('reset');
        $('#modalAddUpdate').modal('show');
        typeOption();
    }

    function updateGroup(group) {
        $('#response-message').html('');
        $('#modalAddUpdateTitle').text('Form Ubah Grup');
        stateMenu.selectedID = group.administrator_group_id;
        stateMenu.formAction = 'update';
        $.ajax({
            url: "<?php echo site_url('admin/service/administrator/getDataEditGroup') ?>",
            type: 'GET',
            data: {
                administrator_group_id: stateMenu.selectedID
            },
            "content_type": 'application/json',
            beforeSend: function() {
                $('.checkChild').prop('checked', false);
                $('.checkParent').prop('checked', false);
                $('.form-check').hide();

                let htmlLoading = `
                        <div id="loadCheck" class="text-center text-muted d-flex align-center justify-content-center my-2 p-1" style="background: #f1f1f1;">
                            <div class="spinner-border text-info spinner-border-sm" role="status" style="margin-right: 7px;margin-top: 1px;>
                                <span class="sr-only">&nbsp;</span>
                            </div>
                            <small>Sedang memuat data, mohon tunggu beberapa saat...</small>
                        </div>
                    `;
                $('#privilege-menu').append(htmlLoading);
            },
            success: function(res) {
                res.data.forEach((item, index) => {
                    $('.checkChild[value="' + item.administrator_privilege_administrator_menu_id + '"]').prop('checked', true);
                    $('.checkParent[value="' + item.administrator_privilege_administrator_menu_id + '"]').prop('checked', true);
                });

                setTimeout(function() {
                    $('#loadCheck').remove();
                    $('.form-check').show('slow');
                }, 300);
            }
        });
        $('#formAddUpdate select').val(group.administrator_group_type);
        $('#formAddUpdate input[name=title]').val(group.administrator_group_title);
        $('#modalAddUpdate').modal('show');
        typeOption();
    }

    function detailGroup(group) {
        $('#menu-name').text(group.administrator_group_title);
        $('#menu-type').text(group.administrator_group_type);

        $.ajax({
            url: window.location.origin + '/admin/service/administrator/getDetailMenuAdministratorGroup',
            method: 'POST',
            data: {
                administratorGroupId: group.administrator_group_id
            },
            success: function(administrator) {
                let html = ''
                if (group.administrator_group_type === 'superuser') {
                    html += `
                            <div class="card border mb-1">
                                <div class="card-body p-2">
                                    <div class="row align-items-start">
                                        <div class="col-md-12 d-flex flex-row align-items-center">
                                            <i class='bx bx-lock-open bx-border-circle text-success border-success' style="font-size: 24px;"></i>
                                            <div class="d-flex flex-column ml-1">
                                                <label class="text-success mb-0">Semua Akses</label>
                                                <p id="menu-name" class="mb-0"></p>
                                            </div>
                                        </div>
                                    </div>
                                <div>
                            </div>
                    `
                } else {
                    administrator.data.result.forEach((item, index) => {

                        html += `
                            <div class="card border mb-1">
                                <div class="card-body p-2">
                                    <div class="row align-items-start">
                                        <div class="col-md-4 d-flex flex-row align-items-center">
                                            <i class='bx bx-lock-open bx-border-circle text-dark' style="font-size: 24px;"></i>
                                            <div class="d-flex flex-column ml-1">
                                                <label class="text-success mb-0">${item.administratorMenuTitle}</label>
                                                <p id="menu-name" class="mb-0"></p>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">`

                        item.SubMenu.forEach((subitem, subindex) => {

                            html += `<div class="d-block ml-2" style="margin-bottom: 5px;">
                                                        <label class="d-flex align-items-center">
                                                            <i class='bx bx-check-circle'></i> 
                                                            <span class="ml-1">${subitem.administrator_menu_title}</span>
                                                        </label>
                                                    </div>`
                        })

                        html += `</div>
                                    </div>
                                </div>
                            </div>
                        `;
                    })
                }

                $('#menu-access').html(html)
                $('#detailGroup').modal('show');
            }
        })
    }

    $('#submit').on('click', (e) => {
        e.preventDefault();
        // $('#modalAddUpdate').modal('hide');
        $('#response-message').html('');
        $('#submit').prop('disabled', true)
        $('#submit').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')

        let tmpFormData = $("#formAddUpdate").serializeArray();
        let url = stateMenu.baseUrl + stateMenu.addUrl;
        let formData = {};
        let dataMenu = [];
        let inputName = '';

        formData['id'] = stateMenu.selectedID;
        let index = 0;
        $.each(tmpFormData, function() {
            if (this.name == 'type' || this.name == 'title') {
                formData[this.name] = this.value;
            } else {
                inputName = this.name;
                dataMenu[index++] = this.value;
            }

        });
        formData[inputName] = dataMenu;


        if (stateMenu.formAction == 'update') {
            url = stateMenu.baseUrl + stateMenu.updateUrl;
        }
        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            success: function(response) {
                $('#submit').prop('disabled', false)
                $('#submit').html('Simpan')
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
                }, 5000);
                $.refreshTable('table-group');

            },
            error: function(err) {
                $('#submit').prop('disabled', false)
                $('#submit').html('Simpan')
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
        $.refreshTable('table-group');
    });
</script>