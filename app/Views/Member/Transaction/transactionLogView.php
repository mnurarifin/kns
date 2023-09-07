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
              <a class="text-primary" onclick="window.open('<?= DELIVERY_WAREHOUSE_MAPS ?>', '_blank').focus()"><?= DELIVERY_WAREHOUSE_ADDRESS ?></a> </label>
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
              <div class="col col-6">
                <label for="text_warehouse_transaction_delivery_courier_code">Jasa Kurir</label>
                <div id="text_warehouse_transaction_delivery_courier_code"></div>
              </div>
              <div class="col col-6">
                <label for="text_warehouse_transaction_delivery_courier_service">Layanan</label>
                <div id="text_warehouse_transaction_delivery_courier_service"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row mb-50 align-center">
          <div class="col-sm-6">
            <label for="img_transaction_payment_image">Bukti Transfer</label><br />
            <img src="" class="w-100" id="img_transaction_payment_image">
            <svg class="no-image" style="display: none; width: 150px;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 172 172">
              <g fill="none" fill-rule="nonzero" stroke="none" stroke-width="none" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                <path d="M0,172v-172h172v172z" fill="none" stroke="none" stroke-width="1"></path>
                <g fill="#6f2abb" stroke="none" stroke-width="1">
                  <path d="M10.28641,6.84641c-1.39982,0.00037 -2.65984,0.84884 -3.18658,2.14577c-0.52674,1.29693 -0.21516,2.7837 0.78799,3.76001l151.36,151.36c0.86281,0.89867 2.14404,1.26068 3.34956,0.94641c1.20552,-0.31427 2.14696,-1.2557 2.46122,-2.46122c0.31427,-1.20552 -0.04774,-2.48675 -0.94641,-3.34956l-11.32781,-11.32781h12.33563v-123.84h-136.17562l-16.19219,-16.19219c-0.64765,-0.66575 -1.53698,-1.04135 -2.46578,-1.04141zM6.88,24.08v123.84h126.27219l-6.37609,-6.3761l-33.68781,-33.68781l-8.0961,8.09609c-1.34504,1.34504 -3.51933,1.34504 -4.86437,0c-1.34504,-1.34504 -1.34504,-3.51933 0,-4.86437l8.09609,-8.0961l-34.59484,-34.59485l-39.86906,37.20844v-74.64531h2.43219l-6.88,-6.88zM35.82437,30.96h122.41563v94.89563l-38.84781,-38.84781c-1.34504,-1.34504 -3.51933,-1.34504 -4.86437,0l-11.32781,11.32781zM120.4,51.6c-5.69958,0 -10.32,4.62042 -10.32,10.32c0,5.69958 4.62042,10.32 10.32,10.32c5.69958,0 10.32,-4.62042 10.32,-10.32c0,-5.69958 -4.62042,-10.32 -10.32,-10.32z"></path>
                </g>
                <g fill="#6f2abb" stroke="none" stroke-width="1">
                  <path d="M34.015,164.92h-4.43v-12.8h4.18c1.38667,0 2.43,0.28667 3.13,0.86c0.7,0.58 1.05,1.43 1.05,2.55v0c0,0.6 -0.17,1.13 -0.51,1.59c-0.34,0.46 -0.80333,0.81667 -1.39,1.07v0c0.69333,0.19333 1.24,0.56 1.64,1.1c0.40667,0.54 0.61,1.18667 0.61,1.94v0c0,1.14667 -0.37333,2.05 -1.12,2.71c-0.74667,0.65333 -1.8,0.98 -3.16,0.98zM34.085,158.93h-2.82v4.61h2.79c0.78667,0 1.40667,-0.20333 1.86,-0.61c0.45333,-0.40667 0.68,-0.96667 0.68,-1.68v0c0,-1.54667 -0.83667,-2.32 -2.51,-2.32zM31.265,153.51v4.07h2.55c0.74,0 1.33,-0.18333 1.77,-0.55c0.44667,-0.37333 0.67,-0.87667 0.67,-1.51v0c0,-0.7 -0.20667,-1.21 -0.62,-1.53c-0.40667,-0.32 -1.03,-0.48 -1.87,-0.48v0zM44.475,165.1v0c-1.28667,0 -2.33333,-0.42333 -3.14,-1.27c-0.81333,-0.84667 -1.22,-1.98 -1.22,-3.4v0v-0.3c0,-0.94667 0.18,-1.79 0.54,-2.53c0.36,-0.74 0.86667,-1.32 1.52,-1.74c0.64667,-0.42 1.34667,-0.63 2.1,-0.63v0c1.24,0 2.2,0.41 2.88,1.23c0.68667,0.81333 1.03,1.97667 1.03,3.49v0v0.68h-6.44c0.02,0.94 0.29333,1.69667 0.82,2.27c0.52667,0.58 1.19333,0.87 2,0.87v0c0.57333,0 1.06,-0.11667 1.46,-0.35c0.4,-0.23333 0.75,-0.54333 1.05,-0.93v0l0.99,0.77c-0.8,1.22667 -1.99667,1.84 -3.59,1.84zM44.275,156.57v0c-0.65333,0 -1.20333,0.24 -1.65,0.72c-0.44667,0.47333 -0.72333,1.14333 -0.83,2.01v0h4.77v-0.13c-0.04667,-0.82667 -0.27,-1.46667 -0.67,-1.92c-0.4,-0.45333 -0.94,-0.68 -1.62,-0.68zM51.835,151.42v13.5h-1.63v-13.5zM60.345,164.92l-0.04,-0.94c-0.63333,0.74667 -1.56,1.12 -2.78,1.12v0c-1.01333,0 -1.78667,-0.29667 -2.32,-0.89c-0.53333,-0.58667 -0.8,-1.45667 -0.8,-2.61v0v-6.19h1.62v6.14c0,1.44667 0.58667,2.17 1.76,2.17v0c1.24,0 2.06667,-0.46333 2.48,-1.39v0v-6.92h1.62v9.51zM64.345,155.41h1.54l0.05,1.05c0.69333,-0.82 1.63333,-1.23 2.82,-1.23v0c1.32667,0 2.23,0.51 2.71,1.53v0c0.32,-0.45333 0.73333,-0.82 1.24,-1.1c0.50667,-0.28667 1.10667,-0.43 1.8,-0.43v0c2.08667,0 3.14667,1.10667 3.18,3.32v0v6.37h-1.63v-6.28c0,-0.67333 -0.15333,-1.18 -0.46,-1.52c-0.31333,-0.34 -0.83667,-0.51 -1.57,-0.51v0c-0.6,0 -1.1,0.18 -1.5,0.54c-0.4,0.36667 -0.63333,0.85333 -0.7,1.46v0v6.31h-1.63v-6.23c0,-1.38667 -0.67667,-2.08 -2.03,-2.08v0c-1.06667,0 -1.79667,0.45667 -2.19,1.37v0v6.94h-1.63zM92.155,152.12h1.7v8.7c-0.00667,1.20667 -0.38667,2.19667 -1.14,2.97c-0.75333,0.76667 -1.77333,1.19667 -3.06,1.29v0l-0.45,0.02c-1.4,0 -2.51667,-0.38 -3.35,-1.14c-0.83333,-0.75333 -1.25333,-1.79333 -1.26,-3.12v0v-8.72h1.67v8.67c0,0.92667 0.25333,1.64667 0.76,2.16c0.51333,0.51333 1.24,0.77 2.18,0.77v0c0.95333,0 1.68333,-0.25667 2.19,-0.77c0.50667,-0.50667 0.76,-1.22333 0.76,-2.15v0zM104.295,160.12v0.15c0,1.44667 -0.33,2.61333 -0.99,3.5c-0.66667,0.88667 -1.56333,1.33 -2.69,1.33v0c-1.15333,0 -2.06333,-0.36667 -2.73,-1.1v0v4.58h-1.62v-13.17h1.48l0.08,1.05c0.66667,-0.82 1.58667,-1.23 2.76,-1.23v0c1.14667,0 2.05,0.43333 2.71,1.3c0.66667,0.86 1,2.05667 1,3.59zM102.675,160.09v0c0,-1.07333 -0.23,-1.92 -0.69,-2.54c-0.46,-0.62667 -1.08667,-0.94 -1.88,-0.94v0c-0.98667,0 -1.72667,0.43667 -2.22,1.31v0v4.55c0.48667,0.86667 1.23333,1.3 2.24,1.3v0c0.78,0 1.4,-0.31 1.86,-0.93c0.46,-0.62 0.69,-1.53667 0.69,-2.75zM108.125,151.42v13.5h-1.62v-13.5zM110.295,160.19v-0.11c0,-0.93333 0.18333,-1.77333 0.55,-2.52c0.36667,-0.74 0.87667,-1.31333 1.53,-1.72c0.65333,-0.40667 1.4,-0.61 2.24,-0.61v0c1.29333,0 2.34,0.45 3.14,1.35c0.8,0.89333 1.2,2.08667 1.2,3.58v0v0.11c0,0.92667 -0.17667,1.75667 -0.53,2.49c-0.35333,0.74 -0.86,1.31333 -1.52,1.72c-0.66,0.41333 -1.41667,0.62 -2.27,0.62v0c-1.29333,0 -2.34,-0.45 -3.14,-1.35c-0.8,-0.89333 -1.2,-2.08 -1.2,-3.56zM111.935,160.27v0c0,1.05333 0.24333,1.9 0.73,2.54c0.49333,0.64 1.15,0.96 1.97,0.96v0c0.82667,0 1.48333,-0.32333 1.97,-0.97c0.48667,-0.64667 0.73,-1.55333 0.73,-2.72v0c0,-1.04667 -0.25,-1.89333 -0.75,-2.54c-0.49333,-0.64667 -1.15,-0.97 -1.97,-0.97v0c-0.8,0 -1.44667,0.32 -1.94,0.96c-0.49333,0.64 -0.74,1.55333 -0.74,2.74zM128.575,164.92h-1.71c-0.09333,-0.18667 -0.17,-0.52 -0.23,-1v0c-0.75333,0.78667 -1.65333,1.18 -2.7,1.18v0c-0.94,0 -1.71,-0.26667 -2.31,-0.8c-0.6,-0.53333 -0.9,-1.20667 -0.9,-2.02v0c0,-0.98667 0.37667,-1.75333 1.13,-2.3c0.75333,-0.55333 1.81,-0.83 3.17,-0.83v0h1.59v-0.74c0,-0.57333 -0.17,-1.02667 -0.51,-1.36c-0.34,-0.34 -0.84333,-0.51 -1.51,-0.51v0c-0.58,0 -1.06667,0.14667 -1.46,0.44c-0.38667,0.29333 -0.58,0.65 -0.58,1.07v0h-1.64c0,-0.47333 0.17,-0.93333 0.51,-1.38c0.33333,-0.44 0.79,-0.79 1.37,-1.05c0.57333,-0.26 1.20667,-0.39 1.9,-0.39v0c1.09333,0 1.95333,0.27667 2.58,0.83c0.62,0.54667 0.94,1.3 0.96,2.26v0v4.38c0,0.87333 0.11333,1.56667 0.34,2.08v0zM124.165,163.68v0c0.51333,0 0.99667,-0.13 1.45,-0.39c0.46,-0.26667 0.79333,-0.61 1,-1.03v0v-1.95h-1.28c-1.99333,0 -2.99,0.58 -2.99,1.74v0c0,0.51333 0.17,0.91333 0.51,1.2c0.34,0.28667 0.77667,0.43 1.31,0.43zM130.395,160.21v-0.12c0,-1.46 0.34333,-2.63333 1.03,-3.52c0.69333,-0.89333 1.6,-1.34 2.72,-1.34v0c1.11333,0 1.99333,0.38333 2.64,1.15v0v-4.96h1.63v13.5h-1.49l-0.08,-1.02c-0.65333,0.8 -1.56,1.2 -2.72,1.2v0c-1.1,0 -1.99667,-0.45333 -2.69,-1.36c-0.69333,-0.9 -1.04,-2.07667 -1.04,-3.53zM132.015,160.27v0c0,1.08 0.22333,1.92333 0.67,2.53c0.44667,0.61333 1.06333,0.92 1.85,0.92v0c1.02667,0 1.77667,-0.46333 2.25,-1.39v0v-4.37c-0.48667,-0.9 -1.23333,-1.35 -2.24,-1.35v0c-0.79333,0 -1.41333,0.31 -1.86,0.93c-0.44667,0.61333 -0.67,1.52333 -0.67,2.73z"></path>
                </g>
                <path d="M19.585,178.58v-37.16h128.83v37.16z" fill="#ff0000" stroke="#50e3c2" stroke-width="3" opacity="0"></path>
              </g>
            </svg>
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
        url: window.location.origin + "/member/transaction/get-transaction/",
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
        }
      })
    }

    getTransaction = () => {
      $("#table").dataTableLib({
        url: window.location.origin + "/member/transaction/get-plan-activity/",
        selectID: "member_plan_activity_id",
        colModel: [{
            display: "Tanggal",
            name: "member_plan_activity_datetime_formatted",
            align: "left",
          },
          {
            display: "Stockist",
            name: "transaction_stockist_name",
            align: "left",
          },
          {
            display: "Keterangan",
            name: "member_plan_activity_note",
            align: "left",
          },
          {
            display: "Metode Pengiriman",
            name: "warehouse_transaction_delivery_method",
            align: "center",
            render: (params, args) => {
              return params == 'pickup' ? 'COD' : args.warehouse_transaction_delivery_courier_code.toUpperCase()
            }
          },
          {
            display: "No Resi",
            name: "warehouse_transaction_delivery_awb",
            align: "center",
            render: (params, args) => {
              return args.warehouse_transaction_delivery_method == 'pickup' ? '-' : args.warehouse_transaction_delivery_awb == null ? '-' : args.warehouse_transaction_delivery_awb
            }
          }
          // {
          //   display: "Detail",
          //   name: "warehouse_transaction_id",
          //   align: "right",
          //   render: (params, data) => {
          //     return `<button class="btn btn-primary" onclick="showModalDetail(${params});">Detail</button>`
          //   },
          // },
        ],
        options: {
          limit: [10, 15, 20, 50, 100],
          currentLimit: 10,
        },
        search: true,
        searchTitle: "Pencarian",
        searchItems: [{
          display: "Tanggal",
          name: "member_plan_activity_datetime",
          type: "date"
        },
        {
          display: "Kode Transaksi",
          name: "member_plan_activity_note",
          type: "text"
        }],
        sortName: "member_plan_activity_datetime",
        sortOrder: "DESC",
        tableIsResponsive: true,
        select: false,
        multiSelect: false,
        buttonAction: [],
        success: (res) => {
          datatable = res.data.results
        }
      })
    }

    showModalDetail = (id) => {
      let transaction = datatable.find(o => o.warehouse_transaction_id == id)
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
      if (transaction.warehouse_transaction_payment_image == "") {
        $(".no-image").show()
      } else {
        $(".no-image").hide()
      }
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

    formatCurrency = ($params) => {
      let formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      })
      return formatter.format($params)
    }

    getTransaction()
  })
</script>
<!-- END: Content-->