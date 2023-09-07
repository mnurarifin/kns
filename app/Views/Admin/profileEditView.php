<section id="page-profile-settings">
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
                        <div class="row">
                            <div class="col-md-12" style="margin-top: 10px;">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane active" id="account-vertical-general" aria-labelledby="account-pill-general" aria-expanded="true">
                                                <div class="media">
                                                    <a href="javascript: void(0);">
                                                        <img :src="form.administrator_temp_image" id="profile-image" class="rounded mr-75" alt="profile image" height="80" width="80">
                                                    </a>
                                                    <div class="media-body mt-25">
                                                        <div class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">
                                                            <label for="upload-files" class="btn btn-sm btn-light-primary ml-50 mb-50 mb-sm-0">
                                                                <span>Upload Foto Baru</span>
                                                                <input @change="previewFiles" id="upload-files" type="file" hidden="">
                                                            </label>
                                                        </div>
                                                        <p class="text-muted ml-1 mt-50"><small>Diperbolehkan
                                                                JPG, GIF atau PNG.
                                                                Maximal
                                                                Ukuran
                                                                sebesar
                                                                2mb</small></p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <form class="validate-form" novalidate="novalidate">
                                                    <div class="alert alert-success" v-show="alert.success.status" style="display: none;">
                                                        <span v-html="alert.success.content"></span>
                                                    </div>
                                                    <div class="alert alert-danger" v-show="alert.danger.status" style="display: none;"> <span v-html="alert.danger.content"></span> </div>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <div class="controls">
                                                                    <label>Username</label>
                                                                    <input v-model="form.administrator_username" type="text" class="form-control" placeholder="Masukkan Username" name="username">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <div class="controls">
                                                                    <label>Nama</label>
                                                                    <input v-model="form.administrator_name" type="text" class="form-control" placeholder="Masukkan Nama" name="name">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <div class="controls">
                                                                    <label>E-mail</label>
                                                                    <input v-model="form.administrator_email" type="email" class="form-control" placeholder="Masukkan Email" name="email">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                            <a style="color:white" onclick="pageAccount.updateMyProfile()" class="btn btn-primary glow mr-sm-1 mb-1">Simpan</a>
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
</section>


<script src="https://unpkg.com/vue@next"></script>
<script>
    $(window).on('hashchange', function() {
        if (location.hash === '#change_password') {
            $('#account-pill-password').click();
        }
    });

    function changeHash() {
        history.replaceState(null, null, ' ');
    }

    const pageAccount =
        Vue.createApp({
            data: function() {
                return {
                    form: {
                        administrator_username: '',
                        administrator_name: '',
                        administrator_email: '',
                        administrator_image: '',
                        administrator_temp_image: '',
                    },
                    button: {
                        formBtn: {
                            disabled: false
                        }
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
                getMyProfile() {
                    $.ajax({
                        url: '<?php echo site_url('admin/service/profile/getMyProfile') ?>',
                        method: 'GET',
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data;
                                data.results.administrator_temp_image = data.results.administrator_image ? `<?= "/upload/" . URL_IMG_ADMIN ?>${data.results.administrator_image}` : `/no-image-profile.png`;
                                pageAccount.form = data.results;

                            }
                        }
                    });
                },
                updateMyProfile() {
                    let formData = new FormData();
                    formData.append("administrator_username", this.form.administrator_username);
                    formData.append("administrator_name", this.form.administrator_name);
                    formData.append("administrator_email", this.form.administrator_email);
                    formData.append("administrator_image", this.form.administrator_image);
                    $.ajax({
                        url: '<?php echo site_url('admin/service/profile/updateMyProfile') ?>',
                        method: 'POST',
                        data: formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status == 200) {
                                pageAccount.alert.success.content = response.message;
                                pageAccount.alert.success.status = true;

                                setTimeout(() => {
                                    pageAccount.alert.success.status = false;
                                    location.reload();
                                }, 1000);
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
                                    }, 3000);
                                }

                            }
                        },
                    });
                },
                previewFiles() {
                    pageAccount.button.formBtn.disabled = true;
                    let files = document.getElementById("upload-files").files[0];
                    if (files.size > 2086756) {
                        pageAccount.alert.danger.content = `<ul>`;
                        pageAccount.alert.danger.content +=
                            `<li> File harus tidak lebih dari 1 mb </li>`;
                        pageAccount.alert.danger.content += `</ul>`;
                        pageAccount.alert.danger.status = true;

                        setTimeout(() => {
                            pageAccount.alert.danger.status = false;
                        }, 3000);
                        return
                    }
                    var reader = new FileReader();
                    let temp_url = reader.readAsDataURL(files);

                    pageAccount.form.administrator_temp_image = temp_url;

                    reader.onload = function(e) {
                        $('#profile-image')
                            .attr('src', e.target.result)
                    };

                    let formData = new FormData();
                    formData.append("file", files);

                    $.ajax({
                        url: '<?php echo site_url('admin/service/profile/uploadImage') ?>',
                        method: 'POST',
                        data: formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status == 200) {
                                let data = response.data;
                                pageAccount.form.administrator_image = data.name;
                                pageAccount.button.formBtn.disabled = false;

                            }
                        },
                        error: function(res) {
                            let response = res.responseJSON;
                            pageAccount.button.formBtn.disabled = false;

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
                                    }, 3000);
                                }

                            }
                        },
                    });


                }
            },
            computed: {

            },

            mounted() {
                this.getMyProfile();
                $("#pageLoader").hide();

            }

        }).mount("#page-profile-settings");
</script>