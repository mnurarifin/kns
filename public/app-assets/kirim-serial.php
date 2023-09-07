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
						<h5 class="content-header-title float-left pr-1 mb-0">Kirim Serial</h5>

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

        <div class="col-md-12 col-12">
          <div class="card">
            <div class="card-body p-2 text-center">
              <div class="row">
                <div class="col-md-12 col-12">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="serialreg-tab" data-toggle="tab" href="#serialreg" aria-controls="serialreg" role="tab" aria-selected="true">
                        <i class="bx bx-layer align-middle"></i>
                        <span class="align-middle">Serial Registrasi</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="serialupgr-tab" data-toggle="tab" href="#serialupgr" aria-controls="serialupgr" role="tab" aria-selected="false">
                        <i class="bx bx-layer align-middle"></i>
                        <span class="align-middle">Serial Upgrade</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="serialro-tab" data-toggle="tab" href="#serialro" aria-controls="serialro" role="tab" aria-selected="false">
                        <i class="bx bx-layer align-middle"></i>
                        <span class="align-middle">Serial RO</span>
                      </a>
                    </li>
                  </ul>
  
                  <div class="tab-content">
                    <div class="tab-pane active" id="serialreg" aria-labelledby="serialreg-tab" role="tabpanel">
                      <div class="card border-info mb-3">
                        <div class="card-body p-2 text-center">
                          <div class="row justify-content-start">
                            <div class="col-8 d-flex justify-content-start">
                              <form class="form-inline">
                                <div class="form-group">
                                  <label for="nohpmitra">Masukkan No. HP Calon Mitra</label>
                                </div>
                                <div class="form-group ml-sm-1 mr-sm-50">
                                  <fieldset class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" id="nohpmitra" placeholder="No. HP Calon Mitra" style="width: 300px;">
                                    <div class="form-control-position">
                                        <i class='bx bx-mobile'></i>
                                    </div>
                                  </fieldset>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="card mb-1">
                        <div class="row">
                          <?php for ($i = 1; $i <= 12; $i++) { ?>
                          <div class="col-md-4 col-12">
                            <div class="card border-light p-1 my-50">
                              <div class="row">
                                <div class="col col-3 d-flex align-center justify-content-start">
                                  <fieldset>
                                    <div class="custom-control custom-control-large custom-checkbox">
                                      <input type="checkbox" class="custom-control-input" name="customCheck" id="checkSerial-<?= $i ?>">
                                      <label class="custom-control-label" for="checkSerial-<?= $i ?>"></label>
                                    </div>
                                  </fieldset>
                                </div>

                                <div class="col col-9">
                                  <div class="row">
                                    <div class="py-25 text-left col col-12 d-flex flex-row align-center justify-content-between">
                                      <div class="font-size-10 cl-grey pb-25">Serial</div>
                                      <div class="font-size-16 cl-primary font-weight-bold">BAS1000421</div>
                                    </div>
                                    <div class="py-25 text-left col col-12 d-flex flex-row align-center justify-content-between">
                                      <div class="font-size-10 cl-grey pb-25">Tipe</div>
                                      <div class="font-size-12 dark font-weight-bold">Registrasi</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            
                            </div>
                          </div>
                          <?php } ?>
                        </div>

                        <div class="row align-center mt-2">
                          <div class="col-md-6 col-12 text-left">
                            <strong>2</strong> Serial Registrasi Dipilih
                          </div>
                          <div class="col-md-6 col-12 text-right">
                            <button type="submit" class="btn btn-success">
                              <div class="d-flex align-center">
                                <i class="bx bx-right-arrow-alt align-middle mr-50" style="top: 0;"></i>
                                <span class="align-middle font-weight-bold">Kirim</span>
                              </div>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane" id="serialupgr" aria-labelledby="serialupgr-tab" role="tabpanel">
                      <div class="card border-info mb-3">
                        <div class="card-body p-2 text-center">
                          <div class="row justify-content-start">
                            <div class="col-8 d-flex justify-content-start">
                              <form class="form-inline">
                                <div class="form-group">
                                  <label for="nohpmitra">Masukkan No. HP Calon Mitra</label>
                                </div>
                                <div class="form-group ml-sm-1 mr-sm-50">
                                  <fieldset class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" id="nohpmitra" placeholder="No. HP Calon Mitra" style="width: 300px;">
                                    <div class="form-control-position">
                                        <i class='bx bx-mobile'></i>
                                    </div>
                                  </fieldset>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="card mb-1">
                        <div class="row">
                          <?php for ($i = 1; $i <= 12; $i++) { ?>
                          <div class="col-md-4 col-12">
                            <div class="card border-light p-1 my-50">
                              <div class="row">
                                <div class="col col-3 d-flex align-center justify-content-start">
                                  <fieldset>
                                    <div class="custom-control custom-control-large custom-checkbox">
                                      <input type="checkbox" class="custom-control-input" name="customCheck" id="checkSerialup-<?= $i ?>">
                                      <label class="custom-control-label" for="checkSerialup-<?= $i ?>"></label>
                                    </div>
                                  </fieldset>
                                </div>

                                <div class="col col-9">
                                  <div class="row">
                                    <div class="py-25 text-left col col-12 d-flex flex-row align-center justify-content-between">
                                      <div class="font-size-10 cl-grey pb-25">Serial</div>
                                      <div class="font-size-16 cl-primary font-weight-bold">BAS1000421</div>
                                    </div>
                                    <div class="py-25 text-left col col-12 d-flex flex-row align-center justify-content-between">
                                      <div class="font-size-10 cl-grey pb-25">Tipe</div>
                                      <div class="font-size-12 dark font-weight-bold">Upgrade</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            
                            </div>
                          </div>
                          <?php } ?>
                        </div>

                        <div class="row align-center mt-2">
                          <div class="col-md-6 col-12 text-left">
                            <strong>2</strong> Serial Upgrade Dipilih
                          </div>
                          <div class="col-md-6 col-12 text-right">
                            <button type="submit" class="btn btn-success">
                              <div class="d-flex align-center">
                                <i class="bx bx-right-arrow-alt align-middle mr-50" style="top: 0;"></i>
                                <span class="align-middle font-weight-bold">Kirim</span>
                              </div>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane" id="serialro" aria-labelledby="serialro-tab" role="tabpanel">
                      <div class="card border-info mb-3">
                        <div class="card-body p-2 text-center">
                          <div class="row justify-content-start">
                            <div class="col-8 d-flex justify-content-start">
                              <form class="form-inline">
                                <div class="form-group">
                                  <label for="nohpmitra">Masukkan No. HP Calon Mitra</label>
                                </div>
                                <div class="form-group ml-sm-1 mr-sm-50">
                                  <fieldset class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" id="nohpmitra" placeholder="No. HP Calon Mitra" style="width: 300px;">
                                    <div class="form-control-position">
                                        <i class='bx bx-mobile'></i>
                                    </div>
                                  </fieldset>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="card mb-1">
                        <div class="row">
                          <?php for ($i = 1; $i <= 12; $i++) { ?>
                          <div class="col-md-4 col-12">
                            <div class="card border-light p-1 my-50">
                              <div class="row">
                                <div class="col col-3 d-flex align-center justify-content-start">
                                  <fieldset>
                                    <div class="custom-control custom-control-large custom-checkbox">
                                      <input type="checkbox" class="custom-control-input" name="customCheck" id="checkSerialro-<?= $i ?>">
                                      <label class="custom-control-label" for="checkSerialro-<?= $i ?>"></label>
                                    </div>
                                  </fieldset>
                                </div>

                                <div class="col col-9">
                                  <div class="row">
                                    <div class="py-25 text-left col col-12 d-flex flex-row align-center justify-content-between">
                                      <div class="font-size-10 cl-grey pb-25">Serial</div>
                                      <div class="font-size-16 cl-primary font-weight-bold">BAS1000421</div>
                                    </div>
                                    <div class="py-25 text-left col col-12 d-flex flex-row align-center justify-content-between">
                                      <div class="font-size-10 cl-grey pb-25">Tipe</div>
                                      <div class="font-size-12 dark font-weight-bold">RO</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            
                            </div>
                          </div>
                          <?php } ?>
                        </div>

                        <div class="row align-center mt-2">
                          <div class="col-md-6 col-12 text-left">
                            <strong>2</strong> Serial RO Dipilih
                          </div>
                          <div class="col-md-6 col-12 text-right">
                            <button type="submit" class="btn btn-success">
                              <div class="d-flex align-center">
                                <i class="bx bx-right-arrow-alt align-middle mr-50" style="top: 0;"></i>
                                <span class="align-middle font-weight-bold">Kirim</span>
                              </div>
                            </button>
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
  </div>
</div>
<!-- END: Content-->

<!-- Modal -->
<div class="modal fade text-left" id="default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel1">Pencarian Serial</h3>
				<button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
					<i class="bx bx-x"></i>
				</button>
			</div>
			<div class="modal-body">
				<form class="form form-vertical">
					<div class="row">
						<div class="col-12">
							<input type="text" id="first-name-vertical" class="form-control" name="fname" placeholder="Kode Serial">
						</div>
						<div class="col-12 pt-1">
              <select class="form-control" id="selectStatus" placeholder="Status Serial">
                <option>- Pilih Status Serial -</option>
                <option value="1">Tersedia</option>
                <option value="0">Telah Digunakan</option>
              </select>
						</div>
						<div class="col-12 pt-1">
              <fieldset class="form-group position-relative has-icon-left">
                <input type="text" class="form-control pickadate-months-year" placeholder="Tanggal Pembelian">
                <div class="form-control-position">
                    <i class='bx bx-calendar'></i>
                </div>
              </fieldset>
						</div>
            <div class="col-12 pt-1">
              <fieldset class="form-group position-relative has-icon-left">
                <input type="text" class="form-control pickadate-months-year" placeholder="Tanggal Digunakan">
                <div class="form-control-position">
                    <i class='bx bx-calendar'></i>
                </div>
              </fieldset>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light-secondary" data-dismiss="modal">
					<i class="bx bx-x d-block d-sm-none"></i>
					<span class="d-none d-sm-block">Batal</span>
				</button>
				<button type="button" class="ml-1 btn btn-primary" data-dismiss="modal">
					<i class="bx bx-check d-block d-sm-none"></i>
					<span class="d-none d-sm-block">Cari</span>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- End Modal -->

<?php
include "components/footer.php";
?>