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
        <div class="col col-12">
          <div class="p-1 card" id="card_activation">
            <div class="row">
              <div class="col-md-4 col-4">
                <div class="form-group mb-0">
                  <label for="">ID Mitra</label>
                  <select id="member_receiver" class="select2 receiver_select">
                  </select>
                </div>
              </div>
              <div class="col-md-4 col-4 pb-2 d-none" id="upline_username">
                <div class="form-group mb-0">
                  <label for="">ID Upline</label>
                  <!-- <input type="text" class="form-control" id="input_upline_username">
                                            <small class="text-danger alert-input" id="alert_input_upline_username" style="display: none;"></small> -->
                  <select id="input_upline_username" class="select2 upline_select">
                  </select>
                  <small class="text-danger alert-input" id="alert_input_upline_username" style="display: none;"></small>
                </div>
              </div>
              <div class="col-md-4 col-4 d-none" id="network_position">
                <div class="form-group mb-0">
                  <label for="network_position">Posisi Jaringan</label>
                  <fieldset class="d-flex flex-row pt-1">
                    <div class="custom-control custom-radio pr-2">
                      <input type="radio" class="custom-control-input" name="network_position" value="L" id="input_network_position_L">
                      <label class="custom-control-label" for="input_network_position_L" style="width: 75px;">Kiri</label>
                    </div>
                    <div class="custom-control custom-radio pr-2">
                      <input type="radio" class="custom-control-input" name="network_position" value="R" id="input_network_position_R">
                      <label class="custom-control-label" for="input_network_position_R" style="width: 75px;">Kanan</label>
                    </div>
                  </fieldset>
                  <small class="text-danger alert-input" id="alert_input_network_position" style="display: none;"></small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12 col-12">
          <div class="row" id="product_list">
          </div>
        </div>

        <div class="" style="position: fixed; top: calc(100vh - 120px - 3.8rem); height: 120px; width: calc(100% - 300px); padding-left: 1.75rem; padding-right: 1.75rem;">
          <div class="row" id="summary">
            <div class="col col-12 col-md-12 p-0">
              <div class="card p-1 shadow">
                <div class="row align-items-center">
                  <div class="col col-8 d-flex flex-column">
                    <div class="m-0"><b id="total_quantity">0</b> produk dipilih</div>
                    <div class="m-0">Total <b id="total_price">Rp 0</b></div>
                    <div class="m-0">Total Poin BV <b id="total_bv">0</b></div>
                    <!-- <div class="m-0 small"><span id="rank_notification"><span class="text-danger">(Belum memenuhi minimal transaksi.)</span></span></div> -->
                  </div>
                  <div class="col col-4 text-right"><button class="btn btn-primary" id="btn_next" disabled>Lanjut</button></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row" id="step_2" style="display: none;">
        <div class="col col-12 col-md-12 mb-1">
          <div class="card p-1 mb-0">
            <div class="row">
              <label class="col col-4 text-left">Produk</label>
              <label class="col col-2 text-center">Poin BV</label>
              <label class="col col-1 text-center">Jumlah</label>
              <label class="col col-2 text-center">Berat</label>
              <label class="col col-3 text-right">Harga</label>
            </div>
          </div>
        </div>
        <div class="col col-12 col-md-12" id="transaction_list">
        </div>
        <div class="col col-12 col-md-12 mb-1 d-none">
          <div class="card p-1 mb-0">
            <div class="row">
              <label class="col col-8 d-flex align-items-center">Pilih metode pengiriman</label>
              <small class="col col-8 d-flex align-items-center">Metode ambil hanya bisa dipilih jika alamat berada di wilayah DIY</small>
              <div class="col col-4">
                <div class="row" id="transaction_method" data-method="pickup">
                  <div class="col col-6 d-flex align-items-center" id="method_pickup">
                    <i class="bx bxs-check-circle text-success mr-1" id="check_pickup" style="font-size: 2rem;"></i>
                    <label for="check_pickup">Ambil</label>
                  </div>
                  <div class="col col-6 d-flex align-items-center" id="method_courier">
                    <i class="bx bx-circle mr-1" id="check_courier" style="font-size: 2rem;"></i>
                    <label for="check_courier">Kirim</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col col-12 col-md-12 mb-1 d-none" id="div_delivery">
          <div class="card p-1 mb-0">
            <div class="row">
              <label class="col col-8 d-flex align-items-center">Alamat Pengiriman</label>
              <div class="col col-4">
                <div class="row" id="transaction_address" data-address="self">
                  <div class="col col-6 d-flex align-items-center" id="address_self">
                    <i class="bx bxs-check-circle text-success mr-1" id="check_self" style="font-size: 2rem;"></i>
                    <label for="check_self">Alamat Utama</label>
                  </div>
                  <div class="col col-6 d-flex align-items-center" id="address_other">
                    <i class="bx bx-circle mr-1" id="check_other" style="font-size: 2rem;"></i>
                    <label for="check_other">Alamat Lain</label>
                  </div>
                </div>
              </div>
              <div class="col col-12 d-none align-items-center" id="detail_alamat">
                <div class="row w-100">

                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="input_transaction_name">Nama Penerima</label>
                      <input type="text" class="form-control" rows="4" id="input_transaction_name" placeholder="" value="">
                      <small class="text-danger alert-input" id="alert_input_transaction_name" style="display: none;"></small>
                    </div>
                  </div>

                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="input_transaction_mobilephone">No. HP Penerima</label>
                      <input type="text" class="form-control" rows="4" id="input_transaction_mobilephone" placeholder="" value="">
                      <small class="text-danger alert-input" id="alert_input_transaction_mobilephone" style="display: none;"></small>
                    </div>
                  </div>

                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="input_transaction_address">Alamat Lengkap</label>
                      <textarea class="form-control" rows="4" id="input_transaction_address" placeholder="" style="height: calc(4.2em + 6.82rem + 20.1px);" value="" disabled></textarea>
                      <small class="text-danger alert-input" id="alert_input_transaction_address" style="display: none;"></small>
                    </div>
                  </div>

                  <div class="col-sm-6">
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
            <div class="row d-none">
              <label class="col col-12 d-flex align-items-center">Jasa Pengiriman</label>
              <div class="col col-4">
                <div class="form-group mb-0">
                  <label for="input_transaction_courier_code">Kurir</label>
                  <select class="form-control" id="input_transaction_courier_code">
                    <option value="0">Pilih Kurir</option>
                  </select>
                  <small class="text-danger alert-input" id="alert_input_transaction_courier_code" style="display: none;"></small>
                </div>
              </div>
              <div class="col col-4">
                <div class="form-group mb-0">
                  <label for="input_transaction_courier_service">Layanan</label>
                  <select class="form-control" id="input_transaction_courier_service">
                    <option value="0">Pilih Layanan</option>
                  </select>
                  <small class="text-danger alert-input" id="alert_input_transaction_courier_service" style="display: none;"></small>
                </div>
              </div>
              <div class="col col-4">
                <div class="form-group mb-0">
                  <label for="input_transaction_courier_cost">Ongkos Kirim</label>
                  <div class="text-right" id="input_transaction_courier_cost"></div>
                  <small class="text-danger alert-input" id="alert_input_transaction_courier_cost" style="display: none;"></small>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col col-12 col-md-12 mb-1 d-none">
          <div class="card p-1 mb-0">
            <div class="row align-items-center">
              <label class="col col-9 mb-0 d-flex align-items-center">Total</label>
              <label class="col col-3 mb-0 text-right" id="transaction_price_total" style="font-size: 1.6rem;">0</label>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-12">
          <div class="row">
            <div class="col col-12 col-md-12 mb-1">
              <div class="row">
                <div class="col col-6 text-left"><button class="btn btn-primary" id="btn_prev">Kembali</button></div>
                <div class="col col-6 text-right"><button class="btn btn-primary" id="btn_submit">Proses</button></div>
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
    let product_id = {}
    let product_bv = {}
    let product_data = []
    let product_detail = []
    let member_address = {}
    let member_data = {}
    let delivery_data = {}
    let delivery_cost = 0
    let transaction_total_weight = 0
    let transaction_total_price = 0
    let transaction_nett_price = 0
    let local = false
    let rank_data = []
    let total_price = 0
    let total_bv = 0
    let type_buy = "repeatorder"

    $('.receiver_select').select2({
      minimumInputLength: 3,
      language: {
        inputTooShort: function(args) {
          return `Silahkan ketik ${args.minimum} karakter atau lebih`;
        }
      },
      allowClear: true,
      placeholder: 'Mitra Tujuan',
      width: '100%',
      ajax: {
        url: window.location.origin + '/member/stockist/getReceiver',
        dataType: 'json',
        data: function(params) {
          return {
            search: params.term
          }
        },
        processResults: function(data, page) {
          return {
            results: data
          };
        }
      }
    });

    $('.upline_select').select2({
      minimumInputLength: 3,
      language: {
        inputTooShort: function(args) {
          return `Silahkan ketik ${args.minimum} karakter atau lebih`;
        }
      },
      allowClear: true,
      placeholder: 'ID Upline',
      width: '100%',
      ajax: {
        url: window.location.origin + '/member/stockist/getUpline',
        dataType: 'json',
        data: function(params) {
          return {
            search: params.term
          }
        },
        processResults: function(data, page) {
          return {
            results: data
          };
        }
      }
    });

    $('#member_receiver').on('change', () => {
      if ($('#member_receiver').val() != null) {
        $.ajax({
          url: "<?= BASEURL ?>/member/stockist/check-network",
          type: "GET",
          data: {
            member_id: $('#member_receiver').val()
          },
          success: (res) => {
            if (res.message == 'repeatorder') {
              $('#network_position').addClass('d-none')
              $('#upline_username').addClass('d-none')
              type_buy = "repeatorder"
              $("#btn_next").prop("disabled", false)
            } else {
              type_buy = "activation"
              $('#network_position').removeClass('d-none')
              $('#upline_username').removeClass('d-none')
              if (total_bv < 50) {
                $("#btn_next").prop("disabled", true)
              } else {
                $("#btn_next").prop("disabled", false)
              }
            }
          },
        })
      } else {
        $('#network_position').addClass('d-none')
        $('#upline_username').addClass('d-none')
        $("#btn_next").prop("disabled", true)
      }
    })

    getProduct = () => {
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-all-product",
        type: "GET",
        data: {
          type: "repeatorder"
        },
        success: (res) => {
          let class_ = ''
          if (window.innerWidth < 1600 && window.innerWidth > 1300) {
            class_ = 'max-width: 190px;'
          } else if (window.innerWidth < 1299) {
            class_ = 'max-width: 120px;'
          }

          product_data = res.data.results
          $("#product_list").empty()
          $.each(product_data, (i, val) => {
            $("#product_list").append(`
            <div class="col col-12 col-md-4 mb-1">
              <div class="card card-bordered border select_product p-1 mb-1" data-id="${val.product_id}" data-bv="${val.product_bv}" data-price="${val.product_price}" style="cursor: pointer;">
                <div class="row">
                  <div class="col-4 text-center">
                    <img class="logo" src="${val.product_image}" style="width :5rem;">
                  </div>
                  <div class="col-8">
                    <p style="font-weight: bold; ${class_}">${val.product_name}</p>
                    <p class="mb-0">${formatCurrency(val.product_price)}</p>
                    <p class="mb-0">${val.product_weight} gr</p>
                    <p style="font-weight: bold;">${val.product_bv} Poin BV</p>
                  </div>
                </div>
                <div class="d-flex align-items-center justify-content-center mb-1">
                  <i class="bx bx-circle" id="select_product_${val.product_id}" style="font-size: 2rem;"></i> Pilih Produk
                </div>
                <div class="text-center d-inline-flex">
                  <i class="control control-substract bx bxs-minus-square" data-id="${val.product_id}" data-bv="${val.product_bv}" style="font-size: calc(1.4em + 0.94rem + 3.7px);"></i>
                  <input type="number" min="1" class="form-control text-center" id="quantity_${val.product_id}" value="0" disabled>
                  <i class="control control-add bx bxs-plus-square" data-id="${val.product_id}" data-bv="${val.product_bv}" style="font-size: calc(1.4em + 0.94rem + 3.7px);"></i>
                </div>
              </div>
            </div>
            `)
          })
        },
      })
    }

    $("body").on("click", ".select_product", (ev) => {
      if (!$(ev.target).before().hasClass("control")) {
        let id = $(ev.target).closest(".select_product").data("id")
        let bv = $(ev.target).closest(".select_product").data("bv")
        if (product_id.hasOwnProperty(id)) {
          $(`#select_product_${id}`).removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
          $(`#quantity_${id}`).val(0)
          $('#member_receiver').trigger("change")
          delete product_id[id]
          delete product_bv[id]
        } else {
          $(`#select_product_${id}`).removeClass("bx-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
          $(`#quantity_${id}`).val(1)
          $('#member_receiver').trigger("change")
          product_id[id] = 1
          product_bv[id] = bv
        }
      }
      renderSummary()
    })

    $("body").on("click", ".control-add", (ev) => {
      let id = $(ev.target).data("id")
      let bv = $(ev.target).data("bv")
      if (product_id.hasOwnProperty(id)) {
        let qty = parseInt($(`#quantity_${id}`).val()) + 1
        qty = qty > 999999 ? 999999 : qty
        product_id[id] = qty
        product_bv[id] = qty * bv
        $(`#quantity_${id}`).val(qty)
      }
      renderSummary()
    })

    $("body").on("click", ".control-substract", (ev) => {
      let id = $(ev.target).data("id")
      let bv = $(ev.target).data("bv")
      if (product_id.hasOwnProperty(id)) {
        let qty = parseInt($(`#quantity_${id}`).val()) - 1
        qty = qty < 1 ? 1 : qty
        product_id[id] = qty
        product_bv[id] = qty * bv
        $(`#quantity_${id}`).val(qty)
      }
      renderSummary()
    })

    renderSummary = () => {
      total_quantity = 0
      total_price = 0
      total_bv = 0
      $.each($(".select_product"), (i, val) => {
        if ($(val).find("i").hasClass("bxs-check-circle")) {
          total_quantity += 1
          total_bv += parseInt($(`#quantity_${$(val).data("id")}`).val()) * parseInt($(val).data("bv"))
          total_price += parseInt($(`#quantity_${$(val).data("id")}`).val()) * parseInt($(val).data("price"))
        }
      })

      $('#member_receiver').trigger("change")
      $("#total_quantity").html(total_quantity)
      $("#total_bv").html(total_bv)
      $("#total_price").html(formatCurrency(total_price))
      let rank_notification = `<span class="text-danger">(Belum memenuhi minimal transaksi.)`
      let transaction_valid = true

      $("#rank_notification").html(rank_notification)
      if (total_price > 0 && $('#member_receiver').val() != null) {
        $("#btn_next").prop("disabled", false)
      } else {
        $("#btn_next").prop("disabled", true)
      }
    }

    $("#btn_next").on("click", (ev) => {
      if (parseInt($("#total_quantity").html()) > 0) {
        $("#transaction_list").empty()
        transaction_total_weight = 0
        transaction_total_price = 0
        product_detail = []
        let total_qty = 0

        $.each(product_id, (i, val) => {
          let product = product_data.find(o => o.product_id == i)
          product_detail.push({
            product_id: product.product_id,
            quantity: val,
          })
          total_qty += val
          $("#transaction_list").append(`
          <div class="card p-1 mb-1">
              <div class="row align-items-center">
                  <div class="col col-4 text-left"><b>${product.product_name}</b><br>@ ${product.product_weight} gram<br>@ ${formatCurrency(product.product_price)}</div>
                  <div class="col col-2 text-center">${product.product_bv}</div>
                  <div class="col col-1 text-center">${val}</div>
                  <div class="col col-2 text-center">${parseInt(product.product_weight) * parseInt(val)} gram</div>
                  <div class="col col-3 text-right">${formatCurrency(parseInt(product.product_price) * parseInt(val))}</div>
              </div>
          </div>
          `)
          transaction_total_weight += parseInt(product.product_weight) * parseInt(val)
          transaction_total_price += parseInt(product.product_price) * parseInt(val)
        })
        $("#transaction_list").append(`
          <div class="card p-1 mb-1">
              <div class="row align-items-center">
                  <label class="col col-4 text-left">Total</label>
                  <div class="col col-2 text-center">${total_bv}</div>
                  <div class="col col-1 text-center">${total_qty}</div>
                  <div class="col col-2 text-center" id="transaction_total_weight">${transaction_total_weight} gram</div>
                  <label class="col col-3 text-right" style="font-size: 1.6rem;">${formatCurrency(transaction_total_price)}</label>
              </div>
          </div>
          `)
        renderTotal()

        if (!local) {
          $("#method_courier").trigger("click")
        }

        $("#step_1").hide()
        $("#step_2").show()
      }
    })

    $("#btn_prev").on("click", (ev) => {
      $("#step_1").show()
      $("#step_2").hide()
    })

    $("#address_self").on("click", (ev) => {
      $('#detail_alamat').addClass("d-none")
      $('#detail_alamat').removeClass("d-flex")
    })

    $("#address_other").on("click", (ev) => {
      $('#detail_alamat').removeClass("d-none")
      $('#detail_alamat').addClass("d-flex")
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
      }
    })

    $("#btn_submit").on("click", () => {
      Swal.fire({
        title: 'Apakah anda yakin ingin melakukan Transaksi?',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: `<span style="color: #475F7B;">Tidak</span>`,
        cancelButtonColor: '#E6EAEE',
        confirmButtonText: 'Ya',
        confirmButtonColor: '#6f2abb',
      }).then((result) => {
        if (result.isConfirmed) {
          addTransaction()
        }
      })
    })

    addTransaction = () => {
      let data = {
        member_id: $('#member_receiver').val(),
        name: $("#input_transaction_name").val(),
        mobilephone: $("#input_transaction_mobilephone").val(),
        address_type: $("#transaction_address").data("address"),
        address: $("#input_transaction_address").val(),
        province_id: $("#input_transaction_province_id").val(),
        city_id: $("#input_transaction_city_id").val(),
        subdistrict_id: $("#input_transaction_subdistrict_id").val(),
        network_position: $("#input_network_position_L").prop("checked") ? "L" : $("#input_network_position_R").prop("checked") ? "R" : "",
        upline_username: $("#input_upline_username").val() ? $("#input_upline_username").val() : "",
        type_buy: type_buy,
        total_bv: total_bv,
        detail: product_detail,
      }
      $.ajax({
        url: "<?= BASEURL ?>/member/stockist/add-transaction",
        type: "POST",
        data: data,
        success: (res) => {
          data = res.data.results
          window.scrollTo(0, 0);
          $(".alert-input").hide()
          $("#alert-success").html(res.message)
          $("#alert-success").show()
          setTimeout(function() {
            $("#alert-success").hide()
            if (type_buy == "activation") {
              window.location = `/member/stockist/sell-success?sponsor_username=${data.sponsor_username}&sponsor_member_name=${data.sponsor_member_name}&upline_username=${data.upline_username}&upline_member_name=${data.upline_member_name}&network_position=${data.network_position}`
            } else {
              location.reload()
            }
          }, 2000)
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
            $("#btn_prev").trigger("click")
            $.each(res.data, (i, val) => {
              $(`#alert_input_${i}`).html(val).show()
            })
          }
        },
      })
    }

    $("#input_transaction_province_id").on("click change", (ev) => {
      getCity($(ev.target).val())
    })

    $("#input_transaction_city_id").on("click change", (ev) => {
      getSubdistrict($(ev.target).val())
      // $("#input_transaction_courier_code").trigger("change")
    })

    $("#input_transaction_subdistrict_id").on("click change", (ev) => {
      // $("#input_transaction_courier_code").trigger("change")
    })

    $("#input_transaction_courier_service").on("click change keyup", (ev) => {
      delivery_cost = delivery_data && delivery_data.hasOwnProperty("costs") && delivery_data.costs.length > 0 ? delivery_data.costs[$("#input_transaction_courier_service>option:selected").data("index")].cost[0].value : 0
      $("#input_transaction_courier_cost").html(formatCurrency(delivery_cost))
      renderTotal()
    })

    renderTotal = () => {
      transaction_nett_price = parseInt(transaction_total_price) + parseInt(delivery_cost)
      $("#transaction_price_total").html(formatCurrency(transaction_nett_price))
    }

    getProvince = () => {
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-ref-province",
        type: "GET",
        async: false,
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

    formatCurrency = ($params) => {
      let formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      })
      return formatter.format($params)
    }

    $("#method_courier").trigger("click")

    getProvince()
    // getCourier()
    getProduct()
  })
</script>
<!-- END: Content-->