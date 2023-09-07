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
                <li class="breadcrumb-item"><a href="<?= BASEURL ?>/office/dashboard/show"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="#"><?= $breadcrumbs[0] ?></a></li>
                <li class="breadcrumb-item active"><?= $breadcrumbs[1] ?></li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="content-body">
      <div class="row justify-content-center">
        <div class="col-md-12 col-12">
          <div class="card">
            <div class="card-body p-2 text-center">
              <div class="row">
                <div class="col-md-12 col-12">

                  <div class="row">
                    <div class="col-md-6 col-12">
                      <div class="d-block mb-1">
                        <h6 class="text-left">Password Lama</h6>
                        <fieldset class="form-group position-relative has-icon-left text-left">
                          <div class="input-group">
                            <input type="password" class="form-control" id="input_password_old" placeholder="Masukkan password lama" style="border-right: none;">
                            <div class="input-group-append">
                              <div class="form-control" type="button" style="border-left: none; border-top-left-radius: 0; border-bottom-left-radius: 0;" id="btn_password_old">
                                <i class="bx bx-hide"></i>
                              </div>
                            </div>
                          </div>
                          <div class="form-control-position"><i class="bx bx-key"></i></div>
                          <span class="text-danger alert-input" id="alert_input_password_old" style="display: none;"></span>
                        </fieldset>
                      </div>
                    </div>

                    <div class="col-md-6 col-12">
                      <div class="d-block mb-1">
                        <h6 class="text-left">Password Baru</h6>
                        <fieldset class="form-group position-relative has-icon-left text-left">
                          <div class="input-group">
                            <input type="password" class="form-control" id="input_password_new" placeholder="Masukkan password baru" style="border-right: none;">
                            <div class="input-group-append">
                              <div class="form-control" type="button" style="border-left: none; border-top-left-radius: 0; border-bottom-left-radius: 0;" id="btn_password_new">
                                <i class="bx bx-hide"></i>
                              </div>
                            </div>
                          </div>
                          <div class="form-control-position"><i class="bx bx-key"></i></div>
                          <span class="text-danger alert-input" id="alert_input_password_new" style="display: none;"></span>
                        </fieldset>
                      </div>
                    </div>
                  </div>

                  <div class="row justify-content-end">
                    <button type="button" class="btn btn-success mr-1 mb-1" id="btn_save_password">
                      <i class="bx bx-save mr-50"></i>Simpan
                    </button>
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

<div class="modal fade text-left" id="modal-otp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="myModalLabel1">OTP</h3>
        <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
          <i class="bx bx-x"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col col-9">
            <input type="text" class="form-control" id="input_otp">
          </div>
          <div class="col col-3">
            <button class="btn btn-primary" id="btn-otp">Generate</button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
          <i class="bx bx-x d-block d-sm-none"></i>
          <span class="d-none d-sm-block">Tutup</span>
        </button>
        <button type="button" class="btn btn-primary" id="btn-verify">
          <i class=""></i>
          <span class="">Simpan</span>
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  $(function() {
    $("#btn_password_old").on("click", (ev) => {
      if ($(ev.target).hasClass("bx-hide")) {
        $(ev.target).removeClass("bx-hide")
        $(ev.target).addClass("bx-show")
        $("#input_password_old").prop("type", "text")
      } else {
        $(ev.target).removeClass("bx-show")
        $(ev.target).addClass("bx-hide")
        $("#input_password_old").prop("type", "password")
      }
    })

    $("#btn_password_new").on("click", (ev) => {
      if ($(ev.target).hasClass("bx-hide")) {
        $(ev.target).removeClass("bx-hide")
        $(ev.target).addClass("bx-show")
        $("#input_password_new").prop("type", "text")
      } else {
        $(ev.target).removeClass("bx-show")
        $(ev.target).addClass("bx-hide")
        $("#input_password_new").prop("type", "password")
      }
    })

    $("#btn-otp").on("click", () => {
      generateOTP()
    })

    $("#btn-verify").on("click", () => {
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
          save()
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
    })

    save = () => {
      $.ajax({
        url: "<?= BASEURL ?>/member/profile/update-password",
        type: "POST",
        data: {
          password_old: $("#input_password_old").val(),
          password_new: $("#input_password_new").val(),
        },
        success: (res) => {
          data = res.data.results
          $(".alert-input").hide()
          $("#alert-success").html(res.message)
          $("#alert-success").show()
          setTimeout(function() {
            $("#alert-success").hide()
          }, 3000);
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

    $("#btn_save_password").on("click", () => {
      $("#modal-otp").modal("show")
    })
  })
</script>
<!-- END: Content-->