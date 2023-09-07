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
						<h5 class="content-header-title float-left pr-1 mb-0">Downline</h5>

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
			<div class="row">
				<div class="col-md-12 col-12 pb-1 text-right">
					<button type="button" class="btn btn-light">
						<span>Reset</span>
					</button>
					<button type="button" class="ml-1 btn btn-primary" data-toggle="modal" data-target="#default">
						<span>Cari</span>
					</button>
				</div>
				<div class="col-md-12 col-12">
					<section id="table-success">
            <div class="card mb-1">
              <div class="table-responsive">
                <table id="table-extended-success" class="table mb-0">
                  <thead>
										<tr>
											<th>Nama Mitra</th>
											<th>Kode Mitra</th>
											<th>Email</th>
											<th>Tanggal Gabung</th>
											<th>Posisi</th>
											<th>Level</th>
										</tr>
                  </thead>
                  <tbody>
										<?php for ($i = 1; $i <= 5; $i++) { ?>
										<tr>
											<td>John Doe</td>
											<td>MLM1000001</td>
											<td>isa@gmail.com</td>
											<td>1 Januari 1980</td>
											<td>
												<i class="bx bx-right-arrow-alt"></i>
												<span>Kanan</span>
											</td>
											<td>
												<div class="d-flex justify-content-center rounded-pill bg-dark white" style="width: 35px; height: 35px">
													<div class="my-auto">1</div>
												</div>
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

<!-- Modal -->
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
				<form class="form form-vertical">
					<div class="row">
						<div class="col-12">
							<input type="text" id="first-name-vertical" class="form-control" name="fname" placeholder="Nama Mitra">
						</div>
						<div class="col-12 pt-1">
							<input type="text" id="first-name-vertical" class="form-control" name="fname" placeholder="Kode Mitra">
						</div>
						<div class="col-12 pt-1">
							<input type="text" id="first-name-vertical" class="form-control" name="fname" placeholder="Email">
						</div>
						<div class="col-12 pt-1">
							<fieldset class="form-group position-relative mb-0">
								<input type="text" class="form-control pickadate-months-year" placeholder="Pilih Tanggal">
							</fieldset>
						</div>
						<div class="col-12 pt-1">
							<input type="text" id="first-name-vertical" class="form-control" name="fname" placeholder="Level">
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