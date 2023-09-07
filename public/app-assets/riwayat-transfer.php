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
						<h5 class="content-header-title float-left pr-1 mb-0">Riwayat Transfer</h5>

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
      <div class="row justify-content-center">
        <div class="col-md-6 col-12">
          <div class="card">
            <div class="card-body p-2 text-center">
              <h4 class="mb-50 text-danger font-weight-bold">Rp 10.000.000</h4>
              <p class="card-text mb-0 dark" style="padding-bottom: 5px;">Transfer Komisi Terakhir</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-12">
          <div class="card">
            <div class="card-body p-2 text-center">
              <h4 class="mb-50 text-primary font-weight-bold">Rp 10.000.000</h4>
              <p class="card-text mb-0 dark" style="padding-bottom: 5px;">Total Komisi Yang Telah Ditransfer</p>
            </div>
          </div>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-md-12 col-12">
          <div class="card mb-1">
            <div class="card-body p-2 text-center">
              <div class="row justify-content-end">
                <div class="col-6 d-flex justify-content-end">
                  <form class="form-inline">
                    <div class="form-group">
                      <label for="inputDaterange">Tampilkan berdasarkan</label>
                    </div>
                    <div class="form-group ml-sm-1 mr-sm-50">
                      <fieldset class="form-group position-relative has-icon-left">
                        <input type="text" class="form-control daterange" id="inputDaterange" placeholder="Pilih rentang tanggal transfer" style="width: 250px;">
                        <div class="form-control-position">
                            <i class='bx bx-calendar-check'></i>
                        </div>
                      </fieldset>
                    </div>
                    <button type="submit" class="btn btn-light">Reset</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12 col-12">
          <div class="card">
            <div class="card-body p-2 text-center">
              <div class="col-md-12 col-12">
                <div id="table-success">
                  <div class="card mb-1">
                    <div class="table-responsive">
                      <table id="table-extended-success" class="table mb-0">
                        <thead>
                          <tr>
                            <th align="center">Aksi</th>
                            <th class="text-left">Periode</th>
                            <th class="text-left">Tgl. Transfer</th>
                            <th class="text-right">Jumlah</th>
                            <th>Status</th>
                            <th class="text-left">Keterangan</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php for ($i = 1; $i <= 5; $i++) { ?>
                          <tr>
                            <td align="center">
                              <button class="d-flex align-center justify-content-center rounded-pill bg-dark white" style="width: 35px; height: 35px">
                                <i class='bx bx-printer'></i>
                              </button>
                            </td>
                            <td class="text-left">10 Juni 2022</td>
                            <td class="text-left">25 Juli 2022</td>
                            <td class="text-right">Rp 1.600.000</td>
                            <td><div class="badge badge-pill badge-success">SUKSES</div></td>
                            <td class="text-left">
                              Transfer Komisi Harian admin
                            </td>
                          </tr>
                          <tr>
                            <td align="center">
                              <button class="d-flex align-center justify-content-center rounded-pill bg-dark white" style="width: 35px; height: 35px">
                                <i class='bx bx-printer'></i>
                              </button>
                            </td>
                            <td class="text-left">10 Juni 2022</td>
                            <td class="text-left">25 Juli 2022</td>
                            <td class="text-right">Rp 1.600.000</td>
                            <td><div class="badge badge-pill badge-warning">PENDING</div></td>
                            <td class="text-left">
                              Transfer Komisi Harian admin
                            </td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
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
    </div>
  </div>
</div>
<!-- END: Content-->

<?php
include "components/footer.php";
?>