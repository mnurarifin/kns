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
						<h5 class="content-header-title float-left pr-1 mb-0">Riwayat Komisi</h5>

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
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12 col-12">
          <div class="card">
            <div class="card-body p-2 text-center">
              <div class="row">
                <div class="col-md-12 col-12">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="week1-tab" data-toggle="tab" href="#week1" aria-controls="week1" role="tab" aria-selected="true">
                        <i class="bx bx-calendar align-middle"></i>
                        <span class="align-middle">Minggu Ke-1</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="week2-tab" data-toggle="tab" href="#week2" aria-controls="week2" role="tab" aria-selected="false">
                        <i class="bx bx-calendar align-middle"></i>
                        <span class="align-middle">Minggu Ke-2</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="week3-tab" data-toggle="tab" href="#week3" aria-controls="week3" role="tab" aria-selected="false">
                        <i class="bx bx-calendar align-middle"></i>
                        <span class="align-middle">Minggu Ke-3</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="week4-tab" data-toggle="tab" href="#week4" aria-controls="week4" role="tab" aria-selected="false">
                        <i class="bx bx-calendar align-middle"></i>
                        <span class="align-middle">Minggu Ke-4</span>
                      </a>
                    </li>
                  </ul>
  
                  <div class="tab-content">
                    <div class="tab-pane active" id="week1" aria-labelledby="week1-tab" role="tabpanel">
                      <div class="card mb-1">
                        <div class="table-responsive">
                          <table id="table-extended-success" class="table mb-0">
                            <thead>
                              <tr>
                                <th class="text-left">Hari</th>
                                <th class="text-left">Tanggal</th>
                                <th class="text-right">Pasangan (Rp)</th>
                                <th class="text-right">Sponsor (Rp)</th>
                                <th class="text-right">Unilevel (Rp)</th>
                                <th class="text-right">Total (Rp)</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php for ($i = 1; $i <= 6; $i++) { ?>
                              <tr>
                                <td class="text-left">Sabtu</td>
                                <td class="text-left">25 Juli 2022</td>
                                <td class="text-right">Rp 600.000</td>
                                <td class="text-right">Rp 0</td>
                                <td class="text-right">Rp 6.000</td>
                                <td class="text-right">
                                  <div class="p-1 badge badge-pill badge-success" style="font-weight: bold; font-size: 14px;">Rp 606.000</div>
                                </td>
                              </tr>
                              <?php } ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="week2" aria-labelledby="week2-tab" role="tabpanel">
                      <div class="card mb-1">
                        <div class="table-responsive">
                          <table id="table-extended-success" class="table mb-0">
                            <thead>
                              <tr>
                                <th class="text-left">Hari</th>
                                <th class="text-left">Tanggal</th>
                                <th class="text-right">Pasangan (Rp)</th>
                                <th class="text-right">Sponsor (Rp)</th>
                                <th class="text-right">Unilevel (Rp)</th>
                                <th class="text-right">Total (Rp)</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php for ($i = 1; $i <= 6; $i++) { ?>
                              <tr>
                                <td class="text-left">Sabtu</td>
                                <td class="text-left">25 Juli 2022</td>
                                <td class="text-right">Rp 600.000</td>
                                <td class="text-right">Rp 0</td>
                                <td class="text-right">Rp 6.000</td>
                                <td class="text-right">
                                  <div class="p-1 badge badge-pill badge-success" style="font-weight: bold; font-size: 14px;">Rp 606.000</div>
                                </td>
                              </tr>
                              <?php } ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="week3" aria-labelledby="week3-tab" role="tabpanel">
                      <div class="card mb-1">
                        <div class="table-responsive">
                          <table id="table-extended-success" class="table mb-0">
                            <thead>
                              <tr>
                                <th class="text-left">Hari</th>
                                <th class="text-left">Tanggal</th>
                                <th class="text-right">Pasangan (Rp)</th>
                                <th class="text-right">Sponsor (Rp)</th>
                                <th class="text-right">Unilevel (Rp)</th>
                                <th class="text-right">Total (Rp)</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php for ($i = 1; $i <= 6; $i++) { ?>
                              <tr>
                                <td class="text-left">Sabtu</td>
                                <td class="text-left">25 Juli 2022</td>
                                <td class="text-right">Rp 600.000</td>
                                <td class="text-right">Rp 0</td>
                                <td class="text-right">Rp 6.000</td>
                                <td class="text-right">
                                  <div class="p-1 badge badge-pill badge-success" style="font-weight: bold; font-size: 14px;">Rp 606.000</div>
                                </td>
                              </tr>
                              <?php } ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="week4" aria-labelledby="week4-tab" role="tabpanel">
                      <div class="card mb-1">
                        <div class="table-responsive">
                          <table id="table-extended-success" class="table mb-0">
                            <thead>
                              <tr>
                                <th class="text-left">Hari</th>
                                <th class="text-left">Tanggal</th>
                                <th class="text-right">Pasangan (Rp)</th>
                                <th class="text-right">Sponsor (Rp)</th>
                                <th class="text-right">Unilevel (Rp)</th>
                                <th class="text-right">Total (Rp)</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php for ($i = 1; $i <= 6; $i++) { ?>
                              <tr>
                                <td class="text-left">Sabtu</td>
                                <td class="text-left">25 Juli 2022</td>
                                <td class="text-right">Rp 600.000</td>
                                <td class="text-right">Rp 0</td>
                                <td class="text-right">Rp 6.000</td>
                                <td class="text-right">
                                  <div class="p-1 badge badge-pill badge-success" style="font-weight: bold; font-size: 14px;">Rp 606.000</div>
                                </td>
                              </tr>
                              <?php } ?>
                            </tbody>
                          </table>
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
</div>
<!-- END: Content-->

<?php
include "components/footer.php";
?>