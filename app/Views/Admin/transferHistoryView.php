<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">History Approval Transfer Komisi <span id="title"></span></h4>
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
<div id="modalDetailTransfer" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Transfer</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="detail-transfer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-dismiss="modal">Tutup</button>
            </div>
        </div>

    </div>
</div>
<script>
    let urlSegments = window.location.pathname.split('/');
    urlSegments = urlSegments[urlSegments.length - 1];

    $(function() {
        $("#table-bonus-transfer").bind("DOMSubtreeModified", function() {
            if ($("#table-bonus-transfer").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        if (!urlSegments) urlSegments = 'daily';
        if (urlSegments == 'daily') {
            $("#title").text('Harian');
        } else if (urlSegments == 'weekly') {
            $("#title").text('Mingguan');
        } else if (urlSegments == 'monthly') {
            $("#title").text("Bulanan");
        }
        let url = '/ApprovalTransferBonusService/historyBonusTransferList/' + urlSegments;
        let bankOptions = [];
        $.ajax({
            url: '/ApprovalTransferBonusService/getBank',
            type: 'GET',
            async: false,
            dataType: 'json',
            success: function(response) {
                let data = response.data;
                for (let i = 0; i < data.length; i++) {
                    let row = {};
                    row = {
                        title: data[i].bank_name,
                        value: data[i].bank_id
                    };
                    bankOptions[i] = row;
                }
            }
        });
        $("#table-bonus-transfer").dataTableLib({
            url: url,
            selectID: 'bonus_transfer_status_update_datetime',
            colModel: [{
                    display: 'Nama Bank',
                    name: 'bonus_transfer_bank_name',
                    sortAble: true,
                    export: true
                },
                {
                    display: 'Kategori',
                    name: 'bonus_transfer_category',
                    sortAble: true,
                    export: true,
                    render: (param) => {
                        if (param == 'daily') {
                            return 'Harian'
                        } else if (param == 'weekly') {
                            return 'Mingguan'
                        } else if (param == 'monthly') {
                            return 'Bulanan'
                        }
                    }
                },
                {
                    display: 'Waktu',
                    name: 'bonus_transfer_status_update_datetime',
                    export: true,
                    render: (param) => {
                        return param;
                    }
                },
                {
                    display: 'Detail',
                    name: 'detail',
                    sortAble: false,
                    export: false,
                    action: {
                        function: 'detailMenu',
                        icon: 'bx bx-table',
                        class: 'warning'
                    },
                    align: 'center'
                }
            ],
            search: true,
            searchTitle: 'Pencarian Data Bonus Transfer',
            searchItems: [{
                display: 'Nama Bank',
                name: 'bonus_transfer_bank_id',
                type: 'select',
                option: bankOptions
            }, ],
            sortName: "bonus_transfer_status_update_datetime",
            sortOrder: "asc",
            tableIsResponsive: true,
            buttonAction: [{
                display: 'Export Excel',
                icon: 'bx bxs-file',
                style: 'btn-success',
                action: 'exportExcel',
                url: '/approval-transfer-bonus/exportHistoryTransferBonus/' + urlSegments
            }, ]
        });
    });

    function detailMenu(data) {
        $("#detail-transfer").html('');
        $("#modalDetailTransfer").modal('show');
        let dateTime = data.bonus_transfer_status_update_datetime;
        let bankID = data.bonus_transfer_bank_id;
        ajaxCallback({
            url: '/ApprovalTransferBonusService/detailTransfer',
            method: 'POST',
            data: {
                bankID: bankID,
                dateTime: dateTime,
                category: urlSegments
            },
            success: (response) => {
                let html = '';

                $.each(response.data, function(i, v) {
                    html = `${html}<table class="table table-striped"><tbody><tr>
                        <th role="row">No.</th>
                        <td>${parseInt(i)+1}</td>
                        </tr><tr>
                        <th role="row">Nama Mitra</th>
                        <td>${v.bonus_transfer_member_name}</td>
                        </tr><tr>
                        <th role="row">Network Code</th>
                        <td>${v.bonus_transfer_network_code}</td>
                        </tr><tr>
                        <th role="row">Kode Transfer</th>
                        <td>${v.bonus_transfer_code}</td>
                        </tr><tr>
                        <th role="row">Nama Bank</th>
                        <td>${v.bonus_transfer_bank_name}</td>
                        </tr><tr>
                        <th role="row">Nama Akun Bank</th>
                        <td>${v.bonus_transfer_bank_account_name}</td>
                        </tr><tr>
                        <th role="row">No. Rekening</th>
                        <td>${v.bonus_transfer_bank_account_no}</td>
                        </tr><tr>
                        <th role="row">Total Bonus</th>
                        <td>Rp ${number_format(v.bonus_transfer_total_bonus,0,'','.')}</td>
                        </tr><tr>
                        <th role="row">Biaya Admin</th>
                        <td>Rp ${number_format(v.bonus_transfer_adm_charge_value,0,'','.')}</td>
                        </tr><tr>
                        <th role="row">Bonus Nett</th>
                        <td>Rp ${number_format(v.bonus_transfer_nett,0,'','.')}</td>
                        </tr><tr>
                        <th role="row">Tanggal Transfer</th>
                        <td>${v.bonus_transfer_status_update_datetime}</td>
                    </tr></tbody></table>`;
                });
                $("#detail-transfer").html(html);
            }
        });
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