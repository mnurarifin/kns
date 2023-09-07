<style>
  .bgImage {
    right: 11px;
    top: 15px;
    border-radius: 12px;
    width: 15%;
    position: absolute;
    opacity: 0.7;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    height: 35%;
  }
</style>
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
          <div class="row" id="summary">
            <div class="col col-12 col-md-12">
              <div class="card p-1">
                <div class="row">
                  <div class="col-sm-12 col-md-6 d-flex flex-column">
                    <div class="m-0" style="color: #7e00b3">Potensi poin semua akun : <h1><b id="potency_total_point_all" style="color: #7e00b3">0</b></h1>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6 d-flex flex-column">
                    <div class="m-0" style="color: #7e00b3">Total poin semua akun : <h1><b id="network_total_point_all" style="color: #7e00b3">0</b></h1>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group mb-2">
            <select class="form-control" id="member_children" style="width: fit-content;">
            </select>
          </div>
          <div class="row" id="summary">
            <div class="col col-12 col-md-12 mb-1">
              <div class="card p-1">
                <div class="row">
                  <div class="col-sm-12 col-md-6 d-flex flex-column">
                    <div class="m-0">Potensi poin akun : <h1><b id="potency_total_point">0</b></h1>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6 d-flex flex-column">
                    <div class="m-0">Total poin akun : <h1><b id="network_total_point">0</b></h1>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-12">
          <div class="row" id="reward_list">
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
    let reward_id = {}
    let reward_data = []
    let point_data = []
    let product_detail = []
    let transaction_total_weight = 0
    let transaction_total_point = 0

    let OTP = <?php echo session()->has("otp") && session("otp") ? 'true' : 'false' ?>

    getMemberChildren = () => {
      $.ajax({
        url: "<?= BASEURL ?>/member/bonus/get-member-children/",
        type: "GET",
        async: false,
        success: (res) => {
          data = res.data.results
          $.each(data, (i, val) => {
            $("#member_children").append(`<option value="${val.member_id}">${val.network_code}</option>`)
          })
          getReward()
        },
        error: (err) => {
          res = err.responseJSON
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

    getReward = () => {
      $.ajax({
        url: "<?= BASEURL ?>/member/reward/get-reward/" + $("#member_children").val(),
        type: "GET",
        success: (res) => {
          reward_data = res.data.results
          point_data = res.data.point
          $("#reward_list").empty()
          $.each(reward_data, (i, val) => {
            let trip
            let wording
            if (parseInt(val.reward_trip_point) == 0) {
              wording = 'Menghasilkan 1 '
              trip = 'TSHIRT';
            } else {
              wording = 'Menghasilkan Poin Trip : '
              trip = formatDecimal(parseInt(val.reward_trip_point))
            }
            let is_qualified_point = point_data.network_point >= val.reward_condition_point
            let is_qualified = is_qualified_point && val.reward_qualified_id != null && val.reward_qualified_claim == "unclaimed"
            let text = is_qualified ? "Telah memenuhi syarat" : (val.reward_qualified_claim == 'claimed' && val.reward_qualified_status == 'pending') ? 'Menunggu Approval Admin' : (val.reward_qualified_claim == 'claimed' && val.reward_qualified_status == 'approved') ? 'Disetujui Admin' : "Belum memenuhi syarat"
            let btn = is_qualified ? "btn-success btn-login glow" : (val.reward_qualified_claim == 'claimed' && val.reward_qualified_status == 'pending') ? 'btn-warning' : (val.reward_qualified_claim == 'claimed' && val.reward_qualified_status == 'approved') ? 'btn-success btn-login glow' : "btn-secondary"
            $("#reward_list").append(`
              <div class="col col-12 col-md-4 mb-1">
                <div class="card card-bordered border select_reward p-1 mb-1" data-id="${val.reward_id}" data-qualified-id="${val.reward_qualified_id}">
                <span class="bgImage" style="background-image: url('${val.reward_image_filename}')"></span>
                  <p style="font-weight: bold;">${val.reward_title}</p>
                  <p style="">Nilai Reward : ${formatCurrency(val.reward_value)}</p>
                  <small>Syarat Poin : <span class="${is_qualified_point ? `text-success` : `text-danger`}">${formatDecimal(parseInt(val.reward_condition_point))}</span></small>
                  <small>${wording}<span style="color: #7e00b3;">${trip}</span></small>
                  <button class="btn ${btn} mt-1" id="select_reward_${val.reward_id}" style="" ${is_qualified ? `` : `disabled`}>${text}</button>
                </div>
              </div>
            `)
          })
          $("#network_total_point").html(formatDecimal(parseInt(point_data.network_point)))
          $("#potency_total_point").html(formatDecimal(parseInt(point_data.potency_point)))
          $("#network_total_point_all").html(formatDecimal(parseInt(point_data.network_point_all)))
          $("#potency_total_point_all").html(formatDecimal(parseInt(point_data.potency_point_all)))
        },
      })
    }

    $("#member_children").on("change", () => {
      getReward()
    })

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

    $("body").on("click", ".select_reward", (ev) => {
      let qualified_id = $(ev.target).closest(".select_reward").data("qualified-id")
      Swal.fire({
        title: 'Apakah anda yakin ingin melakukan Klaim Reward?',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: `<span style="color: #475F7B;">Tidak</span>`,
        cancelButtonColor: '#E6EAEE',
        confirmButtonText: 'Ya',
        confirmButtonColor: '#6f2abb',
      }).then((result) => {
        if (result.isConfirmed) {
          claimReward(qualified_id)
        }
      })
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

    claimReward = (qualified_id) => {
      let data = {
        otp: $("#input_otp").val(),
        reward_qualified_id: qualified_id,
      }
      $.ajax({
        url: "<?= BASEURL ?>/member/reward/claim",
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
          getReward()
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

    getMemberChildren()
  })
</script>
<!-- END: Content-->