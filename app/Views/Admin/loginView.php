<div class="content-wrapper">
    <div class="content-header row">
    </div>
    <div class="content-body">
        <!-- login page start -->
        <section id="auth-login" class="row flexbox-container">
            <div class="col-xl-8 col-11">
                <div class="card bg-authentication mb-0">
                    <div class="row m-0">
                        <!-- left section-login -->
                        <div class="col-md-6 col-12 px-0">
                            <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
                                <div class="card-header pb-1">
                                    <div class="card-title">
                                        <h4 class="text-center mb-2"><?php echo isset($title) ? $title : ''; ?></h4>
                                    </div>
                                    <?php
                                    if ($session->confirmation) {
                                        echo '<div class="alert alert-danger mb-0">' . $session->confirmation . '</div>';
                                    }
                                    ?>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form action="<?php echo $formAction; ?>" method="POST">
                                            <input type="hidden" name="redirect_url" value="<?php echo $redirect_url; ?>">
                                            <div class="form-group mb-50">
                                                <label class="text-bold-600" for="inputUsername">Username</label>
                                                <input type="text" class="form-control" name="username" id="inputUsername" placeholder="Masukan Username">
                                                <?php if ($session->username) {
                                                    echo '<small class="text-danger">' . $session->username . '</small>';
                                                } ?>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-bold-600" for="inputPassword">Password</label>
                                                <input type="password" class="form-control" name="password" id="inputPassword" placeholder="Masukan Password">
                                                <?php if ($session->password) {
                                                    echo '<small class="text-danger">' . $session->password . '</small>';
                                                } ?>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-bold-600">Captcha</label><br>
                                                <i class="mb-1" style="color: red;"><?php print_r($session->errorCaptcha) ?></i>
                                                <img id="captcha_image" src="" class="img-fluid" alt="kode unik" style="width: -webkit-fill-available;">
                                            </div>
                                            <div class="form-group">
                                                <div class="row align-items-end">
                                                    <div class="col-md-8 col-sm-6">
                                                        <input type="text" class="form-control" name="captcha" autocomplete="off" placeholder="Masukan Kode Captcha">
                                                    </div>
                                                    <div class="col-md-4 col-sm-6 mt-1 text-right">
                                                        <button class="btn btn-outline-secondary btn-block br-25 font-9" type="button" id="refresh_capcta" style="padding-left: unset; padding-right: unset;">
                                                            refresh
                                                        </button>
                                                    </div>
                                                </div>
                                                <?php if ($session->captcha) {
                                                    echo '<small class="text-danger">' . $session->captcha . '</small>';
                                                } ?>
                                            </div>
                                            <div class="form-group d-flex flex-md-row flex-column justify-content-between align-items-center">
                                                <!-- <div class="text-left">
                                                    <div class="checkbox checkbox-sm">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                        <label class="checkboxsmall" for="exampleCheck1"><small>Keep me logged in</small></label>
                                                    </div>
                                                </div>
                                                <div class="text-right"><a href="auth-forgot-password.html" class="card-link"><small>Forgot Password?</small></a></div> -->
                                            </div>
                                            <button type="submit" class="btn btn-primary glow w-100 position-relative">Login<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- right section image -->
                        <div class="col-md-6 d-md-block d-none text-center align-self-center p-3">
                            <div class="card-content">
                                <img class="img-fluid" src="<?php echo base_url(); ?>/app-assets/images/pages/login.png" alt="branding logo">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- login page ends -->

    </div>
</div>

<script>
    let urlCaptcha = window.location.origin + '/system_login/get_captcha'
    $(document).ready(function() {
        $("#captcha_image").attr("src", urlCaptcha);
        $("#refresh_capcta").click(function() {
            $("#captcha_image").attr("src", urlCaptcha + "?" + Math.random());
        });
    });
</script>