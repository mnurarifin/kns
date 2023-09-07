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

<!-- Modal -->
<div class="modal fade text-left" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="myModalLabel1">Detail Riwayat Komisi Matching</h3>
        <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
          <i class="bx bx-x"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="row mb-50 align-center">
          <div class="col-12 d-flex flex-row align-center p-0">
            <table class="table w-100" id="table-detail">
              <thead>
                <tr>
                  <th class="text-center">Tanggal</th>
                  <th class="text-center">Keterangan</th>
                  <th class="text-center">Bonus</th>
                </tr>
              </thead>
              <tbody>
                <tr id="total">
                  <th class="text-right" colspan="2">Total</th>
                  <th class="text-right"><span id="total_bonus">0</span></th>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
          <i class="bx bx-x d-block d-sm-none"></i>
          <span class="d-none d-sm-block">Tutup</span>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->

<script>
  $(function() {
    $("#data_kosong").hide()

    let data

    getBonusTransfer = () => {
      $("#table").dataTableLib({
        url: window.location.origin + "/member/bonus/get-bonus-transfer",
        selectID: "bonus_transfer_id",
        colModel: [{
            display: 'Tanggal',
            name: 'bonus_transfer_date_formatted',
            align: 'left',
          },
          {
            display: 'Sponsor',
            name: 'bonus_transfer_sponsor',
            align: 'right',
            render: (params) => {
              return `<span>${formatCurrency(params)}</span>`
            },
          },
          {
            display: 'Generasi',
            name: 'bonus_transfer_gen_node',
            align: 'right',
            render: (params) => {
              return `<span>${formatCurrency(params)}</span>`
            },
          },
          {
            display: 'Power Leg',
            name: 'bonus_transfer_power_leg',
            align: 'right',
            render: (params) => {
              return `<span>${formatCurrency(params)}</span>`
            },
          },
          {
            display: 'Matching Leg',
            name: 'bonus_transfer_matching_leg',
            align: 'right',
            render: (params, data) => {
              return `<span>${formatCurrency(params)}</span>`
            },
          },
          {
            display: 'Cash Reward',
            name: 'bonus_transfer_cash_reward',
            align: 'right',
            render: (params, data) => {
              return `<span>${formatCurrency(params)}</span>`
            },
          },
          {
            display: 'Total',
            name: 'total',
            align: 'right',
            render: (params) => {
              return `<span>${formatCurrency(params)}</span>`
            },
          },
          {
            display: 'Statement Komisi',
            name: 'bonus_transfer_id',
            align: 'right',
            render: (params) => {
              return `<button class="btn text-info" onclick="location.href = '/member/bonus/statement?id=${params}';"><i class='bx bx-notepad'></i></button>`
            },
          },
        ],
        options: {
          limit: [10, 15, 20, 50, 100],
          currentLimit: 10,
        },
        search: true,
        searchTitle: "Pencarian",
        searchItems: [{
          display: 'Tanggal',
          name: 'bonus_transfer_date',
          type: 'date'
        }, ],
        sortName: "bonus_transfer_date",
        sortOrder: "DESC",
        tableIsResponsive: true,
        select: false,
        multiSelect: false,
        buttonAction: [],
        success: (res) => {
          data = res.data.results
        }
      })
    }

    formatCurrency = ($params) => {
      let formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      })
      return formatter.format($params)
    }

    getBonusTransfer()
  })
</script>
<!-- END: Content-->