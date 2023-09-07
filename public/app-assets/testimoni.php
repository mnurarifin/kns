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
						<h5 class="content-header-title float-left pr-1 mb-0">Testimoni</h5>

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
					<div class="col-md-12 col-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Form Testimoni</h4>
							</div>
							<div class="card-content">
								<div class="card-body">
									<form class="form">
										<div class="form-body">
											<div class="row">
												<div class="col-12 pb-2">
													<fieldset class="form-label-group mb-0">
														<textarea data-length=20 class="form-control char-textarea" id="textarea-counter" rows="3" placeholder="Testimoni"></textarea>
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