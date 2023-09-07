<style>
    .table {
        position: relative;
    }

    .table-responsive {
        max-height: calc(100vh - 0px);
    }

    .table th,
    .table td {
        padding: 0.4rem 1rem;
        white-space: nowrap;
    }

    th {
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        background: white;
    }
</style>
<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?php echo $title; ?> <span id="title"></span></h4>
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
                        <div id="table-history-sales-serial"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="modal-detail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-radius: 7px 7px 0 0; border: 1px solid #ccc; background-color: #f5f5f5;">
                <h4 class="modal-title" id="modal-label">Detail Penjualan Serial (<strong><span id="detail-serial-title"></span></strong>)</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="panel panel-default mb-2">
                    <div class="panel-body">
                        <h5 class="modal-title" id="modal-label-user">Pengguna (<strong><span id="detail-serial-user"></span></strong>)</h5>
                        <table class="data table table-bordered table-hover">
                            <thead>
                                <tr style="font-weight: bold; background-color: #2d2626; color: #fff;">
                                    <td style="width:20%; text-align:center;">Kode Mitra</td>
                                    <td style="width:20%; text-align:left;">Nama Mitra</td>
                                    <td style="width:40%; text-align:left;">Tanggal</td>
                                    <td style="width:20%; text-align:left;">Type</td>
                                </tr>
                            </thead>
                            <tbody id="tbody-detail-sales">
                            </tbody>
                        </table>
                    </div>
                </div>
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
<script >
    $(function() {
        $("#table-history-sales-serial").bind("DOMSubtreeModified", function() {
            if ($("#table-history-sales-serial").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        $("#table-history-sales-serial").dataTableLib({
            url: window.location.origin + '/admin/service/serial/getDataSalesSerial/<?=$tipeSerial;?>',
            selectID : 'serial_id', 
            colModel : [
            {display: 'Serial', name: 'serial_id', sortAble: false, align: 'center',  export: true},
            {display: 'Tgl Dijual', name: 'buy_datetime', sortAble: false, align: 'left',  export: true},
            {display: 'Digunakan', name: 'serial_is_used', sortAble: false, align: 'center', render: (params) => { return params == '1' ? '<i class="bx bxs-check-circle success" title="Ya" data-toggle="tooltip"></i>' : '<i class="bx bxs-x-circle danger" title="Belum" data-toggle="tooltip"></i>'}, export: true},
            {display: 'Tgl Digunakan', name: 'used_datetime', sortAble: false, align: 'left',  export: true},
            {display: 'Detail', name: '', sortAble: false, align: 'center', action:{function:'detailSerial', icon: 'bx bx-book info', class: 'warning'}},
            ],
            options: {
                limit: [10,15,20],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Serial',
            searchItems:[
                {display: 'Serial', name: 'serial_id', type:'text'},
                {display: 'Tgl Dijual', name: 'buy_datetime', type:'date'},
                {display: 'Digunakan', name: 'serial_is_used', type:'select', option: [{title: 'YA', value: '1'}, {title: 'TIDAK', value: '0'},
                ]},
            ],
            sortName: "buy_datetime",
            sortOrder: "asc",
            tableIsResponsive: true,
            buttonAction: []
        })
    });

    function detailSerial(data) {
        $.ajax({
            url: window.location.origin + '/admin/service/serial/getDetailSalesSerial/<?=$tipeSerial;?>',
            method: 'GET',
            dataType: 'json',
            data: {serial_id:data.serial_id},
            success: function(res) {
                if (res.data.length > 0) {
                    let html = '';
                    $.map(res.data, function(val, index) {
                        var str = val.type;
                        if(str == 'repeatorder') {
                            str = 'ro';
                        }
                        var res = str.toUpperCase();
                        html += `
                            <tr>
                                <td style="text-align:center">${val.member_code}</td>
                                <td style="text-align:left">${val.member_name}</td>
                                <td style="text-align:left">${val.tanggal}</td>
                                <td style="text-align:left">${res}</td>
                            </tr>
                        `;
                    });
                    if (data.serial_is_used == 1) {
                        $('#detail-serial-user').text(data.user_code+' | '+data.user_name);
                    } else {
                        $('#detail-serial-user').text('-');
                    }
                    $('#detail-serial-title').text(data.serial_id);
                    $('#tbody-detail-sales').html(html);
                    $('#modal-detail').modal('show');
                }
            }
        });
    }

</script>