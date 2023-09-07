<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Histori Transfer Komisi</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div id="pageLoader">
                            <div class="text-center text-muted d-flex align-center justify-content-center bg-grey-light p-2">
                                <div class="spinner-border text-info spinner-border-sm" role="status" style="margin-right: 8px;margin-top: 2px;">
                                    <span class="sr-only">&nbsp;</span>
                                </div>
                                <span>Sedang memuat informasi, mohon tunggu beberapa saat...</span>
                            </div>
                        </div>
                        <div id="table-bonus-transfer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(function() {
        $("#table-bonus-transfer").bind("DOMSubtreeModified", function() {
            if ($("#table-bonus-transfer").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });
    $(document).ready(function(){
        $("#table-bonus-transfer").dataTableLib({
            url: '/admin/service/transfer/get_data_download',
            selectID: 'tanggal',
            colModel: [
                {display: 'Tanggal', name: 'tanggal', sortAble: true, export: true},
                {display: 'Pending', name: 'pending', sortAble: true, export: true},
                {display: 'Approved', name: 'success', sortAble: true, export: true},
                {display: 'Rejected', name: 'rejected', sortAble: true, export: true},
                {display: 'Total Transfer Sukses', name: 'success_transfer', sortAble: true, export: true, render: (param)=>{ return 'Rp '+number_format(param,0,'','.');}},
                {display: 'Download Pending', name: 'download_pending', sortAble: false, export: false, action:{function:'downloadPending', icon:'bx bx-book info'}},
                {display: 'Download All', name: 'download_all', sortAble: false, export: false, action:{function:'downloadAll', icon:'bx bx-book info'}},
                {display: 'Detail', name: 'detail', sortAble: false, export: false, action:{function:'detail', icon:'bx bx-book info'}},
            ],
            search: false,
            searchTitle: 'Pencarian Data Download Data Komisi Transfer',
            searchItems:[
                // {display: 'Tanggal', name: 'tanggal', type: 'date'},
            ],
            sortName: "tanggal",
            sortOrder: "DESC",
            tableIsResponsive: true,
            select: false,
            multiSelect: false,
            buttonAction: []
        });
    });

    function downloadPending(komisi) {
        $.ajax({
            url: "<?php echo site_url('admin/service/transfer/download/pending') ?>",
            type: 'POST',
            dataType: 'JSON',
            data: {
                tanggal: komisi.tanggal,
            },
            success: function(res) {
                window.open(`<?php echo base_url().'/'?>`+res.data.url);
            },
            error: function(res) {
                Swal.fire({
                    title: 'Error',
                    text: "Gagal Download",
                    icon: 'warning',
                    showCancelButton: false,

                    confirmButtonText: 'Ok'
                })
                // window.location = JSON.parse(res.responseText);
            }
        });
    }

    function downloadAll(komisi) {
        $.ajax({
            url: "<?php echo site_url('admin/service/transfer/download/all') ?>",
            type: 'POST',
            dataType: 'JSON',
            data: {
                tanggal: komisi.tanggal,
            },
            success: function(res) {
                window.open(`<?php echo base_url().'/'?>`+res.data.url);
            },
            error: function(res) {
                window.location = JSON.parse(res.responseText);
            }
        });
    }

    function detail(komisi) {
        window.open('/transfer/history/'+komisi.tanggal);
    }

    function number_format(number, decimals, dec_point, thousands_point) {
        if (number == null || !isFinite(number)) {
            throw new TypeError("number is not valid");
        }

        if (!decimals) {
            var len = number.toString().split('.').length;
            decimals = len > 1 ? len : 0;
        }

        if (!dec_point) {
            dec_point = '.';
        }

        if (!thousands_point) {
            thousands_point = ',';
        }

        number = parseFloat(number).toFixed(decimals);

        number = number.replace(".", dec_point);

        var splitNum = number.split(dec_point);
        splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
        number = splitNum.join(dec_point);

        return number;
    }
</script>