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

    <div class="row" id="summary_point"></div>

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
        <h3 class="modal-title" id="myModalLabel1"></h3>
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
                  <th class="text-left">Produk</th>
                  <th class="text-right">Harga</th>
                  <th class="text-right">Jumlah</th>
                  <th class="text-right">Sub Total</th>
                </tr>
              </thead>
              <tbody>
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
    let datatable = []

    $("#data_kosong").hide()

    getTransactionPayment = () => {
      $("#table").dataTableLib({
        url: window.location.origin + "/member/transaction/get-point-log/",
        selectID: "ro_balance_log_id",
        colModel: [{
            display: "Tanggal",
            name: "ro_point_log_datetime_formatted",
            align: "left",
          },
          {
            display: "Jumlah",
            name: "ro_balance_log_value",
            align: "left",
            render: (params) => {
              return formatDecimal(params)
            }
          },
          {
            display: "Tipe",
            name: "ro_balance_log_type",
            align: "left",
            render: (params) => {
              return params == "in" ? "Masuk" : "Keluar"
            }
          },
          {
            display: "Detail",
            name: "ro_balance_log_id",
            align: "right",
            render: (params, data) => {
              return data.ro_balance_log_type == "out" ? `` : `<button class="btn btn-primary" onclick="showModalDetail(${params});">Detail</button>`
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
          display: "Tanggal",
          name: "ro_balance_log_datetime",
          type: "date"
        }, ],
        sortName: "ro_balance_log_datetime",
        sortOrder: "DESC",
        tableIsResponsive: true,
        select: false,
        multiSelect: false,
        buttonAction: [],
        success: (res) => {
          datatable = res.data.results
          $("#summary_point").append(`
          <div class="col col-4">
            <div class="card p-1">
              <label class="label">Saldo Belanja Diperoleh</label>
              <h1 class="mt-1 mb-0 text-right text-warning">${formatDecimal(res.data.point.point_total)}</h1>
            </div>
          </div>
          <div class="col col-4">
            <div class="card p-1">
              <label class="label">Saldo Belanja Digunakan</label>
              <h1 class="mt-1 mb-0 text-right text-danger">${formatDecimal(res.data.point.point_used)}</h1>
            </div>
          </div>
          <div class="col col-4">
            <div class="card p-1">
              <label class="label">Total Saldo Belanja</label>
              <h1 class="mt-1 mb-0 text-right text-success">${formatDecimal(res.data.point.point_balance)}</h1>
            </div>
          </div>
          `)
        }
      })
    }

    showModalDetail = (id) => {
      let ro_point_log = datatable.find(o => o.ro_balance_log_id == id)
      let total = 0
      $("#table-detail tbody").empty()
      $.each(ro_point_log.transaction.detail, (i, val) => {
        $("#table-detail tbody").append(`
        <tr class="">
          <td class="text-left">${val.warehouse_transaction_detail_product_name}</td>
          <td class="text-right">${formatCurrency(val.warehouse_transaction_detail_unit_price)}</td>
          <td class="text-right">${val.warehouse_transaction_detail_quantity}</td>
          <td class="text-right">${formatCurrency(parseInt(val.warehouse_transaction_detail_quantity) * parseInt(val.warehouse_transaction_detail_unit_price))}</td>
        </tr>
        `)
        total += parseInt(val.warehouse_transaction_detail_quantity) * parseInt(val.warehouse_transaction_detail_unit_price)
      })
      $("#table-detail tbody").append(`
      <tr class="">
        <th class="text-left" colspan="3">Total</th>
        <td class="text-right">${formatCurrency(total)}</td>
      </tr>
      `)
      $("#btn_upload").data("id", id)
      $("#modal-detail .modal-title").html(`Detail Riwayat Saldo Belanja RO`)
      $("#modal-detail").modal("show")
    }

    $("#input_warehouse_transaction_payment_image").on("change", (ev) => {
      const [file] = ev.target.files
      if (file) {
        $("#img_transaction_payment_image").prop("src", URL.createObjectURL(file))
        $("#btn_upload").prop("disabled", false)
      }
    })

    formatCurrency = ($params) => {
      let formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      })
      return formatter.format($params)
    }

    formatDecimal = ($params) => {
      let formatter = new Intl.NumberFormat('id-ID', {
        style: 'decimal',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      })
      return formatter.format($params)
    }

    getTransactionPayment()
  })
</script>
<!-- END: Content-->