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
						<h5 class="content-header-title float-left pr-1 mb-0">Sponsor Pribadi</h5>

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
        <div class="col-md-12 col-12">
          <div class="card bg-primary bg-light mb-1">
            <div class="card-body p-1">
              <div class="row">
                <div class="col-md-6 col-12">
                  <div class="row">
                    <div class="col-md-5 col-12">
                      <div class="d-flex">
                        <i class="bx bx-user-circle my-auto" style="font-size: 3rem;"></i>
                        <div class="pl-1">
                          <p class="card-text mb-0 dark" style="padding-bottom: 5px;">Kode Mitra</p>
                          <h5 class="mb-0 dark font-weight-bold">MLM1000001</h5>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-5 col-12">
                      <div class="d-flex">
                        <i class="bx bx-bar-chart-alt my-auto" style="font-size: 3rem;"></i>
                        <div class="pl-1">
                          <p class="card-text mb-0 dark" style="padding-bottom: 5px;">Level</p>
                          <h5 class="mb-0 dark font-weight-bold">0</h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-12 my-auto text-right">
                  <button type="button" class="btn btn-white">
                    <span class="pr-1 primary">Level Sebelumnya</span>
                    <i class="bx bx-up-arrow-circle primary"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-12">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <div class="card mb-1">
            <div class="card-body p-1">
              <div class="row">
                <div class="col-12">
                  <div class="row">
                    <div class="col-md-4 col-12">
                      <div class="d-flex">
                        <img class="rounded-pill" src="../../../../images/portrait/small/avatar-s-11.jpg" alt="avatar" height="80" width="80">
                        <div class="pl-1 my-auto">
                          <p class="card-text mb-0 primary font-weight-bold" style="padding-bottom: 2px;">MLM1000001</p>
                          <p class="card-text mb-0 dark" style="padding-bottom: 2px;">John Doe</p>
                          <p class="card-text mb-0 dark">Kota Yogyakarta</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2 col-12 pl-md-0 my-auto">
                      <p class="card-text mb-0 dark" style="padding-bottom: 2px;">No. HP / Email</p>
                      <p class="card-text mb-0 secondary" style="padding-bottom: 2px;">08123456789</p>
                      <p class="card-text mb-0 secondary" style="padding-bottom: 2px;">isa@gmail.com</p>
                    </div>
                    <div class="col-md-2 col-12 my-auto">
                      <p class="card-text mb-0 dark" style="padding-bottom: 2px;">Tgl. Pendaftaran</p>
                      <p class="card-text mb-0 secondary">1 Januari 1980</p>
                    </div>
                    <div class="col-md-2 col-12 my-auto text-center">
                      <p class="card-text mb-0 dark" style="padding-bottom: 2px;">Sponsoring</p>
                      <p class="card-text mb-0 secondary">10</p>
                    </div>
                    <div class="col-md-2 col-12 my-auto text-right">
                      <p class="card-text mb-0 dark" style="padding-bottom: 2px;">Kemitraan</p>
                      <p class="card-text mb-0 dark font-weight-bold">Silver</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
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

<?php
include "components/footer.php";
?>