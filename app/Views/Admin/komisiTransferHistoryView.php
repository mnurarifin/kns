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
                    <h4 class="card-title"><?php echo isset($title) ? $title : ''; ?></h4>
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
                        <p class="card-text"></p>
                        <div id="table-bonus"></div>
                        <div id="table-xo"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modalHistoryTransfer" tabindex="-1" role="dialog" aria-labelledby="modalHistoryTransferTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center" id="modalHistoryTransferTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="changeHash()">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="table-transfer-history"></div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $("#table-bonus").bind("DOMSubtreeModified", function() {
            if ($("#table-bonus").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    let hash = window.location.hash;

    $(document).ready(function() {
        let adminList = [];
        let url = window.location.origin + '/admin/service/administrator/getDataAdministrator'
        $.ajax({
            url: url,
            data: {
                limit: 1000
            },
            method: 'GET',
            async: false,
            beforeSend: function() {
                adminList = []
            },
            success: function(res) {
                if (res.status == 200) {
                    adminList = res.data.results.map(el => {
                        return {
                            title: el.administrator_name,
                            value: el.administrator_id
                        }
                    })
                } else {
                    adminList = []
                }
            },
            error: function(err) {
                adminList = []
            }
        });

        $("#table-bonus").dataTableLib({
            url: window.location.origin + '/admin/service/komisi/getSummaryHistoryTransfer',
            selectID: 'bonus_transfer_date',
            colModel: [{
                    display: 'Detail',
                    name: 'bonus_transfer_datetime',
                    sortAble: false,
                    align: 'center',
                    render: (params) => {
                        return `<a onclick="historyTransfer('${params}')"> <i class="bx bx-book info" ></i> </a> `;
                    }
                },
                {
                    display: 'Tanggal Transfer',
                    name: 'bonus_transfer_datetime_formatted',
                    sortAble: true,
                    align: 'left',
                    export: true,
                },
                {
                    display: 'Total Komisi',
                    name: 'bonus_transfer_subtotal',
                    sortAble: false,
                    align: 'right',
                    export: true,
                },
                {
                    display: 'Total Admin',
                    name: 'bonus_transfer_adm_charge_value',
                    sortAble: false,
                    align: 'right',
                    export: true,
                },
                {
                    display: 'Total Pajak',
                    name: 'bonus_transfer_tax_value',
                    sortAble: false,
                    align: 'right',
                    export: true,
                },
                {
                    display: 'Total Transfer',
                    name: 'bonus_transfer_total',
                    sortAble: true,
                    align: 'left',
                    export: true,
                },
                {
                    display: 'Komisi Sponsor',
                    name: 'bonus_transfer_sponsor',
                    sortAble: false,
                    align: 'left',
                    export: true,
                },
                {
                    display: 'Komisi Generasi',
                    name: 'bonus_transfer_gen_node',
                    sortAble: false,
                    align: 'left',
                    export: true,
                },
                {
                    display: 'Komisi Power Leg',
                    name: 'bonus_transfer_power_leg',
                    sortAble: false,
                    align: 'left',
                    export: true,
                },
                {
                    display: 'Komisi Matching Leg',
                    name: 'bonus_transfer_matching_leg',
                    sortAble: false,
                    align: 'left',
                    export: true,
                },
                {
                    display: 'Komisi Cash Reward',
                    name: 'bonus_transfer_cash_reward',
                    sortAble: false,
                    align: 'left',
                    export: true,
                },
            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Transfer Komisi',
            searchItems: [{
                display: 'Tanggal Transfer',
                name: 'bonus_transfer_datetime',
                type: 'date'
            }, ],
            sortName: "bonus_transfer_datetime",
            sortOrder: "desc",
            tableIsResponsive: true,
            buttonAction: [{
                display: 'Export Excel',
                icon: 'bx bxs-file',
                style: 'info',
                action: 'exportExcel',
                url: window.location.origin + "/admin/komisi/excelSummaryHistoryTransfer"
            }]
        });
    });

    function historyTransfer(date) {
        $("#table-transfer-history").dataTableLib({
            url: window.location.origin + `/admin/service/komisi/getDataHistoryTransfer?date=${date}`,
            selectID: 'bonus_transfer_id',
            colModel: [{
                    display: 'Status',
                    name: 'bonus_transfer_status',
                    sortAble: true,
                    align: 'left',
                    render: (params) => {
                        return params == "pending" ? `<span class="badge badge-light-warning badge-pill">Diproses</span>` : params == "failed" ? `<span class="badge badge-light-danger badge-pill">Gagal</span>` : `<span class="badge badge-light-success badge-pill">Sukses</span>`
                    },
                }, {
                    display: 'Nama Mitra',
                    name: 'bonus_transfer_member_name',
                    sortAble: true,
                    align: 'left',
                },
                {
                    display: 'Nama Rekening',
                    name: 'bonus_transfer_member_bank_account_name',
                    sortAble: true,
                    align: 'left',
                },
                {
                    display: 'Nama Bank',
                    name: 'bonus_transfer_member_bank_name',
                    sortAble: true,
                    align: 'left',
                },
                {
                    display: 'No. Rekening',
                    name: 'bonus_transfer_member_bank_account_no',
                    sortAble: true,
                    align: 'left',
                },
                {
                    display: 'Nominal Komisi',
                    name: 'bonus_transfer_subtotal',
                    sortAble: true,
                    align: 'right',
                },
                {
                    display: 'Biaya Admin',
                    name: 'bonus_transfer_adm_charge_value',
                    sortAble: true,
                    align: 'right',
                },
                {
                    display: 'Nominal Transfer',
                    name: 'bonus_transfer_total',
                    sortAble: true,
                    align: 'right',
                },
                {
                    display: 'Komisi Sponsor',
                    name: 'bonus_transfer_sponsor',
                    sortAble: false,
                    align: 'left',
                    export: true,
                },
                {
                    display: 'Komisi Generasi',
                    name: 'bonus_transfer_gen_node',
                    sortAble: false,
                    align: 'left',
                    export: true,
                },
                {
                    display: 'Komisi Power Leg',
                    name: 'bonus_transfer_power_leg',
                    sortAble: false,
                    align: 'left',
                    export: true,
                },
                {
                    display: 'Komisi Matching Leg',
                    name: 'bonus_transfer_matching_leg',
                    sortAble: false,
                    align: 'left',
                    export: true,
                },
                {
                    display: 'Komisi Cash Reward',
                    name: 'bonus_transfer_cash_reward',
                    sortAble: false,
                    align: 'left',
                    export: true,
                },
            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Transfer Komisi',
            searchItems: [{
                    display: 'Kode',
                    name: 'bonus_transfer_code',
                    type: 'text'
                },
                {
                    display: 'Nama Mitra',
                    name: 'bonus_transfer_member_name',
                    type: 'text',
                },
                {
                    display: 'Nama Rekening',
                    name: 'bonus_transfer_bank_account_name',
                    type: 'text',
                },
                {
                    display: 'Nama Bank',
                    name: 'bonus_transfer_bank_name',
                    type: 'text',
                },
                {
                    display: 'Nomor Rekening',
                    name: 'bonus_transfer_bank_account_no',
                    type: 'text',
                }
            ],
            sortName: "bonus_transfer_id",
            sortOrder: "desc",
            tableIsResponsive: true,
            buttonAction: [{
                display: 'Export Excel',
                icon: 'bx bxs-file',
                style: 'info',
                action: 'exportExcel',
                url: window.location.origin + `/admin/komisi/excelDataHistoryTransfer/${date}`
            }]
        });
        $('#modalHistoryTransferTitle').html(`Riwayat Transfer Tanggal ${date}`)
        $('#modalHistoryTransfer').modal();
    }

    function changeHash() {
        if ($("#modalHistoryTransfer").hasClass("show") == true) {
            window.location.hash = hash
        }
    }
</script>