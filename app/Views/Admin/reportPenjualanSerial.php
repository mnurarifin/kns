

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
                <h5 class="modal-title" id="exampleModalCenterTitle">Detail Penjualan Serial</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nama Admin</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <p id="admin-name"></p>
                            </div>
                            <div class="col-md-4">
                                <label>Tanggal</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <p id="date-time"></p>
                            </div>
                            <div class="col-md-4">
                                <label>Kode Member</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <p id="code-member"></p>
                            </div>
                            <div class="col-md-4">
                                <label>Jumlah Serial</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <div id="jumlah-serial"></div>
                            </div>
                            <div class="col-md-4">
                                <label>Serial</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <div id="detail-serial"></div>
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
            url: window.location.origin+'/admin/service/report/getSalesSerialReport/' + tipeSerial,
            selectID: 'country_id',
            colModel: [
            {display: 'Tanggal', name: 'serial_buy_datetime', sortAble: false, align:'left', render: (params) => {
                return toTanggal(params)
            }},
            {display: 'Kode member', name: 'member_ref_network_code', sortAble: false, align:'center'},
            {display: 'Nama Member', name: 'member_name', sortAble: false, align:'right'},
            {display: 'Administrator', name: 'administrator_name', sortAble: false},
            {display: 'Detail', name: '', sortAble: false, align: 'center', action: { function: 'detailReport', icon:'bx bx-book info', class:'info'}},
            ],
            sortName: "serial_buy_datetime",
            sortOrder: "desc",
            tableIsResponsive: true,
            search: true,
            searchTitle: 'Pencarian',
            searchItems:[
            {display: 'Tanggal', name: 'serial_buy_datetime', type:'date'},
            {display: 'Kode Member', name: 'member_ref_network_code', type:'text'},
            ],
            select: true,
            multiSelect: true,
        });
    });

    function detailReport(report){
        console.log(report);
        
        $.ajax({
            url: "<?php echo site_url('admin/service/report/getDetailSerialReport/') . $tipeSerial ?>",
            type: 'GET',
            data: {
                date: report.serial_buy_datetime,
                member_id: report.serial_buyer_member_id
            },
            success: function(res) {
                console.log(res)
                let strSerial = "";
                $.each(res.data, function(key, val){
                    strSerial += `<li>${val.serial_id} ( ${val.serial_pin} )</li>`;
                })
                $('#detailReport').modal('show');
                $('#detail-serial').html(strSerial);
                $('#admin-name').text(report.administrator_name);
                $('#code-member').text(report.member_ref_network_code);
                $('#jumlah-serial').text(res.data.length);
                $('#date-time').text(toTanggal(report.serial_buy_datetime));
            }
        });
    }
</script>