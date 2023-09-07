<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-overlay">
  </div>
  <div class="content-loading">
    <i class="bx bx-loader bx-spin"></i>
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
      <div class="row" id="step_1">
        <div class="col-md-12 col-12">
          <div class="row" id="product_list">
          </div>
        </div>
        <div class="col-md-12 col-12">
          <div class="row" id="summary">
            <div class="col col-12 col-md-12 mb-1">
              <div class="card p-1">
                <div class="row">
                  <div class="col col-8 d-flex flex-column">
                    <div class="m-0"><span id="total_quantity">0</span> produk dipilih</div>
                    <div class="m-0">Total <span id="total_price">Rp 0</span></div>
                  </div>
                  <div class="col col-4 text-right"><button class="btn btn-primary" id="btn_next">Lanjut</button></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row" id="step_2" style="display: none;">
        <div class="col col-12 col-md-12 mb-1 d-none d-lg-block">
          <div class="card p-1 mb-0">
            <div class="row">
              <label class="col col-3 text-left">Produk</label>
              <label class="col col-3 text-center">Jumlah</label>
              <label class="col col-3 text-center">Berat</label>
              <label class="col col-3 text-right">Harga</label>
            </div>
          </div>
        </div>

        <div class="col col-12 col-md-12" id="transaction_list">
        </div>

        <div class="col col-12 col-md-12 mb-1">
          <div class="card p-1 mb-0">
            <div class="row">
              <label class="col col-12 d-flex align-items-center my-50">Pilih metode pengiriman</label>
              <small class="col-12 col-lg-8" id="method-note">Barang dapat di ambil di <?= COMPANY_ADDRESS ?></small>
              <div class="col-12 col-lg-4">
                <div class="row" id="transaction_method" data-method="pickup">
                  <div class="col col-6 d-flex align-items-center" id="method_pickup">
                    <i class="bx bxs-check-circle text-success mr-1" id="check_pickup" style="font-size: 2rem;"></i>
                    <label for="check_pickup">Ambil</label>
                  </div>
                  <div class="col col-6 d-flex align-items-center" id="method_courier">
                    <i class="bx bx-circle text-light mr-1" id="check_courier" style="font-size: 2rem;"></i>
                    <label for="check_courier">Kirim</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col col-12 col-md-12 mb-1">
          <div class="card p-1 mb-0">
            <div class="row">
              <label class="col-12 col-lg-8 d-flex align-items-center my-50">Pilih tempat ambil / pengirim</label>
              <div class="col-12 col-lg-4">
                <div class="row" id="transaction_type" data-type="warehouse">
                  <div class="col-12 col-lg-4 d-flex align-items-center" data-type="warehouse" id="type_warehouse">
                    <i class="bx bxs-check-circle text-success mr-1" id="check_warehouse" style="font-size: 2rem;"></i>
                    <label for="check_warehouse">Perusahaan</label>
                  </div>
                  <div class="col-12 col-lg-4 d-flex align-items-center" data-type="stockist" id="type_master_stockist">
                    <i class="bx bx-circle text-light mr-1" id="check_master_stockist" style="font-size: 2rem;"></i>
                    <label for="check_master_stockist">Master Stokis</label>
                  </div>
                  <div class="col-12 col-lg-4 d-flex align-items-center" data-type="stockist" id="type_stockist">
                    <i class="bx bx-circle text-light mr-1" id="check_stockist" style="font-size: 2rem;"></i>
                    <label for="check_stockist">Stokis</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col col-12 col-md-12 mb-1" id="div_delivery" style="display: none;">
          <div class="card p-1 mb-0">
            <div class="row">
              <label class="col-12 col-lg-8 d-flex align-items-center my-50">Alamat Pengiriman</label>
              <div class="col-12 col-lg-4 my-50">
                <div class="row" id="transaction_address" data-address="self">
                  <div class="col col-6 d-flex align-items-center" id="address_self">
                    <i class="bx bxs-check-circle text-success mr-1" id="check_self" style="font-size: 2rem;"></i>
                    <label for="check_self">Alamat Utama</label>
                  </div>
                  <div class="col col-6 d-flex align-items-center" id="address_other">
                    <i class="bx bx-circle text-light mr-1" id="check_other" style="font-size: 2rem;"></i>
                    <label for="check_other">Alamat Lain</label>
                  </div>
                </div>
              </div>
              <div class="col col-12 align-items-center" id="div_address">
                <div class="row">

                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="input_transaction_name">Nama Penerima</label>
                      <input type="text" class="form-control" rows="4" id="input_transaction_name" placeholder="" value="">
                      <small class="text-danger alert-input" id="alert_input_transaction_name" style="display: none;"></small>
                    </div>
                  </div>

                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="input_transaction_mobilephone">No. HP Penerima</label>
                      <input type="text" class="form-control" rows="4" id="input_transaction_mobilephone" placeholder="" value="">
                      <small class="text-danger alert-input" id="alert_input_transaction_mobilephone" style="display: none;"></small>
                    </div>
                  </div>

                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="input_transaction_address">Alamat Lengkap</label>
                      <textarea class="form-control" rows="4" id="input_transaction_address" placeholder="" style="height: calc(4.2em + 6.82rem + 20.1px);" value="" disabled></textarea>
                      <small class="text-danger alert-input" id="alert_input_transaction_address" style="display: none;"></small>
                    </div>
                  </div>

                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="input_transaction_province_id">Provinsi</label>
                      <select class="form-control" id="input_transaction_province_id" disabled>
                        <option value="0">Pilih Provinsi</option>
                      </select>
                      <small class="text-danger alert-input" id="alert_input_transaction_province_id" style="display: none;"></small>
                    </div>

                    <div class="form-group">
                      <label for="input_transaction_city_id">Kota</label>
                      <select class="form-control" id="input_transaction_city_id" disabled>
                        <option value="0">Pilih Kota</option>
                      </select>
                      <small class="text-danger alert-input" id="alert_input_transaction_city_id" style="display: none;"></small>
                    </div>

                    <div class="form-group">
                      <label for="input_transaction_subdistrict_id">Kecamatan</label>
                      <select class="form-control" id="input_transaction_subdistrict_id" disabled>
                        <option value="0">Pilih Kecamatan</option>
                      </select>
                      <small class="text-danger alert-input" id="alert_input_transaction_subdistrict_id" style="display: none;"></small>
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <div class="row">
              <label class="col col-12 d-flex align-items-center my-50">Jasa Pengiriman</label>
              <div class="col-12 col-lg-4 mb-sm-50">
                <div class="form-group mb-0">
                  <label for="input_transaction_courier_code">Kurir</label>
                  <select class="form-control" id="input_transaction_courier_code">
                    <option value="0">Pilih Kurir</option>
                  </select>
                  <small class="text-danger alert-input" id="alert_input_transaction_courier_code" style="display: none;"></small>
                </div>
              </div>
              <div class="col-12 col-lg-4 mb-sm-50">
                <div class="form-group mb-0">
                  <label for="input_transaction_courier_service">Layanan</label>
                  <select class="form-control" id="input_transaction_courier_service">
                    <!-- <option value="0">Pilih Layanan</option> -->
                    <option value="DFOD">DFOD</option>
                  </select>
                  <small class="text-danger alert-input" id="alert_input_transaction_courier_service" style="display: none;"></small>
                </div>
              </div>
              <div class="col-12 col-lg-4 mb-sm-50 d-none">
                <div class="form-group mb-0">
                  <label for="input_transaction_courier_cost">Ongkos Kirim</label>
                  <div class="text-right" id="input_transaction_courier_cost"></div>
                  <small class="text-danger alert-input" id="alert_input_transaction_courier_cost" style="display: none;"></small>
                </div>
              </div>
              <div class="col-12 col-lg-12 mb-sm-50">
                <small style="color: #6a0dad !important;">Pengiriman menggunakan layanan Delivery Fee On Delivery sehingga biaya pengiriman dibayarkan oleh mitra ketika produk sudah sampai ke mitra</small>
              </div>
            </div>
          </div>
        </div>
        <div class="col col-12 col-md-12 mb-1" id="div_type" style="display: none;">
          <div class="card p-1 mb-0">
            <div class="row">
              <label class="col-12 col-lg-8 d-flex align-items-center my-50">Stokis</label>
              <div class="col-12 col-lg-4">
                <select class="form-control" id="input_stockist_list">
                  <option value="0">Pilih Stokis</option>
                </select>
                <small class="text-danger alert-input" id="alert_input_stockist_member_id" style="display: none;"></small>
              </div>
            </div>
          </div>
        </div>
        <div class="col col-12 col-md-12 mb-1">
          <div class="card p-1 mb-0">
            <div class="row align-items-center">
              <label class="col-12 col-lg-9 mb-0 d-flex align-items-center">Total</label>
              <label class="col-12 col-lg-3 mb-0 text-right" id="transaction_price_total" style="font-size: 1.6rem;">0</label>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-12">
          <div class="row">
            <div class="col col-12 col-md-12 mb-1">
              <div class="row">
                <div class="col col-6 text-left"><button class="btn btn-secondary btn-sm-block" id="btn_prev">Kembali</button></div>
                <div class="col col-6 text-right"><button class="btn btn-primary btn-sm-block" id="btn_done">Proses</button></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade text-left" id="modal-summary" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="modal-label">Rangkuman</h3>
        <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
          <i class="bx bx-x"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col col-12">
            <table class="table table-bordered">
              <thead>
                <tr class="d-none d-sm-none d-md-table-row">
                  <th>Produk</th>
                  <th class="text-right">Jumlah</th>
                  <th class="text-right">Harga Satuan</th>
                  <th class="text-right">Harga Total</th>
                </tr>

                <tr class="d-table-row d-sm-table-row d-md-none">
                  <th colspan="4">Informasi Produk</th>
                </tr>
              </thead>

              <tbody id="table-summary" class="d-none d-sm-none d-md-table-row-group">
              </tbody>

              <tbody id="table-summary-mob" class="d-table-row d-sm-table-row d-md-none">
              </tbody>
            </table>
            <div id="note-summary"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">
          <span class="d-block" id="btn_submit">Proses</span>
        </button>
        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
          <span class="d-block">Tutup</span>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->

<script>
  $(function() {
    let product_id = {}
    let product_data = []
    let product_detail = []
    let member_address = {}
    let delivery_data = {}
    let delivery_cost = 0
    let transaction_total_weight = 0
    let transaction_total_price = 0
    let transaction_nett_price = 0
    let free = false
    let free_product = 0

    let OTP = <?php echo session()->has("otp") && session("otp") ? 'true' : 'false' ?>

    getPackage = () => {
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-product",
        type: "GET",
        // data: {
        //   type: "repeatorder"
        // },
        beforeSend: () => {
          $(".content-loading").addClass("loadings")
        },
        complete: () => {
          $(".content-loading").removeClass("loadings")
        },
        success: (res) => {
          product_data = res.data.results
          $("#product_list").empty()
          $.each(product_data, (i, val) => {
            $("#product_list").append(`
              <div class="col col-12 col-md-4 mb-1">
                <div class="card card-bordered border select_product p-1 mb-1" data-id="${val.product_id}" data-price="${val.product_price}" style="cursor: pointer;">
                  <div class="row">
                    <div class="col col-4">
                      <img src="${val.product_image}" class="w-100"/>
                    </div>
                    <div class="col col-8">
                      <p style="font-weight: bold;" class="mb-0">${val.product_name}</p>
                      <p class="mb-1">${formatCurrency(val.product_price)}</p>
                    </div>
                  </div>
                  <p class="mb-1 d-flex align-items-center"><i class="bx bx-circle text-light mr-1" id="select_product_${val.product_id}" style="font-size: 2rem;"></i>Pilih Paket</p>
                  <div class="text-center d-inline-flex">
                    <i class="control control-substract bx bxs-minus-square" data-id="${val.product_id}" style="font-size: calc(1.4em + 0.94rem + 3.7px);"></i>
                    <input type="number" min="1" class="form-control text-center" id="quantity_${val.product_id}" value="0" disabled>
                    <i class="control control-add bx bxs-plus-square" data-id="${val.product_id}" style="font-size: calc(1.4em + 0.94rem + 3.7px);"></i>
                  </div>
                </div>
              </div>
            `)
          })
        },
      })
    }

    getMemberAddress = () => {
      $.ajax({
        url: "<?= BASEURL ?>/member/transaction/get-member-address",
        type: "GET",
        beforeSend: () => {
          $(".content-loading").addClass("loadings")
        },
        complete: () => {
          $(".content-loading").removeClass("loadings")
        },
        success: (res) => {
          member_address = res.data.results
          $("#input_transaction_name").val(member_address.member_name).prop("disabled", true)
          $("#input_transaction_mobilephone").val(member_address.member_mobilephone).prop("disabled", true)
          $("#input_transaction_address").val(member_address.member_address).prop("disabled", true)
          $("#input_transaction_province_id").val(member_address.member_province_id).prop("disabled", true)
          $("#input_transaction_province_id").trigger("change")
          $("#input_transaction_city_id").val(member_address.member_city_id).prop("disabled", true)
          $("#input_transaction_city_id").trigger("change")
          $("#input_transaction_subdistrict_id").val(member_address.member_subdistrict_id).prop("disabled", true)
          $("#input_transaction_subdistrict_id").trigger("change")
        },
      })
    }

    showModalOTP = () => {
      <?php if (!(session()->has("otp") && session("otp") == TRUE)) { ?>
        if (OTP) {
          showModalRo()
        } else {
          $("#modal-otp").modal("show")
        }
      <?php } else { ?>
        showModalRo()
      <?php } ?>
    }

    $("body").on("click", ".select_product", (ev) => {
      if (!$(ev.target).before().hasClass("control")) {
        let id = $(ev.target).closest(".select_product").data("id")
        if (product_id.hasOwnProperty(id)) {
          $(`#select_product_${id}`).removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
          $(`#quantity_${id}`).val(0)
          delete product_id[id]
        } else {
          $(`#select_product_${id}`).removeClass("bx-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
          $(`#quantity_${id}`).val(1)
          product_id[id] = 1
        }
      }
      renderSummary()
    })

    $("body").on("click", ".control-add", (ev) => {
      let id = $(ev.target).data("id")
      if (product_id.hasOwnProperty(id)) {
        let qty = parseInt($(`#quantity_${id}`).val()) + 1
        qty = qty > 999999 ? 999999 : qty
        product_id[id] = qty
        $(`#quantity_${id}`).val(qty)
      }
      renderSummary()
    })

    $("body").on("click", ".control-substract", (ev) => {
      let id = $(ev.target).data("id")
      if (product_id.hasOwnProperty(id)) {
        let qty = parseInt($(`#quantity_${id}`).val()) - 1
        qty = qty < 1 ? 1 : qty
        product_id[id] = qty
        $(`#quantity_${id}`).val(qty)
      }
      renderSummary()
    })

    renderSummary = () => {
      total_quantity = 0
      total_price = 0
      $.each($(".select_product"), (i, val) => {
        if ($(val).children("p").children("i").hasClass("bxs-check-circle")) {
          total_quantity += 1
          total_price += parseInt($(`#quantity_${$(val).data("id")}`).val()) * parseInt($(val).data("price"))
        }
      })
      $("#total_quantity").html(total_quantity)
      $("#total_price").html(formatCurrency(total_price))
    }

    $("#btn_done").on("click", (ev) => {
      let table = ``
      let table_mob = ``
      let total = 0
      $.each(product_id, (i, val) => {
        let product = product_data.find(o => o.product_id == i)
        table += `
        <tr>
          <td>${product.product_name}</td>
          <td class="text-right">${val}</td>
          <td class="text-right">${formatCurrency(product.product_price)}</td>
          <td class="text-right">${formatCurrency(parseInt(val) * parseInt(product.product_price))}</td>
        </tr>
        `

        table_mob += `
        <tr>
          <td colspan="4">
            <div class="d-flex flex-column my-50">
              <strong class="d-block mb-50" style="white-space: break-spaces;">${product.product_name}</strong>
              <div class="d-flex flex-row align-items-center justify-content-between">
                <span>${formatCurrency(product.product_price)}</span>
                <span>x ${val}</span>
                <span>${formatCurrency(parseInt(val) * parseInt(product.product_price))}</span>
              </div>
            </div>
          </td>
        </tr>
        `

        total += parseInt(parseInt(val) * parseInt(product.product_price))
      })
      table += `
      <tr style="border-top: 2px solid #DFE3E7;">
        <td colspan="3">Sub Total</td>
        <td class="text-right">${formatCurrency(parseInt(total))}</td>
      </tr>
      <tr style="border-top: 2px solid #DFE3E7;">
        <td colspan="3">Ongkir</td>
        <td class="text-right">${formatCurrency(parseInt(delivery_cost))}</td>
      </tr>
      <tr style="border-top: 2px solid #DFE3E7;">
        <td colspan="3">Total</td>
        <td class="text-right">${formatCurrency(parseInt(total) + parseInt(delivery_cost))}</td>
      </tr>
      `

      table_mob += `
      <tr style="border-top: 2px solid #DFE3E7; background: #F5F5F5;">
        <td colspan="4">
          <div class="d-flex flex-row align-items-center justify-content-between">
            <span>Sub Total</span>
            <strong>${formatCurrency(parseInt(total))}</strong>
          </div>
        </td>
      </tr>
      <tr style="border-top: 2px solid #DFE3E7; background: #F5F5F5;">
        <td colspan="4">
          <div class="d-flex flex-row align-items-center justify-content-between">
            <span>Ongkir</span>
            <strong>${formatCurrency(parseInt(delivery_cost))}</strong>
          </div>
        </td>
      </tr>
      <tr style="border-top: 2px solid #DFE3E7; background: #F5F5F5;">
        <td colspan="4">
          <div class="d-flex flex-row align-items-center justify-content-between">
            <span>Total</span>
            <strong>${formatCurrency(parseInt(total) + parseInt(delivery_cost))}</strong>
          </div>
        </td>
      </tr>
      `
      let note = ``
      if ($("#transaction_method").data("method") == "pick") {
        if ($("#transaction_type").data("type") == "stockist") {
          note = `Transaksi dapat diambil di stokis ` + $("#input_stockist_list option:selected").text()
        } else {
          note = `Transaksi dapat diambil di ` + `<?= DELIVERY_WAREHOUSE_ADDRESS ?>`
        }
      } else {
        if ($("#transaction_type").data("type") == "stockist") {
          note = `Transaksi dikirim dari stokis ` + $("#input_stockist_list option:selected").text() + ` ke alamat ` + $("#input_transaction_address").val()
        } else {
          note = `Transaksi dikirim dari ` + `<?= DELIVERY_WAREHOUSE_ADDRESS ?>` + ` ke alamat ` + $("#input_transaction_address").val()
        }
      }
      $("#note-summary").html(note)

      $("#table-summary").empty()
      $("#table-summary").append(table)
      $("#table-summary-mob").empty()
      $("#table-summary-mob").append(table_mob)
      $("#modal-summary").modal("show")
    })

    $("#btn_next").on("click", (ev) => {
      if (parseInt($("#total_quantity").html()) > 0) {
        $("#transaction_list").empty()
        transaction_total_weight = 0
        transaction_total_price = 0
        product_detail = []

        $.each(product_id, (i, val) => {
          let product = product_data.find(o => o.product_id == i)
          product_detail.push({
            product_id: product.product_id,
            product_qty: val,
          })
          $("#transaction_list").append(`
          <div class="card p-1 mb-1 d-none d-lg-block">
            <div class="row align-items-center">
              <div class="col col-3 text-left"><b>${product.product_name}</b><br>@ ${formatWeight(product.product_weight)}<br>@ ${formatCurrency(product.product_price)}</div>
              <div class="col col-3 text-center">${val}</div>
              <div class="col col-3 text-center">${formatWeight(parseInt(product.product_weight) * parseInt(val))}</div>
              <div class="col col-3 text-right">${formatCurrency(parseInt(product.product_price) * parseInt(val))}</div>
            </div>
          </div>

          <div class="card p-1 mb-1 d-block d-lg-none">
            <div class="row align-items-center">
              <div class="col col-12 text-left pb-1 mb-1" style="border-bottom: 1px solid #ddd;">
                <b class="d-block mb-1">${product.product_name}</b>
                @ ${formatWeight(product.product_weight)}<br>
                @ ${formatCurrency(product.product_price)}
              </div>
              <div class="col col-12 text-left">
                <div class="d-flex flex-row align-items-center justify-content-between mb-50">
                  <label class="mb-0">Jumlah</label>
                  ${val}
                </div>
                <div class="d-flex flex-row align-items-center justify-content-between mb-50">
                  <label class="mb-0">Berat</label>
                  ${formatWeight(parseInt(product.product_weight) * parseInt(val))}
                </div>
                <div class="d-flex flex-row align-items-center justify-content-between mb-50">
                  <label class="mb-0">Harga</label>
                  ${formatCurrency(parseInt(product.product_price) * parseInt(val))}
                </div>
              </div>
            </div>
          </div>
          `)
          transaction_total_weight += parseInt(product.product_weight) * parseInt(val)
          transaction_total_price += parseInt(product.product_price) * parseInt(val)
        })
        $("#transaction_list").append(`
        <div class="card p-1 mb-1 d-none d-lg-block">
          <div class="row align-items-center">
            <label class="col col-3 text-left">Sub Total</label>
            <div class="col col-3 text-center"></div>
            <div class="col col-3 text-center" id="transaction_total_weight">${formatWeight(transaction_total_weight)}</div>
            <label class="col col-3 text-right" style="font-size: 1.6rem;">${formatCurrency(transaction_total_price)}</label>
          </div>
        </div>

        <div class="card p-1 mb-1 d-block d-lg-none">
          <div class="flex-column">
            <div class="d-flex flex-row align-items-center justify-content-between mb-50">
              <label class="mb-0">Subtotal Berat</label>
              <div id="transaction_total_weight_mobile" style="color: #6a0dad; font-weight: bold;">${formatWeight(transaction_total_weight)}</div>
            </div>
            <div class="d-flex flex-row align-items-center justify-content-between">
              <label class="mb-0">Subtotal Harga</label>
              <div style="font-size: 1.2rem; color: #6a0dad; font-weight: bold;">${formatCurrency(transaction_total_price)}</div>
            </div>
          </div>
        </div>
        `)
        renderTotal()

        $("#step_1").hide()
        $("#step_2").show()
      }
    })

    $("#btn_prev").on("click", (ev) => {
      $("#step_1").show()
      $("#step_2").hide()
    })

    $("#method_pickup").on("click", (ev) => {
      if ($("#transaction_method").data("method") == "courier") {
        $("#transaction_method").data("method", "pickup")
        $("#check_pickup").removeClass("bx-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
        $("#check_courier").removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
        $("#div_delivery").hide()
        renderTotal()
        $("#method-note").html(`Barang dapat di ambil di <?= COMPANY_ADDRESS ?>`)
      }
    })

    $("#method_courier").on("click", (ev) => {
      if ($("#transaction_method").data("method") == "pickup") {
        $("#transaction_method").data("method", "courier")
        $("#check_courier").removeClass("bx-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
        $("#check_pickup").removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
        $("#div_delivery").show()
        $("#input_transaction_courier_code").trigger("change")
        renderTotal()
        $("#method-note").html(`Barang dikirim dari <?= COMPANY_ADDRESS ?>`)
      }
    })

    $("#type_warehouse").on("click", (ev) => {
      if ($("#transaction_type").data("type") == "stockist" || $("#transaction_type").data("type") == "master") {
        $("#transaction_type").data("type", "warehouse")
        $("#check_warehouse").removeClass("bx-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
        $("#check_stockist").removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
        $("#check_master_stockist").removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
        $("#div_type").hide()
        renderTotal()
      }
    })

    $("#type_stockist").on("click", (ev) => {
      getStockist("mobile")
      if ($("#transaction_type").data("type") == "warehouse" || $("#transaction_type").data("type") == "master") {
        $("#transaction_type").data("type", "stockist")
        $("#check_stockist").removeClass("bx-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
        $("#check_warehouse").removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
        $("#check_master_stockist").removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
        $("#div_type").show()
        $("#input_transaction_courier_code").trigger("change")
        renderTotal()
      }
    })

    $("#type_master_stockist").on("click", (ev) => {
      getStockist("master")
      if ($("#transaction_type").data("type") == "warehouse" || $("#transaction_type").data("type") == "stockist") {
        $("#transaction_type").data("type", "master")
        $("#check_master_stockist").removeClass("bx-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
        $("#check_warehouse").removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
        $("#check_stockist").removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
        $("#div_type").show()
        $("#input_transaction_courier_code").trigger("change")
        renderTotal()
      }
    })

    $("#address_self").on("click", (ev) => {
      if ($("#transaction_address").data("address") == "other") {
        $("#transaction_address").data("address", "self")
        $("#check_self").removeClass("bx-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
        $("#check_other").removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
        $("#input_transaction_name").val(member_address.member_name).prop("disabled", true)
        $("#input_transaction_mobilephone").val(member_address.member_mobilephone).prop("disabled", true)
        $("#input_transaction_address").val(member_address.member_address).prop("disabled", true)
        $("#input_transaction_province_id").val(member_address.member_province_id).prop("disabled", true)
        $("#input_transaction_province_id").trigger("change")
        $("#input_transaction_city_id").val(member_address.member_city_id).prop("disabled", true)
        $("#input_transaction_city_id").trigger("change")
        $("#input_transaction_subdistrict_id").val(member_address.member_subdistrict_id).prop("disabled", true)
        $("#input_transaction_subdistrict_id").trigger("change")
        $("#div_address").hide()
      }
      renderTotal()
    })

    $("#address_other").on("click", (ev) => {
      if ($("#transaction_address").data("address") == "self") {
        $("#transaction_address").data("address", "other")
        $("#check_other").removeClass("bx-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
        $("#check_self").removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
        $("#input_transaction_name").val("").prop("disabled", false)
        $("#input_transaction_mobilephone").val("").prop("disabled", false)
        $("#input_transaction_address").val("").prop("disabled", false)
        $("#input_transaction_province_id").val("").prop("disabled", false)
        $("#input_transaction_city_id").val("").prop("disabled", false).trigger("change")
        $("#input_transaction_subdistrict_id").val("").prop("disabled", false)
        $("#div_address").show()
      }
      renderTotal()
    })

    generateOTP = () => {
      $.ajax({
        url: "<?= BASEURL ?>/member/otp/generate",
        type: "GET",
        async: false,
        beforeSend: () => {
          $(".content-loading").addClass("loadings")
        },
        complete: () => {
          $(".content-loading").removeClass("loadings")
        },
        success: (res) => {
          $("#alert-success").html(res.message)
          $("#alert-success").show()
          setTimeout(function() {
            $("#alert-success").hide()
          }, 3000)
        },
      })
    }

    verifyOTP = () => {
      let data = {
        otp: $("#input_otp").val(),
      }
      $.ajax({
        url: "<?= BASEURL ?>/member/otp/verify",
        type: "POST",
        data: data,
        beforeSend: () => {
          $(".content-loading").addClass("loadings")
        },
        complete: () => {
          $(".content-loading").removeClass("loadings")
        },
        success: (res) => {
          data = res.data.results
          $(".alert-input").hide()
          $("#alert-success").html(res.message)
          $("#alert-success").show()
          setTimeout(function() {
            $("#alert-success").hide()
          }, 3000)
          $("#modal-otp").modal("hide")
          OTP = true
          showModalRo()
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
          }
        },
      })
    }

    $("#btn_submit").on("click", () => {
      let data = {
        otp: $("#input_otp").val(),
        transaction_delivery_method: $("#transaction_method").data("method"),
        transaction_courier_code: $("#input_transaction_courier_code").val(),
        transaction_courier_service: $("#input_transaction_courier_service").val(),
        transaction_delivery_cost: delivery_cost,
        name: $("#input_transaction_name").val(),
        mobilephone: $("#input_transaction_mobilephone").val(),
        address: $("#input_transaction_address").val(),
        province_id: $("#input_transaction_province_id").val(),
        city_id: $("#input_transaction_city_id").val(),
        subdistrict_id: $("#input_transaction_subdistrict_id").val(),
        address_type: $("#transaction_address").data("address"),
        detail: product_detail,
        type: $("#transaction_type").data("type"),
        stockist_member_id: $("#transaction_type").data("type") == "stockist" || $("#transaction_type").data("type") == "master" ? $("#input_stockist_list").val() : 0,
      }
      $.ajax({
        url: "<?= BASEURL ?>/member/transaction/add-transaction",
        type: "POST",
        data: data,
        beforeSend: () => {
          $(".content-loading").addClass("loadings")
        },
        complete: () => {
          $(".content-loading").removeClass("loadings")
        },
        success: (res) => {
          data = res.data.results
          $(".alert-input").hide()
          $("#alert-success").html(res.message)
          $("#alert-success").show()
          setTimeout(function() {
            $("#alert-success").hide()
          }, 3000)
          window.location = `/member/transaction/transaction-success?invoice_url=${data.invoice_url}`
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
            $("#modal-summary").modal("hide")
            $.each(res.data, (i, val) => {
              $(`#alert_input_${i}`).html(val).show()
            })
          }
        },
      })
    })

    $("#input_transaction_province_id").on("change", (ev) => {
      getCity($(ev.target).val())
    })

    $("#input_transaction_city_id").on("change", (ev) => {
      getSubdistrict($(ev.target).val())
      $("#input_transaction_courier_code").trigger("change")
    })

    $("#input_transaction_subdistrict_id").on("change", (ev) => {
      $("#input_transaction_courier_code").trigger("change")
    })

    // $("#input_transaction_courier_code").on("change", (ev) => {
    //   $.ajax({
    //     url: "<?= BASEURL ?>/common/get-delivery-cost",
    //     type: "GET",
    //     data: {
    //       transaction_subdistrict_id: $("#input_transaction_subdistrict_id").val() ? $("#input_transaction_subdistrict_id").val() : 0,
    //       transaction_city_id: $("#input_transaction_city_id").val() ? $("#input_transaction_city_id").val() : 0,
    //       transaction_total_weight: isNaN(parseInt($("#transaction_total_weight").html())) ? 0 : parseInt($("#transaction_total_weight").html()),
    //       transaction_courier_code: $("#input_transaction_courier_code").val(),
    //       transaction_type: $("#transaction_type").data("type"),
    //       stockist_member_id: $("#transaction_type").data("type") == "stockist" || $("#transaction_type").data("type") == "master" ? $("#input_stockist_list").val() : 0,
    //     },
    //     beforeSend: () => {
    //       $(".content-loading").addClass("loadings")
    //     },
    //     complete: () => {
    //       $(".content-loading").removeClass("loadings")
    //     },
    //     success: (res) => {
    //       $(".content-loading").removeClass("loadings")
    //       let data = res.data.results
    //       $("#input_transaction_courier_service").empty()
    //       $("#input_transaction_courier_service").append(
    //         `<option value="">Pilih Layanan</option>`)
    //       if (data.length > 0) {
    //         $.each(data[0].costs, (i, val) => {
    //           $("#input_transaction_courier_service").append(`
    //           <option value="${val.service}" data-index="${i}">${val.description} (${val.cost[0].etd} hari)</option>
    //           `)
    //         })
    //       }
    //       delivery_data = data[0]
    //       $("#input_transaction_courier_service").trigger("change")
    //     },
    //   })
    // })

    // $("#input_transaction_courier_service").on("change keyup", (ev) => {
    //   // delivery_cost = delivery_data && delivery_data.hasOwnProperty("costs") && delivery_data.costs.length > 0 ? delivery_data.costs[$("#input_transaction_courier_service>option:selected").data("index")].cost[0].value : 0
    //   if (delivery_data && delivery_data.hasOwnProperty("costs") && delivery_data.costs.length > 0) {
    //     if ($("#transaction_type").data("type") == "stockist") {
    //       if ($("#input_transaction_courier_service>option:selected").data("index") >= 0) {
    //         if ($("#input_transaction_courier_service>option:selected").data("index") != undefined) {
    //           delivery_cost = delivery_data.costs[$("#input_transaction_courier_service>option:selected").data("index")].cost[0].value
    //         } else {
    //           delivery_cost = 0
    //         }
    //       } else {
    //         delivery_cost = 0
    //       }
    //     } else {
    //       if ($("#input_transaction_courier_service>option:selected").data("index") != undefined) {
    //         delivery_cost = delivery_data.costs[$("#input_transaction_courier_service>option:selected").data("index")].cost[0].value
    //       } else {
    //         delivery_cost = 0
    //       }
    //     }
    //   } else {
    //     delivery_cost = 0
    //   }
    //   $("#input_transaction_courier_cost").html(formatCurrency(delivery_cost))
    //   renderTotal()
    // })

    renderTotal = () => {
      delivery_cost = 0
      transaction_nett_price = parseInt(transaction_total_price) + ($("#transaction_method").data("method") == "pickup" ? 0 : parseInt(delivery_cost))
      $("#transaction_price_total").html(formatCurrency(transaction_nett_price))
    }

    getProvince = () => {
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-ref-province",
        type: "GET",
        async: false,
        beforeSend: () => {
          $(".content-loading").addClass("loadings")
        },
        complete: () => {
          $(".content-loading").removeClass("loadings")
        },
        success: (res) => {
          data = res.data.results
          $.each(data, (i, val) => {
            $("#input_transaction_province_id").append(
              `<option value="${val.province_id}">${val.province_name}</option>`)
          })
        },
      })
    }

    getCity = (province_id) => {
      if (province_id != 0) {
        $.ajax({
          url: "<?= BASEURL ?>/common/get-list-ref-city/?province_id=" + province_id,
          type: "GET",
          async: false,
          beforeSend: () => {
            $(".content-loading").addClass("loadings")
          },
          complete: () => {
            $(".content-loading").removeClass("loadings")
          },
          success: (res) => {
            data = res.data.results
            $("#input_transaction_city_id").empty()
            $.each(data, (i, val) => {
              $("#input_transaction_city_id").append(
                `<option value="${val.city_id}">${val.city_name}</option>`)
            })
            getSubdistrict($("#input_transaction_city_id").val())
          },
        })
      }
    }

    getSubdistrict = (city_id) => {
      if (city_id != 0) {
        $.ajax({
          url: "<?= BASEURL ?>/common/get-list-ref-subdistrict/?city_id=" + city_id,
          type: "GET",
          async: false,
          beforeSend: () => {
            $(".content-loading").addClass("loadings")
          },
          complete: () => {
            $(".content-loading").removeClass("loadings")
          },
          success: (res) => {
            data = res.data.results
            $("#input_transaction_subdistrict_id").empty()
            $.each(data, (i, val) => {
              $("#input_transaction_subdistrict_id").append(
                `<option value="${val.subdistrict_id}">${val.subdistrict_name}</option>`)
            })
          },
        })
      }
    }

    getStockist = (param = 'master') => {
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-stockist/",
        type: "GET",
        data: {
          type: param
        },
        success: (res) => {
          data = res.data.results
          $("#input_stockist_list").empty()
          $.each(data, (i, val) => {
            $("#input_stockist_list").append(
              `<option value="${val.stockist_member_id}">${val.stockist_name}</option>`)
          })
        },
      })
    }

    getCourier = () => {
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-ref-courier",
        type: "GET",
        beforeSend: () => {
          $(".content-loading").addClass("loadings")
        },
        complete: () => {
          $(".content-loading").removeClass("loadings")
        },
        success: (res) => {
          data = res.data.results
          $("#input_transaction_courier_code").empty()
          $("#input_transaction_courier_code").append(
            `<option value="">Pilih Kurir</option>`)
          $.each(data, (i, val) => {
            $("#input_transaction_courier_code").append(
              `<option value="${val.courier_code}">${val.courier_code.toUpperCase()}</option>`)
          })
        },
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

    formatDecimal = ($params) => {
      let formatter = new Intl.NumberFormat('id-ID', {
        style: 'decimal',
        minimumFractionDigits: 1,
        maximumFractionDigits: 1,
      })
      return formatter.format($params)
    }

    formatWeight = (params) => {
      let value = params
      let unit = 'gram'
      if (params >= 1000) {
        value = params / 1000;
        unit = 'kilogram'
      }

      let formatter = new Intl.NumberFormat('id-ID', {
        style: 'unit',
        unit: unit,
        unitDisplay: 'short'
      })
      return formatter.format(value)
    }

    getMemberAddress()
    getProvince()
    getCourier()
    getPackage()
    getStockist()
  })
</script>
<!-- END: Content-->