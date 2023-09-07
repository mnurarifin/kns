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
                        <div id="table-bonus-transfer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    let urlSegments = window.location.pathname.split('/');
        urlSegments = urlSegments[urlSegments.length-1];
    let tf_code = "<?php echo $tf_code ?>";

    $(function() {
        $("#table-bonus-transfer").bind("DOMSubtreeModified", function() {
            if ($("#table-bonus-transfer").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function(){
        let url = '/Service/Transfer/history_transfer_success/'+tf_code;
        let bankOptions = [];
        $.ajax({
            url: '/admin/service/transfer/get_bank_option',
            type: 'GET',
            async: false,
            dataType: 'json',
            success: function(response){
                bankOptions = response;
            }
        });
         $("#table-bonus-transfer").dataTableLib({
            url: url,
            selectID: 'bonus_transfer_id',
            colModel: [
                {display: 'Kode Transfer', name: 'bonus_transfer_code', sortAble: true, export: true},
                {display: 'Tanggal', name: 'bonus_transfer_datetime', sortAble: true, export: true, align:'left'},
                {display: 'Kode Mitra', name: 'bonus_transfer_network_code', sortAble: true, export: true},
                {display: 'Nama', name: 'bonus_transfer_member_name', sortAble: true, export: true},
                {display: 'Total Komisi', name: 'bonus_transfer_total_bonus', sortAble: true, export: true, render: (param)=>{ return 'Rp '+number_format(param,0,'','.');}},
                {display: 'Biaya Admin', name: 'bonus_transfer_adm_charge_value', sortAble: true, export: true, render: (param)=>{ return 'Rp '+number_format(param,0,'','.');}},
                {display: 'Total Komisi Transfer', name: 'bonus_transfer_nett', sortAble: true, export: true, render: (param)=>{ return 'Rp '+number_format(param,0,'','.');}},
                {display: 'Nama Bank', name: 'bonus_transfer_bank_name', sortAble: true, export: true},
                {display: 'Nama Rekening', name: 'bonus_transfer_bank_account_name', sortAble: true, export: true},
                {display: 'Nomor Rekening', name: 'bonus_transfer_bank_account_no', sortAble: true, export: true},
                <?php foreach (json_decode(BIN_CONFIG_BONUS) as $key => $value) {
                    if ($value->active_bonus) { ?> {
                        display: '<?php echo $value->label; ?> (Rp)',
                        name: '<?php echo 'bonus_transfer_bonus_' . $value->name; ?>',
                        sortAble: true,
                        align: 'right',
                        render: (param)=>{ return 'Rp '+number_format(param,0,'','.');}
                    },
                    <?php } ?>
                <?php } ?>
            ],
            search: true,
            searchTitle: 'Pencarian Data Bonus Transfer',
            searchItems:[
                {display: 'Tanggal', name: 'bonus_transfer_datetime', type: 'date'},
                {display: 'Kode Transfer', name: 'bonus_transfer_code', type: 'text'},
                {display: 'Kode Mitra', name: 'bonus_transfer_network_code', type: 'text'},
                {display: 'Nama Mitra', name: 'bonus_transfer_member_name', type: 'text'},
                {display: 'Nama Bank', name: 'bonus_transfer_bank_id', type: 'select',option: bankOptions},
            ],
            sortName: "bonus_transfer_id",
            sortOrder: "DESC",
            tableIsResponsive: true,
            // buttonAction: [
            //     {display: 'Export Excel', icon: 'bx bxs-file', action: 'exportExcel', url: '/Transfer/export_excel_history_success/'+urlSegments},
            // ]
        });
    });
    
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