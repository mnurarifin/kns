<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/app-assets/vendors/css/editors/quill/quill.snow.css">
<script src="<?php echo base_url(); ?>/app-assets/vendors/js/editors/quill/quill.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/app-assets/css/pages/app-email.css">
<script src="<?php echo base_url(); ?>/app-assets/js/scripts/pages/app-email-custom.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.ql-editor').attr("data-placeholder", "Tulis Pesan Anda....");
        $('#message_receive_id').select2({
            placeholder: 'Mitra Tujuan',
            ajax: {
                url: window.location.origin + '/admin/service/message/getPenerima',
                dataType: 'json',
                data: function(params) {
                    return {
                        search: params.term
                    }
                },
                processResults: function(data, page) {
                    return {
                        results: data
                    };
                }
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            }
        });
    });
</script>


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

<section id="horizontal-vertical">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Buat Pesan</h5>
                </div>
                <div class="card-body row" style="margin: 0">
                    <div class="outline--form col-lg-12 col-md-12" style="border-radius: 15px; border: 1px solid #DFE3E7; padding: 0">
                        <div class="d-flex justify-content-end align-items-center" style="padding: 20px; border-bottom: 1px solid #eee">
                            <button class="reset btn-outline-danger rounded-lg align-items-center d-flex" style="padding: 6px 14px; margin: 0 6px">
                                <i class="bx bx-trash font-medium-5" style="margin-right: 4px"></i>
                                <span class="black--text">Hapus</span>
                            </button>

                            <button id="draft" class="btn-outline-primary rounded-lg align-items-center d-flex" style="padding: 6px 14px; margin: 0 6px">
                                <i class="bx bx-notepad font-medium-5" style="margin-right: 4px"></i>
                                <span>Draft</span>
                            </button>

                            <button class="btn-outline-warning rounded-lg align-items-center d-flex" style="padding: 6px 14px; margin: 0 6px">
                                <i class="bx bx-archive font-medium-5" style="margin-right: 4px"></i>
                                <span>Arsipkan</span>
                            </button>
                        </div>

                        <div style="margin: 10px 0; padding: 20px">
                            <form id="formAddUpdate">
                                <div id="response-message"></div>
                                <div class="input-group" style="margin-bottom: 10px">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-primary d-flex justify-content-center" style="width: 45px">
                                            <img src="<?php echo base_url(); ?>/app-assets/images/buat_pesan/tujuan-pesan.svg" alt="">
                                        </div>
                                    </div>
                                    <select id="message_receive_id" class="mitra--tujuan select2" name="receiver[]" multiple="multiple" style="width: calc(100% - 45px); padding: 0.47rem 0.8rem">
                                    </select>
                                </div>

                                <div class="input-group" style="margin-bottom: 10px">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-warning d-flex justify-content-center" style="width: 45px">
                                            <img src="<?php echo base_url(); ?>/app-assets/images/buat_pesan/subjek.svg" alt="">
                                        </div>
                                    </div>
                                    <input type="text" placeholder="Subjek" class="form-control" aria-describedby="subjek" id="subject">
                                </div>

                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <!-- Compose mail Quill editor -->
                                            <div class="snow-container border rounded p-50">
                                                <div id="message_content" class="compose-editor" style="border: none; min-height: 200px"></div>
                                                <div class="d-flex justify-content-end">
                                                    <div class="compose-quill-toolbar pb-0" style="border: none">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-lg-6 ml-auto text-right">
                                    <button class="btn btn-success" id="submit" type="submit">
                                        <img class="mr-50" src="<?php echo base_url(); ?>/app-assets/images/buat_pesan/send.svg" alt="">
                                        <span style="font-weight: 700">KIRIM</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(".reset").click(function() {
        $('form').find("input[type=text], textarea").val("");
        location.reload();
    });

    $(document).ready(function() {
        $('.ql-editor').attr("data-placeholder", "Tulis Pesan Anda....");

        var container = document.getElementById('message_content');
        var editor = new Quill(container);
        editor.enable(true)

        $('.select2').select2({
            minimumInputLength: 3,
            allowClear: true,
            placeholder: 'Mitra Tujuan',
            ajax: {
                url: window.location.origin + '/admin/service/message/getPenerima',
                dataType: 'json',
                data: function(params) {
                    return {
                        search: params.term
                    }
                },
                processResults: function(data, page) {
                    return {
                        results: data
                    };
                }
            }
        });

    });

    $('#submit').on('click', (e) => {
        e.preventDefault();

        $('#response-message').html('');
        $('#submit').prop('disabled', true)
        $('#submit').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')

        let message = '';
        let message_content = $('#message_content .ql-editor p').html();
        let subject = $('#subject').val();

        message += subject ? subject + ' <p><br></p>' : ''
        message += message_content == '<br>' ? '' : message_content

        let receive_id = $('#message_receive_id').val()

        $.ajax({
            url: window.location.origin + '/admin/service/message/addMessage',
            method: "POST",
            data: {
                message_receive_id: receive_id,
                message_content: message,
                message_type: 'pesan'
            },
            success: function(response) {
                $('#submit').prop('disabled', false)
                $('#submit').html(
                    `<div class="d-flex align-center"><i class='bx bx-send d-flex align-items-center mr-25'></i> Kirim</div>`
                )

                if (response.status == 200) {
                    $('#response-message').html(
                        `<div class="alert alert-success"> ${response.message} </div>`)
                    location.reload();
                }

                setTimeout(function() {
                    $('#response-messages').html('');
                    $('#response-messages').removeClass();
                }, 2000);

            },
            error: function(err) {
                $('#submit').prop('disabled', false)
                $('#submit').html(
                    `<div class="d-flex align-center"><i class='bx bx-send d-flex align-items-center mr-25 mb-50'></i> Kirim</div>`
                )
                let response = err.responseJSON
                $('#response-message').show()
                if (response.message == "validationError") {
                    let message = '<ul>';
                    for (let key in response.data.validationMessage) {
                        message += `<li>${response.data.validationMessage[key]}</li>`
                    }
                    message += '</ul>'
                    $('#response-message').html(`
                    <div class="alert border-danger alert-dismissible mb-2" role="alert">
                        <div class="d-flex align-items-center">
                            <span class="alert-position">
                                ${message}
                            </span>
                        </div>
                    </div>
                `);
                    setTimeout(function() {
                        $('#response-message').hide('blind', {}, 500)
                    }, 5000);
                } else if (response.message == 'Unauthorized' && response.status == 403) {
                    location.reload();
                }
            }
        });
    });

    $('#draft').on('click', (e) => {
        e.preventDefault();

        $('#response-message').html('');
        $('#draft').prop('disabled', true)

        let message = '';
        let message_content = $('#message_content .ql-editor p').html();
        let subject = $('#subject').val();

        message += subject ? subject + '<br>' : ''
        message += message_content == '<br>' ? '' : message_content

        let receive_id = $('#message_receive_id').val()

        $.ajax({
            url: window.location.origin + '/admin/service/message/addMessage',
            method: "POST",
            data: {
                message_receive_id: receive_id,
                message_content: message,
                message_type: 'draf'
            },
            success: function(response) {
                $('#draft').prop('disabled', false)

                if (response.status == 200) {
                    $('#response-message').html(
                        `<div class="alert alert-success"> ${response.message} </div>`)
                    location.reload();
                }

                setTimeout(function() {
                    $('#response-messages').html('');
                    $('#response-messages').removeClass();
                }, 2000);

            },
            error: function(err) {
                $('#draft').prop('disabled', false)
                let response = err.responseJSON
                $('#response-message').show()
                if (response.message == "validationError") {
                    let message = '<ul>';
                    for (let key in response.data.validationMessage) {
                        message += `<li>${response.data.validationMessage[key]}</li>`
                    }
                    message += '</ul>'
                    $('#response-message').html(`
                    <div class="alert border-danger alert-dismissible mb-2" role="alert">
                        <div class="d-flex align-items-center">
                            <span class="alert-position">
                                ${message}
                            </span>
                        </div>
                    </div>
                `);
                    setTimeout(function() {
                        $('#response-message').hide('blind', {}, 500)
                    }, 5000);
                } else if (response.message == 'Unauthorized' && response.status == 403) {
                    location.reload();
                }
            }
        });
    });
</script>