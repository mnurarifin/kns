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
			<div class="row">
				<div class="col-md-12 col-12">

					<!-- vertical Wizard start-->
					<section id="vertical-wizard">
						<div class="card">
								<div class="card-header">
								</div>
								<div class="card-content">
										<div class="card-body">
												<form action="#" class="wizard-vertical">
														<!-- step 1 -->
														<h3>
																<span class="fonticon-wrap mr-1">
																		<i class="livicon-evo" data-options="name:diagram.svg; size: 50px; style:lines; strokeColor:#828D99;"></i>
																</span>
																<span class="icon-title">
																		<span class="d-block">Data Jaringan & Serial</span>
																		<small class="text-muted">Info data registrasi akun Mitra.</small>
																</span>
														</h3>
														<!-- step 1 end-->
														<!-- step 1 content -->
														<fieldset class="pt-0">
																<h6 class="pb-1">Data Jaringan</h6>

																<div class="card card-bordered border p-2">
																	<div class="row">
																			<div class="col-sm-6">
																					<div class="form-group">
																							<label for="idsponsor">Kode Sponsor</label>
																							<input type="text" class="form-control" id="idsponsor" placeholder="">
																							<small class="text-muted form-text label-sponsor">Masukkan kode mitra sponsor Anda.</small>
																					</div>
																			</div>
																			<div class="col-sm-6">
																					<div class="form-group">
																							<label for="idupline">Kode Upline</label>
																							<input type="text" id="idupline" class="form-control" placeholder="">
																							<small class="text-muted form-text label-upline">Masukkan kode mitra upline Anda.</small>
																					</div>
																			</div>
																			<div class="col-sm-6">
																					<div class="form-group">
																							<label for="pos">Posisi Jaringan</label>
																							<fieldset class="d-flex flex-row pt-1">
																								<div class="custom-control custom-radio pr-2">
																									<input type="radio" class="custom-control-input" name="customRadio" id="pos-kiri" checked>
																									<label class="custom-control-label" for="pos-kiri">Kiri</label>
																								</div>
																								<div class="custom-control custom-radio pr-2">
																									<input type="radio" class="custom-control-input" name="customRadio" id="pos-kanan">
																									<label class="custom-control-label" for="pos-kanan">Kanan</label>
																								</div>
																							</fieldset>
																							<!-- <small class="text-muted form-text">Masukkan kode mitra upline Anda.</small> -->
																					</div>
																			</div>
																	</div>
																</div>

																<h6 class="pb-1">Data Serial</h6>
																<div class="card card-bordered border p-2 mb-0">
																	<div class="row">
																		<div class="col-sm-6">
																				<div class="form-group">
																						<label>Serial Registrasi</label>
																						<select name="serialredId" class="form-control">
																								<option value="">Pilih Serial Registrasi</option>
																								<option value="1" selected="">MLM10001234</option>
																								<option value="2">MLM10001235</option>
																								<option value="3">MLM10001236</option>
																						</select>
																				</div>
																		</div>

																		<div class="col-sm-6">
																				<div class="form-group d-flex flex-column">
																					<label>Aktivasikan Akun Mitra</label>
																					<div class="custom-control custom-switch custom-control-inline mr-0 pt-75">
																						<input type="checkbox" class="custom-control-input" id="isActivate">
																						<label class="custom-control-label" for="isActivate"></label>
																					</div>
																				</div>
																		</div>

																		<div class="col-sm-12">
																			<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert">
																				<div class="d-flex align-items-center">
																						<i class="bx bx-star text-primary"></i>
																						<span class="text-primary">
																							Silakan geser tombol <b>Aktivasi Mitra</b> untuk mengaktivasi akun mitra anda dan dapatkan benefit komisi dari jaringan mitra anda.
																						</span>
																				</div>
																			</div>
																		</div>

																		<div class="col-sm-6">
																				<div class="form-group pt-10">
																						<label>Serial Aktivasi</label>
																						<select name="serialredId" class="form-control">
																								<option value="">Pilih Serial Aktivasi</option>
																								<option value="1" selected="">MLM10001234</option>
																								<option value="2">MLM10001235</option>
																								<option value="3">MLM10001236</option>
																						</select>
																				</div>
																		</div>
																	</div>
																</div>
														</fieldset>
														<!-- step 1 content end-->

														<!-- step 2 -->
														<h3>
																<span class="fonticon-wrap mr-1">
																		<i class="livicon-evo" data-options="name:location-alt.svg; size: 50px; style:lines; strokeColor:#828D99;"></i>
																</span>
																<span class="icon-title">
																		<span class="d-block">Data Personal</span>
																		<small class="text-muted">Lengkapi data diri Mitra.</small>
																</span>
														</h3>
														<!-- step 2 end-->
														<!-- step 2 content -->
														<fieldset class="pt-0">
															<h6 class="pb-1">Data Akun Mitra</h6>
															<div class="card card-bordered border p-2">
																<div class="row">
																	<div class="col-sm-6">
																		<div class="form-group">
																			<label for="fullName">Nama Lengkap </label>
																			<input type="text" class="form-control" id="fullName" placeholder="">
																			<small class="text-muted form-text">Masukkan nama lengkap mitra.</small>
																		</div>
																	</div>

																	<div class="col-sm-6">
																		<div class="form-group">
																			<label for="pos">Jenis Kelamin</label>
																			<fieldset class="d-flex flex-row pt-50">
																				<div class="custom-control custom-radio pr-2">
																					<input type="radio" class="custom-control-input" name="customRadio" id="jk-lakilaki" checked>
																					<label class="custom-control-label" for="jk-lakilaki">Laki-laki</label>
																				</div>
																				<div class="custom-control custom-radio pr-2">
																					<input type="radio" class="custom-control-input" name="customRadio" id="jk-permepuan">
																					<label class="custom-control-label" for="jk-permepuan">Perempuan</label>
																				</div>
																			</fieldset>
																		</div>
																	</div>

																	<div class="col-sm-6">
																		<div class="form-group">
																			<label for="email">Email</label>
																			<input type="email" class="form-control" id="email" placeholder="">
																			<small class="text-muted form-text">Masukkan email akun mitra.</small>
																		</div>
																	</div>
																	<div class="col-sm-6">
																		<div class="form-password-toggle form-group">
																			<label>Password</label>
																			<!-- <input type="password" class="form-control" placeholder=""> -->
																			<div class="input-group input-group-merge">
																				<input type="password" class="form-control" id="inputPassword" placeholder="············" aria-describedby="basic-toggle-password">
																				<span class="input-group-text cursor-pointer bg-white" id="basic-toggle-password"><i class="bx bx-hide"></i></span>
																			</div>	
																			<small class="text-muted form-text">Masukkan password akun mitra.</small>
																		</div>
																	</div>

																	<div class="col-sm-6">
																		<div class="form-group">
																			<label for="noktp">Nomor KTP</label>
																			<input type="text" class="form-control" id="noktp" placeholder="">
																			<small class="text-muted form-text">Masukkan nomor identitas mitra.</small>
																		</div>
																	</div>
																	<div class="col-sm-6">
																		<div class="form-group">
																			<label>Nomor Handphone</label>
																			<input type="tel" class="form-control" placeholder="">
																			<small class="text-muted form-text">Masukkan nomor handphone mitra.</small>
																		</div>
																	</div>
																</div>
															</div>

															<h6 class="pb-1">Data Alamat Mitra</h6>
															<div class="card card-bordered border p-2 mb-2">
																<div class="row">
																	<div class="col-sm-12">
																		<div class="form-group">
																			<label for="alamat">Alamat Lengkap</label>
																			<textarea class="form-control" rows="4" id="alamat" placeholder=""></textarea>
																			<small class="text-muted form-text">Masukkan alamat lengkap domisili mitra.</small>
																		</div>
																	</div>

																	<div class="col-sm-6">
																		<div class="form-group">
																			<label>Provinsi</label>
																			<select class="custom-select form-control" id="provinsi" name="provinsi">
																				<option value="1">DKI. Jakarta</option>
																				<option value="2">Jawa Barat</option>
																				<option value="3">Jawa Tengah</option>
																				<option value="4">DI. Yogyakarta</option>
																				<option value="5">Jawa Timur</option>
																			</select>
																			<small class="form-text text-muted">Pilih provinsi domisili mitra.</small>
																		</div>
																	</div>

																	<div class="col-sm-6">
																		<div class="form-group">
																			<label>Kota</label>
																			<select class="custom-select form-control" id="kota" name="kota">
																				<option value="1">Bandung</option>
																				<option value="2">Sleman</option>
																				<option value="3">Bantul</option>
																				<option value="4">Gunung Kidul</option>
																			</select>
																			<small class="form-text text-muted">Pilih kota domisili mitra.</small>
																		</div>
																	</div>
																</div>
															</div>

															<h6 class="py-50">Data Waris Mitra</h6>
															<div class="card card-bordered border p-2 mb-0">
																<div class="row">
																	<div class="col-sm-6">
																		<div class="form-group">
																			<label for="mother_name">Nama Ibu Kandung</label>
																			<input type="text" class="form-control" id="mother_name" placeholder="">
																			<small class="form-text text-muted">Masukkan nama ibu kandung mitra.</small>
																		</div>
																	</div>
																		
																	<div class="col-sm-6"></div>
																	<div class="col-sm-6">
																		<div class="form-group">
																			<label for="nama_ahli_waris">Nama Ahli Waris</label>
																			<input type="text" class="form-control" id="nama_ahli_waris" placeholder="">
																			<small class="form-text text-muted">Masukkan nama lengkap ahli waris mitra.</small>
																		</div>
																	</div>

																	<div class="col-sm-6">
																		<div class="form-group">
																			<label for="hubungan_waris">Hubungan Ahli Waris</label>
																			<input type="text" class="form-control" id="hubungan_waris" placeholder="">
																			<small class="form-text text-muted">Masukkan hubungan mitra dengan ahli waris.</small>
																		</div>
																	</div>
																</div>
															</div>
														</fieldset>
														<!-- step 2 content end-->

														<!-- section 3 -->
														<h3>
																<span class="fonticon-wrap mr-1">
																		<i class="livicon-evo" data-options="name:bank.svg; size: 50px; style:lines; strokeColor:#828D99;"></i>
																</span>
																<span class="icon-title">
																		<span class="d-block">Data Rekening Bank</span>
																		<small class="text-muted">Lengkapi informasi rekening bank akun Anda.</small>
																</span>
														</h3>
														<!-- section 3 end-->
														<!-- step 3 content -->
														<fieldset class="pt-0">
																<h6 class="py-50">Informasi Rekening Bank Mitra</h6>
																<div class="card card-bordered border p-2 mb-0">
																	<div class="row">
																		<div class="col-sm-6">
																			<div class="form-group">
																				<label for="nama_akun">Nama Akun Rekening</label>
																				<input type="text" class="form-control" id="nama_akun" placeholder="">
																				<small class="form-text text-muted">Masukkan nama akun rekening mitra.</small>
																			</div>
																		</div>
																			
																		<div class="col-sm-6">
																			<div class="form-group">
																				<label for="no_rek">Nomor Rekening</label>
																				<input type="text" class="form-control" id="no_rek" placeholder="">
																				<small class="form-text text-muted">Masukkan nomor rekening mitra.</small>
																			</div>
																		</div>

																		<div class="col-sm-6">
																			<div class="form-group">
																				<label for="nama_bank">Nama Bank</label>
																				<select name="nama_bank" id="nama_bank" class="form-control">
																					<option value="">Pilih Bank</option>
																					<option value="overnight" selected="">BCA</option>
																					<option value="express">Mandiri</option>
																					<option value="basic">BRI</option>
																				</select>
																				<small class="form-text text-muted">Pilih bank rekening mitra.</small>
																			</div>
																		</div>
																			
																		<div class="col-sm-6">
																			<div class="form-group">
																				<label for="nama_cabang">Kantor Cabang</label>
																				<input type="text" class="form-control" id="nama_cabang" placeholder="">
																				<small class="form-text text-muted">Masukkan nama cabang bank rekening mitra.</small>
																			</div>
																		</div>

																		<div class="col-sm-6">
																			<div class="form-group">
																				<label for="provinsi_bank">Provinsi</label>
																				<select name="provinsi_bank" id="provinsi_bank" class="form-control">
																					<option value="">Pilih Provinsi Lokasi Bank</option>
																					<option value="1" selected="">Provinsi 1</option>
																					<option value="2">Provinsi 2</option>
																					<option value="3">Provinsi 3</option>
																				</select>
																			</div>
																		</div>

																		<div class="col-sm-6">
																			<div class="form-group">
																				<label for="kota_bank">Kota</label>
																				<select name="kota_bank" id="kota_bank" class="form-control">
																					<option value="">Pilih Kota Lokasi Bank</option>
																					<option value="1" selected="">Kota 1</option>
																					<option value="2">Kota 2</option>
																					<option value="3">Kota 3</option>
																				</select>
																			</div>
																		</div>
																		
																	</div>
																</div>
														</fieldset>
														<!-- step 3 content end-->

												</form>
										</div>
								</div>
						</div>
				</section>
				<!-- vertical Wizard end-->

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
							<input type="text" id="first-name-vertical-1" class="form-control" name="fname" placeholder="Nama Mitra">
						</div>
						<div class="col-12 pt-1">
							<input type="text" id="first-name-vertical-2" class="form-control" name="fname" placeholder="Kode Mitra">
						</div>
						<div class="col-12 pt-1">
							<input type="text" id="first-name-vertical-3" class="form-control" name="fname" placeholder="Email">
						</div>
						<div class="col-12 pt-1">
							<fieldset class="form-group position-relative mb-0">
								<input type="text" class="form-control pickadate-months-year" placeholder="Pilih Tanggal">
							</fieldset>
						</div>
						<div class="col-12 pt-1">
							<input type="text" id="first-name-vertical-4" class="form-control" name="fname" placeholder="Level">
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

<script>
	$(document).ready(function() {
    $("#basic-toggle-password").on('click', function(event) {
        event.preventDefault();
        if($('.form-password-toggle input').attr("type") == "text"){
            $('.form-password-toggle input').attr('type', 'password');
            $('#basic-toggle-password i').addClass( "bx-hide" );
            $('#basic-toggle-password i').removeClass( "bx-show" );
        }else if($('.form-password-toggle input').attr("type") == "password"){
            $('.form-password-toggle input').attr('type', 'text');
            $('#basic-toggle-password i').removeClass( "bx-hide" );
            $('#basic-toggle-password i').addClass( "bx-show" );
        }
    });
	});
</script>

<?php
include "components/footer.php";
?>