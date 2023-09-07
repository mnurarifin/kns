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
						<h5 class="content-header-title float-left pr-1 mb-0">Summary Komisi</h5>

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
        <div class="col-md-3 col-12">
          <div class="card">
            <div class="card-body p-2">
              <div class="pb-1">
                <p class="card-text mb-0">Nama</p>
                <div class="d-flex card-text font-weight-bold secondary">
                  <i class="ficon bx bx-user-circle my-auto"></i>
                  <div style="padding-left: 10px;">
                    <div>John Doe</div>
                    <div>(MLM1000001)</div>
                  </div>
                </div>
              </div>
              <div class="pb-1">
                <p class="card-text mb-0">Tanggal Daftar</p>
                <div class="d-flex card-text font-weight-bold secondary">
                  <i class="ficon bx bx-calendar" style="margin-top: 1px;"></i>
                  <span style="padding-left: 10px;">25 April 1980</span>
                </div>
              </div>
              <div class="pb-1">
                <p class="card-text mb-0">Sponsor</p>
                <div class="d-flex card-text font-weight-bold secondary">
                  <i class="ficon bx bx-group" style="margin-top: 1px;"></i>
                  <span style="padding-left: 10px;">MLM1000001</span>
                </div>
              </div>
              <div class="pb-1">
                <p class="card-text mb-0">Upline</p>
                <div class="d-flex card-text font-weight-bold secondary">
                  <i class="ficon bx bx-user-plus" style="margin-top: 1px;"></i>
                  <span style="padding-left: 10px;">MLM1000001</span>
                </div>
              </div>
              <div class="pb-1">
                <p class="card-text mb-0">Bank</p>
                <div class="d-flex card-text font-weight-bold secondary">
                  <i class="ficon bx bx-credit-card my-auto"></i>
                  <div style="padding-left: 10px;">
                    <div>Mandiri</div>
                    <div>123456789</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-9 col-12">
          <div class="card">
            <div class="card-body p-2">
              <div class="row">
                <div class="col-12">
                  <h6 class="mb-0 dark font-weight-bold">Potensi Komisi Hari Ini (1 Januari 1980)</h6>
                  <div class="row ml-0">
                    <div class="pl-0 col-4">
                      <div class="card border mb-0 mt-1">
                        <div class="card-body p-1">
                          <p class="card-text mb-0 font-weight-bold" style="padding-bottom: 5px;">Komisi Sponsor</p>
                          <h5 class="mb-0 primary font-weight-bold">Rp 10.000.000</h5>
                        </div>
                      </div>
                    </div>
                    <div class="pl-0 col-4">
                      <div class="card border mb-0 mt-1">
                        <div class="card-body p-1">
                          <p class="card-text mb-0 font-weight-bold" style="padding-bottom: 5px;">Komisi Pasangan</p>
                          <h5 class="mb-0 primary font-weight-bold">Rp 10.000.000</h5>
                        </div>
                      </div>
                    </div>
                    <div class="pl-0 col-4">
                      <div class="card border mb-0 mt-1">
                        <div class="card-body p-1">
                          <p class="card-text mb-0 font-weight-bold" style="padding-bottom: 5px;">Komisi Unilevel</p>
                          <h5 class="mb-0 primary font-weight-bold">Rp 10.000.000</h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <h6 class="mb-0 dark font-weight-bold">Summary Komisi</h6>
          <div class="row ml-0">
            <div class="pl-0 col-4">
              <div class="card bg-warning bg-light mb-0 mt-1">
                <div class="card-body p-1">
                  <h5 class="mb-0 dark font-weight-bold">Rp 10.000.000</h5>
                  <p class="card-text mb-0 dark" style="padding-bottom: 5px;">Total Diperoleh</p>
                </div>
              </div>
            </div>
            <div class="pl-0 col-4">
              <div class="card bg-success bg-light mb-0 mt-1">
                <div class="card-body p-1">
                  <h5 class="mb-0 font-weight-bold">Rp 10.000.000</h5>
                  <p class="card-text mb-0 dark" style="padding-bottom: 5px;">Total Dibayarkan</p>
                </div>
              </div>
            </div>
            <div class="pl-0 col-4">
              <div class="card bg-info bg-light mb-0 mt-1">
                <div class="card-body p-1">
                  <h5 class="mb-0 font-weight-bold">Rp 10.000.000</h5>
                  <p class="card-text mb-0 dark" style="padding-bottom: 5px;">Total Saldo Komisi</p>
                </div>
              </div>
            </div>
          </div>
          <div class="row ml-0">
            <div class="pl-0 col-12">
              <?php for ($i = 1; $i <= 3; $i++) { ?>
              <div class="card mb-0 mt-1">
                <div class="card-body p-1">
                  <div class="row ml-0">
                    <div class="pl-0 col-3">
                      <p class="card-text mb-0 dark" style="padding-bottom: 5px;">Komisi</p>
                      <p class="card-text mb-0 secondary font-weight-bold">Sponsor</p>
                    </div>
                    <div class="pl-0 col-3">
                      <p class="card-text mb-0 dark" style="padding-bottom: 5px;">Diperoleh</p>
                      <p class="card-text mb-0 warning font-weight-bold">Rp 1.000.000</p>
                    </div>
                    <div class="pl-0 col-3">
                      <p class="card-text mb-0 dark" style="padding-bottom: 5px;">Dibayarkan</p>
                      <p class="card-text mb-0 success font-weight-bold">Rp 1.000.000</p>
                    </div>
                    <div class="pl-0 col-3">
                      <p class="card-text mb-0 dark" style="padding-bottom: 5px;">Saldo</p>
                      <p class="card-text mb-0 info font-weight-bold">Rp 1.000.000</p>
                    </div>
                  </div>
                </div>
              </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
		</div>
	</div>
</div>
<!-- END: Content-->

<?php
include "components/footer.php";
?>