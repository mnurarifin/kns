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
        <div class="row mb-50 align-center" id="modal_pickup">
          <div class="col-sm-12">
            <label for="" class="mb-1">Produk dapat diambil di
              <a class="text-primary" onclick="window.open('<?= DELIVERY_WAREHOUSE_MAPS ?>', '_blank').focus()"><?= DELIVERY_WAREHOUSE_ADDRESS ?></a></label>
          </div>
        </div>
        <div class="row mb-3 align-center" id="modal_courier">
          <div class="col-sm-12">
            <label for="" class="mb-1">Data Pengiriman</label>
            <div class="row">
              <div class="col col-6">
                <label for="text_warehouse_transaction_delivery_receiver_name">Nama Penerima</label>
                <div id="text_warehouse_transaction_delivery_receiver_name"></div>
              </div>
              <div class="col col-6">
                <label for="text_warehouse_transaction_delivery_receiver_mobilephone">No. HP Penerima</label>
                <div id="text_warehouse_transaction_delivery_receiver_mobilephone"></div>
              </div>
              <div class="col col-12">
                <label for="text_warehouse_transaction_delivery_receiver_address">Alamat Penerima</label>
                <div>
                  <span id="text_warehouse_transaction_delivery_receiver_address"></span>,
                  <span id="text_warehouse_transaction_delivery_receiver_subdistrict_name"></span>,
                  <span id="text_warehouse_transaction_delivery_receiver_city_name"></span>,
                  <span id="text_warehouse_transaction_delivery_receiver_province_name"></span>
                </div>
              </div>
              <div class="col col-6 d-none">
                <label for="text_warehouse_transaction_delivery_courier_code">Jasa Kurir</label>
                <div id="text_warehouse_transaction_delivery_courier_code"></div>
              </div>
              <div class="col col-6 d-none">
                <label for="text_warehouse_transaction_delivery_courier_service">Layanan</label>
                <div id="text_warehouse_transaction_delivery_courier_service"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row mb-50 align-center">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="input_warehouse_transaction_payment_image">Upload Bukti Transfer</label>
              <input type="file" class="form-control" id="input_warehouse_transaction_payment_image" placeholder="" value="">
              <small class="text-danger alert-input" id="alert_input_warehouse_transaction_payment_image" style="display: none;"></small>
            </div>
          </div>
          <div class="col-sm-6">
            <img src="" class="w-100" id="img_transaction_payment_image">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn_upload" disabled>
          <span class="">Upload</span>
        </button>
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
        url: window.location.origin + "/member/transaction/get-transaction-payment/",
        selectID: "warehouse_transaction_id",
        colModel: [{
            display: "Tanggal",
            name: "warehouse_transaction_datetime_formatted",
            align: "left",
          },
          {
            display: "Keterangan",
            name: "warehouse_transaction_notes",
            align: "left",
          },
          {
            display: "Detail",
            name: "warehouse_transaction_id",
            align: "right",
            render: (params, data) => {
              return `<button class="btn btn-primary" onclick="showModalDetail(${params});">Detail</button>`
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
            name: "ro_personal_datetime",
            type: "date"
          },
          {
            display: "Serial",
            name: "ro_personal_serial_ro_id",
            type: "text"
          },
        ],
        sortName: "warehouse_transaction_datetime",
        sortOrder: "DESC",
        tableIsResponsive: true,
        select: false,
        multiSelect: false,
        buttonAction: [],
        success: (res) => {
          datatable = res.data.results
          checkRedirect()
        }
      })
    }

    showModalDetail = (id, data = false) => {
      console.log(id)
      let transaction = data ? data[0] : datatable.find(o => o.warehouse_transaction_id == id)
      let total = 0
      $("#table-detail tbody").empty()
      $.each(transaction.detail, (i, val) => {
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
      <tr class="">
        <td class="text-left" colspan="3">Ongkir</td>
        <td class="text-right">${formatCurrency(transaction.warehouse_transaction_delivery_cost)}</td>
      </tr>
      <tr class="">
        <th class="text-left" colspan="3">Jumlah Transfer</th>
        <th class="text-right">${formatCurrency(parseInt(transaction.warehouse_transaction_delivery_cost) + parseInt(total))}</th>
      </tr>
      `)
      if (transaction.warehouse_transaction_delivery_method == "courier") {
        $("#modal_pickup").hide()
        $("#modal_courier").show()
        $("#text_warehouse_transaction_delivery_receiver_name").html(transaction.warehouse_transaction_delivery_receiver_name)
        $("#text_warehouse_transaction_delivery_receiver_mobilephone").html(transaction.warehouse_transaction_delivery_receiver_mobilephone)
        $("#text_warehouse_transaction_delivery_receiver_address").html(transaction.warehouse_transaction_delivery_receiver_address)
        $("#text_warehouse_transaction_delivery_receiver_province_name").html(transaction.warehouse_transaction_delivery_receiver_province_name)
        $("#text_warehouse_transaction_delivery_receiver_city_name").html(transaction.warehouse_transaction_delivery_receiver_city_name)
        $("#text_warehouse_transaction_delivery_receiver_subdistrict_name").html(transaction.warehouse_transaction_delivery_receiver_subdistrict_name)
        $("#text_warehouse_transaction_delivery_courier_code").html(transaction.warehouse_transaction_delivery_courier_code.toUpperCase())
        $("#text_warehouse_transaction_delivery_courier_service").html(transaction.warehouse_transaction_delivery_courier_service)
      } else {
        $("#modal_pickup").show()
        $("#modal_courier").hide()
      }
      $("#img_transaction_payment_image").prop("src", transaction.warehouse_transaction_payment_image)
      $("#btn_upload").data("id", id)
      $("#modal-detail .modal-title").html(`Detail Transaksi ${transaction.warehouse_transaction_code}`)
      $("#modal-detail").modal("show")
    }

    $("#input_warehouse_transaction_payment_image").on("change", (ev) => {
      const [file] = ev.target.files
      if (file) {
        $("#img_transaction_payment_image").prop("src", URL.createObjectURL(file))
        $("#btn_upload").prop("disabled", false)
      }
    })

    $("#btn_upload").on("click", (ev) => {
      let data = new FormData()
      data.append("warehouse_transaction_payment_image", $("#input_warehouse_transaction_payment_image").prop("files")[0])
      data.append("warehouse_transaction_id", $("#btn_upload").data("id"))
      $.ajax({
        url: "<?= BASEURL ?>/member/transaction/upload-transaction-payment",
        type: "POST",
        processData: false,
        contentType: false,
        data: data,
        success: (res) => {
          data = res.data.results
          $(".alert-input").hide()
          $("#alert-success").html(res.message)
          $("#alert-success").show()
          setTimeout(function() {
            $("#alert-success").hide()
          }, 3000);
          $("#modal-detail").modal("hide")
          getTransactionPayment()
        },
        error: (err) => {
          res = err.responseJSON
          $(".alert-input").hide()
          $("#alert").html(res.message)
          $("#alert").show()
          setTimeout(function() {
            $("#alert").hide()
          }, 3000);
          if (res.error == "validation") {
            $.each(res.data, (i, val) => {
              $(`#alert_input_${i}`).html(val).show()
            })
            setTimeout(function() {
              $(".alert-input").hide()
            }, 3000);
          }
        },
      })
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

    checkRedirect = () => {
      let params = new URLSearchParams(window.location.search)
      if (params.get("id")) {
        $.ajax({
          url: `<?= BASEURL ?>/member/transaction/get-transaction-payment?id=${params.get("id")}`,
          type: "GET",
          success: (res) => {
            data = res.data.results
            showModalDetail(data[0].warehouse_transaction_id, data)
          },
          error: () => {
            Swal.fire({
              title: 'Transaksi tidak ditemukan / belum upload bukti transaksi.',
              icon: 'error',
              showCancelButton: true,
              cancelButtonText: `<span style="color: #475F7B;">Tutup</span>`,
              cancelButtonColor: '#E6EAEE',
              showConfirmButton: false,
            })
          }
        })
      }
    }

    getTransactionPayment()
  })
</script>
<!-- END: Content-->