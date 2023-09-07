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
                        <div id="response-message"></div>
                        <div id="table-stockist"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modalAddUpdateStockist" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdateStockist" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddUpdateTitle">Form Ubah <?= isset($title) ? $title : '' ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form class="form form-horizontal" id="formAddUpdateStockist">
                <div class="modal-body">
                    <div id="response-error-content"></div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Kode</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" id="stockist_code" name="stockist_code" value="" disabled="disabled">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nama Stockist</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="stockist_name" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nama Pemilik</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="stockist_member_name" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>No. Handphone</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="stockist_mobilephone" value="" size="20" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>E-mail</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="stockist_email" value="" size="40" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Alamat</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <textarea name="stockist_address" cols="40" rows="4" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Provinsi</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="stockist_province_id" id="select-province" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Kota / Kabupaten</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="stockist_city_id" id="select-city" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Koordinat Longitude</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="stockist_longitude" value="" size="40" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Koordinat Latitude</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="stockist_latitude" value="" size="40" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Gambar Stockist</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="file" name="memberImage" accept="image/*" value="" size="8" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tutup</span>
                    </button>
                    <button class="btn btn-primary" id="submit" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal-detail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-radius: 7px 7px 0 0; border: 1px solid #ccc; background-color: #f5f5f5;">
                <h4 class="modal-title" id="modal-label">Detail Stockist</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="panel panel-default mb-1">
                    <div class="panel-body p-0">
                        <div class="card rounded-0 mb-1">
                            <div class="card-header rounded-0 bg-dark text-white text-center">
                                <i class='bx bxs-store-alt bx-lg bx-border-circle border-primary text-primary'></i>
                                <h5 class="text-primary mt-50 mb-50"><span id="data-stockist-name" class="text-capitalize"></span></h5>
                                <div class="d-flex align-items-center justify-content-center flex-row">
                                    <div class="d-flex align-items-center flex-row mx-75"><i class="d-flex align-items-center bx bxs-contact text-white mr-50"></i><span id="data-stockist-code"></span></div>
                                    <div class="d-flex align-items-center flex-row mx-75"><i class="d-flex align-items-center bx bx-envelope text-white mr-50"></i><span id="data-stockist-email"></span></div>
                                    <div class="d-flex align-items-center flex-row mx-75"><i class="d-flex align-items-center bx bx-phone text-white mr-50"></i><span id="data-stockist-mobilephone"></span></div>
                                </div>
                            </div>
                        </div>

                        <div class="card widget-state-multi-radial">
                            
                            <div class="card-body py-1">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="biodata" aria-labelledby="biodata-tab" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ul class="list-group list-group-flush d-flex flex-row">
                                                    <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                        <div class="list-left d-flex">
                                                            <div class="list-icon mr-1">
                                                                <div class="avatar bg-rgba-primary m-0">
                                                                    <div class="avatar-content">
                                                                        <i class="bx bx-user text-primary font-size-base"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-content">
                                                                <small class="text-muted d-block">Pemilik</small>
                                                                <span class="list-title"><span id="data-stockist-member"></span></span>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                        <div class="list-left d-flex">
                                                            <div class="list-icon mr-1">
                                                                <div class="avatar bg-rgba-primary m-0">
                                                                    <div class="avatar-content">
                                                                        <i class="bx bx-calendar-event text-primary font-size-base"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-content">
                                                                <small class="text-muted d-block">Masuk Stockist</small>
                                                                <span class="list-title"><span id="data-stockist-join-date"></span></span>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                        <div class="list-left d-flex">
                                                            <div class="list-icon mr-1">
                                                                <div class="avatar bg-rgba-primary m-0">
                                                                    <div class="avatar-content">
                                                                        <i class="bx bx-id-card text-primary font-size-base"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-content">
                                                                <small class="text-muted d-block">Status</small>
                                                                <span class="list-title"><span id="data-stockist-is-active"></span></span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="col-md-12">
                                                <ul class="list-group list-group-flush d-flex flex-row">
                                                    <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                        <div class="list-left d-flex">
                                                            <div class="list-icon mr-1">
                                                                <div class="avatar bg-rgba-primary m-0">
                                                                    <div class="avatar-content">
                                                                        <i class="bx bx-map text-primary font-size-base"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-content">
                                                                <small class="text-muted d-block">Alamat</small>
                                                                <div class="list-title">
                                                                    <span id="data-stockist-address" class="d-block"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                        <div class="list-left d-flex">
                                                            <div class="list-icon mr-1">
                                                                <div class="avatar bg-rgba-primary m-0">
                                                                    <div class="avatar-content">
                                                                        <i class="bx bx-map text-primary font-size-base"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-content">
                                                                <small class="text-muted d-block">Kota / Kabupaten</small>
                                                                <div class="list-title">
                                                                    <span id="data-stockist-city" class="d-block"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                        <div class="list-left d-flex">
                                                            <div class="list-icon mr-1">
                                                                <div class="avatar bg-rgba-primary m-0">
                                                                    <div class="avatar-content">
                                                                        <i class="bx bx-map text-primary font-size-base"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-content">
                                                                <small class="text-muted d-block">Provinsi</small>
                                                                <div class="list-title">
                                                                    <span id="data-stockist-province" class="d-block"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul> 
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <iframe id="stockist-location" src="" width="100%" height="280" frameborder="0" style="border:0"></iframe>
                                            </div>
                                        </div>
                                    </div>

                                </div>
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
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $("#table-stockist").bind("DOMSubtreeModified", function() {
            if ($("#table-stockist").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        getProvince();
        $("#table-stockist").dataTableLib({
            url: window.location.origin + '/admin/service/stockist/getData',
            selectID: 'stockist_member_id',
            colModel: [
            {display: 'Kode Stockist', name: 'stockist_code', sortAble: false, align: 'center', export: true},
            {display: 'Nama Stockist', name: 'stockist_name', sortAble: false,align: 'left', export: true},
            {display: 'Pemilik', name: 'stockist_member_name', sortAble: false,align: 'left', export: true},
            {display: 'Kota', name: 'city_name', sortAble: false, align: 'left', export: true},
            {display: 'No. HP', name: 'stockist_mobilephone', sortAble: false, align: 'left', export: true},
            {display: 'Status', name: 'stockist_is_active', sortAble: false, align: 'center', render: (params) => { return params == '1' ? '<span class="badge badge-light-success badge-pill badge-round" title="Ya" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>' : '<span class="badge badge-light-danger badge-pill badge-round" title="Tidak" data-toggle="tooltip"><i class="bx bx-bulb"></i></span>'}, export: true},
            {display: 'Detail', name: 'detail', sortAble: false, align: 'center', width: "70px", action:{function:'detailStockist', icon:'bx bx-book info'}},
            {display: 'Ubah', name: '', sortAble: false, align: 'center', width: "70px", action: { function: 'updateStockist', icon:'bx bx-edit-alt warning'}},
            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Stockist',
            searchItems:[
                {display: 'Kode Stockist', name: 'stockist_code', type:'text'},
                {display: 'Nama Stockist', name: 'stockist_name', type:'text'},
                {display: 'Nama Pemilik', name: 'stockist_member_name', type:'text'},
                {display: 'Status', name: 'stockist_is_active', type:'select', option: [{title: 'Aktif', value: '1'}, {title: 'Tidak Aktif', value: '0'}]},
            ],
            buttonAction: [
            {display: 'Tambah', icon: 'bx bx-plus', style:"info", action:"addStockist"},
            {display: 'Aktifkan', icon: 'bx bxs-bulb', style:"success", action:"active", url : window.location.origin+"/admin/service/stockist/actActive"},
            {display: 'Non Aktifkan', icon: 'bx bx-bulb', style:"danger", action:"nonactive", url : window.location.origin+"/admin/service/stockist/actUnactive"},
            {display: 'Hapus', icon: 'bx bx-trash', style:"danger", action:"remove", url : window.location.origin+"/admin/service/stockist/actDelete",message:'Hapus'}
            ],
            sortName: "stockist_member_id",
            sortOrder: "ASC",
            tableIsResponsive: true,
            select: true,
            multiSelect: true,
        });

    });

    $('#formAddUpdateStockist').on('submit', (e) => {
        e.preventDefault();
        $('#response-message').html('');
        $('#formAddUpdateStockist button[type=submit]').prop('disabled', true)
        $('#formAddUpdateStockist button[type=submit]').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')
        
        let formData = new FormData(e.target);
        let url = stateStockist.baseUrl + stateStockist.addUrl;
        if(stateStockist.formAction == 'update'){
            formData.append('stockist_id', stateStockist.selectID);
            formData.append('oldImage', stateStockist.selectedOldImage);
            url = stateStockist.baseUrl + stateStockist.updateUrl;
        }

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                $('#formAddUpdateStockist button[type=submit]').prop('disabled', false)
                $('#formAddUpdateStockist button[type=submit]').html('Simpan')
                $('#modalAddUpdateStockist').modal('hide');
                if (response.status == 200) {
                    $('#response-message').html(response.data.message);
                    $('#response-message').addClass('alert alert-success');
                } else {
                    $('#response-error-content').html(response.data.message);
                    $('#response-error-content').addClass('alert alert-danger');
                }
                setTimeout(function() {
                    $('#response-message').html('');
                    $('#response-message').removeClass();
                    $('#response-error-content').html('');
                    $('#response-error-content').removeClass();
                }, 2000);
                $.refreshTable('table-stockist');
            },    
            error: function(err){
                $('#formAddUpdateStockist button[type=submit]').prop('disabled', false)
                $('#formAddUpdateStockist button[type=submit]').html('Simpan')
                let response = err.responseJSON
                $('#response-error-content').show()
                if(response.message == "validationError"){
                    let message = '<ul>';
                    for(let key in response.data.validationMessage){
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
                }else if(response.message == 'Unauthorized' && response.status == 403){
                    location.reload();
                } else {
                    $('#response-error-content').html(response.data.message);
                    $('#response-error-content').addClass('alert alert-danger');
                    setTimeout(function() {
                        $('#response-message').html('');
                        $('#response-message').removeClass();
                        $('#response-error-content').html('');
                        $('#response-error-content').removeClass();
                    }, 3000);
                }
            }
        });
    });

    let stateStockist = {
        formAction: 'add',
        baseUrl : window.location.origin,
        addUrl: '/admin/service/stockist/actAdd',
        updateUrl : '/admin/service/stockist/actUpdate',
        selectID: '',
        selectedOldImage:''
    }


    $('#select-province').on('change',(event) =>{
        getCityByProvince(event.target.value)
    })

    function getProvince(){
        $.ajax({
            url: "<?php echo site_url('admin/service/member/getProvince') ?>",
            type: 'GET',
            "content_type": 'application/json',
            success: function(res) {
                let html = ``;
                html = `<option value="">Pilih Provinsi</option>`
                if(res.status == 200){
                    let data = res.data.results;

                    data.forEach((item, index) => {
                        html += `<option value="${item.province_id}">${item.province_name}</option>`
                    });
                    $('#select-province').html(html);
                }
            }
        });
    }


    function getCityByProvince(province_id,select = 0){
                $.ajax({
            url: "<?php echo site_url('admin/service/member/getCityByProvince/') ?>"+ province_id,
            type: 'GET',
            "content_type": 'application/json',
            success: function(res) {

                let html = ``;
                if(res.status == 200){
                    let data = res.data.results;

                    data.forEach((item, index) => {
                        html += `<option value="${item.city_id}">${item.city_name}</option>`
                    });
                    $('#select-city').html(html);

                    if(select > 0){
                        $('#formAddUpdateStockist select[name=stockist_city_id]').val(select);
                    }

                }   
              
            }
        });
    }

    function addStockist(){
        $('#response-message').html('')
        $('#modalAddUpdateTitle').text('Form Tambah Data Stockist')
        $('#stockist_code').prop("disabled", false);
        stateStockist.selectID = '';
        stateStockist.formAction = 'add'
        $('#formAddUpdateStockist').trigger('reset');
        $('#formAddUpdateStockist input[name=stockist_member_name]').prop('readonly', false);
        $.ajax({
            url: window.location.origin +'/admin/service/ref_area/getDataCity',
            method: 'GET',
            success : function(option){
                let text = '<option selected="true" disabled="disabled">Pilih Data</option>'
                option.data.results.forEach( e => {
                    text += `<option value="${e.id}">${e.label}</option>`
                })
                $('#modalAddUpdateStockist select[name=stockist_city]').html(text);
            }
        })
        $('#modalAddUpdateStockist').modal('show')
    }

    function updateStockist(stockist) {
        $('#formAddUpdateStockist').trigger('reset');
        $('#response-message').html('');
        $('#modalAddUpdateTitle').text('Form Ubah Data Stockist');
        $('#stockist_code').prop("disabled", true);
        stateStockist.selectID = stockist.stockist_member_id;
        stateStockist.selectedOldImage = stockist.stockist_image;
        stateStockist.formAction = 'update';

        $('#formAddUpdateStockist input[name=stockist_member_name]').prop('readonly', true);
        $('#formAddUpdateStockist input[name=stockist_code]').val(stockist.stockist_code);
        $('#formAddUpdateStockist input[name=stockist_name]').val(stockist.stockist_name);
        $('#formAddUpdateStockist input[name=stockist_member_name]').val(stockist.stockist_member_name);
        $('#formAddUpdateStockist input[name=stockist_email]').val(stockist.stockist_email);
        $('#formAddUpdateStockist textarea[name=stockist_address]').val(stockist.stockist_address);
        $('#formAddUpdateStockist input[name=stockist_mobilephone]').val(stockist.stockist_mobilephone);
        $('#formAddUpdateStockist select[name=stockist_city]').val(stockist.city_name);

        $('#formAddUpdateStockist select[name=stockist_province_id]').val(stockist.stockist_province_id);
        
        if(stockist.province_id !== 0){
            getCityByProvince(stockist.stockist_province_id,stockist.stockist_city_id);
        }

        $('#formAddUpdateStockist input[name=stockist_longitude]').val(stockist.stockist_longitude);
        $('#formAddUpdateStockist input[name=stockist_latitude]').val(stockist.stockist_latitude);
        $('#modalAddUpdateStockist').modal('show');
    }

    function detailStockist(res) {
        $('#data-stockist-code').html(res.stockist_code);
        $('#data-stockist-email').html(res.stockist_email);
        $('#data-stockist-mobilephone').html(res.stockist_mobilephone);
        $('#data-stockist-name').html(res.stockist_name);
        $('#data-stockist-member').html(res.stockist_member_name);
        $('#data-stockist-address').html(res.stockist_address);
        $('#data-stockist-city').html(res.city_name);
        $('#data-stockist-province').html(res.province_name);
        $('#data-stockist-join-date').html(res.stockist_join_date);
        $('#stockist-location').attr('src', 'https://maps.google.com/maps?q='+res.stockist_longitude+', '+res.stockist_latitude+'&z=15&output=embed');
        
        $('#data-stockist-is-active').removeClass();
        if (res.stockist_is_active == 1) {
            $('#data-stockist-is-active').addClass('text-success').html('Aktif');
        } else {
            $('#data-stockist-is-active').addClass('text-danger').html('Tidak Aktif');
        }

        $('#modal-detail').modal('show');
    }
</script>