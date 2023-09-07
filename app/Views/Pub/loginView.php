<div class="app-content content app-login" id="app">
  <div class="content-overlay"></div>
  <div class="content-wrapper">
    <div class="content-header row">
    </div>
    <div class="content-body">
      <!-- login page start -->
      <section id="auth-login" class="row justify-content-center flexbox-container">
        <div class="col-xl-7 col-11">
          <div class="card bg-authentication mb-0 p-2">
            <div class="alert alert-danger mb-0" role="alert" id="alert" v-if="messageError">{{messageError}}</div>
            <div class="alert alert-success mb-0" role="alert" id="alert-success" v-if="messageSuccess">{{messageSuccess}}</div>
            <div class="row m-0">
              <!-- left section-login -->
              <div class="col-sm-12 col-md-6 col-xl-6 col-12 px-0">
                <div class="card mb-0 px-1 h-100 d-flex justify-content-center bg-transparent">
                  <div class="card-header pb-1">
                    <div class="card-title">
                      <a href="<?= BASEURL; ?>/home">
                        <div class="login-icon"></div>
                      </a>
                      <h4 class="text-center cl-secondary mb-2">MEMBER AREA</h4>
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
                        <input type="text" v-model="form.username" class="form-control" placeholder="ID Mitra" @keyup.enter="login()">
                        <small class="text-danger alert-input" :v-if="formError.username">{{formError.username}}</small>
                      </div>
                      <div class="form-password-toggle mb-3">
                        <div class="input-group input-group-merge">
                          <input :type="passwordFieldType" v-model="form.password" class="form-control" placeholder="Password" @keyup.enter="login()">
                          <span class="input-group-text cursor-pointer bg-white" @click="switchVisibility"><i class="bx bx-hide"></i></span>
                        </div>
                        <small class="text-danger alert-input" :v-if="formError.password">{{formError.password}}</small>
                      </div>
                      <div class="form-group mb-1">
                        <div class="input-group input-group-merge">
                          <input type="text" class="form-control" id="input_captcha" v-model="form.captcha" placeholder="Kode Unik" @keyup.enter="login()">
                          <span class="input-group-text cursor-pointer bg-white" @click="getCaptcha()"><i class="bx bx-revision"></i></span>
                        </div>
                        <small class="text-danger alert-input" :v-if="formError.captcha">{{formError.captcha}}</small>
                      </div>
                      <div class="form-group mb-2">
                        <img :src="urlCaptcha" class="img-fluid" alt="Captcha" style="width: -webkit-fill-available;">
                      </div>

                      <button class="btn btn-success btn-login glow w-100 position-relative" @click="login()">
                        Login
                        <i id="icon-arrow" class="bx bx-right-arrow-alt"></i>
                      </button>

                      <!-- forgot password -->
                      <div class="text-right mt-1">
                        <a href="#" @click="openForgotPasswordModal" class="cl-secondary">
                          <small>Lupa Password?</small>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true" style="z-index: 99999999;">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body text-center">
              <div class="step-1" v-show="reset_step == 1">
                <img src="<?= BASEURL; ?>/app-assets/lock.svg" alt="lock" class="mb-2">
                <h3>Reset Password</h3>
                <p class="mb-2">Masukkan ID Mitra anda untuk mereset password</p>

                <form class="text-left">
                  <input type="text" v-model="formResetPassword.account_username" class="form-control" placeholder="ID Mitra">
                  <small class="text-danger alert-input" :v-if="formErrorResetPassword.account_username">{{formErrorResetPassword.account_username}}</small>
                </form>

              </div>
              <div class="step-2" v-show="reset_step == 2">
                <img src="<?= BASEURL; ?>/app-assets/lock.svg" alt="lock" class="mb-2">
                <h3>Reset Password</h3>
                <p class="mb-2">Masukkan kode yang telah dikirim ke whatsapp anda</p>

                <div id="otp" class="inputs d-flex flex-row justify-content-center">
                  <input class="mx-1 text-center form-control rounded" type="text" id="first" maxlength="1" />
                  <input class="mx-1 text-center form-control rounded" type="text" id="second" maxlength="1" />
                  <input class="mx-1 text-center form-control rounded" type="text" id="third" maxlength="1" />
                  <input class="mx-1 text-center form-control rounded" type="text" id="fourth" maxlength="1" />
                  <input class="mx-1 text-center form-control rounded" type="text" id="fifth" maxlength="1" />
                  <input class="mx-1 text-center form-control rounded" type="text" id="sixth" maxlength="1" />
                </div>
                <small class="text-danger ml-2 alert-input mt-1" :v-show="formErrorResetPassword.otp">{{formErrorResetPassword.otp}}</small>
              </div>
              <div class="step-3" v-show="reset_step == 3">
                <img src="<?= BASEURL; ?>/app-assets/lock.svg" alt="lock" class="mb-2">
                <h3>Reset Password</h3>
                <p class="mb-2">Masukkan password baru anda</p>

                <form>
                  <div class="form-group text-left">
                    <label for="input_member_gender">Password Baru</label>
                    <div class="input-group input-group-merge">
                      <input :type="passwordFieldTypeKonfirmasi" v-model="formResetPassword.account_password" class="form-control" placeholder="Masukan Password">
                      <span class="input-group-text cursor-pointer bg-white" @click="
                      passwordFieldTypeKonfirmasi = passwordFieldTypeKonfirmasi === 'password' ? 'text' : 'password'
                      "><i class="bx bx-hide"></i></span>
                    </div>
                    <small class="text-danger alert-input" :v-if="formErrorResetPassword.account_password">{{formErrorResetPassword.account_password}}</small>
                  </div>
                  <div class="form-group text-left">
                    <label for="input_member_gender">Konfirmasi Password Baru</label>
                    <div class="input-group input-group-merge">
                      <input :type="passwordFieldTypeKonfirmasi2" v-model="formResetPassword.account_password_confirm" class="form-control" placeholder="Masukan Konfirmasi">

                    </div>
                    <small class="text-danger alert-input" :v-if="formErrorResetPassword.account_password_confirm">{{formErrorResetPassword.account_password_confirm}}</small>
                  </div>
                </form>

              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary btn-block" @click="resetPassword(1)" v-show="reset_step == 1">Reset</button>
              <button type="button" id="nextButton" disabled class="btn btn-primary btn-block" @click="resetPassword(2)" v-show="reset_step == 2">Lanjut</button>
              <button type="button" class="btn btn-primary btn-block" @click="resetPassword(3)" v-show="reset_step == 3">Reset</button>
            </div>
          </div>
        </div>
      </div>
      <!-- login page ends -->
    </div>

    <div class="content-footer">
      <footer class="login-footer" data-booted="true">
        <div class="text-center cl-white col col-12">
          <?= date("Y") ?> - <strong><?= PROJECT_NAME ?></strong></div>
      </footer>
    </div>
  </div>


</div>

<style>
  /* if max-width lesss than 399 remove margin of otp code */
  @media (max-width: 399px) {
    #otp>*[id] {
      margin: 0 !important;
    }
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    function OTPInput() {
      const inputs = document.querySelectorAll('#otp > *[id]');
      for (let i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener('keydown', function(event) {
          // if id equal to fifth then change app.disable to false

          if (event.target.id == 'sixth' && event.key) {
            $('#nextButton').removeAttr('disabled');
          } else {
            $('#nextButton').attr('disabled', 'disabled');
          }

          console.log(event.keyCode)
          if (event.keyCode < 48 || event.keyCode > 57) { // allow only numbers
            if (event.keyCode != 8) { // and backspace
              event.preventDefault(); // prevent the default action
            }
          }


          if (event.key === "Backspace") {
            inputs[i].value = '';
            if (i !== 0) inputs[i - 1].focus();
          } else {
            if (i === inputs.length - 1 && inputs[i].value !== '') {
              return true;
            } else if (event.keyCode > 47 && event.keyCode < 58) {
              inputs[i].value = event.key;
              if (i !== inputs.length - 1) inputs[i + 1].focus();
              event.preventDefault();
            } else if (event.keyCode > 64 && event.keyCode < 91) {
              alert('Please use numbers only');
            }
          }
        });
      }
    }
    OTPInput();
  });

  let app = Vue.createApp({
    data: () => {
      return {
        reset_step: 1,
        form: {},
        formError: {},
        formResetPassword: {
          account_username: '',
        },
        formErrorResetPassword: {
          account_username: '',
        },
        messageError: '',
        messageSuccess: '',
        passwordFieldType: 'password',
        passwordFieldTypeKonfirmasi: 'password',
        passwordFieldTypeKonfirmasi2: 'password',
        urlCaptcha: '',
      }
    },
    methods: {
      resetPassword(step) {
        if (step == 1) {
          this.checkUsername();
        }

        if (step == 2) {
          // get all value from input otp container
          let otp = document.querySelectorAll('#otp > *[id]');
          let otpValue = '';
          for (let i = 0; i < otp.length; i++) {
            otpValue += otp[i].value;
          }

          this.verifyOTP(otpValue);
        }

        if (step == 3) {
          this.processResetPassword();
        }

        if (this.reset_step > 3) {
          this.reset_step = 1;
        }
      },
      async processResetPassword() {
        await axios({
          method: 'post',
          url: `<?= BASEURL ?>/login/changePassword`,
          data: {
            account_password: this.formResetPassword.account_password,
            account_username: this.formResetPassword.account_username,
            account_password_confirm: this.formResetPassword.account_password_confirm,
            otp: this.formResetPassword.account_otp,
          },
        }).then((response) => {
          this.messageSuccess = response.data.message;
          this.reset_step = 1;

          $('#forgotPasswordModal').modal('hide');

          this.resetVerifyForm();

          setTimeout(() => {
            this.messageSuccess = '';
          }, 1000);
        }).catch((error) => {
          console.log(error.response.data.data);
          this.formErrorResetPassword = error.response.data.data;
        })
      },

      openForgotPasswordModal() {
        this.resetVerifyForm();
        $('#forgotPasswordModal').modal('show');
      },
      resetVerifyForm() {
        this.formResetPassword = {
          account_username: '',
        }
        this.formErrorResetPassword = {
          account_username: '',
        }

        // reset otp input
        let otp = document.querySelectorAll('#otp > *[id]');

        for (let i = 0; i < otp.length; i++) {
          otp[i].value = '';
        }

        // disabled next button
        $('#nextButton').attr('disabled', 'disabled');

        this.reset_step = 1;
      },

      getCaptcha() {
        this.urlCaptcha = `<?= BASEURL ?>/login/getCaptcha?${Math.random()}`
      },
      switchVisibility() {
        this.passwordFieldType = this.passwordFieldType === 'password' ? 'text' : 'password';
      },
      switchVisibilityKonfirmasiPassword() {
        this.passwordFieldTypeKonfirmasi = this.passwordFieldTypeKonfirmasi === 'password' ? 'text' : 'password';
      },
      resetError() {
        this.formError = {}
        this.messageError = ''
        this.messageSuccess = ''
      },
      async checkUsername() {
        try {
          let response = await axios({
            method: 'get',
            url: `<?= BASEURL ?>/login/checkMemberAccount/` + this.formResetPassword.account_username,
          })

          this.formErrorResetPassword.account_username = '';
          this.reset_step = 2;
          this.sendOtp();
        } catch (error) {
          this.formErrorResetPassword.account_username = error.response.data.message
        }
      },
      async verifyOTP(otp) {
        try {
          let response = await axios({
            method: 'get',
            url: `<?= BASEURL ?>/login/verifyOtp/` + this.formResetPassword.account_username + '/' + otp,
          })

          this.formResetPassword.account_otp = otp;

          this.reset_step = 3;
        } catch (error) {
          this.formErrorResetPassword.otp = error.response.data.message
        }
      },
      async sendOtp() {
        let response = await axios({
          method: 'get',
          url: `<?= BASEURL ?>/login/sendVerifyOtp/` + this.formResetPassword.account_username,
        })
      },
      async login() {
        try {
          this.resetError()
          let response = await axios({
            method: 'post',
            url: `<?= BASEURL ?>/login/verify`,
            data: this.form,
          })
          this.messageSuccess = response.data.message
          location.href = `<?= BASEURL ?>/${response.data.data.url}`
        } catch (error) {
          this.messageError = error.response.data.message
          if (error.response.status == 400 && error.response.data.error == 'validation') {
            this.formError = error.response.data.data
          }
          this.form.captcha = ''
          this.getCaptcha()
        }
      },

    },
    mounted() {
      this.getCaptcha()
    }
  }).mount('#app')
</script>