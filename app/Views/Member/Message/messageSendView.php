<style>
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #DFE3E7 !important;
        padding: 2px 8px;
    }

    button:focus {
        outline: none;
    }

    .alert-position {
        transform: translateY(5px);
    }
</style>

<div class="app-content content" id="app">
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
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body row" style="margin: 0">
                            <div class="outline--form col-lg-12 col-md-12" style="border-radius: 15px; border: 1px solid #DFE3E7; padding: 0">
                                <div style="margin: 10px 0; padding: 20px">
                                    <form id="formAddUpdate">
                                        <div id="response-message"></div>
                                        <div class="input-group" style="margin-bottom: 10px">
                                            <div class="row">
                                                <div class="col-12">
                                                    <label for="">Tujuan Pesan</label>
                                                </div>
                                                <div class="col-12">
                                                    <!-- <input type="radio" value="member" name="type_receiver" class="mr-25">Mitra</input> -->
                                                    <!-- <input type="radio" value="admin" name="type_receiver" class="mx-25">Admin</input> -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-group" style="margin-bottom: 10px">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text d-flex justify-content-center" style="width: 45px">
                                                    <i class="bx bx-user"></i>
                                                </div>
                                            </div>
                                            <input type="text" placeholder="Kode Mitra" class="form-control" id="receiver" value="Admin" readonly>
                                        </div>

                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-12 form-group">
                                                    <!-- Compose mail Quill editor -->
                                                    <div class="snow-container border rounded p-50">
                                                        <div id="message_content" class="compose-editor" style="border: none; min-height: 200px"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-lg-6 ml-auto text-right">
                                            <a href="#" class="btn btn-primary" onclick="sendMessage()">
                                                <span style="font-weight: 700">KIRIM</span>
                                            </a>
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

<script>
    $(".reset").click(function() {
        $('form').find("input[type=text], textarea").val("");
        location.reload();
    });

    $('input[type=radio][name=type_receiver]').change(function() {
        let val_receiver = $('input[type=radio][name=type_receiver]:checked').val()

        if (val_receiver == 'admin') {
            $('#receiver').val('Admin')
            $('#receiver').prop("disabled", true)
        } else {
            $('#receiver').val('')
            $('#receiver').prop("disabled", false)
        }
    });

    $(document).ready(() => {
        $('.ql-editor').attr("data-placeholder", "Tulis Pesan Anda....");
        var container = document.getElementById('message_content');
        var editor = new Quill(container);
        editor.enable(true)
        $('input[type=radio][name=type_receiver]:checked').val('admin')
    })

    function sendMessage() {
        $.ajax({
            url: "<?= BASEURL ?>/member/message/send-msg",
            type: "POST",
            async: false,
            data: {
                message_receiver_ref_code: 'Admin',
                message_receiver_ref_type: 'admin',
                message_content: $('#message_content .ql-editor p').html(),
                message_type: 'pesan',
            },
            success: (res) => {
                $("#alert-success").html(res.message)
                $("#alert-success").show()
                $('#message_content .ql-editor p').html('')
                $('input[type=radio][name=type_receiver]').prop('checked', false)
                $('#receiver').val('')
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
            }
        })
    }
</script>