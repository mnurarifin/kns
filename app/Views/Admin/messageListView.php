<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/app-assets/vendors/css/editors/quill/quill.snow.css">
<script src="<?php echo base_url(); ?>/app-assets/vendors/js/editors/quill/quill.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/app-assets/css/pages/app-email.css">
<script src="<?php echo base_url(); ?>/app-assets/js/scripts/pages/app-email-custom.js"></script>
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

    html body .content.app-content .content-area-wrapper {
        height: auto !important;
        min-height: calc(100% - 5rem);
    }

    #table-emailList>.row:first-child {
        border-bottom: 1px solid #dfe3e7;
    }

    #table-emailList .table-responsive {
        margin-top: 1rem !important;
    }

    .email-application .content-area-wrapper .sidebar .email-app-sidebar .email-app-menu {
        background-color: rgba(242, 244, 244, .7);
    }

    .ql-container.ql-snow,
    .ql-toolbar.ql-snow {
        border: 0;
    }

    .ql-container.ql-snow {
        min-height: 100px;
    }

    .email-app-details .border-primary {
        border: 2px solid #2c6de9 !important;
    }

    .email-app-details .collapse.show {
        background-color: rgba(242, 244, 244, .7);
    }

    .go-back {
        cursor: pointer;
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
                        <div class="email-application" style="visibility: hidden;">
                            <div class="content-area-wrapper mt-2 mx-0">
                                <!-- inbox category -->
                                <div class="sidebar-left">
                                    <div class="sidebar">
                                        <div class="sidebar-content email-app-sidebar d-flex">
                                            <!-- sidebar close icon -->
                                            <div class="email-app-menu w-100">
                                                <div class="form-group form-group-compose">
                                                    <!-- compose button  -->
                                                    <button type="button" class="btn btn-primary btn-block my-2 compose-btn" onclick="addData()">
                                                        <i class="bx bx-plus"></i>
                                                        Buat Pesan
                                                    </button>
                                                </div>
                                                <div class="sidebar-menu-list">
                                                    <!-- sidebar menu  -->
                                                    <div class="list-group list-group-messages">
                                                        <a href="#" class="list-group-item pt-0 active" id="inbox-menu">
                                                            <div class="fonticon-wrap d-inline mr-25">
                                                                <i class="livicon-evo" data-options="name: envelope-put.svg; size: 24px; style: lines; strokeColor:#5A8DEE; eventOn:grandparent; duration:0.85;">
                                                                </i>
                                                            </div>
                                                            Inbox
                                                            <span id="inbox-badge" class="badge badge-light-primary badge-pill badge-round float-right mt-50">5</span>
                                                        </a>
                                                        <a href="#" class="list-group-item" id="member-menu">
                                                            <div class="fonticon-wrap d-inline mr-25">
                                                                <i class="livicon-evo" data-options="name: user.svg; size: 24px; style: lines; strokeColor:#475f7b; eventOn:grandparent; duration:0.85;">
                                                                </i>
                                                            </div>
                                                            Member
                                                            <span id="member-badge" class="badge badge-light-success badge-pill badge-round float-right mt-50">3</span>
                                                        </a>
                                                        <a href="#" class="list-group-item" id="serial-menu">
                                                            <div class="fonticon-wrap d-inline mr-25">
                                                                <i class="livicon-evo" data-options="name: shoppingcart.svg; size: 24px; style: lines; strokeColor:#475f7b; eventOn:grandparent; duration:0.85;">
                                                                </i>
                                                            </div>
                                                            Pesan Serial
                                                            <span id="serial-badge" class="badge badge-light-success badge-pill badge-round float-right mt-50">3</span>
                                                        </a>
                                                        <a href="#" class="list-group-item" id="draft-menu">
                                                            <div class="fonticon-wrap d-inline mr-25">
                                                                <i class="livicon-evo" data-options="name: pen.svg; size: 24px; style: lines; strokeColor:#475f7b; eventOn:grandparent; duration:0.85;">
                                                                </i>
                                                            </div>
                                                            Draft
                                                            <span id="draft-badge" class="badge badge-light-secondary badge-pill badge-round float-right mt-50">3</span>
                                                        </a>
                                                        <a href="#" class="list-group-item" id="archive-menu">
                                                            <div class="fonticon-wrap d-inline mr-25">
                                                                <i class="livicon-evo" data-options="name: box.svg; size: 24px; style: lines; strokeColor:#475f7b; eventOn:grandparent; duration:0.85;">
                                                                </i>
                                                            </div>
                                                            Arsip
                                                            <span id="arsip-badge" class="badge badge-dark badge-pill badge-round float-right mt-50">3</span>
                                                        </a>
                                                        <a href="#" class="list-group-item" id="send-menu">
                                                            <div class="fonticon-wrap d-inline mr-25">
                                                                <i class="livicon-evo" data-options="name: paper-plane.svg; size: 24px; style: lines; strokeColor:#475f7b; eventOn:grandparent; duration:0.85;">
                                                                </i>
                                                            </div>
                                                            Terkirim
                                                            <span id="send-badge" class="badge badge-dark badge-pill badge-round float-right mt-50">3</span>
                                                        </a>
                                                    </div>
                                                    <!-- sidebar menu  end-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- inbox list -->
                                <div class="content-right">
                                    <div class="content-wrapper m-0 p-0">
                                        <div class="content-header row">
                                        </div>
                                        <div class="content-body">
                                            <!-- email app overlay -->
                                            <div class="email-app-area">
                                                <!-- Email list Area -->
                                                <div class="email-app-list-wrapper">
                                                    <div id="response-message"></div>
                                                    <div class="email-app-list">
                                                        <div id="table-emailList" class="p-1"></div>
                                                    </div>
                                                </div>
                                                <!--/ Email list Area -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modalAddUpdate" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdateTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center" id="modalAddUpdateTitle"><i class='bx bx-envelope d-flex align-items-center mr-50' style="font-size: 1.6rem;"></i>
                    <span>Kirim Email Baru</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form form-horizontal" id="formAddUpdate">
                    <div id="response-message"></div>
                    <!-- <div class="form-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="control-label">Pengirim</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="sendFrom" size="40" class="form-control" />
                            </div>
                        </div>
                    </div> -->
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Tipe Pesan</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="message_type" id="message_type" class="form-control">
                                    <option value="pesan">Personal</option>
                                    <option value="broadcast">Broadcast</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-body" id="message_sender_container">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Tujuan</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="message_receive_id" id="message_sender_id" class="form-control">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Pesan</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <!-- <textarea name="message_content" class="form-control" rows="7"></textarea> -->
                                <!-- Compose mail Quill editor -->
                                <div class="snow-container border rounded p-50 ">
                                    <div id="message_content" class="compose-editor"></div>
                                    <div class="d-flex justify-content-end">
                                        <div class="compose-quill-toolbar pb-0">
                                            <span class="ql-formats mr-0">
                                                <button class="ql-bold"></button>
                                                <button class="ql-italic"></button>
                                                <button class="ql-underline"></button>
                                                <button class="ql-link"></button>
                                            </span>
                                        </div>
                                    </div>
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
                <button class="btn btn-success" id="draft" type="submit">
                    <div class="d-flex align-center"><i class='bx bx-chevrons-down d-flex align-items-center mr-25'></i>
                        Draft</div>
                </button>
                <button class="btn btn-primary" id="submit" type="submit">
                    <div class="d-flex align-center"><i class='bx bx-send d-flex align-items-center mr-25'></i> Kirim
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailPesan" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center" id="exampleModalCenterTitle"><i class='bx bx-envelope d-flex align-items-center mr-50' style="font-size: 1.6rem;"></i> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="email-app-details">

                    <!-- email detail view header end-->
                    <div class="email-scroll-areas">
                        <!-- email details  -->
                        <div class="row">
                            <div class="col-12">
                                <div class="collapsible email-detail-head">


                                    <div class="card collapse-header open" role="tablist">
                                        <div id="headingCollapse7" class="card-header d-flex justify-content-between align-items-center" role="tab" data-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
                                            <div class="collapse-title media">

                                                <div class="media-body mt-25">
                                                    <span id="detail_sender_name" class="text-primary"></span>
                                                    <span id="detail_sender_code" class="d-sm-inline d-none"></span>
                                                    <small id="detail_receive_name" class="text-muted d-block"></small>
                                                </div>
                                            </div>
                                            <div class="information d-sm-flex d-none align-items-center">
                                                <small id="detail_datetime" class="text-muted mr-50">05 Jul 2019,
                                                    10:30</small>


                                            </div>
                                        </div>
                                        <div id="collapse7" role="tabpanel" aria-labelledby="headingCollapse7" class="collapse show">
                                            <div class="card-content">
                                                <div id="detail_content" class="card-body py-1">
                                                    <!-- <p class="text-bold-500">Greetings!</p> -->
                                                    <p>
                                                        It is a long established fact that a reader will be distracted
                                                        by the readable content of a page
                                                        when looking at its layout.The point of using Lorem Ipsum is
                                                        that it has a more-or-less normal
                                                        distribution of letters, as opposed to using 'Content here,
                                                        content here',making it look like
                                                        readable English.
                                                    </p>

                                                    <!-- <p class="mb-0">Sincerely yours,</p>
                                                    <p class="text-bold-500">Envato Design Team</p> -->
                                                </div>
                                                <!-- <div class="card-footer pt-0 border-top">
                                                    <label class="sidebar-label">Attached Files</label>
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="cursor-pointer pb-25">
                                                            <img src="../../../app-assets/images/icon/psd.png" height="30" alt="psd.png">
                                                            <small class="text-muted ml-1 attchement-text">uikit-design.psd</small>
                                                        </li>
                                                        <li class="cursor-pointer">
                                                            <img src="../../../app-assets/images/icon/sketch.png" height="30" alt="sketch.png">
                                                            <small class="text-muted ml-1 attchement-text">uikit-design.sketch</small>
                                                        </li>
                                                    </ul>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- email details  end-->
                        <!-- <div class="row px-1 mt-1">
                            <div class="col-12 px-0">
                                <div class="card shadow-none border border-primary rounded">
                                    <div class="card-body quill-wrapper">
                                        <span class="d-block mb-1">Reply to Lois Jimenez</span>
                                        <div class="snow-container" id="detail-view-quill">
                                            <div class="detail-view-editor"></div>
                                            <div class="d-flex justify-content-end">
                                                <div class="detail-quill-toolbar">
                                                    <span class="ql-formats mr-50">
                                                        <button class="ql-bold"></button>
                                                        <button class="ql-italic"></button>
                                                        <button class="ql-underline"></button>
                                                        <button class="ql-link"></button>
                                                        <button class="ql-image"></button>
                                                    </span>
                                                </div>
                                                <button class="btn btn-primary send-btn">
                                                    <div class="d-flex align-items-center">
                                                        <i class='bx bx-send d-flex align-items-center mr-25'></i>
                                                        <span class="d-none d-sm-inline"> Send</span>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal" id="btnCloseDetail">
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
        addUrl: '/admin/service/message/addMessage',
        updateUrl: '/admin/service/message/updateMessage',
        selectedID: '',
        subStateMenu: '',
    }

    let type = 'inbox';

    $(function() {
        $(".email-application").bind("DOMSubtreeModified", function() {
            if ($(".email-application").height() > 60) {
                $(".email-application").css('visibility', 'visible');
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        generateMessageTable('inbox');
        getDataMessageCount();
        getDataMember();
        clearModal();
    });

    function clearModal() {
        $('#modalAddUpdate').on('hidden.bs.modal', function(e) {
            $(this).find(".ql-editor p").text('');
        });
    }

    function addData() {
        $('#response-message').html('');
        $('#modalAddUpdateTitle > span').text('Buat Pesan Baru');
        stateMenu.selectedID = '';
        stateMenu.formAction = 'add';
        $('#formAddUpdate').trigger('reset');
        $('#modalAddUpdate').modal('show');
        $('#message_type').get(0).selectedIndex = 0;
        $('#message_sender_container').show();
        $('#draft').show();

    }

    function updateGroup(group) {
        $('#response-message').html('');
        $('#modalAddUpdateTitle > span').text('Edit Pesan');
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
                    $('.checkChild[value="' + item.administrator_privilege_administrator_menu_id +
                        '"]').prop('checked', true);
                    $('.checkParent[value="' + item.administrator_privilege_administrator_menu_id +
                        '"]').prop('checked', true);
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
    }

    function detailPesan(group) {
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
                administrator.data.result.forEach((item, index) => {

                    html += `<div class="d-block ml-1" style="margin-bottom: 7px;">
                                <strong class="ml-1">${item.administratorMenuTitle}</strong>
                        </div>
                                `
                    item.SubMenu.forEach((subitem, subindex) => {

                        html += `<div class="d-block ml-3" style="margin-bottom: 5px;">
                                     <label>
                                       <span class="ml-1">- ${subitem.administrator_menu_title}</span>
                                     </label>
                              </div>`
                    })
                })
                $('#menu-access').html(html)
                $('#detailPesan').modal('show');
            }
        })
    }

    // $('#detailPesan').on('hidden.bs.modal', function (e) {
    //     var quill_editor = $(".detail-view-editor .ql-editor");
    //     quill_editor[0].innerHTML = "";
    // })

    function closeDetail() {
        $('#detailPesan').modal('hide');
    }

    $('#submit').on('click', (e) => {
        e.preventDefault();

        $('#response-message').html('');
        $('#submit').prop('disabled', true)
        $('#submit').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')

        let form = $("#formAddUpdate").serializeArray();
        let message_content = $('#message_content .ql-editor').html();

        form.push({
            name: "message_content",
            value: message_content
        });

        $.ajax({
            url: window.location.origin + '/admin/service/message/addMessage',
            method: "POST",
            data: form,
            success: function(response) {
                $('#submit').prop('disabled', false)
                $('#submit').html(
                    `<div class="d-flex align-center"><i class='bx bx-send d-flex align-items-center mr-25'></i> Kirim</div>`
                )

                if (response.status == 200) {
                    $('#response-message').html(
                        `<div class="alert alert-success"> ${response.message} </div>`)
                }

                $('#modalAddUpdate').modal('hide');

                getDataMessageCount();
                generateMessageTable(stateMenu.subStateMenu);

                setTimeout(function() {
                    $('#response-messages').html('');
                    $('#response-messages').removeClass();
                }, 2000);

            },
            error: function(err) {
                $('#submit').prop('disabled', false)
                $('#submit').html('Simpan')
                let response = err.responseJSON

                if (response.message == "validationError") {
                    let message = '<ul>';
                    for (let key in response.data.validationMessage) {
                        message += `<li>${response.data.validationMessage[key]}</li>`
                    }
                    message += '</ul>'
                    $('#response-message').html(`
                        <div class="alert border-danger alert-dismissible mb-2" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-error"></i>
                                <span>
                                    ${message}
                                </span>
                            </div>
                        </div>
                    `);

                    $('#modalAddUpdate').modal('hide');

                } else if (response.message == 'Unauthorized' && response.status == 403) {
                    location.reload();
                }
            }
        });
        $.refreshTable('table-group');
    });

    $('#draft').on('click', (e) => {
        e.preventDefault();

        $('#response-message').html('');
        $('#submit').prop('disabled', true)
        $('#submit').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')

        let form = $("#formAddUpdate").serializeArray();
        let message_content = $('#message_content .ql-editor').html();

        form.push({
            name: "message_content",
            value: message_content
        });
        if (form[0].value == 'broadcast') {
            $('#response-message').html(
                `<div class="alert alert-danger"> Tidak dapat mendraft pesan berisi broadcast </div>`);
            $('#modalAddUpdate').modal('hide');

            return false;
        }

        form[0].value = 'draf';
        console.log(form);

        $.ajax({
            url: window.location.origin + '/admin/service/message/addMessage',
            method: "POST",
            data: form,
            success: function(response) {
                $('#submit').prop('disabled', false)
                $('#submit').html(
                    `<div class="d-flex align-center"><i class='bx bx-send d-flex align-items-center mr-25'></i> Kirim</div>`
                )

                if (response.status == 200) {
                    $('#response-message').html(
                        `<div class="alert alert-success"> ${response.message} </div>`)
                }

                $('#modalAddUpdate').modal('hide');

                getDataMessageCount();
                generateMessageTable(stateMenu.subStateMenu);

                setTimeout(function() {
                    $('#response-messages').html('');
                    $('#response-messages').removeClass();
                }, 2000);

            },
            error: function(err) {
                $('#submit').prop('disabled', false)
                $('#submit').html('Simpan')
                let response = err.responseJSON

                if (response.message == "validationError") {
                    let message = '<ul>';
                    for (let key in response.data.validationMessage) {
                        message += `<li>${response.data.validationMessage[key]}</li>`
                    }
                    message += '</ul>'
                    $('#response-message').html(`
                        <div class="alert border-danger alert-dismissible mb-2" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-error"></i>
                                <span>
                                    ${message}
                                </span>
                            </div>
                        </div>
                    `);

                    $('#modalAddUpdate').modal('hide');

                } else if (response.message == 'Unauthorized' && response.status == 403) {
                    location.reload();
                }
            }
        });
        $.refreshTable('table-group');
    });

    $('#archive-menu').on('click', function() {
        generateMessageTable('archive');
        $('#table-emailListactive').hide();
    });

    $('#inbox-menu').on('click', function() {
        generateMessageTable('inbox');
    });
    $('#serial-menu').on('click', function() {
        generateMessageTable('serial');
    });

    $('#draft-menu').on('click', function() {
        generateMessageTable('draft');
    })

    $('#send-menu').on('click', function() {
        generateMessageTable('send');
    })

    $('#member-menu').on('click', function() {
        generateMessageTable('member');
    })

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

    $("#message_type").on('change', function() {
        if (this.value == 'pesan') {
            $('#message_sender_container').show();
            $('#draft').show();

        } else {
            $('#message_sender_container').hide();
            $('#draft').hide();
        }
    });

    $('#swal2-confirm').on('click', function() {
        getDataMessageCount();
    })

    function generateMessageTable(type) {
        stateMenu.subStateMenu = type;

        $("#table-emailList").dataTableLib({
            url: window.location.origin + '/admin/service/message/getDataMessage/' + type,
            selectID: 'message_id',
            colModel: [{
                    display: '',
                    name: 'message_status',
                    sortAble: false,
                    align: 'left',
                    export: true,
                    render: (params) => {
                        // pake link karena icon box versi skrg gaada bx-envelope-open

                        return `<span class="btn text-info"><i class="bx bx-envelope"></i></span>`;
                    }
                },
                {
                    display: 'Tanggal',
                    name: 'message_datetime',
                    sortAble: true,
                    align: 'left'
                },
                {
                    display: 'Kode Mitra',
                    name: 'message_sender_network_code',
                    sortAble: true,
                    align: 'center'
                },
                {
                    display: 'Nama Pengirim',
                    name: 'message_sender_name',
                    sortAble: true,
                    align: 'left'
                },
                {
                    display: 'Kode Mitra',
                    name: 'message_receive_network_code',
                    type: 'text'
                },
                {
                    display: 'Nama Penerima',
                    name: 'message_receive_name',
                    sortAble: true,
                    align: 'left'
                },
                {
                    display: 'Pesan',
                    name: 'message_content',
                    sortAble: false,
                    align: 'left'
                },
            ],
            sortName: "message_datetime",
            sortOrder: "DESC",
            tableIsResponsive: true,
            search: true,
            searchTitle: 'Pencarian Data Pesan Masuk',
            searchItems: [{
                    display: 'Tanggal',
                    name: 'message_sender_network_code',
                    type: 'date'
                },
                {
                    display: 'Kode Mitra',
                    name: 'message_sender_network_code',
                    type: 'text'
                },
                {
                    display: 'Nama Pengirim',
                    name: 'message_sender_name',
                    type: 'text'
                },
                {
                    display: 'Kode Mitra',
                    name: 'message_receive_network_code',
                    type: 'text'
                },
                {
                    display: 'Nama Penerima',
                    name: 'message_receive_name',
                    type: 'text'
                },

            ],
            select: true,
            multiSelect: true,
            buttonAction: [{
                    display: 'Hapus',
                    icon: 'bx bx-trash',
                    style: "danger",
                    action: "remove",
                    url: window.location.origin + "/admin/service/message/deleteMessage"
                },
                {
                    display: 'Arsipkan',
                    icon: 'bx bxs-bulb',
                    style: "warning",
                    action: "active",
                    url: window.location.origin + "/admin/service/message/archiveMessage"
                },
            ]
        });
    }

    function getDataMember() {
        $.ajax({
            url: window.location.origin + '/admin/service/message/getDataMember',
            method: 'GET',
            success: function(response) {
                if (response.status == 200) {
                    let data = response.data.results;

                    var dropdown = $("#message_sender_id");

                    data.forEach((option) => {
                        let member_id = option.member_id;
                        let member_name = option.member_name
                        dropdown.append($("<option />").val(member_id).text(member_name));
                    });
                }
            }
        });

    }

    function getDataMessageCount() {
        $.ajax({
            url: window.location.origin + '/admin/service/message/getDataMessageCount',
            method: 'GET',
            success: function(response) {
                if (response.status == 200) {
                    let {
                        inbox,
                        serial,
                        draft,
                        arsip,
                        terkirim,
                        member
                    } = response.data.results;

                    $('#inbox-badge').html(inbox);
                    $('#serial-badge').html(serial);
                    $('#draft-badge').html(draft);
                    $('#arsip-badge').html(arsip)
                    $('#send-badge').html(terkirim)
                    $('#member-badge').html(member)

                }
            }
        });
    }

    function getDetailMessage(id) {
        $.ajax({
            url: window.location.origin + `/admin/service/message/getDataById/${id}`,
            method: 'GET',
            success: function(response) {
                if (response.status == 200) {
                    let {
                        message_sender_network_code,
                        message_sender_name,
                        message_receive_network_code,
                        message_receive_name,
                        message_content,
                        message_datetime
                    } = response.data.results;
                    $('#detail_sender_code').text(message_sender_network_code ?
                        `(${message_sender_network_code})` : '');
                    $('#detail_sender_name').text(message_sender_name);
                    $('#detail_receive_name').text(message_receive_name);
                    $('#detail_content').html(message_content);
                    $('#detail_datetime').text(message_datetime);
                }
            }
        });

        $('#detailPesan').modal('show')

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
</script>