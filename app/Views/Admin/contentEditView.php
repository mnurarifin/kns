<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a class="btn btn-light-secondary" href="<?= base_url('admin/content/show') ?>" type="reset"><i class="bx bx-left-arrow-alt"></i> Kembali</a>
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
                                        <label>Judul Artikel</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <input type="text" class="form-control" name="content_title" placeholder="Judul Artikel">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Jadikan Menu</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <input type="radio" id="true" name="is_menu" value="true">
                                        <label for="true">Ya</label>
                                        <input type="radio" id="false" name="is_menu" value="false">
                                        <label for="false">Tidak</label>
                                    </div>
                                </div>
                                <div class="show-menu">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Tipe Menu</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <select name="content_menu_type" id="select-menu-type" class="form-control">
                                                <option value="" label="Pilih Tipe Menu"></option>
                                                <option value="parent" label="Parent"></option>
                                                <option value="sub-parent" label="Sub Parent"></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row sub-parent-menu">
                                        <div class="col-md-3">
                                            <label>Pilih Menu</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <select name="content_menu_parent" id="select-menu-parent" class="form-control">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Menu Link</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <input type="text" class="form-control" name="content_menu_link" placeholder="Link Artikel">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Kategori Artikel</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <select name="content_category_id" id="select-category" class="form-control">

                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Gambar Artikel</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <div style="display: flex; align-items:center; justify-content:center; border:1px solid #DFE3E7; padding:10px;">
                                            <img id="product-image" class="img-fluid" style="height: 200px; width:200px;" alt="Blank">
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="upload-files" name="content_image">
                                            <label id="image-label" class="custom-file-label" for="customFile">Tambah
                                                Gambar</label>
                                        </div>
                                        <!-- <input type="file" class="form-control" accept="image/*" name="content_image" placeholder="Gambar Artikel"> -->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Isi Artikel</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <textarea id="editor"></textarea>
                                    </div>
                                </div>
                                <div class="row float-right pb-1">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" id="submitContent" type="submit">Simpan</button>
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

    let content_id = '<?= $content_id ?>';
    let is_menu = '';
    let menu_par_id = '';
    let menu_link = '';
    let category = '';
    var $radios = $('input:radio[name=is_menu]');

    $('#formKonten').hide()
    $(function() {
        $("#formKonten").bind("DOMSubtreeModified", function() {
            if ($("#formKonten").height() > 60) {
                $("#pageLoader").hide();
                $('#formKonten').show()
            }
        });
    });

    $('.descript').on('focus keypress', function(e) {
        var $this = $(this);
        var msgSpan = $('.counter_msg');
        var ml = parseInt($this.attr('maxlength'), 10);
        var length = this.value.length;
        var msg = ml - length + ' / ' + ml;

        msgSpan.html(msg);
    });

    $('.descript').focusout(function(e) {
        var $this = $(this);
        var msgSpan = $('.counter_msg');
        var ml = parseInt($this.attr('maxlength'), 10);
        var length = this.value.length;
        var msg = ml - length + ' / ' + ml;

        msgSpan.html(msg);
    });

    $(document).ready(function() {
        getMenuPublic()
        let contentOption = [];
        $.ajax({
            url: '/admin/service/content/content_category_option',
            type: 'GET',
            async: false,
            dataType: 'json',
            success: function(response) {
                contentOption = response;
            }
        });

        $.ajax({
            url: '/admin/service/content/detailContent/' + content_id,
            type: 'GET',
            async: false,
            dataType: 'json',
            success: function(response) {
                if (response.status == 200) {
                    let {
                        content_body,
                        content_content_category_id,
                        content_image,
                        content_title
                    } = response.data

                    $('#formKonten input[name=content_image]').val();
                    $('#formKonten input[name=content_title]').val(content_title);
                    category = content_content_category_id
                    editor.setData(content_body)
                    document.getElementById("product-image").src = "<?= $imagePath ?>" + content_image;
                    stateContent.selectedOldImage = content_image
                }
            }
        })

        if (is_menu) {
            $radios.filter('[value=true]').prop('checked', true);
            $('.show-menu').show()
            if (menu_par_id > 0) {
                $('.sub-parent-menu').show()
                $(`#formKonten select[name=content_menu_type]`).val('sub-parent');
                $(`#formKonten select[name=content_menu_parent]`).val(menu_par_id);
            } else {
                $('.sub-parent-menu').hide()
                $(`#formKonten select[name=content_menu_type]`).val('parent');
                $(`#formKonten select[name=content_menu_parent]`).val('');
            }
            $(`#formKonten input[name=content_menu_link]`).val(menu_link);
        } else {
            $radios.filter('[value=false]').prop('checked', true);
            $('.show-menu').hide()
            $('.sub-parent-menu').hide()
        }

        getContentCategory()
    });

    $('#resetForm').on('click', function() {
        editor.setData('')
    })

    function getContentCategory() {
        $.ajax({
            url: "<?php echo site_url('admin/service/content/getContentCategory') ?>",
            type: 'GET',
            success: function(res) {
                let html = ''
                if (res.status == 200) {
                    html += `<option value="" label="Pilih Menu"></option>`
                    res.data.forEach((item) => {
                        html += `<option value="${item.content_category_id}" label="${item.content_category_name}"></option>`
                    });
                    $('#select-category').html(html);
                }
            }
        }).then(() => {
            $(`#formKonten select[name=content_category_id]`).val(category);
        });
    }

    function getMenuPublic() {
        $.ajax({
            url: "<?php echo site_url('admin/service/content/getMenuPublic') ?>",
            type: 'GET',
            success: function(res) {
                let html = ''
                if (res.status == 200) {
                    html += `<option value="" label="Pilih Menu"></option>`
                    res.data.forEach((item) => {
                        html += `<option value="${item.menu_id}" label="${item.menu_title}"></option>`
                    });
                    $('#select-menu-parent').html(html);
                }
            }
        });
    }

    var stateContent = {
        baseUrl: window.location.origin,
        updateUrl: '/admin/service/content/actUpdateContent',
        selectedID: content_id,
        selectedOldImage: ''
    }

    $('#submitContent').on('click', (e) => {
        let data = editor.getData();
        e.preventDefault();
        $('#response-message').html('');
        $('#submitContent').prop('disabled', true)
        $('#submitContent').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')

        let myForm = document.getElementById('formKonten');
        let formData = new FormData(myForm);
        let url = stateContent.baseUrl + stateContent.updateUrl;
        formData.append('id', stateContent.selectedID);
        formData.append('old_image', stateContent.selectedOldImage);
        formData.append('content_body', data);

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
                    window.location.href = window.location.origin + '/admin/content/show'
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

    $("input:radio[name=is_menu]").on("change", function() {
        if ($(this).is(":checked")) {
            if ($(this).val() == 'true') {
                $('.show-menu').show()
            } else {
                $('.show-menu').hide()
            }
        } else {
            $('.show-menu').hide()
        }
    }).change();

    $("select[name=content_menu_type]").on("change", function() {
        if ($(this).val() == 'sub-parent') {
            $('.sub-parent-menu').show()
        } else {
            $('.sub-parent-menu').hide()
        }
    }).change();

    $('#upload-files').change(function() {
        let files = document.getElementById("upload-files").files[0];

        var reader = new FileReader();
        let temp_url = reader.readAsDataURL(files);

        reader.onload = function(e) {
            $('#product-image')
                .attr('src', e.target.result)
        };
    })

    document.addEventListener("DOMContentLoaded", function() {

        let dialogState = ''

        $('body').on('DOMNodeInserted', '.cke_dialog_body', function() {
            if (dialogState == 'image') {
                $($(this).find('.cke_dialog_tab')[0]).css('display', 'none')
                $($(this).find('.cke_dialog_tab')[1]).css('display', 'none')
                $($(this).find('.cke_dialog_tab')[2]).css('display', 'inline-block')
                $($(this).find('.cke_dialog_ui_vbox.cke_dialog_page_contents')[0]).css('display',
                    'none')
                $($(this).find('.cke_dialog_ui_vbox.cke_dialog_page_contents')[1]).css('display',
                    'none')
                $($(this).find('.cke_dialog_ui_vbox.cke_dialog_page_contents')[2]).css('display',
                    'unset')
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
                url: "<?php echo site_url('admin/service/content/uploadContentImage') ?>",
                type: 'POST',
                data: upload_data,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('.cke_dialog_container.cke_editor_editor_dialog').css('display',
                        'none');
                    $('.cke_dialog_background_cover').css('display', 'none');
                    editor.insertHtml(`
                <img style="height:360px; width:400px" src="<?= getenv('UPLOAD_URL') . URL_IMG_CONTENT ?>` +
                        response.data.results.content_image + `"/>
                `)
                },
                error(xhr, status, error) {

                },
                complete(xhr, status) {

                }
            })
        })
    })
</script>