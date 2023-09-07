<style>
    .modal-body {
        overflow-y: auto;
    }

    .table-responsive {
        max-height: fit-content;
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
                        <div id="table-member"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modalDetailMember" tabindex="-1" role="dialog" aria-labelledby="modalDetailMember" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-radius: 7px 7px 0 0; border: 1px solid #ccc; background-color: #f5f5f5;">
                <h4 class="modal-title" id="modal-label">Detail Data Mitra (<strong><span id="title-data-member-code"></span></strong>) &nbsp;&nbsp;&nbsp;<span id="data-member-is-active"></span> <span id="data-member-is-suspended"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body" style="overflow-y: scroll;">
                <div class="panel panel-default mb-2">
                    <div class="panel-body p-0">
                        <div class="card rounded-0 mb-1" style="box-shadow: none;">
                            <div class="card-header rounded-0 bg-dark text-white text-center">
                                <i class='bx bxs-user bx-lg bx-border-circle border-primary text-primary'></i>
                                <h5 class="text-primary mt-50 mb-50"><span id="data-member-name" class="text-capitalize"></span></h5>
                                <div class="d-flex align-items-center justify-content-center row">
                                    <div class="col-sm-12 col-md-6 justify-content-center d-flex">
                                        <i class="d-flex align-items-center bx bx-phone text-white mr-50"></i><span id="data-member-mobilephone"></span>
                                    </div>
                                    <div class="col-sm-12 col-md-6 justify-content-center d-flex">
                                        <i class="d-flex align-items-center bx bx-edit text-white mr-50"></i><span id="data-member-join-date">Join Sejak :</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card widget-state-multi-radial" style="box-shadow: none;">
                            <div class="card-header d-sm-flex align-items-center justify-content-between flex-wrap row">
                                <div class="col-xl-3">
                                    <h4 class="card-title mb-1">Biodata Mitra</h4>
                                </div>
                                <div class="col-xl-9">
                                    <ul class="nav nav-tabs border-0 mt-sm-0 mt-50 mb-0 justify-content-around d-flex" role="tablist">
                                        <li class="nav-item mr-0">
                                            <a class="nav-link active d-flex flex-row align-items-center" id="biodata-tab" data-toggle="tab" href="#biodata" aria-controls="biodata" role="tab" aria-selected="true">
                                                <i class="d-flex align-items-center bx bx-user mr-50"></i>Biodata
                                            </a>
                                        </li>
                                        <li class="nav-item mr-0">
                                            <a class="nav-link d-flex flex-row align-items-center" id="network-tab" data-toggle="tab" href="#network" aria-controls="network" role="tab" aria-selected="false">
                                                <i class="d-flex align-items-center bx bx-sitemap mr-50"></i>Info Jaringan
                                            </a>
                                        </li>
                                        <li class="nav-item mr-0">
                                            <a class="nav-link d-flex flex-row align-items-center" id="bank-tab" data-toggle="tab" href="#bank" aria-controls="bank" role="tab" aria-selected="false">
                                                <i class="d-flex align-items-center bx bxs-bank mr-50"></i>Info Bank
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card-body py-1">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="biodata" aria-labelledby="biodata-tab" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ul class="list-group list-group-flush d-flex flex-md-row flex-sm-column">
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
                                                                <small class="text-muted d-block">Tempat / Tanggal Lahir</small>
                                                                <span class="list-title"><span id="data-member-birth-place"></span>, <span id="data-member-birth-date"></span></span>
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
                                                                <small class="text-muted d-block">Jenis / No. Identitas</small>
                                                                <span class="list-title"><span id="data-member-identity-type" class="d-block"></span><span id="data-member-identity-number"></span></span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="col-md-12">
                                                <ul class="list-group list-group-flush d-flex flex-md-row flex-sm-column">
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
                                                                    <span id="data-member-address" class="d-block"></span>
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
                                                                    <span id="data-member-city" class="d-block"></span>
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
                                                                <small class="text-muted d-block">Negara</small>
                                                                <div class="list-title">
                                                                    <span id="data-member-country" class="d-block"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="network" aria-labelledby="network-tab" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-12">
                                                <div class="card mb-0">
                                                    <div class="card-header py-75 d-flex justify-content-between align-items-center">
                                                        <div class="card-title-content">
                                                            <h4 class="card-title">Username</h4>
                                                        </div>
                                                    </div>

                                                    <div class="card-body pt-0" style="position: relative;">
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <h3 class="text-primary"><span id="data-member-code"></span></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-12 mt-2">
                                                <div class="d-flex align-items-center">
                                                    <i class="bx bx-calendar-event icon-light font-medium-5 mr-50"></i>
                                                    <div class="sales-info-content">
                                                        <h6 class="mb-0">Tgl. Bergabung</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 col-sm-12 my-2">
                                                <span id="data-member-join-datetime" class="h6 mb-0"></span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="card border mb-50">
                                                    <div class="card-header p-1 d-flex justify-content-start align-items-center">
                                                        <i class="bx bx-user-voice bx-md bx-border-circle text-muted font-weight-light mr-2"></i>
                                                        <div class="card-title-content">
                                                            <h4 class="card-title text-dark mb-50">Sponsor</h4>
                                                            <span id="data-member-sponsor-code" class="d-block text-primary font-weight-bolder"></span>
                                                            <span id="data-member-sponsor-name" class="d-block text-muted"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card border mb-50">
                                                    <div class="card-header p-1 d-flex justify-content-start align-items-center">
                                                        <i class="bx bx-user-plus bx-md bx-border-circle text-muted font-weight-light mr-2"></i>
                                                        <div class="card-title-content">
                                                            <h4 class="card-title text-dark mb-50">Upline</h4>
                                                            <span id="data-member-upline-code" class="d-block text-primary font-weight-bolder"></span>
                                                            <span id="data-member-upline-name" class="d-block text-muted"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card border mb-50">
                                                    <div class="card-header p-1 d-flex justify-content-start align-items-center">
                                                        <i class="bx bx-user-pin bx-md bx-border-circle text-muted font-weight-light mr-2"></i>
                                                        <div class="card-title-content">
                                                            <h4 class="card-title text-dark mb-50">Posisi</h4>
                                                            <span id="data-member-position" class="d-block text-primary font-weight-bolder"></span>
                                                            <span id="data-member-position-name" class="d-block text-muted"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="bank" aria-labelledby="bank-tab" role="tabpanel">
                                        <div class="row justify-content-center">
                                            <div class="col-md-8">
                                                <div class="card border mb-50">
                                                    <div class="card-header p-1 d-flex justify-content-start align-items-center">
                                                        <i class="bx bx-receipt bx-lg bx-border-circle text-muted font-weight-light mr-2"></i>
                                                        <div class="card-title-content">
                                                            <h4 class="card-title text-dark mb-50">Rekening (<span id="data-member-bank-name" class="text-primary font-weight-bolder"></span>)</h4>
                                                            <h5 id="data-member-bank-account-number" class="d-block text-primary mb-0"></h5>
                                                            <span id="data-member-bank-account-name" class="d-block text-muted"></span>
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

<div class="modal fade" id="modalUpdateMember" tabindex="-1" role="dialog" aria-labelledby="modalUpdateMember" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUpdateTitle">Form Ubah <?= isset($title) ? $title : '' ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form form-horizontal" id="formUpdate">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <strong>DATA PROFIL</strong>
                            </div>
                        </div>
                        <div id="response-error-edit"></div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Gambar Profile</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="file" name="member_image" accept="image/*" value="" size="8" class="form-control d-none" />
                                <img src="" id="member_image" style="width: 100px;" />
                                <button class="btn btn-primary" id="upload_image">Upload</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Username</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="network_slug" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nama lengkap</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="member_name" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Jenis Kelamin</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="member_gender" class="form-control" style="float:left; width:50%;">
                                    <option value="Laki-laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Tempat / Tgl lahir</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="member_birth_place" value="" size="25" class="form-control" style="float:left; width:40%;" />
                                <input type="date" name="member_birth_date" value="" size="10" class="form-control" id="birth_date" style="float:right; width:59%;" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Jenis / No. Identitas</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="member_identity_type" class="form-control" style="float:left; width:40%;">
                                    <option value="" selected="selected">Jenis Identitas</option>
                                    <option value="KTP">KTP</option>
                                    <!-- <option value="SIM">SIM</option> -->
                                    <option value="PASPOR">Paspor</option>
                                </select>
                                <input type="text" name="member_identity_no" value="" size="25" class="form-control" style="float:right; width:59%;" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>No. Handphone</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="member_mobilephone" value="" size="20" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>E-mail</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="member_email" value="" size="40" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Alamat</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <textarea name="member_address" cols="40" rows="10" cols="50" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Provinsi</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="member_province_id" id="select-province" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Kota / Kabupaten</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="member_city_id" id="select-city" class="form-control">
                                </select>
                            </div>
                        </div>
                        <!-- <div class="row d-none">
                            <div class="col-md-4">
                                <label>Kode Pos</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="detail_zipcode" value="" size="8" class="form-control" />
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nama Ibu Kandung</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="member_mother_name" value="" size="20" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nama Ahli Waris</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="member_devisor_name" value="" size="20" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Hubungan Ahli Waris</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="member_devisor_relation" value="" size="20" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>No. Handphone Ahli Waris</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="member_devisor_mobilephone" value="" size="20" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Pekerjaan</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="member_job" value="" size="20" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <strong>DATA BANK</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nama Bank</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="member_bank_id" class="form-control" id="select-bank">
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Kota Bank</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="member_bank_city" value="" size="30" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Cabang Bank</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="member_bank_branch" value="" size="30" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nama Rekening</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="member_bank_account_name" value="" size="30" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>No. Rekening</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" name="member_bank_account_no" value="" size="30" class="form-control" />
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

<div class="modal fade" id="modalUpdatePassword" tabindex="-1" role="dialog" aria-labelledby="modalUpdatePassword" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUpdatePasswordTitle">Form Update Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form class="form form-horizontal" id="formUpdatePassword">
                <div class="modal-body">
                    <div id="response-error-password"></div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Password Baru</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="password" id="inputPass" class="form-control" name="password_new" placeholder="Password Baru">
                                <small class="text-danger password_new"></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Ulangi Password Baru </label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="password" id="inputPassConfirm" class="form-control" name="password_conf" placeholder="Password Baru">
                                <small class="text-danger password_conf"></small>
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

<div class="modal fade" id="modalShowImage" tabindex="-1" role="dialog" aria-labelledby="modalShowImage" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalShowImageTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body text-center" id="image-body"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        let dataMember = {
            limit: 5,
            selectedID: 0,
        }

        let stateMember = {
            baseUrl: window.location.origin,
            updateUrl: '/admin/service/member/actEditProfil',
            updatePasswordUrl: '/admin/service/member/actEditPassword',
            selectedID: '',
        }

        $("#table-member").bind("DOMSubtreeModified", function() {
            if ($("#table-member").height() > 60) {
                $("#pageLoader").hide()
            }
        })

        $("#table-member").dataTableLib({
            url: window.location.origin + '/admin/service/member/getDataMember',
            selectID: 'member_id',
            colModel: [{
                    display: 'Login',
                    name: 'login',
                    sortAble: false,
                    align: 'center',
                    width: "55px",
                    action: {
                        function: 'login',
                        icon: 'btn-outline-info rounded bx bx-log-in'
                    }
                },
                {
                    display: 'Aksi',
                    name: 'login',
                    sortAble: false,
                    align: 'center',
                    width: '130px',
                    render: (params, args) => {
                        return `
                        <span class="cstmHover px-25" onclick='detailMember(${JSON.stringify(args)})' title="Detail" data-toggle="tooltip"><i class="bx bx-book info"></i></span>
                        <span class="cstmHover" onclick='updateDataMember(${JSON.stringify(args)})' title="Ubah" data-toggle="tooltip"><i class="bx bx-edit-alt warning"></i></span>
                        <span class="px-25 cstmHover" onclick='updatePassword(${JSON.stringify(args)})' title="Ubah Password" data-toggle="tooltip"><i class="bx bxs-key primary"></i></span>`
                        // <span class="cstmHover" onclick='showImage(${JSON.stringify(args)})' title="Foto KTP" data-toggle="tooltip"><i class="bx bx-show info"></i></span>
                    }
                },
                {
                    display: 'Username',
                    name: 'network_slug',
                    sortAble: false,
                    align: 'center',
                    render: (params) => {
                        return params ? params : '-'
                    },
                    export: true
                },
                {
                    display: 'Kode Mitra',
                    name: 'network_code',
                    sortAble: false,
                    align: 'center',
                    render: (params) => {
                        return params ? params : '-'
                    },
                    export: true
                },
                {
                    display: 'Nama Mitra',
                    name: 'member_name',
                    sortAble: false,
                    align: 'left',
                    export: true
                },
                {
                    display: 'Tanggal Aktivasi',
                    name: 'network_activation_datetime',
                    sortAble: false,
                    align: 'center',
                    export: true
                },
                {
                    display: 'Upline',
                    name: 'upline_member_account_username',
                    sortAble: false,
                    align: 'center',
                    render: (params) => {
                        return params ? params : '-'
                    },
                    export: true
                },
                {
                    display: 'Sponsor',
                    name: 'sponsor_member_account_username',
                    sortAble: false,
                    align: 'center',
                    render: (params) => {
                        return params ? params : '-'
                    },
                    export: true
                },
                {
                    display: 'Tempat Order',
                    name: 'seller',
                    sortAble: false,
                    align: 'center',
                    render: (params) => {
                        return params ? params : '-'
                    },
                    export: true
                },
                {
                    display: 'Status',
                    name: 'network_is_active',
                    sortAble: false,
                    align: 'center',
                    render: (params) => {
                        let status
                        switch (params) {
                            case '1':
                                status = '<span class="badge badge-light-success badge-pill badge-round" title="Aktif" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>'
                                break
                            case '2':
                                status = '<span class="badge badge-light-danger badge-pill badge-round" title="Diblokir" data-toggle="tooltip"><i class="bx bx-x-circle"></i></span>'
                                break
                            default:
                                status = '<span class="badge badge-light-danger badge-pill badge-round" title="Non Aktif" data-toggle="tooltip"><i class="bx bx-bulb"></i></span>'
                                break
                        }
                        return status
                    },
                    export: true
                },
            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Mitra',
            searchItems: [{
                    display: 'Username',
                    name: 'network_slug',
                    type: 'text'
                }, {
                    display: 'Kode Mitra',
                    name: 'member_account_username',
                    type: 'text'
                },
                {
                    display: 'Nama Mitra',
                    name: 'member_name',
                    type: 'text'
                },
                {
                    display: 'Tanggal Gabung',
                    name: 'network_activation_datetime',
                    type: 'date'
                },
                {
                    display: 'Status',
                    name: 'member_status',
                    type: 'select',
                    option: [{
                        title: 'Aktif',
                        value: '1'
                    }, {
                        title: 'Tidak Aktif',
                        value: '0'
                    }, {
                        title: 'Diblokir',
                        value: '2'
                    }]
                },
            ],
            buttonAction: [{
                    display: 'Aktifkan',
                    icon: 'bx bxs-bulb',
                    style: "success",
                    action: "active",
                    url: window.location.origin + "/admin/service/member/activeMember"
                },
                {
                    display: 'Non Aktifkan',
                    icon: 'bx bx-bulb',
                    style: "warning",
                    action: "nonactive",
                    url: window.location.origin + "/admin/service/member/deactiveMember"
                },
                {
                    display: 'Blokir',
                    icon: 'bx bx-x-circle',
                    message: "Blokir",
                    style: "danger",
                    action: "accept",
                    url: window.location.origin + "/admin/service/member/suspendMember"
                },
                {
                    display: 'Buka Blokir',
                    icon: 'bx bx-check-circle',
                    message: "Buka Blokir",
                    style: "success",
                    action: "reject",
                    url: window.location.origin + "/admin/service/member/unsuspendMember"
                },
                {
                    display: 'Export Excel',
                    icon: 'bx bxs-file',
                    style: 'info',
                    action: 'exportExcel',
                    url: window.location.origin + "/admin/member/excel"
                },
            ],
            sortName: "member_id",
            sortOrder: "DESC",
            tableIsResponsive: true,
            select: true,
            multiSelect: true,
        })

        $('#select-province').on('change', (event) => {
            getCityByProvince(event.target.value)
        })

        getDataForm = () => {
            $.ajax({
                url: "<?php echo site_url('admin/service/member/getDataForm') ?>",
                type: "GET",
                content_type: "application/json",
                success: function(res) {
                    let html = ""
                    let arrData = []

                    if (res.status == 200) {
                        let arrDataBank = res.data.bank_options
                        let htmlBank = ""
                        htmlBank += `<option value="">N/A</option>`
                        arrDataBank.forEach((item, index) => {
                            htmlBank += `<option value="${item.bank_id}">${item.bank_name}</option>`
                        })
                        $('#select-bank').html(htmlBank)
                    }
                }
            })
        }
        getDataForm()

        getProvince = () => {
            $.ajax({
                url: "<?php echo site_url('admin/service/member/getProvince') ?>",
                type: 'GET',
                "content_type": 'application/json',
                success: function(res) {
                    let html = ``
                    if (res.status == 200) {
                        let data = res.data.results

                        data.forEach((item, index) => {
                            html += `<option value="${item.province_id}">${item.province_name}</option>`
                        })
                        $('#select-province').html(html)

                    }

                }
            })
        }
        getProvince()

        getCityByProvince = (province_id, select = 0) => {
            $.ajax({
                url: "<?php echo site_url('admin/service/member/getCityByProvince/') ?>" + province_id,
                type: 'GET',
                content_type: 'application/json',
                success: function(res) {
                    let html = ``
                    if (res.status == 200) {
                        let data = res.data.results

                        data.forEach((item, index) => {
                            html += `<option value="${item.city_id}">${item.city_name}</option>`
                        })
                        $('#select-city').html(html)

                        if (select > 0) {
                            $('#formUpdate select[name=member_city_id]').val(select)
                        }
                    }
                }
            })
        }

        detailMember = (member) => {
            $('#data-member-image').prop('src', member.member_image_url)
            $('#data-member-gender').html(member.member_gender)
            $('#data-member-mother').html(member.member_mother_name)
            $('#data-member-devisor').html(member.member_devisor_name)
            $('#data-member-devisor-relation').html(member.member_devisor_relation)
            $('#data-member-birth-place').html(member.member_birth_place)
            $('#data-member-birth-date').html(member.member_birth_date)
            $('#data-member-mobilephone').html(member.member_mobilephone)
            $('#data-member-identity-type').html(member.member_identity_type)
            $('#data-member-identity-number').html(member.member_identity_no)
            $('#data-member-email').html(member.member_email)
            $('#data-member-address').html(member.member_address)
            $('#data-member-city').html(member.city_name)
            $('#data-member-country').html(member.member_country)
            $('#data-member-code').html(member.member_account_username)
            $('#data-member-rank').html(member.network_serial_type_name)
            $('#data-member-join-date').html(member.member_join_datetime)
            $('#data-member-join-datetime').html(member.member_join_datetime)
            $('#data-member-sponsor-code').html(member.sponsor_member_account_username)
            $('#data-member-sponsor-name').html(member.network_sponsor_member_name ? member.network_sponsor_member_name : '-')
            $('#data-member-upline-code').html(member.upline_member_account_username)
            $('#data-member-upline-name').html(member.network_upline_member_name ? member.network_upline_member_name : '-')
            $('#data-member-position').html(member.network_position)
            $('#data-member-position-name').html((member.network_position == 'R') ? 'Kanan' : (member.network_position == 'L') ? 'Kiri' : 'ANDA')
            $('#data-member-name').html(`${member.member_name} (${member.member_account_username})`)
            $('#data-member-bank-name').html(member.member_bank_name)
            $('#data-member-bank-city').html('Kota ' + member.member_bank_city)
            $('#data-member-bank-branch').html('Cabang ' + member.member_bank_branch)
            $('#data-member-bank-account-number').html(member.member_bank_account_no)
            $('#data-member-bank-account-name').html(member.member_bank_account_name)
            $('#data-member-tax-no').html(member.member_tax_no)

            // $('#data-member-is-active').removeClass()
            // $('#data-member-is-active').html('')

            // $('#data-member-is-suspended').removeClass()
            // $('#data-member-is-suspended').html('')

            if (member.network_is_suspended == 1) {
                $('#data-member-is-suspended').addClass('label label-danger').html('Member Terblokir')
            }

            $('#title-data-member-code').html(member.member_account_username)
            $('#modalDetailMember').modal('show')
        }

        updateDataMember = (member) => {
            $('#response-message').html('')

            $('#formUpdate select[name=member_gender] option').removeAttr('selected')
            $('#formUpdate input[name=member_name]').val(member.member_name)
            $('#formUpdate input[name=network_slug]').val(member.network_slug)
            $('#formUpdate select[name=member_province_id]').val(member.member_province_id)

            stateMember.selectedID = member.member_id
            stateMember.formAction = 'update'

            if (member.province_id !== 0) {
                getCityByProvince(member.member_province_id, member.member_city_id)
            }

            $('#formUpdate select[name=member_gender] option[value="' + member.member_gender + '"]').attr('selected', 'selected')
            $('#formUpdate select[name=member_identity_type] option[value="' + member.member_identity_type + '"]').attr('selected', 'selected')
            $('#formUpdate input[name=member_birth_place]').val(member.member_birth_place)
            $('#formUpdate input[name=member_birth_date]').val(member.member_birth_date)
            $('#formUpdate input[name=member_identity_no]').val(member.member_identity_no)
            $('#formUpdate input[name=member_mobilephone]').val(member.member_mobilephone)
            $('#formUpdate input[name=member_email]').val(member.member_email)
            $('#formUpdate textarea[name=member_address]').val(member.member_address)
            $('#formUpdate input[name=detail_zipcode]').val(member.member_zipcode)
            $('#formUpdate input[name=memberImage]').val('')
            $('#formUpdate input[name=member_mother_name]').val(member.member_mother_name)
            $('#formUpdate input[name=member_devisor_name]').val(member.member_devisor_name)
            $('#formUpdate input[name=member_devisor_relation]').val(member.member_devisor_relation)
            $('#formUpdate input[name=member_devisor_mobilephone]').val(member.member_devisor_mobilephone)
            $('#formUpdate input[name=member_job]').val(member.member_job)
            $('#formUpdate select[name=city_id]').val(member.member_city_id)
            $('#formUpdate select[name=member_bank_id]').val(member.member_bank_id)
            $('#formUpdate input[name=member_bank_city]').val(member.member_bank_city)
            $('#formUpdate input[name=member_bank_branch]').val(member.member_bank_branch)
            $('#formUpdate input[name=member_bank_account_name]').val(member.member_bank_account_name)
            $('#formUpdate input[name=member_bank_account_no]').val(member.member_bank_account_no)
            $('#member_image').prop('src', member.member_image_url)

            $('#modalUpdateTitle').text('Form Ubah Member')
            $('#modalUpdateMember').modal('show')
        }

        updatePassword = (member) => {
            $('#response-message').html('')
            $('#response-error-password').html('')
            $('#response-error-password').removeClass('alert alert-danger')
            $('#modalUpdatePasswordTitle').text('Form Ubah Password Member')
            stateMember.selectID = member.member_id
            stateMember.formAction = 'updatePassword'
            $('#modalUpdatePassword').modal('show')
        }

        // resetPinMember = (member) => {
        //     Swal.fire({
        //         title: 'Perhatian!',
        //         text: "Apakah anda yakin akan melakukan reset?",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Ok',
        //         cancelButtonText: 'Batal'
        //     }).then((result) => {
        //         if (result.value) {
        //             $.ajax({
        //                 url: "<?php echo site_url('admin/service/member/resetPinMember') ?>",
        //                 type: 'POST',
        //                 data: {
        //                     member_id: member.member_id
        //                 },
        //                 success: function(res) {
        //                     if (res.status == 200) {
        //                         $('#response-message').html(res.message)
        //                         $('#response-message').addClass('alert alert-success')
        //                     } else {
        //                         $('#response-error-edit').html(response.data.message)
        //                         $('#response-error-edit').addClass('alert alert-danger')
        //                     }

        //                     setTimeout(function() {
        //                         $('#response-message').html('')
        //                         $('#response-message').removeClass()
        //                         $('#response-error-edit').html('')
        //                         $('#response-error-edit').removeClass()
        //                     }, 2000)
        //                 }
        //             })
        //         }
        //     })
        // }

        login = (member) => {
            var formData = new FormData()
            formData.append('member_id', member.member_id)
            $.ajax({
                url: window.location.origin + '/admin/service/member/getTokenLogin',
                data: formData,
                method: 'POST',
                processData: false,
                contentType: false,
            }).done(function(res) {
                let link = `<?= BASEURL ?>/${res.data}`
                if (res.message == "OK") {
                    window.open(link)
                } else {
                    $('#response-message').addClass('alert alert-danger')
                    $('#response-message').html(res.data.message)
                }
                setTimeout(function() {
                    $('#response-message').html('')
                    $('#response-message').removeClass()
                }, 2000)
            }).fail(function(err) {
                $('#response-message').addClass('alert alert-danger')
                $('#response-message').html(err.responseJSON.message)

                setTimeout(function() {
                    $('#response-message').html('')
                    $('#response-message').removeClass()
                }, 2000)
            })
        }

        $('#submit').on('click', (e) => {
            e.preventDefault()
            $('#response-message').html('')
            $('#submit').prop('disabled', true)
            $('#submit').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')

            let myForm = document.getElementById('formUpdate')
            let formData = new FormData(myForm)
            let url = stateMember.baseUrl + stateMember.updateUrl
            if ($('#formUpdate input[name=member_image]').val() == '') {
                formData.delete('member_image')
            }
            formData.append('id', stateMember.selectedID)

            $.ajax({
                url: url,
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#submit').prop('disabled', false)
                    $('#submit').html('Simpan')
                    if (response.message == 'OK') {
                        $('#modalUpdateMember').modal('hide')
                        $('#response-message').html(response.data.message)
                        $('#response-message').addClass('alert alert-success')
                    } else {
                        $('#modalUpdateMember').modal('hide')
                        $('#response-error-edit').html(response.data.message)
                        $('#response-error-edit').addClass('alert alert-danger')
                    }
                    setTimeout(function() {
                        $('#response-message').html('')
                        $('#response-message').removeClass()
                        $('#response-error-edit').html('')
                        $('#response-error-edit').removeClass()
                    }, 2000)
                    $.refreshTable('table-member')
                },
                error: function(err) {
                    $('#submit').prop('disabled', false)
                    $('#submit').html('Simpan')
                    let response = err.responseJSON
                    if (response.message == "validationError") {
                        let message = '<ul>'
                        for (let key in response.data.validationMessage) {
                            message += `<li>${response.data.validationMessage[key]}</li>`
                        }
                        message += '</ul>'
                        $('#response-error-edit').html(`
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
                        `)
                    } else if (response.message == 'Unauthorized' && response.status == 403) {
                        location.reload();
                    }
                }
            });
        })

        $('#formUpdatePassword').submit(function(event) {
            event.preventDefault()

            const _pass = $('#inputPass').val()
            const _confirm = $('#inputPassConfirm').val()
            $('#response-error-password').html('')
            $('#response-error-password').removeClass('alert alert-danger')

            if (_pass == '') {
                let mesg = `Password Tidak boleh Kosong!`
                $('.password_new').text(mesg)
                $('#inputPassConfirm').val('')
                setTimeout(function() {
                    $('#inputPass').focus()
                }, 100)
            } else if (_confirm == '') {
                let mesg = `Masukkan kembali password anda untuk konfirmasi.`
                $('.password_conf').text(mesg)
                $('.password_new').text('')
                setTimeout(function() {
                    $('#inputPassConfirm').focus()
                }, 100)
            } else {
                let message_confirm = 'Apakah anda yakin untuk mengubah password?'
                Swal.fire({
                    title: 'Perhatian!',
                    text: "Anda yakin akan mengubah password mitra ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ok',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
                        let myForm = document.getElementById('formUpdatePassword')
                        let formData = new FormData(myForm)
                        let url = stateMember.baseUrl + stateMember.updatePasswordUrl

                        formData.append('member_id', stateMember.selectID)
                        $.ajax({
                            url: url,
                            data: formData,
                            method: 'POST',
                            processData: false,
                            contentType: false,
                            beforeSend: () => {
                                $('#message-alert').slideUp()
                                $(this).find('button[type=submit]').attr('disabled', 'disabled')
                                $(this).find('button[type=submit]').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')
                            }
                        }).done(function(res) {
                            let arrData = []
                            if (res.message == "OK") {
                                $('#formUpdatePassword').get(0).reset()
                                $('#response-message').addClass('alert alert-success')
                                $('#response-message').html(res.data.message)
                                $('#modalUpdatePassword').modal('hide')
                            } else {
                                let errMsg = "<ul>"
                                $.each(res.data.message, function(key, val) {
                                    errMsg += `<li>${val}</li>`
                                })
                                errMsg += "</ul>"
                                $('#response-error-password').html(errMsg)
                                $('#response-error-password').addClass('alert alert-danger')
                            }
                            setTimeout(function() {
                                $('#response-message').html('')
                                $('#response-message').removeClass()
                                /*$('#response-error-password').removeClass()
                                $('#response-error-password').html('')*/
                            }, 3000)
                        }).fail(function(err) {
                            res = err.responseJSON
                            if (res.message == "validationError") {
                                $.each(res.data.validationMessage, function(key, val) {
                                    $('.' + key).text(val)
                                })
                            } else {
                                $('#response-error-password').html(res.message)
                                $('#response-error-password').addClass('alert alert-danger')
                            }
                        }).always(() => {
                            $(this).find('button[type=submit]').removeAttr('disabled')
                            $(this).find('button[type=submit]').html('Simpan')
                        })
                    } else {
                        $('#response-error-password').html('')
                        $('#response-error-password').removeClass('alert alert-danger')
                    }
                })
            }
        })

        $('#upload_image').on("click", (e) => {
            e.preventDefault()
            $('#formUpdate input[name=member_image]').trigger('click');
        })

        $('#formUpdate input[name=member_image]').on('change', (ev) => {
            const [file] = ev.target.files
            if (file) {
                $('#member_image').prop('src', URL.createObjectURL(file))
            }
        })
    })

    function showImage(params) {
        $('#modalShowImage').modal('show');
        $('#modalShowImageTitle').html('FOTO KTP')
        $('#image-body').html('')
        var htmlBody = '';

        htmlBody = `<img src="${params.member_identity_image_url}" height="400px" width="450px">`
        $('#image-body').html(htmlBody)
    }
</script>