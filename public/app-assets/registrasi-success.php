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
						<h5 class="content-header-title float-left pr-1 mb-0">Registrasi & Aktivasi Mitra</h5>

						<div class="breadcrumb-wrapper col-12">
							<ol class="breadcrumb p-0 mb-0">
								<li class="breadcrumb-item"><a href="dashboard.html"><i class="bx bx-home-alt"></i></a></li>
								<li class="breadcrumb-item"><a href="#">Jaringan</a></li>
								<li class="breadcrumb-item active">Registrasi & Aktivasi Mitra</li>
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
								<h4 class="card-title cl-primary">Registrasi Mitra Berhasil!</h4>
							</div>
							<div class="card-content">
								<div class="card-body">
									<div class="card border mb-1">
										<div class="row py-3 align-center">
											<div class="col-sm-12 col-md-6 col-6 justify-content-center my-25">
												<img src="./images/register-ok.png" style="height: 240px;display: block; margin: 1rem auto;">
											</div>
											<div class="col-sm-12 col-md-6 col-6 my-25">
												<h5 class="cl-primary mb-2" style="line-height: 1.75rem;">Mitra baru Anda telah berhasil didaftarkan pada<br>Sistem MJA Indonesia.</h5>
	
												<p class="mb-50 font-weight-bold"><span style="width: 100px;display: inline-block;">Nama </span>: <span class="text-black">John Doe</span></p>
												<p class="mb-50 font-weight-bold"><span style="width: 100px;display: inline-block;">Username </span>: <span class="text-black">MJA1000157</span></p>
												<p class="mb-50 font-weight-bold"><span style="width: 100px;display: inline-block;">password </span>: <span class="text-black">demo123 </span></p>
	
												<div class="d-block mt-2 text-dark">
													Yuk informasikan ke mitra baru Anda untuk segera login ke website <a href="https://www.mjagaharu.co.id">www.mjagaharu.co.id</a> dan segera ubah password default tersebut.<br>Salam sukses bersama MJA Indonesia !
													<b class="d-block">Sumber Sehat Sumber Uang...</b>
												</div>
											</div>
										</div>
									</div>

									<div class="d-flex align-center justify-content-center p-2">
										<button type="button" class="btn btn-outline-secondary mx-25">
											<i class="bx bx-left-arrow-alt"></i>
											<span class="pl-25">Kembali ke Beranda</span>
										</button>
										<button type="button" class="btn btn-outline-secondary mx-25">
											<i class="bx bx-user-pin "></i>
											<span class="pl-25">Lihat Jaringan Saya</span>
										</button>
									</div>
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