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
                <li class="breadcrumb-item"><a href="<?= BASEURL ?>/member/dashboard"><i class="bx bx-home-alt"></i></a>
                </li>
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

          <div class="card card-bordered border p-2 mb-1">
            <div class="row">

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="input_sponsor_username">Username Sponsor</label>
                  <input type="text" class="form-control" id="input_sponsor_username" placeholder="" value="<?= session("member")["member_account_username"] ?>" <?= session("member")["member_id"] == "1" ? "" : "disabled" ?>>
                  <small class="text-danger alert-input" id="alert_input_sponsor_username" style="display: none;"></small>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-password-toggle form-group">
                  <label>Password Calon Mitra</label>
                  <div for="input_member_account_password" class="input-group input-group-merge">
                    <input type="password" class="form-control" id="input_member_account_password" placeholder="" aria-describedby="basic-toggle-password" value="">
                    <span class="input-group-text cursor-pointer bg-white" id="basic-toggle-password"><i class="bx bx-hide"></i></span>
                  </div>
                  <small class="text-danger alert-input" id="alert_input_member_account_password" style="display: none;"></small>
                </div>
              </div>

              <div class="col-sm-6 d-none">
                <div class="form-group">
                  <label for="input_serial_id">Serial ID</label>
                  <input type="text" class="form-control" id="input_serial_id" placeholder="">
                  <small class="text-danger alert-input" id="alert_input_serial_id" style="display: none;"></small>
                </div>
              </div>

              <div class="col-sm-6 d-none">
                <div class="form-group">
                  <label for="input_serial_pin">Serial PIN</label>
                  <input type="text" class="form-control" id="input_serial_pin" placeholder="">
                  <small class="text-danger alert-input" id="alert_input_serial_pin" style="display: none;"></small>
                </div>
              </div>
            </div>
          </div>

          <div class="card card-bordered border p-2 mb-1">
            <div class="row">
              <div class="col-md-6">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="input_member_name">Nama Lengkap</label>
                      <input type="text" class="form-control" id="input_member_name" placeholder="" value="">
                      <small class="text-danger alert-input" id="alert_member_name" style="display: none;"></small>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="input_member_mobilephone">Nomor Handphone</label>
                      <input type="tel" class="form-control" id="input_member_mobilephone" placeholder="" value="">
                      <small class="text-danger alert-input" id="alert_input_member_mobilephone" style="display: none;"></small>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="">Tempat Lahir</label>
                      <input type="text" class="form-control" id="input_member_birth_place" placeholder="" value="">
                      <small class="text-danger alert-input" id="alert_input_member_birth_place" style="display: none;"></small>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="">Tanggal Lahir</label>
                      <input type="date" class="form-control" id="input_member_birth_date" placeholder="" value="">
                      <small class="text-danger alert-input" id="alert_input_member_birth_date" style="display: none;"></small>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="input_member_tax_no">Nomor NPWP</label>
                      <input type="text" class="form-control" id="input_member_tax_no" placeholder="" value="">
                      <small class="text-danger alert-input" id="alert_input_member_tax_no" style="display: none;"></small>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row">
                  <div class="col-12">
                    <div class="row" style="height: calc(4 * (1.4em + 0.94rem + 3.7px) + 6.8rem);">
                      <div class="col-12">
                        <label for="">Foto KTP</label>
                      </div>
                      <div class="col-12 text-center">
                        <input type="file" class="d-none" id="input_member_identity_image" />
                        <img src="<?= base_url() . '/no-image.png' ?>" class="w-50" id="img_identity_image" />
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <button type="button" class="btn btn-primary mr-1 mb-1 w-100" id="btn_image">Upload Foto KTP</button>
                    <small class="text-danger alert-input" id="alert_input_member_identity_image" style="display: none;"></small>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="input_member_identity_no">Nomor KTP</label>
                      <input type="text" class="form-control" id="input_member_identity_no" placeholder="" value="">
                      <small class="text-danger alert-input" id="alert_input_member_identity_no" style="display: none;"></small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card card-bordered border p-2 mb-1">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="input_member_address">Alamat Lengkap</label>
                  <textarea class="form-control" rows="4" id="input_member_address" placeholder="" style="height: calc(4.2em + 6.82rem + 20.1px);" value=""></textarea>
                  <small class="text-danger alert-input" id="alert_input_member_address" style="display: none;"></small>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="input_member_province_id">Provinsi</label>
                  <select class="custom-select form-control" id="input_member_province_id">
                    <option value="0">Pilih Provinsi</option>
                  </select>
                  <small class="text-danger alert-input" id="alert_input_member_province_id" style="display: none;"></small>
                </div>

                <div class="form-group">
                  <label for="input_member_city_id">Kota</label>
                  <select class="custom-select form-control" id="input_member_city_id">
                    <option value="0">Pilih Kota</option>
                  </select>
                  <small class="text-danger alert-input" id="alert_input_member_city_id" style="display: none;"></small>
                </div>

                <div class="form-group">
                  <label for="input_member_subdistrict_id">Kecamatan</label>
                  <select class="custom-select form-control" id="input_member_subdistrict_id">
                    <option value="0">Pilih Kecamatan</option>
                  </select>
                  <small class="text-danger alert-input" id="alert_input_member_subdistrict_id" style="display: none;"></small>
                </div>
              </div>

            </div>
          </div>

          <div class="card card-bordered border p-2 mb-1">
            <div class="row">

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="input_member_bank_account_name">Nama Akun Rekening</label>
                  <input type="text" class="form-control" id="input_member_bank_account_name" placeholder="" value="" disabled>
                  <small class="text-danger alert-input" id="alert_input_member_bank_account_name" style="display: none;"></small>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="input_member_bank_account_no">Nomor Rekening</label>
                  <input type="text" class="form-control" id="input_member_bank_account_no" placeholder="" value="">
                  <small class="text-danger alert-input" id="alert_input_member_bank_account_no" style="display: none;"></small>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="input_member_bank_id">Nama Bank</label>
                  <select class="form-control" id="input_member_bank_id">
                    <option value="0">Pilih Bank</option>
                  </select>
                  <small class="text-danger alert-input" id="alert_input_member_bank_id" style="display: none;"></small>
                </div>
              </div>

            </div>
          </div>
          <div class="row mb-3">
            <div class="col col-12 text-right"><button class="btn btn-primary" id="btn_submit">Proses</button></div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(function() {
    let params = new URLSearchParams(window.location.search)
    $("#btn_image").on("click", () => {
      $("#input_member_identity_image").trigger("click")
    })

    $("#input_member_identity_image").on("change", (ev) => {
      const [file] = ev.target.files
      if (file) {
        $("#img_identity_image").prop("src", URL.createObjectURL(file))
      }
    })

    $("#btn_submit").on("click", (ev) => {
      let data = new FormData()
      data.append("sponsor_username", $("#input_sponsor_username").val())
      data.append("serial_id", $("#input_serial_id").val())
      data.append("serial_pin", $("#input_serial_pin").val())
      data.append("member_account_username", $("#input_member_account_username").val())
      data.append("member_account_password", $("#input_member_account_password").val())
      data.append("member_name", $("#input_member_name").val())
      data.append("member_gender", $("#input_member_gender_male").prop("checked") ? "Laki-laki" : $("#input_member_gender_female").prop("checked") ? "Perempuan" : "")
      // data.append("member_email", $("#input_member_email").val())
      data.append("member_identity_no", $("#input_member_identity_no").val())
      data.append("member_identity_image", $("#input_member_identity_image").prop("files")[0])
      data.append("member_mobilephone", $("#input_member_mobilephone").val())
      data.append("member_birth_place", $("#input_member_birth_place").val())
      data.append("member_birth_date", $("#input_member_birth_date").val())
      data.append("member_province_id", $("#input_member_province_id").val())
      data.append("member_city_id", $("#input_member_city_id").val())
      data.append("member_subdistrict_id", $("#input_member_subdistrict_id").val())
      data.append("member_address", $("#input_member_address").val())
      data.append("member_bank_account_name", $("#input_member_bank_account_name").val())
      data.append("member_bank_account_no", $("#input_member_bank_account_no").val())
      data.append("member_bank_id", $("#input_member_bank_id").val())
      data.append("member_bank_branch", $("#input_member_bank_branch").val())
      data.append("member_tax_no", $("#input_member_tax_no").val())
      $.ajax({
        url: "<?= BASEURL ?>/member/network/register",
        type: "POST",
        processData: false,
        contentType: false,
        data: data,
        beforeSend: function() {
          $(".content-loading").addClass("loadings")
        },
        success: (res) => {
          data = res.data.results
          $(".alert-input").hide()
          $("#alert-success").html(res.message)
          $("#alert-success").show()
          setTimeout(function() {
            $("#alert-success").hide()
          }, 3000);
          window.location = `/member/network/registration-success?username=${data.member_account_username}&sponsor_username=${data.sponsor_username}&member_name=${data.member_name}&sponsor_member_name=${data.sponsor_member_name}`
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
          window.scrollTo(0, 0);
        },
        complete: function() {
          $(".content-loading").removeClass("loadings")
        },
      })
    })

    $("#input_member_identity_image").on("change", (ev) => {
      const [file] = ev.target.files
      if (file) {
        $("#img_member_identity_image").prop("src", URL.createObjectURL(file))
      }
    })

    $("#input_member_name").on("click change keyup", (ev) => {
      if ($("#transaction_address").data("address") == "self") {
        $("#input_transaction_name").val($(ev.target).val()).trigger("change")
      }
    })

    $("#input_member_mobilephone").on("click change keyup", (ev) => {
      if ($("#transaction_address").data("address") == "self") {
        $("#input_transaction_mobilephone").val($(ev.target).val()).trigger("change")
      }
    })

    $("#input_member_address").on("click change keyup", (ev) => {
      if ($("#transaction_address").data("address") == "self") {
        $("#input_transaction_address").val($(ev.target).val()).trigger("change")
      }
    })

    $("#input_member_province_id").on("click change", (ev) => {
      if ($("#transaction_address").data("address") == "self") {
        $("#input_transaction_province_id").val($(ev.target).val()).trigger("change")
      }
      getCity($(ev.target).val())
    })

    $("#input_member_city_id").on("click change", (ev) => {
      if ($("#transaction_address").data("address") == "self") {
        $("#input_transaction_city_id").val($(ev.target).val())
      }
      getSubdistrict($(ev.target).val())
      $("#input_transaction_courier_code").trigger("change")
    })

    getProvince = () => {
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-ref-province",
        type: "GET",
        async: false,
        success: (res) => {
          data = res.data.results
          $.each(data, (i, val) => {
            $("#input_member_province_id").append(
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
            $("#input_member_city_id").empty()
            $.each(data, (i, val) => {
              $("#input_member_city_id").append(
                `<option value="${val.city_id}">${val.city_name}</option>`)
            })
            $("#input_member_city_id").trigger("change")
            getSubdistrict($("#input_member_city_id").val())
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
            $("#input_member_subdistrict_id").empty()
            $.each(data, (i, val) => {
              $("#input_member_subdistrict_id").append(
                `<option value="${val.subdistrict_id}">${val.subdistrict_name}</option>`)
            })
            $("#input_member_subdistrict_id").trigger("change")
          },
        })
      }
    }

    getBank = () => {
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-ref-bank/",
        type: "GET",
        success: (res) => {
          data = res.data.results
          $("#input_member_bank_id").empty()
          $.each(data, (i, val) => {
            $("#input_member_bank_id").append(
              `<option value="${val.bank_id}">${val.bank_name}</option>`)
          })
        },
      })
    }

    $("#input_member_name").on("keyup change", (ev) => {
      $("#input_member_bank_account_name").val($(ev.target).val())
    })

    $("#basic-toggle-password").on("click", (ev) => {
      if ($("#input_member_account_password").prop("type") == "password") {
        $("#input_member_account_password").prop("type", "text")
        $("#input_member_account_password").parent().find("i").removeClass("bx-hide").addClass("bx-show")
      } else {
        $("#input_member_account_password").prop("type", "password")
        $("#input_member_account_password").parent().find("i").removeClass("bx-show").addClass("bx-hide")
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

    getProvince()
    getBank()
  })
</script>
<!-- END: Content-->