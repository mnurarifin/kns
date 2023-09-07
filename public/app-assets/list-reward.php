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
						<h5 class="content-header-title float-left pr-1 mb-0">List Reward</h5>

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
					<div class="col-md-6 col-12">
						<div class="card">
							<div class="card-content">
								<div class="card-body">
									<div class="row">
                    <div class="col-4 my-auto">
                      <img class="round" src="../../../../images/icon/cup.png" height="130" width="130">
                    </div>
                    <div class="col-8">
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
                      <div class="row">
                        <div class="col-6">
                          <p class="card-text">Syarat Point Kanan</p>
                        </div>
                        <div class="col-6 text-right">
                          <p class="card-text font-weight-bold">200</p>
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

<?php
include "components/footer.php";
?>