<style>
    .modal-body{
        overflow-y: scroll;
    }
</style>

<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Rekap Komisi Harian</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">         
                        <div class="row" style="margin-bottom: 20px; font-weight:bold;">
                            <div class="col-md-4">
                                <caption>Total Komisi : <span id="tb"></span></caption>
                            </div>
                            <div class="col-md-4">
                                <caption>Total Ditransfer : <span id="tb-transfer"></span></caption>
                            </div>
                            <div class="col-md-4">
                                <caption>Total Biaya Admin : <span id="tb-admin"></span></caption>
                            </div>
                        </div>
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
        let urlSegments = window.location.pathname.split('/');
        urlSegments = urlSegments[urlSegments.length-1];
        if(urlSegments=='daily'){
            $("#title").text('Harian');
        }else if(urlSegments=='weekly'){
            $("#title").text('Mingguan');
        }else if(urlSegments=='monthly'){
            $("#title").text("Bulanan");
        }
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
            url: '/admin/service/transfer/bonus_transfer_list_daily',
            selectID: 'bonus_network_id',
            colModel: [
                {display: 'Kode Transfer', name: 'bonus_transfer_code', sortAble: true, export: true},
                {display: 'Status', name: 'bonus_transfer_status', sortAble: true, export: true},
                {display: 'Kode Mitra', name: 'bonus_transfer_network_code', sortAble: true, export: true},
                {display: 'Nama', name: 'bonus_transfer_member_name', sortAble: true, export: true},
                {display: 'Nama Bank', name: 'bonus_transfer_bank_name', sortAble: true, export: true},
                {display: 'Nama Akun Bank', name: 'bonus_transfer_bank_account_name', sortAble: true, export: true},
                {display: 'Nomor Akun Bank', name: 'bonus_transfer_bank_account_no', sortAble: true, export: true},
                {display: 'No Handphone', name: 'bonus_transfer_mobilephone', sortAble: true, export: true},
                {display: 'Tanggal', name: 'bonus_transfer_datetime', sortAble: true, export: true},
                {display: 'Komisi Sponsor (Rp)',name: 'bonus_transfer_bonus_sponsor',export: true, sortAble: true,align: 'right',render: (param)=>{ return 'Rp '+number_format(param,0,'','.');}
                },
                {display: 'Komisi RO (Rp)',name: 'bonus_transfer_bonus_ro',export: true, sortAble: true,align: 'right',render: (param)=>{ return 'Rp '+number_format(param,0,'','.');}
                },
                {display: 'Biaya Admin', name: 'bonus_transfer_adm_charge_value', align:'right', sortAble: true, export: true, render: (param)=>{ return 'Rp '+number_format(param,0,'','.');}},
                {display: 'Total Komisi', name: 'bonus_transfer_total_bonus', align:'right', sortAble: true, export: true, render: (param)=>{ return 'Rp '+number_format(param,0,'','.');}},
                {display: 'Total Komisi Transfer', name: 'bonus_transfer_nett', align:'right', sortAble: true, export: true, render: (param)=>{ return 'Rp '+number_format(param,0,'','.');}},
                
            ],
            search: true,
            searchTitle: 'Pencarian Data Komisi Transfer',
            searchItems:[
                // {display: 'Tanggal', name: 'bonus_transfer_datetime', type: 'date'},
                {display: 'Kode Transfer', name: 'bonus_transfer_code', type: 'text'},
                {display: 'Kode Mitra', name: 'bonus_transfer_network_code', type: 'text'},
                {display: 'Nama', name: 'bonus_transfer_member_name', type: 'text'},
                {display: 'Nama Bank', name: 'bonus_transfer_bank_id', type: 'select',option: bankOptions},
                {display: 'Nama Akun Bank', name: 'bonus_transfer_bank_account_name', type: 'text'},
                {display: 'Nomor Akun Bank', name: 'bonus_transfer_bank_account_no', type: 'text'},
                {display: 'No Handphone', name: 'bonus_transfer_mobilephone', type: 'text'},

            ],
            sortName: "bonus_transfer_id",
            sortOrder: "ASC",
            tableIsResponsive: true,
            select: false,
            multiSelect: false,
            buttonAction: [
                {display: 'Export Excel', icon: 'bx bxs-file',style: 'info', action: 'exportExcel', url: window.location.origin+"/transfer/excel_approval_sponsor_ro"},
            ]
        });
        ajaxCallback({
            url: '/admin/service/transfer/bonus_total_daily',
            method: 'GET',
            success: (response) => {
                let totalBonus = response.data.paid;
                $("#tb").text(`Rp ${number_format(response.data.paid,0,'','.')}`);
                $("#tb-transfer").text(`Rp ${number_format(response.data.nett_paid,0,'','.')}`);
                $("#tb-admin").text(`Rp ${number_format(response.data.admin,0,'','.')}`);
            }
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