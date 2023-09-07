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
						<h5 class="content-header-title float-left pr-1 mb-0">Stok Serial</h5>

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
                      <div class="card mb-1 border">
                        <div class="row align-center">
                          <div class="col-md-12 col-12 py-1 px-3 text-right">
                            <button type="button" class="btn btn-light">
                              <span>Reset</span>
                            </button>
                            <button type="button" class="ml-1 btn btn-primary" data-toggle="modal" data-target="#default">
                              <span>Cari</span>
                            </button>
                          </div>
                        </div>
                      </div>

                      <div class="card mb-1">
                        <div class="row">
                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-success p-1">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-success mb-1" style="font-weight: bold; font-size: 14px;">BASIC</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 cl-primary font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-success font-weight-bold">Tersedia</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-warning p-1">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-warning mb-1" style="font-weight: bold; font-size: 14px;">PREMIUM</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 text-warning font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-success font-weight-bold">Tersedia</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-light p-1" style="background: #e5e5e5;">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-light mb-1" style="font-weight: bold; font-size: 14px;">BASIC</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 dark font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-danger font-weight-bold">Telah Digunakan</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          
                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-success p-1">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-success mb-1" style="font-weight: bold; font-size: 14px;">BASIC</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 cl-primary font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-success font-weight-bold">Tersedia</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-warning p-1">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-warning mb-1" style="font-weight: bold; font-size: 14px;">PREMIUM</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 text-warning font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-success font-weight-bold">Tersedia</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-light p-1" style="background: #e5e5e5;">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-light mb-1" style="font-weight: bold; font-size: 14px;">BASIC</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 dark font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-danger font-weight-bold">Telah Digunakan</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-2">
                          <div class="col-md-6 col-12">
                            <nav>
                              <ul class="pagination pagination-primary mb-0">
                                <li class="page-item previous">
                                  <a class="page-link" href="#">
                                    <i class="bx bx-chevron-left"></i>
                                  </a>
                                </li>
                                <li class="page-item">
                                  <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item">
                                  <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                  <a class="page-link" href="#">3</a>
                                </li>
                                <li class="page-item active" aria-current="page">
                                  <a class="page-link" href="#">4</a>
                                </li>
                                <li class="page-item">
                                  <a class="page-link" href="#">5</a>
                                </li>
                                <li class="page-item next">
                                  <a class="page-link" href="#">
                                    <i class="bx bx-chevron-right"></i>
                                  </a>
                                </li>
                              </ul>
                            </nav>
                          </div>
                          <div class="col-md-6 col-12 text-right">
                            <div class="row">
                              <div class="col-md-9 col-12 my-auto text-right">
                                <p class="card-text mb-0 dark">Menampilkan 10 data dari total 50 data</p>
                              </div>
                              <div class="col-md-3 col-4 text-right">
                                <fieldset class="form-group mb-0">
                                  <select class="form-control" id="basicSelect">
                                    <option>10</option>
                                    <option>20</option>
                                    <option>50</option>
                                    <option>100</option>
                                  </select>
                                </fieldset>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane" id="serialupgr" aria-labelledby="serialupgr-tab" role="tabpanel">
                      <div class="card mb-1 border">
                        <div class="row align-center">
                          <div class="col-md-12 col-12 py-1 px-3 text-right">
                            <button type="button" class="btn btn-light">
                              <span>Reset</span>
                            </button>
                            <button type="button" class="ml-1 btn btn-primary" data-toggle="modal" data-target="#default">
                              <span>Cari</span>
                            </button>
                          </div>
                        </div>
                      </div>

                      <div class="card mb-1">
                        <div class="row">
                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-success p-1">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-success mb-1" style="font-weight: bold; font-size: 14px;">BASIC</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 cl-primary font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-success font-weight-bold">Tersedia</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-warning p-1">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-warning mb-1" style="font-weight: bold; font-size: 14px;">PREMIUM</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 text-warning font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-success font-weight-bold">Tersedia</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-success p-1">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-success mb-1" style="font-weight: bold; font-size: 14px;">BASIC</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 cl-primary font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-success font-weight-bold">Tersedia</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          
                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-success p-1">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-success mb-1" style="font-weight: bold; font-size: 14px;">BASIC</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 cl-primary font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-success font-weight-bold">Tersedia</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-warning p-1">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-warning mb-1" style="font-weight: bold; font-size: 14px;">PREMIUM</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 text-warning font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-success font-weight-bold">Tersedia</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-success p-1">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-success mb-1" style="font-weight: bold; font-size: 14px;">BASIC</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 cl-primary font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-success font-weight-bold">Tersedia</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-2">
                          <div class="col-md-6 col-12">
                            <nav>
                              <ul class="pagination pagination-primary mb-0">
                                <li class="page-item previous">
                                  <a class="page-link" href="#">
                                    <i class="bx bx-chevron-left"></i>
                                  </a>
                                </li>
                                <li class="page-item">
                                  <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item">
                                  <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                  <a class="page-link" href="#">3</a>
                                </li>
                                <li class="page-item active" aria-current="page">
                                  <a class="page-link" href="#">4</a>
                                </li>
                                <li class="page-item">
                                  <a class="page-link" href="#">5</a>
                                </li>
                                <li class="page-item next">
                                  <a class="page-link" href="#">
                                    <i class="bx bx-chevron-right"></i>
                                  </a>
                                </li>
                              </ul>
                            </nav>
                          </div>
                          <div class="col-md-6 col-12 text-right">
                            <div class="row">
                              <div class="col-md-9 col-12 my-auto text-right">
                                <p class="card-text mb-0 dark">Menampilkan 10 data dari total 50 data</p>
                              </div>
                              <div class="col-md-3 col-4 text-right">
                                <fieldset class="form-group mb-0">
                                  <select class="form-control" id="basicSelect">
                                    <option>10</option>
                                    <option>20</option>
                                    <option>50</option>
                                    <option>100</option>
                                  </select>
                                </fieldset>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane" id="serialro" aria-labelledby="serialro-tab" role="tabpanel">
                      <div class="card mb-1 border">
                        <div class="row align-center">
                          <div class="col-md-12 col-12 py-1 px-3 text-right">
                            <button type="button" class="btn btn-light">
                              <span>Reset</span>
                            </button>
                            <button type="button" class="ml-1 btn btn-primary" data-toggle="modal" data-target="#default">
                              <span>Cari</span>
                            </button>
                          </div>
                        </div>
                      </div>

                      <div class="card mb-1">
                        <div class="row">
                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-success p-1">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-success mb-1" style="font-weight: bold; font-size: 14px;">BASIC</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 cl-primary font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-success font-weight-bold">Tersedia</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-warning p-1">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-warning mb-1" style="font-weight: bold; font-size: 14px;">PREMIUM</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 text-warning font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-success font-weight-bold">Tersedia</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-light p-1" style="background: #e5e5e5;">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-light mb-1" style="font-weight: bold; font-size: 14px;">BASIC</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 dark font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-danger font-weight-bold">Telah Digunakan</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          
                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-success p-1">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-success mb-1" style="font-weight: bold; font-size: 14px;">BASIC</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 cl-primary font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-success font-weight-bold">Tersedia</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-warning p-1">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-warning mb-1" style="font-weight: bold; font-size: 14px;">PREMIUM</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 text-warning font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-success font-weight-bold">Tersedia</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-12">
                            <div class="card card-shadow border-light p-1" style="background: #e5e5e5;">
                              <div class="row">
                                <div class="pb-1 text-left px-2 text-right col col-12">
                                  <div class="badge badge-pill badge-light mb-1" style="font-weight: bold; font-size: 14px;">BASIC</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Serial</div>
                                  <div class="font-size-16 dark font-weight-bold">BAS1000421</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Pin</div>
                                  <div class="font-size-16 dark font-weight-bold">AhzvRY</div>
                                </div>
                                <div class="pb-1 text-left col col-6">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Pembelian</div>
                                  <div class="font-size-10 dark font-weight-500">06 Juni 2022</div>
                                </div>
                                <div class="pb-1 text-left col-md-6 col">
                                  <div class="font-size-10 cl-grey pb-25">Tanggal Digunakan</div>
                                  <div class="font-size-10 dark font-weight-500">-</div>
                                </div>
                                <div class="col-md-6 col text-left">
                                  <div class="font-size-10 cl-grey pb-25">Status</div>
                                  <div>
                                    <div class="font-size-10 text-danger font-weight-bold">Telah Digunakan</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-2">
                          <div class="col-md-6 col-12">
                            <nav>
                              <ul class="pagination pagination-primary mb-0">
                                <li class="page-item previous">
                                  <a class="page-link" href="#">
                                    <i class="bx bx-chevron-left"></i>
                                  </a>
                                </li>
                                <li class="page-item">
                                  <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item">
                                  <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                  <a class="page-link" href="#">3</a>
                                </li>
                                <li class="page-item active" aria-current="page">
                                  <a class="page-link" href="#">4</a>
                                </li>
                                <li class="page-item">
                                  <a class="page-link" href="#">5</a>
                                </li>
                                <li class="page-item next">
                                  <a class="page-link" href="#">
                                    <i class="bx bx-chevron-right"></i>
                                  </a>
                                </li>
                              </ul>
                            </nav>
                          </div>
                          <div class="col-md-6 col-12 text-right">
                            <div class="row">
                              <div class="col-md-9 col-12 my-auto text-right">
                                <p class="card-text mb-0 dark">Menampilkan 10 data dari total 50 data</p>
                              </div>
                              <div class="col-md-3 col-4 text-right">
                                <fieldset class="form-group mb-0">
                                  <select class="form-control" id="basicSelect">
                                    <option>10</option>
                                    <option>20</option>
                                    <option>50</option>
                                    <option>100</option>
                                  </select>
                                </fieldset>
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