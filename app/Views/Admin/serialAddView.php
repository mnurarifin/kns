<style>
    .alert-position {
        transform: translateY(5px);
    }
</style>
<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?=isset($title) ? $title : ''?></h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard align-items-center">
                        <p class="card-text"></p>
                        <div id="response-messages"></div>
                        <form class="form form-horizontal" id="formCreateSerial">
                            <div class="col-12 d-flex justify-content-center">
                                <div class="d-inline-flex align-items-center mb-1 mr-1">
                                    <div class="input-group input-group-mg bootstrap-touchspin">
                                        <span class="input-group-btn input-group-prepend bootstrap-touchspin-injected"><button class="btn btn-primary bootstrap-touchspin-down" type="button">-</button></span>
                                        <input type="number" name="jumlah" class="touchspin form-control">
                                        <span class="input-group-btn input-group-append bootstrap-touchspin-injected"><button class="btn btn-primary bootstrap-touchspin-up" type="button">+</button></span>
                                    </div>
                                </div>
                                <fieldset class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="inputGroupSelect01">Tipe</label>
                                        </div>
                                        <select name="netType" class="form-control">
                                            <option value="sys">Registrasi</option>
                                            <option value="bin">Binary</option>
                                            <option value="uni">Unilevel</option>
                                        </select>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-12 d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary mr-1 mb-1">Simpan</button>
                                <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
$('#formCreateSerial').on('submit', (e) => {
    e.preventDefault();
    $('#response-messages').html('');
    $('#formCreateSerial button[type=submit]').prop('disabled', true)
    $('#formCreateSerial button[type=submit]').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memproses...')

    let formData = new FormData(e.target);

    $.ajax({
        url: window.location.origin + '/admin/service/serial/createSerial',
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response){
            $('#formCreateSerial button[type=submit]').prop('disabled', false)
            $('#formCreateSerial button[type=submit]').html('Simpan')
            
            if (response.status == 200) {
                $('#response-messages').html(`<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-like"></i>
                                                <span>
                                                    ${response.data.message}
                                                </span>
                                            </div>`);
                $('#response-messages').addClass('alert alert-success alert-dismissible mb-2');
            } else {
                $('#response-messages').html(response.data.message);
                $('#response-messages').addClass('alert alert-danger');
            }
        },    
        error: function(err){
            $('#formCreateSerial button[type=submit]').prop('disabled', false)
            $('#formCreateSerial button[type=submit]').html('Simpan')
            let response = err.responseJSON
            $('#response-message').show()
            if(response.message == "validationError"){
                let message = '<ul>';
                for(let key in response.data.validationMessage){
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
            }else if(response.message == 'Unauthorized' && response.status == 403){
                location.reload();
            }
        }
    });
});
</script>