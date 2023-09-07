<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-overlay">
  </div>
  <div class="content-wrapper">
    <div class="alert alert-danger" role="alert" id="alert" style="position: absolute; top: 84px; right: 32px; display: none;"></div>
    <div class="alert alert-success" role="alert" id="alert-success" style="position: absolute; top: 84px; right: 32px; display: none;"></div>

    <div class="content-header row">
      <div class="content-header-left col-12 mb-2 mt-1">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h5 class="content-header-title float-left pr-1 mb-0"><?= $title ?></h5>

            <div class="breadcrumb-wrapper col-12">
              <ol class="breadcrumb p-0 mb-0">
                <li class="breadcrumb-item"><a href="<?= BASEURL ?>/member/dashboard"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="#"><?= $breadcrumbs[0] ?></a></li>
                <li class="breadcrumb-item active"><?= $breadcrumbs[1] ?></li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="content-body">
      <div class="row">
        <div class="col-md-12 col-12">
          <div class="mb-1" id="table">
          </div>
          <div class="card p-2" id="data_kosong">
            <div class="row">
              <div class="col-md-12 d-flex justify-content-center">
                <img src="<?= BASEURL ?>/app-assets/images/no-data-green.svg" alt="
                  style=" filter: grayscale(100%);">
              </div>
              <div class="col-md-12 d-flex justify-content-center mt-3">
                <label>Tidak ada informasi yang ditampilkan</label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(function() {
    $("#data_kosong").hide()

    let OTP = <?php echo session()->has("otp") && session("otp") ? 'true' : 'false' ?>

    getRewardLog = () => {
      $("#table").dataTableLib({
        url: window.location.origin + "/member/reward/get-reward-log-qualified/",
        selectID: "reward_qualified_id",
        colModel: [{
            display: "Tanggal",
            name: "reward_qualified_datetime_formatted",
            align: "left",
          },
          {
            display: "Reward",
            name: "reward_qualified_reward_title",
            align: "left",
          },
          {
            display: "Poin Trip",
            name: "reward_trip_point_formatted",
            align: "center",
          },
          {
            display: "Status Klaim",
            name: "reward_qualified_claim",
            align: "left",
            render: (params) => {
              return params == "claimed" ? "Sudah Diklaim" : "Belum Diklaim"
            }
          },
          {
            display: "Status Approval",
            name: "reward_qualified_status",
            align: "left",
            render: (params) => {
              return params == "pending" ? `<span class="badge badge-light-warning badge-pill">Menunggu Approval Admin</span>` : params == "approved" ? `<span class="badge badge-light-success badge-pill">Disetujui Admin</span>` : `<span class="badge badge-light-danger badge-pill">Ditolak Admin</span>`
            }
          },
        ],
        options: {
          limit: [10, 15, 20, 50, 100],
          currentLimit: 10,
        },
        search: true,
        searchTitle: "Pencarian",
        searchItems: [{
          display: "Tanggal",
          name: "reward_qualified_datetime",
          type: "date"
        }, ],
        sortName: "reward_qualified_datetime",
        sortOrder: "DESC",
        tableIsResponsive: true,
        select: false,
        multiSelect: false,
        buttonAction: [],
      })
    }

    getRewardLog()
  })
</script>
<!-- END: Content-->