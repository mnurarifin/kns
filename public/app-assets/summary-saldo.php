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
						<h5 class="content-header-title float-left pr-1 mb-0">Summary Saldo</h5>

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
        <div class="col-md-4 col-12">
          <div class="card text-white bg-success">
            <div class="card-body p-2 text-center">
							<div class="row flex-row align-center">
								<div class="pr-0 my-auto text-center col-sm-2 col-md-2 col-2">
									<img src="./images/icon/saldo.png" class="img-fluid filter-white" style="width: auto; max-width: none; height: 40px; filter: brightness(0) invert(1);">
								</div>
								<div class="col-sm-6 col-md-6 col-6 text-left">
									<div class="font-size-18 white--text font-weight-bold pl-1" style="font-size: 18px;">Rp 206.644</div>
									<div class="font-size-10 white--text pl-1">Total Saldo Anda</div>
								</div>
								<div class="pt-0 text-right my-auto col-sm-4 col-md-4 col-4">
									<button type="button" class="ml-1 btn btn-light-secondary round"  data-toggle="modal" data-target="#modalKlaim">
										<span class="d-none d-sm-block">Klaim</span>
									</button>
								</div>
							</div>
            </div>
          </div>
        </div>
        <div class="col-md-8 col-12">
        </div>
      </div>

			<div class="row">
				<div class="col-md-12 col-12 pb-1 text-right">
					<div class="card mb-50">
						<div class="card-body d-flex flex-row justify-content-end">
							<button type="button" class="btn btn-light">
								<span>Reset</span>
							</button>
							<button type="button" class="ml-1 btn btn-primary" data-toggle="modal" data-target="#default">
								<span>Cari</span>
							</button>
						</div>
					</div>
				</div>

				<div class="col-md-12 col-12">
					<section id="table-success">
            <div class="card mb-1">
              <div class="table-responsive">
                <table id="table-extended-success" class="table mb-0">
                  <thead>
										<tr>
											<th>Tanggal</th>
											<th class="text-center">Jenis Saldo</th>
											<th>Info</th>
											<th class="text-right">Potongan</th>
											<th class="text-right">Jumlah (Rp)</th>
										</tr>
                  </thead>
                  <tbody>
										<?php for ($i = 1; $i <= 3; $i++) { ?>
										<tr>
											<td>31 Agustus 2022 20:31:06</td>
											<td class="text-center" style="text-align: center;">
												<div class="badge badge-pill badge-success">Masuk</div>
											</td>
											<td>Komisi dari pasangan</td>
											<td class="text-right">11.731</td>
											<td class="text-right text-success">
												222.887
											</td>
										</tr>
										<tr>
											<td>31 Agustus 2022 20:31:06</td>
											<td class="text-center" style="text-align: center;">
												<div class="badge badge-pill badge-warning">Keluar</div>
											</td>
											<td>Komisi dari pasangan</td>
											<td class="text-right">11.731</td>
											<td class="text-right text-warning">
												222.887
											</td>
										</tr>
										<?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </section>
				</div>
				<div class="col-md-12 col-12">
					<div class="row">
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
<!-- END: Content-->

<!-- Modal Cari -->
<div class="modal fade text-left" id="default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel1">Pencarian</h3>
				<button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
					<i class="bx bx-x"></i>
				</button>
			</div>
			<div class="modal-body">
				<form class="form form-vertical" style="height: 360px;">
					<div class="row">
						<div class="col-12">
							<fieldset class="form-group position-relative mb-2">
								<input type="text" class="form-control pickadate-months-year" placeholder="Pilih Tanggal">
							</fieldset>
						</div>
						<div class="col-12">
							<fieldset class="form-group">
								<select class="form-control" id="basicSelect">
									<option>-- Pilih Jenis Saldo --</option>
									<option>Masuk</option>
									<option>Keluar</option>
								</select>
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

<!-- Modal Klaim -->
<div class="modal fade text-left" id="modalKlaim" tabindex="-1" role="dialog" aria-labelledby="myModalKlaim" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalKlaim">Klaim Saldo</h3>
				<button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
					<i class="bx bx-x"></i>
				</button>
			</div>
			<div class="modal-body">
				<form class="form form-vertical">
					<div class="row">
						<div class="col-12">
							<div class="card border p-2 mb-0">
								<fieldset class="form-group position-relative has-icon-left mb-0">
									<input type="text" class="form-control form-control-lg form-control-success pl-4" id="iconLeft1" placeholder="0">
									<div class="form-control-position">
										<i class="bx bx-wallet-alt bx-md"></i>
									</div>
									<label class="pt-1 text-danger">Minimal saldo yang bisa diklaim Rp 50.000</label>
								</fieldset>
							</div>
						</div>
						<div class="col-12">
							<table class="table mt-2 table-bordered">
								<tr>
									<th width="50%">Potongan</th>
									<td class="text-right text-dark">Rp 0</td>
								</tr>
								<tr>
									<th width="50%">Saldo yang diterima</th>
									<td class="text-right">
										<span class="font-weight-bold text-success">Rp 2.000.000</span>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light-secondary" data-dismiss="modal">
					<i class="bx bx-x d-block d-sm-none"></i>
					<span class="d-none d-sm-block">Batal</span>
				</button>
				<button type="button" class="ml-1 btn btn-success" data-dismiss="modal">
					<i class="bx bx-check d-block d-sm-none"></i>
					<span class="d-none d-sm-block">Klaim</span>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- End Modal -->

<?php
include "components/footer.php";
?>