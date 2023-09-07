<section id="page-profile-settings-password">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?= isset($title) ? $title : '' ?></h4>
                </div>
                <div class="card-content">
                    <div id="pageLoader">
                        <div class="text-center text-muted d-flex align-center justify-content-center bg-grey-light p-2">
                            <div class="spinner-border text-info spinner-border-sm" role="status" style="margin-right: 8px;margin-top: 2px;">
                                <span class="sr-only">&nbsp;</span>
                            </div>
                            <span>Sedang memuat informasi, mohon tunggu beberapa saat...</span>
                        </div>
                    </div>

                    <div class="card-body card-dashboard">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-12" style="margin-top: 10px;">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane active" id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
                                                    <div class="alert alert-success" v-show="alert.success.status" style="display: none;"> <span v-html="alert.success.content"></span> </div>
                                                    <div class="alert alert-danger" v-show="alert.danger.status" style="display: none;">
                                                        <span v-html="alert.danger.content"></span>
                                                    </div>

                                                    <form class="validate-form" novalidate="novalidate">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <div class="controls">
                                                                        <label>Password lama</label>
                                                                        <input v-model="formPassword.administrator_old_password" type="password" class="form-control" placeholder="Masukan Password Lama" name="password">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <div class="controls">
                                                                        <label>Password Baru</label>
                                                                        <input v-model="formPassword.administrator_password" type="password" class="form-control" placeholder="Masukan Password Baru" id="account-new-password" name="new-password">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <div class="controls">
                                                                        <label>Konfirmasi Password Baru</label>
                                                                        <input v-model="formPassword.administrator_new_password" type="password" class="form-control" data-validation-match-match="password" placeholder="Masukan Ulang Password Baru" name="confirm-new-password">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                                <a style="color:white" onclick="pageAccount.updateMyPassword()" class="btn btn-primary glow mr-sm-1 mb-1">Simpan</a>
                                                            </div>
                                                        </div>
                                                    </form>
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
</section>



<script src="https://unpkg.com/vue@next"></script>
<script>
    const pageAccount =
        Vue.createApp({
            data: function() {
                return {
                    formPassword: {
                        administrator_old_password: '',
                        administrator_password: '',
                        administrator_new_password: '',
                    },

                    alert: {
                        success: {
                            status: false,
                            content: '',
                        },
                        danger: {
                            status: false,
                            content: '',
                        }
                    },
                }
            },
            methods: {
                updateMyPassword() {
                    $.ajax({
                        url: '<?php echo site_url('admin/service/profile/updateMyPassword') ?>',
                        method: 'POST',
                        data: pageAccount.formPassword,
                        success: function(response) {
                            if (response.status == 200) {
                                pageAccount.formPassword = {
                                    administrator_old_password: '',
                                    administrator_password: '',
                                    administrator_new_password: '',
                                }
                                pageAccount.alert.success.content = response.message;
                                pageAccount.alert.success.status = true;

                                setTimeout(() => {
                                    pageAccount.alert.success.status = false;
                                }, 3000);
                            }
                        },
                        error: function(res) {
                            let response = res.responseJSON;

                            if (response.status == 400 && response.message == "validationError") {
                                let resValidation = Object.values(response.data.validationMessage);

                                if (resValidation.length > 0) {
                                    pageAccount.alert.danger.content = `<ul>`;
                                    resValidation.forEach((data) => {
                                        pageAccount.alert.danger.content +=
                                            `<li> ${data} </li>`;
                                    });
                                    pageAccount.alert.danger.content += `</ul>`;
                                    pageAccount.alert.danger.status = true;

                                    setTimeout(() => {
                                        pageAccount.alert.danger.status = false;
                                    }, 5000);
                                }

                            }
                        },
                    });


                },
            },
            computed: {

            },

            mounted() {
                $("#pageLoader").hide();
            }

        }).mount("#page-profile-settings-password");
</script>