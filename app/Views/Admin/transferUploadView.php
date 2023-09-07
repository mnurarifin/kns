<div id="modal-conf" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Konfirmasi Transfer Komisi</h4>
            </div>
            <div class="modal-body">
                <b><p>Apakah anda yakin untuk mengunggah file transfer komisi?</p></b>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">TIDAK</button>
                <button type="button" id="btn-upload" class="btn btn-success" data-dismiss="modal">YA</button>
            </div>
        </div>

    </div>
</div>
<div id="modal-response" class="modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id="content-response">
            <div class="modal-body">
                <span id="span-msg"></span>
            </div>
        </div>
    </div>
</div>
<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Upload Transfer Komisi <span id="title"></span></h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div style="background-color:#2ba0c3; max-width: 70%; margin-bottom: 50px; padding: 15px; color:#fff;">
                            <li>Silakan export file excel dari menu Approval Transfer (Harian / Mingguan)</li>
                            <li>Ubah status transfer komisi sesuai dengan yang sudah ditransfer ataupun belum</li>
                            <li>Silahkan klik <b>Pilih File</b> dan pilih file Excel dari Perangkat Anda yang ingin di Upload</li>
                            <li>Anda bisa unggah file dengan klik <b>Upload</b></li>
                        </div>
                        <div style="max-width: 50%;">
                            <input class="btn-default" type="file" name="file_excel" id="file-excel"><br>
                            <input class="btn-success" type="button" name="btn" id="submit-excel" value="UPLOAD" style="margin-top: 20px; height: 40px; width: 95px; border-radius: 4px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $("#submit-excel").on('click', function (e) {
        $('#modal-conf').modal('show');
        $('#btn-upload').on('click', function(){
            upload_data = new FormData()
            upload_data.append('file_excel', $('#file-excel')[0].files[0]);
            $.ajax({
                url: '/transfer/upload_excel_transfer_komisi',
                type: 'POST',
                data: upload_data,
                contentType: false,
                processData: false,
                async: false,
                dataType: 'json',
                success: function(response){
                    if (response.message != 'ERROR') {
                        $('#modal-conf').modal('hide');
                        $('#span-msg').html('Upload file transfer komisi berhasil! Approved : '+response.data.approved+ ' Rejected : '+response.data.rejected+' Pending : '+response.data.pending);
                        $('#span-msg').css("color","#FFF");
                        $('#content-response').css("background-color","#1CC689");
                        $('#modal-response').modal('show');
                        setTimeout(function() {
                            $('#modal-response').modal('hide');
                            location.reload();
                        }, 5000);
                    } else {
                        let msg = "Upload file transfer komisi gagal. "+response.data.msg;
                        $('#modal-conf').modal('hide');
                        $('#span-msg').css("color","#FFF");
                        $('#span-msg').html(msg);
                        $('#content-response').css("background-color","#FF5B5C");
                        $('#modal-response').modal('show');
                        setTimeout(function() {
                            $('#modal-response').modal('hide');
                            location.reload();
                        }, 5000);
                    }
                }
            });
        });
        
    });
</script>