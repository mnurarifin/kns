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
						<h5 class="content-header-title float-left pr-1 mb-0">Buat Pesan</h5>

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
					<div class="col-md-8 col-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Form Kirim Pesan Baru</h4>
							</div>
							<div class="card-content">
								<div class="card-body">
									<form class="form">
										<div class="form-body">
											<div class="row">
												<div class="col-12 pb-1">
													<label class="pb-1">Tujuan Pesan</label>
													<ul class="list-unstyled mb-0">
														<li class="d-inline-block mr-2 mb-1">
															<fieldset>
																<div class="radio">
																	<input type="radio" name="bsradio" id="radio1" checked="">
																	<label for="radio1">Mitra</label>
																</div>
															</fieldset>
														</li>
														<li class="d-inline-block mr-2 mb-1">
															<fieldset>
																<div class="radio">
																	<input type="radio" name="bsradio" id="radio2">
																	<label for="radio2">Admin</label>
																</div>
															</fieldset>
														</li>
													</ul>
												</div>

												<div class="col-12 pb-1">
													<fieldset class="form-label-group form-group position-relative has-icon-left">
														<input type="text" class="form-control" id="iconLabelLeft" placeholder="Kode Mitra">
														<div class="form-control-position">
															<i class="bx bx-user"></i>
														</div>
														<label for="iconLabelLeft">Kode Mitra</label>
													</fieldset>
												</div>

												<div class="col-12 pb-2">
													<fieldset class="form-label-group mb-0">
														<textarea data-length=20 class="form-control char-textarea" id="textarea-counter" rows="8" placeholder="Isi Pesan"></textarea>
													</fieldset>
													<small class="counter-value float-right"><span class="char-count">0</span> / 20 </small>
												</div>
												<div class="col-12 d-flex justify-content-end">
													<button type="submit" class="btn btn-primary">Kirim</button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
<!-- END: Content-->

<?php
include "components/footer.php";
?>