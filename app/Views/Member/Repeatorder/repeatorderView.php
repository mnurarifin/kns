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
                  <div class="col col-4 d-flex flex-column">
                    <div class="m-0">Total saldo BV : <h1><b class="text-warning" id="point_balance">0</b></h1>
                    </div>
                  </div>
                  <div class="col col-4 d-flex flex-column">
                    <div class="m-0">Input BV : <h1><input type="number" class="text-success form-control" id="total_point" style="font-weight: bold; font-size: 2rem;"></h1>
                    </div>
                  </div>
                  <div class="col col-4 text-right"><button class="btn btn-primary" id="btn_submit" disabled>Submit</button></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade text-left" id="modal-otp" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="modal-label">OTP</h3>
        <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
          <i class="bx bx-x"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="row mb-50 align-center align-items-end">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="input_otp">OTP</label>
              <input type="text" class="form-control" id="input_otp" placeholder="" value="">
              <small class="text-danger alert-input" id="alert_input_otp" style="display: none;"></small>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <button type="button" class="btn btn-primary w-100" onclick="generateOTP();">
                <i class="bx bx-x d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Kirim OTP</span>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="verifyOTP();">
          <i class="bx bx-x d-block d-sm-none"></i>
          <span class="d-none d-sm-block">Kirim</span>
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
    let point_data = []
    let product_detail = []
    let transaction_total_weight = 0
    let transaction_total_point = 0

    let OTP = <?php echo session()->has("otp") && session("otp") ? 'true' : 'false' ?>

    getBalance = () => {
      $.ajax({
        url: "<?= BASEURL ?>/member/repeatorder/get-balance",
        type: "GET",
        data: {
          type: "repeatorder"
        },
        success: (res) => {
          point_data = res.data.results
          $("#point_balance").html(formatDecimal(point_data.point_balance))
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

    $("#total_point").on("keyup", () => {
      if ($("#total_point").val() > point_data.point_balance) {
        $("#total_point").removeClass("text-success")
        $("#total_point").addClass("text-danger")
        $("#btn_submit").prop("disabled", true)
      } else {
        $("#total_point").removeClass("text-danger")
        $("#total_point").addClass("text-success")
        $("#btn_submit").prop("disabled", false)
      }
    })

    generateOTP = () => {
      $.ajax({
        url: "<?= BASEURL ?>/member/otp/generate",
        type: "GET",
        async: false,
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
          $("#total_point").html(0)
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
      Swal.fire({
        title: 'Apakah anda yakin ingin melakukan Repeat Order?',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: `<span style="color: #475F7B;">Tidak</span>`,
        cancelButtonColor: '#E6EAEE',
        confirmButtonText: 'Ya',
        confirmButtonColor: '#6f2abb',
      }).then((result) => {
        if (result.isConfirmed) {
          addRepeatOrder()
        }
      })
    })

    addRepeatOrder = () => {
      let data = {
        otp: $("#input_otp").val(),
        bv: $("#total_point").val(),
      }
      $.ajax({
        url: "<?= BASEURL ?>/member/repeatorder/add-repeat-order",
        type: "POST",
        data: data,
        success: (res) => {
          data = res.data.results
          $(".alert-input").hide()
          $("#alert-success").html(res.message)
          $("#alert-success").show()
          setTimeout(function() {
            $("#alert-success").hide()
          }, 3000)
          $("#total_point").val(0)
          getBalance()
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

    formatDecimal = ($params) => {
      let formatter = new Intl.NumberFormat('id-ID', {
        style: 'decimal',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      })
      return formatter.format($params)
    }

    getBalance()
  })
</script>
<!-- END: Content-->