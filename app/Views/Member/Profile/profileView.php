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
        <div class="col-md-3 col-12">
          <div class="card">
            <div class="card-body p-2 text-center">
              <div class="row">
                <div class="col-md-12 col-12">
                  <div class="card border p-2 mb-50">
                    <input type="file" class="d-none" id="input_image" />
                    <img src="" class="w-100" id="img_image" />
                  </div>
                  <span class="text-danger alert-input" id="alert_input_member_image" style="display: none;"></span>
                </div>

                <div class="col-md-12 col-12">
                  <button type="button" class="btn btn-primary mr-1 mb-1 w-100" id="btn_image">Ubah Foto Profil</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-9 col-12">
          <div class="card">
            <div class="card-body p-2 text-center">
              <div class="row">
                <div class="col-md-12 col-12">
                  <div class="card mb-1">

                    <div class="row">
                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">ID Member</h6>
                          <fieldset class="form-group position-relative has-icon-left text-left">
                            <input type="text" class="form-control" id="input_member_account_username" placeholder="" disabled>
                            <div class="form-control-position"><i class="bx bx-user"></i></div>
                            <span class="text-danger alert-input" id="alert_input_member_account_username" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>

                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Nama Lengkap</h6>
                          <fieldset class="form-group position-relative has-icon-left text-left">
                            <input type="text" class="form-control" id="input_member_name" placeholder="" disabled>
                            <div class="form-control-position"><i class="bx bx-user"></i></div>
                            <span class="text-danger alert-input" id="alert_input_member_name" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>

                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Jenis Identitas</h6>
                          <fieldset class="form-group position-relative has-icon-left text-left">
                            <div class="input-group">
                              <select class="form-control" id="input_member_identity_type" disabled>
                                <option value="" selected="selected">Jenis Identitas</option>
                                <option value="KTP">KTP</option>
                                <!-- <option value="SIM">SIM</option> -->
                                <option value="PASPOR">Paspor</option>
                              </select>
                            </div>
                            <div class="form-control-position"><i class="bx bx-user-pin"></i></div>
                            <span class="text-danger alert-input" id="alert_input_member_identity_type" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>

                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Nomor Identitas</h6>
                          <fieldset class="form-group position-relative has-icon-left text-left">
                            <input type="text" class="form-control" id="input_member_identity_no" placeholder="" disabled>
                            <div class="form-control-position"><i class="bx bx-user-pin"></i></div>
                            <span class="text-danger alert-input" id="alert_input_member_identity_no" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>

                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Email</h6>
                          <fieldset class="form-group position-relative has-icon-left text-left">
                            <input type="text" class="form-control" id="input_member_email" placeholder="" disabled>
                            <div class="form-control-position"><i class="bx bx-envelope"></i></div>
                            <span class="text-danger alert-input" id="alert_input_member_email" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>


                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Tanggal Gabung</h6>
                          <fieldset class="form-group position-relative has-icon-left text-left">
                            <input type="text" class="form-control" id="input_member_join_datetime" placeholder="" disabled>
                            <div class="form-control-position"><i class="bx bx-calendar"></i></div>
                            <span class="text-danger alert-input" id="alert_input_member_join_datetime" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>

                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Nomor Handphone</h6>
                          <fieldset class="form-group position-relative has-icon-left text-left">
                            <input type="text" class="form-control" id="input_member_mobilephone" placeholder="" disabled>
                            <div class="form-control-position"><i class="bx bx-mobile"></i></div>
                            <span class="text-danger alert-input" id="alert_input_member_mobilephone" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>

                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Pekerjaan</h6>
                          <fieldset class="form-group position-relative has-icon-left text-left">
                            <input type="text" class="form-control" id="input_member_job" placeholder="" disabled>
                            <div class="form-control-position"> <i class="bx bx-briefcase"></i></div>
                            <span class="text-danger alert-input" id="alert_input_member_job" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>

                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Jenis Kelamin</h6>
                          <fieldset class="form-group position-relative has-icon-left text-left">
                            <ul class="list-unstyled mb-0 text-left pt-50">
                              <li class="d-inline-block mr-2 mb-1">
                                <div class="radio">
                                  <input type="radio" name="bsradio" id="input_member_gender_male" disabled>
                                  <label for="radio1">Laki-laki</label>
                                </div>
                              </li>
                              <li class="d-inline-block mr-2 mb-1">
                                <div class="radio">
                                  <input type="radio" name="bsradio" id="input_member_gender_female" disabled>
                                  <label for="radio2">Perempuan</label>
                                </div>
                              </li>
                            </ul>
                            <span class="text-danger alert-input" id="alert_input_member_gender" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Tempat Lahir</h6>
                          <fieldset class="form-group position-relative has-icon-left text-left">
                            <input type="text" class="form-control" id="input_member_birth_place" placeholder="" disabled>
                            <div class="form-control-position"><i class="bx bx-map"></i></div>
                            <span class="text-danger alert-input" id="alert_input_member_birth_place" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>

                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Tanggal Lahir</h6>
                          <fieldset class="form-group position-relative has-icon-left text-left">
                            <input type="date" class="form-control" id="input_member_birth_date" placeholder="" disabled>
                            <div class="form-control-position"><i class="bx bx-calendar"></i></div>
                            <span class="text-danger alert-input" id="alert_input_member_birth_date" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Provinsi</h6>
                          <fieldset class="form-group text-left">
                            <div class="input-group">
                              <select class="form-control" id="input_member_province_id" disabled>
                              </select>
                            </div>
                            <span class="text-danger alert-input" id="alert_input_member_province_id" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>

                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Kabupaten/Kota</h6>
                          <fieldset class="form-group text-left">
                            <div class="input-group">
                              <select class="form-control" id="input_member_city_id" disabled>
                              </select>
                            </div>
                            <span class="text-danger alert-input" id="alert_input_member_city_id" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Kecamatan</h6>
                          <fieldset class="form-group text-left">
                            <div class="input-group">
                              <select class="form-control" id="input_member_subdistrict_id" disabled>
                              </select>
                            </div>
                            <span class="text-danger alert-input" id="alert_input_member_subdistrict_id" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>

                    </div>

                    <div class="row">
                      <div class="col-md-12 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Alamat</h6>
                          <fieldset class="form-group position-relative has-icon-left text-left">
                            <textarea data-length=20 class="form-control char-textarea" id="input_member_address" rows="3" placeholder="" disabled></textarea>
                            <div class="form-control-position"><i class="bx bx-map-alt"></i></div>
                            <span class="text-danger alert-input" id="alert_input_member_address" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Nama Rekening</h6>
                          <fieldset class="form-group position-relative has-icon-left text-left">
                            <input type="text" class="form-control" id="input_member_bank_account_name" placeholder="" disabled>
                            <div class="form-control-position"><i class="bx bx-mobile"></i></div>
                            <span class="text-danger alert-input" id="alert_input_member_bank_account_name" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>

                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">No Rekening</h6>
                          <fieldset class="form-group position-relative has-icon-left text-left">
                            <input type="text" class="form-control" id="input_member_bank_account_no" placeholder="" disabled>
                            <div class="form-control-position"><i class="bx bx-mobile"></i></div>
                            <span class="text-danger alert-input" id="alert_input_member_bank_account_no" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Nama Bank</h6>
                          <fieldset class="form-group text-left">
                            <div class="input-group">
                              <select class="form-control" id="input_member_bank_id" disabled></select>
                            </div>
                            <span class="text-danger alert-input" id="alert_input_member_bank_id" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>

                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Kantor Cabang</h6>
                          <fieldset class="form-group position-relative has-icon-left text-left">
                            <input type="text" class="form-control" id="input_member_bank_branch" placeholder="" disabled>
                            <div class="form-control-position"><i class="bx bx-mobile"></i></div>
                            <span class="text-danger alert-input" id="alert_input_member_bank_branch" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Nama Ahli Waris</h6>
                          <fieldset class="form-group position-relative text-left">
                            <input type="text" class="form-control" id="input_member_devisor_name" placeholder="" disabled>
                            <span class="text-danger alert-input" id="alert_input_member_devisor_name" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>

                      <div class="col-md-6 col-12">
                        <div class="d-block mb-1">
                          <h6 class="text-left">Hubungan Ahli Waris</h6>
                          <fieldset class="form-group position-relative text-left">
                            <input type="text" class="form-control" id="input_member_devisor_relation" placeholder="" disabled>
                            <span class="text-danger alert-input" id="alert_input_member_devisor_relation" style="display: none;"></span>
                          </fieldset>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 col-12 text-left mt-2">
                        <button type="button" class="btn btn-primary mr-1 mb-1" id="btn_update_profile"><i class="bx bx-edit-alt mr-50"></i>Ubah</button>
                        <button type="button" class="btn btn-primary mr-1 mb-1" id="btn_save_profile" style="display: none;"><i class="bx bx-save mr-50"></i>Simpan</button>
                        <button type="button" class="btn btn-light mr-1 mb-1" id="btn_cancel_profile"><i class="bx bx-x-circle mr-50"></i>Batal</button>
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
  </div>
</div>

<!-- Modal -->
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
            <button class="btn btn-primary" id="btn-otp">Kirim</button>
          </div>
          <div class="col col-12">
            <small class="text-danger" id="notif-otp" style="display: none;">Kode OTP berhasil dikirim.</small>
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
<!-- End Modal -->

<script>
  $(function() {
    let data_member

    getMember = () => {
      $.ajax({
        url: "<?= BASEURL ?>/member/profile/get",
        type: "GET",
        success: (res) => {
          data_member = res.data.results
          $("#img_image").prop("src", data_member.member_image)
          $("#input_member_account_username").val(data_member.member_account_username)
          $("#input_member_name").val(data_member.member_name)
          $("#input_member_email").val(data_member.member_email)
          $("#input_member_identity_no").val(data_member.member_identity_no)
          $("#input_member_identity_type").val(data_member.member_identity_type)
          $("#input_member_join_datetime").val(data_member.member_join_datetime)
          $("#input_member_mobilephone").val(data_member.member_mobilephone)
          $("#input_member_job").val(data_member.member_job)

          if (data_member.member_gender == "Laki-laki") {
            $("#input_member_gender_male").prop("checked", true)
          } else {
            $("#input_member_gender_female").prop("checked", true)
          }
          $("#input_member_birth_place").val(data_member.member_birth_place)
          $("#input_member_birth_date").val(data_member.member_birth_date)
          $("#input_member_province_id").val(data_member.member_province_id).trigger("change")
          // getCity(data_member.member_province_id)
          $("#input_member_city_id").val(data_member.member_city_id).trigger("change")
          // getSubdistrict(data_member.member_city_id)
          $("#input_member_subdistrict_id").val(data_member.member_subdistrict_id)
          $("#input_member_address").val(data_member.member_address)
          $("#input_member_bank_account_name").val(data_member.member_bank_account_name)
          $("#input_member_bank_account_no").val(data_member.member_bank_account_no)
          $("#input_member_bank_id").val(data_member.member_bank_id)
          $("#input_member_bank_branch").val(data_member.member_bank_branch)
          $("#input_member_devisor_name").val(data_member.member_devisor_name)
          $("#input_member_devisor_relation").val(data_member.member_devisor_relation)
        },
      })
    }

    $("#btn_update_profile").on("click", () => {
      $("#input_member_name").prop("disabled", false)
      $("#input_member_email").prop("disabled", false)
      $("#input_member_mobilephone").prop("disabled", false)
      $("#input_member_job").prop("disabled", false)

      $("#input_member_gender_male").prop("disabled", false)
      $("#input_member_gender_female").prop("disabled", false)
      $("#input_member_birth_place").prop("disabled", false)
      $("#input_member_birth_date").prop("disabled", false)
      $("#input_member_province_id").prop("disabled", false)
      $("#input_member_city_id").prop("disabled", false)
      $("#input_member_subdistrict_id").prop("disabled", false)
      $("#input_member_address").prop("disabled", false)
      $("#input_member_identity_type").prop("disabled", false)
      $("#input_member_identity_no").prop("disabled", false)
      $("#input_member_bank_account_name").prop("disabled", true)
      $("#input_member_bank_account_no").prop("disabled", false)
      $("#input_member_bank_id").prop("disabled", false)
      $("#input_member_bank_branch").prop("disabled", false)
      $("#input_member_devisor_name").prop("disabled", false)
      $("#input_member_devisor_relation").prop("disabled", false)
      $("#btn_update_profile").hide()
      $("#btn_save_profile").show()
    })

    $("#btn_save_profile").on("click", () => {
      if (
        $("#input_member_name").val() != (data_member.member_name ?? "") ||
        $("#input_member_mobilephone").val() != (data_member.member_mobilephone ?? "") ||
        $("#input_member_identity_type").val() != (data_member.member_identity_type ?? "") ||
        $("#input_member_identity_no").val() != (data_member.member_identity_no ?? "") ||
        $("#input_member_bank_account_no").val() != (data_member.member_bank_account_no ?? "")
      ) {
        $("#input_otp").val("")
        $("#modal-otp").modal("show")
      } else {
        save()
      }
    })

    $("#btn-otp").on("click", () => {
      generateOTP()
      $("#notif-otp").show()
      setTimeout(function() {
        $("#notif-otp").hide()
      }, 3000)
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

    save = () => {
      $.ajax({
        url: "<?= BASEURL ?>/member/profile/update",
        type: "POST",
        data: {
          member_name: $("#input_member_name").val(),
          member_email: $("#input_member_email").val(),
          member_mobilephone: $("#input_member_mobilephone").val(),
          member_job: $("#input_member_job").val(),
          member_gender: $("#input_member_gender_male").prop("checked") ? "Laki-laki" : "Perempuan",
          member_birth_place: $("#input_member_birth_place").val(),
          member_birth_date: $("#input_member_birth_date").val(),
          member_province_id: $("#input_member_province_id").val(),
          member_city_id: $("#input_member_city_id").val(),
          member_subdistrict_id: $("#input_member_subdistrict_id").val(),
          member_address: $("#input_member_address").val(),
          member_identity_type: $("#input_member_identity_type").val(),
          member_identity_no: $("#input_member_identity_no").val(),
          member_bank_account_name: $("#input_member_bank_account_name").val(),
          member_bank_account_no: $("#input_member_bank_account_no").val(),
          member_bank_id: $("#input_member_bank_id").val(),
          member_bank_branch: $("#input_member_bank_branch").val(),
          member_devisor_name: $("#input_member_devisor_name").val(),
          member_devisor_relation: $("#input_member_devisor_relation").val(),
        },
        success: (res) => {
          data = res.data.results
          console.log(res)
          $("#input_member_name").prop("disabled", true)
          $("#input_member_email").prop("disabled", true)
          $("#input_member_mobilephone").prop("disabled", true)
          $("#input_member_job").prop("disabled", true)
          $("#input_member_gender_male").prop("disabled", true)
          $("#input_member_gender_female").prop("disabled", true)
          $("#input_member_birth_place").prop("disabled", true)
          $("#input_member_birth_date").prop("disabled", true)
          $("#input_member_province_id").prop("disabled", true)
          $("#input_member_city_id").prop("disabled", true)
          $("#input_member_subdistrict_id").prop("disabled", true)
          $("#input_member_address").prop("disabled", true)
          $("#input_member_identity_type").prop("disabled", true)
          $("#input_member_identity_no").prop("disabled", true)
          $("#input_member_bank_account_name").prop("disabled", true)
          $("#input_member_bank_account_no").prop("disabled", true)
          $("#input_member_bank_id").prop("disabled", true)
          $("#input_member_bank_branch").prop("disabled", true)
          $("#input_member_devisor_name").prop("disabled", true)
          $("#input_member_devisor_relation").prop("disabled", true)
          $("#btn_update_profile").show()
          $("#btn_save_profile").hide()
          $(".alert-input").hide()
          $("#alert-success").html(res.message)
          $("#alert-success").show()
          getMember()
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

    $("#btn_cancel_profile").on("click", () => {
      $("#input_member_name").val(data_member.member_name).prop("disabled", true)
      $("#input_member_email").val(data_member.member_email).prop("disabled", true)
      $("#input_member_identity_type").val(data_member.member_identity_type).prop("disabled", true)
      $("#input_member_identity_no").val(data_member.member_identity_no).prop("disabled", true)
      $("#input_member_join_datetime").val(data_member.member_join_datetime).prop("disabled", true)
      $("#input_member_mobilephone").val(data_member.member_mobilephone).prop("disabled", true)
      $("#input_member_job").val(data_member.member_job).prop("disabled", true)
      if (data_member.member_gender == "Laki-laki") {
        $("#input_member_gender_male").prop("checked", true).prop("disabled", true)
      } else {
        $("#input_member_gender_female").prop("checked", true).prop("disabled", true)
      }
      $("#input_member_birth_place").val(data_member.member_birth_place).prop("disabled", true)
      $("#input_member_birth_date").val(data_member.member_birth_date).prop("disabled", true)
      $("#input_member_province_id").val(data_member.member_province_id).prop("disabled", true).trigger("change")
      // getCity(data_member.member_province_id)
      $("#input_member_city_id").val(data_member.member_city_id).prop("disabled", true).trigger("change")
      // getSubdistrict(data_member.member_city_id)
      $("#input_member_subdistrict_id").val(data_member.member_subdistrict_id).prop("disabled", true)
      $("#input_member_address").val(data_member.member_address).prop("disabled", true)
      $("#input_member_bank_account_name").val(data_member.member_bank_account_name).prop("disabled", true)
      $("#input_member_bank_account_no").val(data_member.member_bank_account_no).prop("disabled", true)
      $("#input_member_bank_id").val(data_member.member_bank_id).prop("disabled", true)
      $("#input_member_bank_branch").val(data_member.member_bank_branch).prop("disabled", true)
      $("#input_member_devisor_name").val(data_member.member_devisor_name).prop("disabled", true)
      $("#input_member_devisor_relation").val(data_member.member_devisor_relation).prop("disabled", true)
      $(".alert-input").hide()
      $("#btn_update_profile").show()
      $("#btn_save_profile").hide()
    })

    $("#btn_image").on("click", () => {
      if ($("#btn_image").html() == "Ubah Foto Profil") {
        $("#input_image").trigger("click")
      } else {
        $("#btn_image").html("Uploading...")
        $("#btn_image").prop("disabled", true)
        let data = new FormData()
        data.append('member_image', $("#input_image").prop("files")[0])
        $.ajax({
          url: "<?= BASEURL ?>/member/profile/update-image",
          type: "POST",
          processData: false,
          contentType: false,
          data: data,
          success: (res) => {
            $("#btn_image").html("Ubah Foto Profil")
            $("#btn_image").prop("disabled", false)
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
            $("#btn_image").html("Ubah Foto Profil")
            $("#btn_image").prop("disabled", false)
            if (res.error == "validation") {
              $.each(res.data, (i, val) => {
                $(`#alert_input_${i}`).html(val).show()
              })
            }
          },
        })
      }
    })

    $("#input_image").on("change", (ev) => {
      const [file] = ev.target.files
      if (file) {
        $("#img_image").prop("src", URL.createObjectURL(file))
        $("#btn_image").html("Simpan Foto Profil")
      }
    })

    $("#input_member_province_id").on("change", (ev) => {
      getCity($(ev.target).val())
    })

    $("#input_member_city_id").on("change", (ev) => {
      getSubdistrict($(ev.target).val())
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
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-ref-city/?province_id=" + province_id,
        type: "GET",
        async: false,
        success: (res) => {
          data = res.data.results
          $("#input_member_city_id").empty()
          $.each(data, (i, val) => {
            $("#input_member_city_id").append(
              `<option value="${val.city_id}">${val.city_name}</option>`)
          })
        },
      })
    }

    getSubdistrict = (city_id) => {
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-ref-subdistrict/?city_id=" + city_id,
        type: "GET",
        async: false,
        success: (res) => {
          data = res.data.results
          $("#input_member_subdistrict_id").empty()
          $.each(data, (i, val) => {
            $("#input_member_subdistrict_id").append(
              `<option value="${val.subdistrict_id}">${val.subdistrict_name}</option>`)
          })
        },
      })
    }

    getBank = () => {
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-ref-bank/",
        type: "GET",
        async: false,
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

    getProvince()
    getBank()
    getMember()
  })
</script>
<!-- END: Content-->