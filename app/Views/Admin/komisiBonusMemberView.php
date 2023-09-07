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

<div class="modal fade" id="modalHistoryBonus" tabindex="-1" role="dialog" aria-labelledby="modalHistoryBonusTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center" id="modalHistoryBonusTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="changeHash()">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="table-bonus-history"></div>
            </div>
        </div>
    </div>
</div>

<div id="modal-detail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-radius: 7px 7px 0 0; border: 1px solid #ccc; background-color: #f5f5f5;">
                <h4 class="modal-title" id="modal-label">Detail Komisi Mitra (<strong><span id="data-mitra"></span></strong>) &nbsp;&nbsp;&nbsp;<span id="data-member-is-active"></span> <span id="data-member-is-suspended"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="panel panel-default mb-2">
                    <div class="panel-body">
                        <table class="data table table-bordered table-hover">
                            <thead>
                                <tr style="font-weight: bold; background-color: #2d2626; color: #fff;">
                                    <td style="width:40%; text-align:center;">Nama Komisi</td>
                                    <td style="width:20%; text-align:right;">Diterima</td>
                                    <td style="width:20%; text-align:right;">Dibayarkan</td>
                                    <td style="width:20%; text-align:right;">Saldo</td>
                                </tr>
                            </thead>
                            <tbody id="tbody-detail-komisi">
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
        $("#table-bonus").dataTableLib({
            url: window.location.origin + '/admin/service/komisi/getDataBonus',
            selectID: 'bonus_member_id',
            colModel: [{
                    display: 'Detail',
                    // display: 'Riwayat',
                    name: 'member_ref_network_code',
                    sortAble: false,
                    align: 'center',
                    action: {
                        function: 'detailKomisi',
                        icon: 'bx bx-book info'
                    }
                    // render: (params, columns) => {
                    //     return `<a onclick="historyBonus('${params}', '${columns.member_name}')"> <i class="bx bx-receipt success" ></i> </a> `;
                    // }
                },
                {
                    display: 'Kode Mitra',
                    name: 'network_code',
                    sortAble: true,
                    align: 'center'
                },
                {
                    display: 'Nama Mitra',
                    name: 'member_name',
                    sortAble: true,
                    align: 'left'
                },
                {
                    display: 'Total Diterima (Rp)',
                    name: 'bonus_acc_formatted',
                    sortAble: false,
                    align: 'right',
                },
                {
                    display: 'Total Dibayar (Rp)',
                    name: 'bonus_paid_formatted',
                    sortAble: false,
                    align: 'right',
                },
                {
                    display: 'Saldo (Rp)',
                    name: 'saldo_formatted',
                    sortAble: false,
                    align: 'right',
                },
            ],
            options: {
                limit: [10, 15, 20],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Bonus Mitra',
            searchItems: [{
                    display: 'Kode Mitra',
                    name: 'member_ref_network_code',
                    type: 'text'
                },
                {
                    display: 'Nama',
                    name: 'member_name',
                    type: 'text'
                },
            ],
            sortName: "saldo",
            sortOrder: "desc",
            tableIsResponsive: true,
            buttonAction: []
        });
    });

    function historyBonus(network_code, member_name) {
        $("#table-bonus-history").dataTableLib({
            url: window.location.origin + `/admin/service/komisi/getDataHistoryBonus?network_code=${network_code}`,
            selectID: 'bonus_log_id',
            colModel: [{
                    display: 'Tanggal',
                    name: 'bonus_log_date',
                    sortAble: true,
                    align: 'left',
                    render: (params, columns) => {
                        return columns.bonus_log_date_formatted
                    }
                },
                {
                    display: 'Tipe',
                    name: 'bonus_log_type',
                    sortAble: true,
                    align: 'center',
                    render: (params) => {
                        if (params == 'out') {
                            return `<button type="button" class="btn btn-icon btn-outline-danger btn-sm mb-0" style="padding-top:5px;padding-bottom:5px;"><i class="ficon bx bx-up-arrow-alt"></i></button>`
                        } else if (params == 'in') {
                            return `<button type="button" class="btn btn-icon btn-outline-success btn-sm mb-0" style="padding-top:5px;padding-bottom:5px;"><i class="ficon bx bx-down-arrow-alt"></i></button>`
                        }
                    }
                },
                {
                    display: 'Nominal',
                    name: 'bonus_log_value',
                    sortAble: true,
                    align: 'right',
                    render: (params, columns) => {
                        return columns.bonus_log_value_formatted
                    }
                },
                {
                    display: 'Catatan',
                    name: 'bonus_log_note',
                    sortAble: true,
                    align: 'left'
                },
            ],
            options: {
                limit: [1, 10, 15, 20],
                currentLimit: 1,
            },
            search: true,
            searchTitle: 'Pencarian Data Bonus Member',
            searchItems: [{
                    display: 'Tanggal',
                    name: 'bonus_log_date',
                    type: 'date'
                },
                {
                    display: 'Tipe',
                    name: 'bonus_log_type',
                    type: 'select',
                    option: [{
                            title: 'Masuk',
                            value: 'in'
                        },
                        {
                            title: 'Keluar',
                            value: 'out'
                        },
                    ]
                },
            ],
            sortName: "bonus_log_id",
            sortOrder: "desc",
            tableIsResponsive: true,
            buttonAction: []
        });
        $('#modalHistoryBonusTitle').html(`Riwayat Bonus ${network_code} (${member_name})`)
        $('#modalHistoryBonus').modal();
    }

    function changeHash() {
        if ($("#modalHistoryBonus").hasClass("show") == true) {
            window.location.hash = hash
        }
    }

    function detailKomisi(komisi) {
        let member_id = komisi.bonus_member_id;
        let name = komisi.member_name;
        let code = komisi.network_code;
        let total_acc = komisi.bonus_acc;
        let total_paid = komisi.bonus_paid;
        let total_saldo = komisi.saldo;
        $.ajax({
            url: window.location.origin + '/admin/service/komisi/getDetailBonus',
            type: 'GET',
            data: {
                member_id: member_id
            },
            success: function(res) {
                if (res.status == 200) {
                    console.log(res)
                    if (res.data.results.length > 0) {
                        let html = '';
                        $.each(res.data.results, function(key, val) {
                            html += `
                                <tr>
                                    <td style="text-align:left">${formatCurrency(val.label)}</td>
                                    <td style="text-align:right">${formatCurrency(val.value_acc)}</td>
                                    <td style="text-align:right">${formatCurrency(val.value_paid)}</td>
                                    <td style="text-align:right">${formatCurrency(val.saldo)}</td>
                                </tr>
                            `;
                        });
                        html += `
                            <tr style="font-weight:bold; color:#000;">
                                <td style="text-align:center">TOTAL</td>
                                <td style="text-align:right">${formatCurrency(total_acc)}</td>
                                <td style="text-align:right">${formatCurrency(total_paid)}</td>
                                <td style="text-align:right">${formatCurrency(total_saldo)}</td>
                            </tr>
                        `;
                        $('#data-mitra').html(name + ' | ' + code);
                        $('#tbody-detail-komisi').html(html);
                        $('#modal-detail').modal('show');
                    }
                }
            }
        });

        formatCurrency = ($params) => {
            let formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            })
            return formatter.format($params)
        }
    }
</script>