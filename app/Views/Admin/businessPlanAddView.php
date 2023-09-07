<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a class="btn btn-light-secondary" href="<?= base_url('content/business_plan') ?>" type="reset"><i class="bx bx-left-arrow-alt"></i> Kembali</a>
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
                        <div id="response-message"></div>
                        <form class="form form-horizontal" id="formKonten">
                            <div id="response-error-content"></div>
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Judul</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <input type="text" class="form-control" name="content_title" placeholder="Judul">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Kategori</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <select name="content_category_id" id="select-category" class="form-control">

                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Isi</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <textarea id="editor"></textarea>
                                    </div>
                                </div>
                                <div class="row float-right pb-1">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" id="submitContent" type="submit">Simpan</button>
                                        <button class="btn btn-light-secondary" id="resetForm" type="reset">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo base_url(); ?>/app-assets/vendors/ckeditor/ckeditor.js"></script>

<script type="text/javascript">
    var editor = CKEDITOR.replace('editor', {});

    document.addEventListener("DOMContentLoaded", function() {

        let dialogState = ''

        $('body').on('DOMNodeInserted', '.cke_dialog_body', function() {
            if (dialogState == 'image') {
                $($(this).find('.cke_dialog_tab')[0]).css('display', 'none')
                $($(this).find('.cke_dialog_tab')[1]).css('display', 'none')
                $($(this).find('.cke_dialog_tab')[2]).css('display', 'inline-block')
                $($(this).find('.cke_dialog_ui_vbox.cke_dialog_page_contents')[0]).css('display', 'none')
                $($(this).find('.cke_dialog_ui_vbox.cke_dialog_page_contents')[1]).css('display', 'none')
                $($(this).find('.cke_dialog_ui_vbox.cke_dialog_page_contents')[2]).css('display', 'unset')
            }
        });

        $('body').on('click', '.cke_button__image', function() {
            dialogState = 'image'
            $('.cke_dialog_container.cke_editor_editor_dialog').css('z-index', '10010')
            $('.cke_dialog_background_cover').css('display', '')
        })

        $('body').on('click', '.cke_dialog_close_button', function() {
            dialogState = ''
            $('.cke_dialog_background_cover').css('display', 'none')
        })

        $('body').on('click', '.cke_dialog_ui_fileButton', function() {
            var iframe = $('iframe.cke_dialog_ui_input_file')
            upload_data = new FormData()
            upload_data.append('image', $('form>input', iframe.contents())[0].files[0])
            $.ajax({
                url: "<?php echo site_url('service/content/uploadContentImage') ?>",
                type: 'POST',
                data: upload_data,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('.cke_dialog_container.cke_editor_editor_dialog').css('display', 'none');
                    $('.cke_dialog_background_cover').css('display', 'none');
                    editor.insertHtml(`
        <img style="height:360px; width:400px" src="<?= getenv('UPLOAD_URL') . URL_IMAGE_CONTENT ?>` + response.data.results.content_image + `"/>
        `)
                },
                error(xhr, status, error) {

                },
                complete(xhr, status) {

                }
            })
        })
    })

    $('#formKonten').hide()
    $(function() {
        $("#formKonten").bind("DOMSubtreeModified", function() {
            if ($("#formKonten").height() > 60) {
                $("#pageLoader").hide();
                $('#formKonten').show()
            }
        });
    });

    $(document).ready(function() {
        getContentCategory()
        let contentOption = [];
        $.ajax({
            url: '/service/content/content_category_option',
            type: 'GET',
            async: false,
            dataType: 'json',
            success: function(response) {
                contentOption = response;
            }
        });
    });

    $('#resetForm').on('click', function() {
        editor.setData('')
    })

    var action = 'add';
    var stateContent = {
        baseUrl: window.location.origin,
        addUrl: '/service/content/actAddBusinessPlan',
    }

    function getContentCategory() {
        $.ajax({
            url: "<?php echo site_url('service/content/getContentCategory') ?>",
            type: 'GET',
            success: function(res) {
                let html = ''
                if (res.status == 200) {
                    html += `<option value="" label="Pilih Kategori"></option>`
                    res.data.forEach((item) => {
                        if (item.content_category_is_business_plan == 1) {
                            html += `<option value="${item.content_category_id}" label="${item.content_category_name}"></option>`
                        }
                    });
                    $('#select-category').html(html);
                }
            }
        });
    }

    $('#submitContent').on('click', (e) => {
        let data = editor.getData();
        e.preventDefault();
        $('#response-message').html('');
        $('#submitContent').prop('disabled', true)
        $('#submitContent').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')

        let myForm = document.getElementById('formKonten');
        let formData = new FormData(myForm);
        let url = '';
        formData.append('content_body', data);
        if (action == 'add') {
            url = stateContent.baseUrl + stateContent.addUrl
        }

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#submitContent').prop('disabled', false);
                $('#submitContent').html('Simpan');
                if (response.message == 'OK') {
                    $('#response-message').html(response.data);
                    $('#response-message').addClass('alert alert-success');
                } else {
                    $('#response-error-content').html(response.data.message);
                    $('#response-error-content').addClass('alert alert-danger');
                }
                setTimeout(function() {
                    $('#response-message').html('');
                    $('#response-message').removeClass();
                    $('#response-error-content').html('');
                    $('#response-error-content').removeClass();
                    window.location.href = window.location.origin + '/content/business_plan'
                }, 2000);

            },
            error: function(err) {
                $('#submitContent').prop('disabled', false)
                $('#submitContent').html('Simpan')
                let response = err.responseJSON
                $('#response-error-content').show()
                if (response.message == "validationError") {
                    let message = '<ul>';
                    for (let key in response.data.validationMessage) {
                        message += `<li>${response.data.validationMessage[key]}</li>`
                    }
                    message += '</ul>'
                    $('#response-error-content').html(`
                        <div class="alert border-danger alert-dismissible mb-2" role="alert">
                        <div class="d-flex align-items-center">
                        <span class="alert-position">
                        ${message}
                        </span>
                        </div>
                        </div>
                        `);
                    setTimeout(function() {
                        $('#response-error-content').hide('blind', {}, 500)
                    }, 5000);
                } else if (response.message == 'Unauthorized' || response.status == 403) {
                    location.reload();
                } else {
                    $('#response-error-content').html(response.message);
                    $('#response-error-content').addClass('alert alert-danger');
                    setTimeout(function() {
                        $('#response-message').html('');
                        $('#response-message').removeClass();
                        $('#response-error-content').html('');
                        $('#response-error-content').removeClass();
                    }, 2000);
                }
            }
        });
    })
</script>