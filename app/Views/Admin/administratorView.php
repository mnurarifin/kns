<style>
    .modal-body {
        max-height: 100% !important;
    }

    .alert-position {
        transform: translateY(5px);
    }
</style>
<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?=isset($title) ? $title : ''?></h4>
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
                        <div id="response-messages"></div>
                        <div id="table-administrator"></div>
                        <div id="table-xo"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- update Data Administrator -->
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
                                <label>Administrator Username</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="administratorUsername" placeholder="Administrator Username" >
                            </div>
                        </div>
                        <div class="row" id="administratorPassword">
                            <div class="col-md-4">
                                <label>Administrator Password</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="password" class="form-control" name="administratorPassword" placeholder="Administrator Password">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Administrator Nama </label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="administratorName" placeholder="Administrator Nama">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Administrator Email </label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="administratorEmail" placeholder="Administrator Email">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Administrator Group Title </label>
                            </div>
                            <div class="col-md-8 form-group" id="formSelectOption">
                                <select name="optionGroup" class="form-control"></select>
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

<!-- Update Password Administrator -->
<div class="modal fade" id="modalUpdatePassword" tabindex="-1" role="dialog" aria-labelledby="modalUpdatePassword" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUpdatePasswordTitle">Vertically Centered</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form class="form form-horizontal" id="formUpdatePassword">
                <div class="modal-body">
                    <div id="response-message-password"></div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Password Baru</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="password" id="inputPass" class="form-control" name="password" placeholder="Administrator Password">
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-4">
                                <label>Ulangi Password Baru </label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="password" id="inputPassConfirm" class="form-control" name="passwordConf" placeholder="Administrator Password">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" id="submitEditPass">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">DETAIL DATA ADMINISTRATOR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="panel-body" style="padding: 0;">
                    <div class="card rounded-0 mb-1">
                        <div class="card-header rounded-0 bg-dark text-white text-center">
                            <i class='bx bxs-user bx-lg bx-border-circle border-primary text-primary'></i>
                            <h5 class="text-primary mt-50 mb-25"><span id="detailNama"></span></h5>
                            <div class="d-flex align-items-center justify-content-center flex-row"><i class="d-flex align-items-center bx bx-envelope text-white mr-50"></i><span id="detailEmail"></span></div>
                        </div>
                    </div>

                    <div class="row mx-0">
                        <div class="col-md-4">
                            <div class="card border rounded-0 mb-1">
                                <div class="card-body p-1 d-flex flex-column align-items-center justify-content-between">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="avatar bg-rgba-primary m-0 p-25 mb-75">
                                            <div class="avatar-content">
                                                <i class="bx bx-user text-primary font-medium-2"></i>
                                            </div>
                                        </div>
                                        <div class="total-amount text-center">
                                            <small class="d-block text-muted mb-25">Username</small>
                                            <h6 class="mb-0 text-primary"><span id="detailUsername"></span></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border rounded-0 mb-1">
                                <div class="card-body p-1 d-flex flex-column align-items-center justify-content-between">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="avatar bg-rgba-primary m-0 p-25 mb-75">
                                            <div class="avatar-content">
                                                <i class="bx bx-user text-primary font-medium-2"></i>
                                            </div>
                                        </div>
                                        <div class="total-amount text-center">
                                            <small class="d-block text-muted mb-25">Group</small>
                                            <h6 class="mb-0 text-primary"><span id="detailGroupTitle"></span></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border rounded-0 mb-1">
                                <div class="card-body p-1 d-flex flex-column align-items-center justify-content-between">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="avatar bg-rgba-primary m-0 p-25 mb-75">
                                            <div class="avatar-content">
                                                <i class="bx bx-user text-primary font-medium-2"></i>
                                            </div>
                                        </div>
                                        <div class="total-amount text-center">
                                        <small class="d-block text-muted mb-25">Tipe Group</small>
                                            <h6 class="mb-0 text-primary"><span id="detailGroupType"></span></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-block px-2 py-1">
                        <h5>Menu Privilege</h5>
                        <div id="detailPrivilege" class="d-block py-1"></div>
                    </div>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
</div>

<script>
	let dataAdministrator = {
        limit : 5,
        selectedID : 0,
    }

    $(function() {
        $("#table-administrator").bind("DOMSubtreeModified", function() {
            if ($("#table-administrator").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });
    
	$(document).ready(function(){
        $('#response-message').html('');
        $('#response-message-password').html('');

        let groupOption = [];
        $.ajax({
            url: '/admin/service/administrator/administrator_group_option',
            type: 'GET',
            async: false,
            dataType: 'json',
            success: function(response){
                groupOption = response;
            }
        });
        $("#table-administrator").dataTableLib({
            url: window.location.origin+'/admin/service/administrator/getDataAdministrator',
            selectID: 'administrator_id',
            colModel: [
                {display: 'Username', name: 'administrator_username', sortAble: false, align: 'center'},
                {display: 'Nama', name: 'administrator_name', sortAble: false, align: 'center'},
                {display: 'Email', name: 'administrator_email', sortAble: false, align: 'center'},
                {display: 'Group', name: 'administrator_group_title', sortAble: false, align: 'center'},
                {display: 'Tipe Group', name: 'administrator_group_type', sortAble: false, align: 'center'},
                {display: 'Login Terakhir', name: 'administrator_last_login', sortAble: false, align: 'center'},
                // {display: 'menu', name: 'administrator_menu_title', sortAble: false, align: 'center'},
                {display: 'Status', name: 'administrator_is_active', sortAble: false, align: 'center', render: (params) => { return params == '1' ? '<span class="badge badge-light-success badge-pill badge-round" title="Aktif" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>' : '<span class="badge badge-light-danger badge-pill badge-round" title="Non Aktif" data-toggle="tooltip"><i class="bx bx-bulb"></i></span>'}},
                {display: 'Detail', name: 'detail', sortAble: false, align: 'center', action:{function:'detailAdministrator', icon:'bx bx-book info', class:'warning'}},
                {display: 'Ubah', name: 'edit', sortAble: false, align: 'center', action:{function:'updateAdministrator', icon:'bx bx-edit-alt warning', class:'warning'}},
                {display: 'Password', name: 'editPassword', sortAble: false, align: 'center', action:{function: 'updatePassword', icon: 'bx bxs-key', class: 'warning'}}
            ],
            search: true,
            searchTitle: 'Pencarian Administrator',
            searchItems:[
                {display: 'Username', name: 'administrator_username', type:'text'},
                {display: 'Nama', name: 'administrator_name', type:'text'},
                {display: 'Email', name: 'administrator_email', type:'text'},
                {display: 'Group', name: 'administrator_group_title', type:'select', option: groupOption},
                {display: 'Tipe Group', name: 'administrator_group_type', type:'select', option: [{title: 'Administrator', value: 'administrator'}, {title: 'Superuser', value: 'superuser'}]},
                {display: 'Status', name: 'administrator_is_active', type:'select', option: [{title: 'Aktif', value: '1'}, {title: 'Tidak Aktif', value: '0'}]},
            ],
            sortName: "administrator_username",
            sortOrder: "asc",
            tableIsResponsive: true,
            select: true,
            multiSelect: true,
            buttonAction: [
                {display: 'Tambah', icon: 'bx bx-plus', style:"info", action:"addAdministrator"},
                {display: 'Hapus', icon: 'bx bx-trash', style:"danger", action:"remove", url : window.location.origin+"/admin/service/administrator/removeAdministrator",message:'Oke'},
                {display: 'Aktifkan', icon: 'bx bxs-bulb', style:"success", action:"active", url : window.location.origin+"/admin/service/administrator/activeAdministrator"},
                {display: 'Non-Aktifkan', icon:'bx bx-bulb', style:"warning", action:"nonactive", url: window.location.origin+"/admin/service/administrator/notActiveAdministrator"},
            ]
        });

        $('#formUpdatePassword').submit(function(event){
            event.preventDefault();
            const _pass = $('#inputPass').val();
            const _confirm = $('#inputPassConfirm').val();
            $('#response-message-password').html('');
            $('#response-message-password').removeClass('alert alert-danger');

            if (_pass == '') {
                let mesg = `
                    <div class="alert alert-danger alert-dismissible fade show">
                        Password Tidak boleh Kosong!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>`;
                $('#response-message-password').html(mesg);
                $('#inputPassConfirm').val('');
                setTimeout(function() {
                    $('#inputPass').focus();
                }, 100);
            } else if (_confirm == '') {
                let mesg = `
                    <div class="alert alert-danger alert-dismissible fade show">
                        Masukkan kembali password anda untuk konfirmasi.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>`;
                $('#response-message-password').html(mesg);
                setTimeout(function() {
                    $('#inputPassConfirm').focus();
                }, 100);
            } else {

                let message_confirm = 'Apakah Anda Yakin ?';

                Swal.fire({
                    title: 'Perhatian!',
                    text: "Anda yakin akan mengubah password user ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ok',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
    
                        let myForm = document.getElementById('formUpdatePassword');
                        let formData = new FormData(myForm);
                        let url = stateAdministrator.baseUrl + stateAdministrator.updatePasswordUrl;
                        
                        formData.append('id', stateAdministrator.selectID);
                        $('#response-message-password').html('<div id="message-alert"></div>')

                        $.ajax({    
                            url: url,
                            data: formData,
                            method: 'POST',
                            processData: false,
                            contentType: false,
                            beforeSend: function() {
                                $('#message-alert').slideUp();
                                $('#submitEditPass').attr('disabled', 'disabled');
                                $('#cancelEditPass').attr('disabled', 'disabled');
                            },
                            success: function(res) {
                                let messages = ``;

                                if (res.status == 200) {
                                    messages = `
                                        <div class="alert alert-success alert-dismissible fade show">
                                            ${ res.data.message }
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    `;
                                } else {
                                    messages = `
                                        <div class="alert alert-danger alert-dismissible fade show">
                                            ${ res.data.message }
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    `;
                                }
                                
                                $('#response-message-password').html(messages);
                                $('#formUpdatePassword').get(0).reset();
                                $('#submitEditPass').removeAttr('disabled');
                                $('#cancelEditPass').removeAttr('disabled');

                                setTimeout(function() {
                                    $('#inputPass').focus();
                                }, 100);
                            },
                            error: function(err) {
                                const error = err.responseJSON;
                                const errorObj = error.data.validationMessage;
                                let keys = Object.keys(errorObj);

                                let msgErr = `
                                    <div class="alert alert-danger alert-dismissible fade show">
                                        <ul class="pl-2 m-0">`;
                                    for (i=0; i < keys.length; i++) {
                                        msgErr += `<li>${ errorObj[keys[i]] }</li>`;
                                    }
                                    msgErr +=`</ul>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>`;

                                $('#response-message-password').html(msgErr);
                                $('#formUpdatePassword').get(0).reset();
                                $('#submitEditPass').removeAttr('disabled');
                                $('#cancelEditPass').removeAttr('disabled');

                                setTimeout(function() {
                                    $('#inputPass').focus();
                                }, 100);
                            }
                        });

                    } else {
                        $('#response-message-password').html('');
                        $('#response-message-password').removeClass('alert alert-danger');
                    }
                });
            }
        });

        $.ajax({
            url: window.location.origin +'/admin/service/administrator/getAdministratorGroup',
            method: 'GET',
            success : function(option){
                let text = '<option selected="true" disabled="disabled">Pilih Data</option>'
                option.data.results.forEach( e => {
                    text += `<option value="${e.id}">${e.label}</option>`
                })
                $('#formAddUpdate select[name=optionGroup]').html(text);
            }
        })
    });

    let stateAdministrator = {
        formAction: 'add',
        baseUrl : window.location.origin,
        addUrl: '/admin/service/administrator/actAddAdministrator',
        updateUrl : '/admin/service/administrator/actUpdateAdministrator',
        updatePasswordUrl : '/admin/service/administrator/actEditPasswordAdministrator',
        // urlDetail: '/admin/service/administrator/detailAdministrator',
        selectID: ''
    }

    function detailAdministrator(administrator){
        $('#detailNama').text(administrator.administrator_name)
        $('#detailUsername').text(administrator.administrator_username)
        $('#detailEmail').text(administrator.administrator_email)
        $('#detailGroupTitle').text(administrator.administrator_group_title)
        $('#detailGroupType').text(administrator.administrator_group_type)

        $.ajax({
        url: window.location.origin +'/admin/service/administrator/getDetailAdministrator',
        method: 'POST',
        data: {administratorId:administrator.administrator_id},
        success : function(administrator){
            let html ='';

            if(administrator.administrator_group_type === 'superuser') {
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
                    </div>`;
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

                                            item.SubMenu.forEach((subitem, subindex) =>{
                                
                                                html+= `<div class="d-block ml-2" style="margin-bottom: 5px;">
                                                        <label class="d-flex align-items-center">
                                                            <i class='bx bx-check-circle'></i> 
                                                            <span class="ml-1">${subitem.administrator_menu_title}</span>
                                                        </label>
                                                    </div>`
                                            })

                        html +=         `</div>
                                    </div>
                                </div>
                            </div>
                        `;
                })
            }

                $('#detailPrivilege').html(html)
                $('#modalDetail').modal('show');
        }
        })
    }

    function addAdministrator(){
        $('#response-message').html('') 
        $('#modalAddUpdateTitle').text('Form Tambah Administrator')
        $('#administratorPassword').show();
        stateAdministrator.selectID = '';
        stateAdministrator.formAction = 'Add'
        $('#formAddUpdate').trigger('reset');
        $('#modalAddUpdate').modal('show')
    }

    function updateAdministrator(administrator){
        $('#response-message').html('');
        $('#modalAddUpdateTitle').text('Form Ubah Data Administrator');
        $('#administratorPassword').hide();
        stateAdministrator.selectID = administrator.administrator_id;
        stateAdministrator.formAction = 'update';
        $('#formAddUpdate input[name=administratorUsername]').val(administrator.administrator_username);
        $('#formAddUpdate input[name=administratorName]').val(administrator.administrator_name);
        $('#formAddUpdate input[name=administratorEmail]').val(administrator.administrator_email);
        $('#formAddUpdate select[name=optionGroup]').val(administrator.administrator_administrator_group_id);
        $('#modalAddUpdate').modal('show');
    }

    function updatePassword(administrator){
        $('#response-message').html('');
        $('#response-message-password').html('');
        $('#response-message-password').removeClass('alert alert-danger');
        $('#modalUpdatePasswordTitle').text('Form Ubah Password Administrator');
        stateAdministrator.selectID = administrator.administrator_id;
        stateAdministrator.formAction = 'updatePassword';
        $('#modalUpdatePassword').modal('show');

    }
   
    $('#formAddUpdate').on('submit', (e) => {
        e.preventDefault();
        $('#response-message').html('');
        $('#formAddUpdate button[type=submit]').prop('disabled', true)
        $('#formAddUpdate button[type=submit]').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')
        
        let formData = new FormData(e.target);
        let url = stateAdministrator.baseUrl + stateAdministrator.addUrl;
        if(stateAdministrator.formAction == 'update'){
            formData.append('administratorId', stateAdministrator.selectID);
            url = stateAdministrator.baseUrl + stateAdministrator.updateUrl;
        }

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
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
                setTimeout(function(){ 
                    $('#response-messages').html('');
                    $('#response-messages').removeClass();
                }, 2000);
                $.refreshTable('table-administrator');
              
            },
            error: function(err){
                $('#formAddUpdate button[type=submit]').prop('disabled', false)
                $('#formAddUpdate button[type=submit]').html('Simpan')
                let response = err.responseJSON
                $('#response-message').show()
                if(response.message == "validationError"){
                    let message = '<ul>';
                    for(let key in response.data.validationMessage){
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
                }else if(response.message == 'Unauthorized' && response.status == 403){
                    location.reload();
                }
            }
        });
    });

</script>