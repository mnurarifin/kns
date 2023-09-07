

<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?=isset($title) ? $title : ''?></h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div id="table-country"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="detailReport" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Detail History Serial</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Pemilik</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <p id="serial-owner"></p>
                            </div>
                            <div class="col-md-4">
                                <label>Log Serial</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <div id="log-serial"></div>
                            </div>
                            <div class="col-md-4">
                                <label>Sudah Digunakan</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <p id="is-used"></p>
                            </div>
                            <div class="col-md-4 used-by">
                                <label>Digunakan Oleh</label>
                            </div>
                            <div class="col-md-8 form-group used-by">
                                <p id="used-by"></p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Tutup</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">  
    var tipeSerial = "<?php echo $tipeSerial; ?>";
    $(document).ready(function() {
        $("#table-country").dataTableLib({
            url: window.location.origin+'/admin/service/report/getHistorySerialReport/' + tipeSerial,
            selectID: 'country_id',
            colModel: [
                // {display: 'Nama member', name: 'country_name', sortAble: false},
                {display: 'Kode Serial', name: 'serial_transfer_log_serial_id', sortAble: false, align:'left'},
                {display: 'Kode member', name: 'member_ref_network_code', sortAble: false, align:'center'},
                {display: 'Pemilik Serial', name: 'member_name', sortAble: false, align:'right'},
                {display: 'Detail', name: '', sortAble: false, align: 'center', action: { function: 'detailReport', icon:'bx bx-book info', class:'info'}},
                ],
                sortName: "serial_transfer_log_id",
                sortOrder: "DESC",
                tableIsResponsive: true,
                search: true,
                searchTitle: 'Pencarian',
                searchItems:[
                {display: 'Tanggal', name: 'serial_transfer_log_serial_buy_datetime', type:'date'},
                {display: 'Kode Member', name: 'member_ref_network_code', type:'text'},
                ],
                select: true,
                multiSelect: true,
            });
    });

    function detailReport(report){
        console.log(report);
        
        $.ajax({
            url: "<?php echo site_url('admin/service/report/detailHistorySerialReport/') . $tipeSerial ?>",
            type: 'GET',
            data: {
                serial: report.serial_transfer_log_serial_id
            },
            success: function(res) {
                $('.modal-title').html(`Detail History Serial ${report.serial_transfer_log_serial_id}`)
                console.log(res)
                let serial = res.data.serial
                let strSerial = "";
                $.each(res.data.log, function(key, val){
                    strSerial += `<li>${val.member_ref_network_code} ( ${val.serial_transfer_log_serial_buy_datetime} )</li>`;
                })
                $('#detailReport').modal('show');
                $('#log-serial').html(strSerial);
                $('#serial-owner').text(`${report.member_name} (${report.member_ref_network_code})`);
                $('#is-used').html(serial.serial_is_used == 1 ? '<i class="success ficon bx bx-check-circle">' : '<i class="danger ficon bx bx-x-circle">');
                $('.used-by').hide()
                if (serial.serial_is_used == 1) {
                    $('.used-by').show()
                    $('#used-by').text(serial.member_name);
                }
            }
        });
    }
</script>