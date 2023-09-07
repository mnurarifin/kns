<?php
include "components/header-login.php";
?>

<!-- BEGIN: Content-->
<div class="app-content content app-login">
	<div class="content-overlay"></div>
	<div class="content-wrapper">
		<div class="content-header row">
		</div>
		<div class="content-body">
			<!-- login page start -->
			<section id="auth-login" class="row justify-content-center flexbox-container">
				<div class="col-xl-7 col-11">
					<div class="card bg-authentication mb-0 p-2">
						<div class="row m-0">
							<!-- left section-login -->
							<div class="col-12 col-lg-6 px-0">
								<div class="card mb-0 px-md-1 px-sm-0 h-100 d-flex justify-content-center bg-transparent">
									<div class="card-header pb-1">
										<div class="card-title">
											<div class="login-icon"></div>
											<h4 class="text-center cl-primary mb-2">Masuk ke Akun Anda</h4>
										</div>
									</div>
								</div>
							</div>

							<!-- right section-login -->
							<div class="col-12 col-lg-6 px-0">
								<div class="card card-auth card-bordered border mb-0 px-1 h-100 d-flex justify-content-center">
									<div class="card-content">
										<div class="card-body">
											<form action="dashboard.php">
												<div class="form-group mb-1">
													<label class="text-bold-600" for="InputEmail1">Username</label>
													<input type="email" class="form-control" id="InputEmail1" placeholder="masukkan username">
												</div>
												<div class="form-group mb-1">
													<label class="text-bold-600" for="InputEmail1">Nomor Telepon</label>
													<input type="text" class="form-control" id="InputPhone" placeholder="masukkan no. telepon">
												</div>
												<div class="form-password-toggle mb-1">
													<label class="form-label" for="inputPassword">Kata Sandi</label>
													<div class="input-group input-group-merge">
														<input type="password" class="form-control" id="inputPassword" placeholder="············" aria-describedby="basic-toggle-password">
														<span class="input-group-text cursor-pointer bg-white" id="basic-toggle-password"><i class="bx bx-hide"></i></span>
													</div>
												</div>

												<div class="form-group mb-1">
													<label class="form-label" for="InputCaptcha">Kata Sandi</label>
													<div class="input-group input-group-merge">
														<input type="text" class="form-control" id="InputCaptcha" placeholder="masukkan kode unik" aria-describedby="basic-input-captcha">
														<span class="input-group-text cursor-pointer bg-white" id="basic-input-captcha" onclick="createCaptcha()"><i class="bx bx-revision"></i></span>
													</div>
												</div>

												<div class="form-group mb-2">
													<div id="captcha-div"></div>
												</div>

												<button type="submit" class="btn btn-success btn-login glow w-100 position-relative">
													Login
													<i id="icon-arrow" class="bx bx-right-arrow-alt"></i>
												</button>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- login page ends -->
		</div>

		<div class="content-footer">
			<footer class="login-footer bg-light-primary" data-booted="true">
				<div class="text-center cl-primary col col-12">
					2022 - <strong>KISS</strong></div>
			</footer>
		</div>
	</div>
	<!-- END: Content-->

	<script>
		$(document).ready(function() {
			$("#basic-toggle-password").on('click', function(event) {
				event.preventDefault();
				if ($('.form-password-toggle input').attr("type") == "text") {
					$('.form-password-toggle input').attr('type', 'password');
					$('#basic-toggle-password i').addClass("bx-hide");
					$('#basic-toggle-password i').removeClass("bx-show");
				} else if ($('.form-password-toggle input').attr("type") == "password") {
					$('.form-password-toggle input').attr('type', 'text');
					$('#basic-toggle-password i').removeClass("bx-hide");
					$('#basic-toggle-password i').addClass("bx-show");
				}
			});
		});
	</script>

	<?php
	include "components/footer-login.php";
	?>