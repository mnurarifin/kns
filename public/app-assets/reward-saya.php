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
						<h5 class="content-header-title float-left pr-1 mb-0">Reward Saya</h5>

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
			<section id="floating-label-layouts">
				<div class="row match-height">
					<?php for ($i = 1; $i <= 4; $i++) { ?>
					<div class="col-lg-6 col-12">
						<div class="card">
							<div class="card-content">
								<div class="px-1 py-1 card-body">
									<div class="row">
										<div class="col-4 my-auto">
											<img class="round" src="../../../../images/icon/cup.png" height="130" width="130">
										</div>
										<div class="col-8">
											<div class="pb-2 text-right">
												<button type="button" class="btn btn-primary rounded-pill" data-toggle="modal" data-target="#default">
													<i class="bx bxs-award"></i>
													<span class="align-middle ml-25">Klaim Reward</span>
												</button>
											</div>
											<h4 class="mb-0 pb-2">Piala Emas</h4>
											<div class="row pb-1">
												<div class="col-6">
													<p class="card-text">Nominal Reward</p>
												</div>
												<div class="col-6 text-right">
													<p class="card-text font-weight-bold">Rp 10.000.000</p>
												</div>
											</div>
											<div class="row pb-1">
												<div class="col-6">
													<p class="card-text">Syarat Point Kiri</p>
												</div>
												<div class="col-6 text-right">
													<p class="card-text font-weight-bold">200</p>
												</div>
											</div>
											<div class="row pb-2">
												<div class="col-6">
													<p class="card-text">Syarat Point Kanan</p>
												</div>
												<div class="col-6 text-right">
													<p class="card-text font-weight-bold">200</p>
												</div>
											</div>
											<div class="row pb-1">
												<div class="col-6">
													<p class="card-text">Tanggal Qualified</p>
												</div>
												<div class="col-6 text-right">
													<p class="card-text font-weight-bold">1 Januari 1980</p>
												</div>
											</div>
											<div class="row pb-1">
												<div class="col-6">
													<p class="card-text">Tanggal Klaim</p>
												</div>
												<div class="col-6 text-right">
													<p class="card-text font-weight-bold">30 Januari 1980</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</section>
		</div>
	</div>
</div>
<!-- END: Content-->

<!-- Modal -->
<div class="modal fade text-left" id="default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h3 class="modal-title" id="myModalLabel1">Konfirmasi</h3>
				<button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
					<i class="bx bx-x"></i>
				</button>
			</div>
			<div class="modal-body">
				<p>Apakah Anda yakin untuk mengklaim reward <b>Piala Emas</b>?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light-secondary" data-dismiss="modal">
					<i class="bx bx-x d-block d-sm-none"></i>
					<span class="d-none d-sm-block">Batal</span>
				</button>
				<button type="button" class="btn btn-primary ml-1" data-dismiss="modal">
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