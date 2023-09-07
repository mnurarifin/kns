<?php
	include "components/header.php";
	include "components/sidebar.php";
?>

<!-- BEGIN: Content-->
<div class="app-content content">
	<div class="content-overlay"></div>
	<div class="content-wrapper">
		<div class="content-header row">
			<div class="content-header-left col-12 mb-2 mt-1">
				<div class="row breadcrumbs-top">
					<div class="col-12">
						<h5 class="content-header-title float-left pr-1 mb-0">Profile</h5>

            <div class="breadcrumb-wrapper col-12">
							<ol class="breadcrumb p-0 mb-0">
								<li class="breadcrumb-item"><a href="dashboard.html"><i class="bx bx-home-alt"></i></a></li>
								<li class="breadcrumb-item"><a href="#">Jaringan</a></li>
								<li class="breadcrumb-item active">Active Page</li>
							</ol>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="content-body">
      <div class="row justify-content-center">
        <div class="col-md-3 col-12">
          <div class="card">
            <div class="card-body p-2 text-center">
              <div class="row">
                <div class="col-md-12 col-12">
                    <div class="card border p-2 mb-50">
                      <img src="images/profile-pict.png" width="100%">
                    </div>
                </div>

                <div class="col-md-12 col-12">
                  <div class="card border p-2 mb-0">
                    <fieldset class="form-group mb-0">
                      <input type="file" class="form-control-file" id="basicInputFile">
                    </fieldset>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-9 col-12">
          <div class="card">
            <div class="card-body p-2 text-center">
              <div class="row">
                <div class="col-md-12 col-12">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" aria-controls="profile" role="tab" aria-selected="true">
                        <i class="bx bx-user align-middle"></i>
                        <span class="align-middle">Data Profile</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank" aria-controls="bank" role="tab" aria-selected="false">
                        <i class="bx bx-buildings align-middle"></i>
                        <span class="align-middle">Data Bank</span>
                      </a>
                    </li>
                  </ul>
  
                  <div class="tab-content">
                    <div class="tab-pane active" id="profile" aria-labelledby="profile-tab" role="tabpanel">
                      <div class="card mb-1">
                        <form class="form form-profile py-2">
                          <div class="row">
                            <div class="col-md-6 col-12">
                              <div class="d-block mb-1">
                                <h6 class="text-left">Nama Lengkap</h6>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" id="iconLeft1" placeholder="">
                                    <div class="form-control-position">
                                        <i class="bx bx-user"></i>
                                    </div>
                                </fieldset>
                              </div>
                            </div>

                            <div class="col-md-6 col-12">
                              <div class="d-block mb-1">
                                <h6 class="text-left">Email</h6>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" id="iconLeft1" placeholder="">
                                    <div class="form-control-position">
                                        <i class="bx bx-envelope"></i>
                                    </div>
                                </fieldset>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-6 col-12">
                              <div class="d-block mb-1">
                                <h6 class="text-left">Nomor Identitas</h6>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" id="iconLeft1" placeholder="">
                                    <div class="form-control-position">
                                        <i class="bx bx-user-pin"></i>
                                    </div>
                                </fieldset>
                              </div>
                            </div>

                            <div class="col-md-6 col-12">
                              <div class="d-block mb-1">
                                <h6 class="text-left">Tanggal Gabung</h6>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" id="iconLeft1" placeholder="">
                                    <div class="form-control-position">
                                        <i class="bx bx-calendar"></i>
                                    </div>
                                </fieldset>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-6 col-12">
                              <div class="d-block mb-1">
                                <h6 class="text-left">Nomor Handphone</h6>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" id="iconLeft1" placeholder="">
                                    <div class="form-control-position">
                                        <i class="bx bx-mobile"></i>
                                    </div>
                                </fieldset>
                              </div>
                            </div>

                            <div class="col-md-6 col-12">
                              <div class="d-block mb-1">
                                <h6 class="text-left">Jenis Kelamin</h6>
                                <fieldset class="form-group position-relative has-icon-left">
                                  <ul class="list-unstyled mb-0 text-left pt-50">
                                    <li class="d-inline-block mr-2 mb-1">
                                      <fieldset>
                                        <div class="radio">
                                          <input type="radio" name="bsradio" id="radio1" checked="">
                                          <label for="radio1">Laki-laki</label>
                                        </div>
                                      </fieldset>
                                    </li>
                                    <li class="d-inline-block mr-2 mb-1">
                                      <fieldset>
                                        <div class="radio">
                                          <input type="radio" name="bsradio" id="radio2">
                                          <label for="radio2">Perempuan</label>
                                        </div>
                                      </fieldset>
                                    </li>
                                  </ul>
                                </fieldset>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-6 col-12">
                              <div class="d-block mb-1">
                                <h6 class="text-left">Tempat Lahir</h6>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" id="iconLeft1" placeholder="">
                                    <div class="form-control-position">
                                        <i class="bx bx-map"></i>
                                    </div>
                                </fieldset>
                              </div>
                            </div>

                            <div class="col-md-6 col-12">
                              <div class="d-block mb-1">
                                <h6 class="text-left">Tanggal Lahir</h6>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" id="iconLeft1" placeholder="">
                                    <div class="form-control-position">
                                        <i class="bx bx-calendar"></i>
                                    </div>
                                </fieldset>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-6 col-12">
                              <div class="d-block mb-1">
                                <h6 class="text-left">Provinsi</h6>
                                <fieldset class="form-group">
                                  <div class="input-group">
                                    <select class="form-control" id="inputGroupSelect01">
                                      <option selected="">Choose...</option>
                                      <option value="1">One</option>
                                      <option value="2">Two</option>
                                      <option value="3">Three</option>
                                    </select>
                                  </div>
                                </fieldset>
                              </div>
                            </div>

                            <div class="col-md-6 col-12">
                              <div class="d-block mb-1">
                                <h6 class="text-left">Kabupaten/Kota</h6>
                                <fieldset class="form-group">
                                  <div class="input-group">
                                    <select class="form-control" id="inputGroupSelect01">
                                      <option selected="">Choose...</option>
                                      <option value="1">One</option>
                                      <option value="2">Two</option>
                                      <option value="3">Three</option>
                                    </select>
                                  </div>
                                </fieldset>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-12 col-12">
                              <div class="d-block mb-1">
                                <h6 class="text-left">Alamat</h6>
                                <fieldset class="form-group position-relative has-icon-left">
                                  <textarea data-length=20 class="form-control char-textarea" id="textarea-counter" rows="3" placeholder=""></textarea>
                                    <div class="form-control-position">
                                        <i class="bx bx-map-alt"></i>
                                    </div>
                                </fieldset>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-12 col-12 text-left mt-2">
                              <button type="button" class="btn btn-success mr-1 mb-1"><i class="bx bx-edit-alt mr-50"></i>Ubah</button>
                              <button type="button" class="btn btn-success mr-1 mb-1"><i class="bx bx-save mr-50"></i>Simpan</button>
                              <button type="button" class="btn btn-light mr-1 mb-1"><i class="bx bx-x-circle mr-50"></i>Batal</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>

                    <div class="tab-pane" id="bank" aria-labelledby="bank-tab" role="tabpanel">
                      <div class="card mb-1">
                      <form class="form form-profile py-2">
                          <div class="row">
                            <div class="col-md-6 col-12">
                              <div class="d-block mb-1">
                                <h6 class="text-left">Nama Rekening</h6>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" id="iconLeft1" placeholder="">
                                    <div class="form-control-position">
                                        <i class="bx bx-user"></i>
                                    </div>
                                </fieldset>
                              </div>

                              <div class="d-block mb-1">
                                <h6 class="text-left">Nomor Rekening</h6>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" id="iconLeft1" placeholder="">
                                    <div class="form-control-position">
                                        <i class="bx bx-credit-card"></i>
                                    </div>
                                </fieldset>
                              </div>
                            </div>

                            <div class="col-md-6 col-12">
                              <div class="d-block mb-1">
                                <h6 class="text-left">Nama Bank</h6>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" id="iconLeft1" placeholder="">
                                    <div class="form-control-position">
                                        <i class="bx bx-buildings"></i>
                                    </div>
                                </fieldset>
                              </div>

                              <div class="d-block mb-1">
                                <h6 class="text-left">Kota</h6>
                                <fieldset class="form-group">
                                  <div class="input-group">
                                    <select class="form-control" id="inputGroupSelect01">
                                      <option selected="">Choose...</option>
                                      <option value="1">One</option>
                                      <option value="2">Two</option>
                                      <option value="3">Three</option>
                                    </select>
                                  </div>
                                </fieldset>
                              </div>

                              <div class="d-block mb-1">
                                <h6 class="text-left">Kantor Cabang</h6>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" id="iconLeft1" placeholder="">
                                    <div class="form-control-position">
                                        <i class="bx bx-area"></i>
                                    </div>
                                </fieldset>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-12 col-12 text-left mt-2">
                              <button type="button" class="btn btn-success mr-1 mb-1"><i class="bx bx-edit-alt mr-50"></i>Ubah</button>
                              <button type="button" class="btn btn-success mr-1 mb-1"><i class="bx bx-save mr-50"></i>Simpan</button>
                              <button type="button" class="btn btn-light mr-1 mb-1"><i class="bx bx-x-circle mr-50"></i>Batal</button>
                            </div>
                          </div>
                        </form>
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
<!-- END: Content-->

<?php
include "components/footer.php";
?>