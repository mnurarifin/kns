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
                                <li class="breadcrumb-item"><a href="<?= BASEURL ?>/admin/dashboard"><i class="bx bx-home-alt"></i></a></li>
                                <li class="breadcrumb-item"><a href="#"><?= $breadcrumbs[0] ?></a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section id="floating-label-layouts">
                <div class="row match-height">
                    <div class="col-md-12 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Form Testimoni</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-12 pb-2">
                                                <fieldset class="form-label-group mb-0">
                                                    <textarea data-length=500 class="form-control char-textarea" id="testimony_content" name="testimony_content" rows="3" placeholder="Testimoni"></textarea>
                                                </fieldset>
                                                <small class="counter-value float-right"><span class="char-count">0</span> / 500 </small>
                                                <small class="text-danger d-none error_testimony_content alert-input"></small>
                                            </div>
                                            <div class="col-12 d-flex justify-content-end">
                                                <button onclick="save()" class="btn btn-primary">Kirim</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    $(function() {
        save = () => {
            $(".alert-input").addClass('d-none')

            $.ajax({
                url: "<?= BASEURL ?>/member/testimony/add",
                type: "POST",
                data: {
                    testimony_content: $('#testimony_content').val()
                },
                success: (res) => {
                    data = res.data.results
                    $("#alert-success").html(res.message)
                    $("#alert-success").show()
                    $('#testimony_content').val('')
                    setTimeout(function() {
                        $("#alert-success").hide()
                    }, 3000)
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
                            $(`.error_${i}`).html(val)
                            $(`.error_${i}`).removeClass('d-none')
                        })
                    }
                },
            })
        }
    })
</script>
<!-- END: Content-->