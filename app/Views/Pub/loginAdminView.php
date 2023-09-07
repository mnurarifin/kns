<div class="app-content content app-login">
  <div class="content-overlay"></div>
  <div class="content-wrapper">
    <div class="content-header row">
    </div>
    <div class="content-body">
      <!-- login page start -->
      <section id="auth-login" class="row justify-content-center flexbox-container">
        <div class="col-xl-7 col-11">
          <div class="card bg-authentication mb-0 p-2">
            <div class="alert alert-danger mb-0" role="alert" id="alert" style="display: none;"></div>
            <div class="alert alert-success mb-0" role="alert" id="alert-success" style="display: none;"></div>
            <div class="row m-0">
              <!-- left section-login -->
              <div class="col-sm-12 col-md-6 col-xl-6 col-12 px-0">
                <div class="card mb-0 px-1 h-100 d-flex justify-content-center bg-transparent">
                  <div class="card-header pb-1">
                    <div class="card-title">
                      <div class="login-icon"></div>
                      <h4 class="text-center cl-secondary mb-2">ADMINISTRATOR AREA</h4>
                    </div>
                  </div>
                </div>
              </div>

              <!-- right section-login -->
              <div class="col-sm-12 col-md-6 col-xl-6 col-12 px-0">
                <div class="card mb-0 px-1 h-100 d-flex justify-content-center bg-transparent">
                  <div class="card-content">
                    <div class="card-body">
                      <div class="form-group mb-2">
                        <input type="text" name="username" class="form-control" id="input_username" placeholder="Username" value="">
                        <small class="text-danger alert-input" id="alert_input_username" style="display: none;"></small>
                      </div>
                      <div class="form-password-toggle mb-3">
                        <div class="input-group input-group-merge">
                          <input type="password" name="password" class="form-control" id="input_password" placeholder="Password" aria-describedby="basic-toggle-password" value="">
                          <span class="input-group-text cursor-pointer bg-white" id="basic-toggle-password"><i class="bx bx-hide"></i></span>
                        </div>
                        <small class="text-danger alert-input" id="alert_input_password" style="display: none;"></small>
                      </div>

                      <div class="form-group mb-1">
                        <div class="input-group input-group-merge">
                          <input type="text" class="form-control" id="input_captcha" name="captcha" placeholder="Kode Unik" aria-describedby="basic-input-captcha">
                          <span class="input-group-text cursor-pointer bg-white" id="refresh_captcha"><i class="bx bx-revision"></i></span>
                        </div>
                        <small class="text-danger alert-input" id="alert_input_captcha" style="display: none;"></small>
                      </div>
                      <div class="form-group mb-2">
                        <img id="captcha_image" src="" class="img-fluid" alt="kode unik" style="width: -webkit-fill-available;">
                      </div>

                      <button type="submit" class="btn btn-success btn-login glow w-100 position-relative" id="btn_login">
                        Login
                        <i id="icon-arrow" class="bx bx-right-arrow-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- login page ends -->
    </div>

    <div class="content-footer">
      <footer class="login-footer" data-booted="true">
        <div class="text-center cl-white col col-12">
          <?= date('Y') ?> - <strong><?= PROJECT_NAME ?></strong></div>
      </footer>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $("#captcha_image").attr("src", window.location.origin + '/login/getCaptcha')
    $("#refresh_captcha").on("click", () => {
      $("#captcha_image").attr("src", window.location.origin + '/login/getCaptcha' + "?" + Math.random())
    })

    $("#basic-toggle-password").on('click', function(event) {
      event.preventDefault()
      if ($('.form-password-toggle input').attr("type") == "text") {
        $('.form-password-toggle input').attr('type', 'password')
        $('#basic-toggle-password i').addClass("bx-hide")
        $('#basic-toggle-password i').removeClass("bx-show")
      } else if ($('.form-password-toggle input').attr("type") == "password") {
        $('.form-password-toggle input').attr('type', 'text')
        $('#basic-toggle-password i').removeClass("bx-hide")
        $('#basic-toggle-password i').addClass("bx-show")
      }
    })

    $("#input_username").focus()

    $("input").on("keyup", (ev) => {
      if (ev.which == 13) {
        $("#btn_login").trigger("click")
      }
    })

    $("#btn_login").on("click", (ev) => {
      $.ajax({
        url: "<?= BASEURL ?>/login-admin/verify",
        type: "POST",
        data: {
          username: $("#input_username").val(),
          password: $("#input_password").val(),
          captcha: $("#input_captcha").val(),
        },
        success: (res) => {
          data = res.data.results
          $(".alert-input").hide()
          $("#alert-success").html(res.message)
          $("#alert-success").show()
          setTimeout(function() {
            $("#alert-success").hide()
          }, 3000);
          location.href = res.data.url
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
          $("#refresh_captcha").trigger("click")
        },
      })
    })
  })
</script>