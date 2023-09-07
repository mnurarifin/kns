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
                    <h4 class="card-title">Komisi Mitra</h4>
                </div>
                <div class="card-content">
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
    $(document).ready(function() {
        $("#table-bonus").dataTableLib({
            url: window.location.origin + '/admin/service/bonus/getDataSummaryKomisiMitra',
            selectID: 'bonus_network_id',
            colModel: [{
                    display: 'Kode Mitra',
                    name: 'member_ref_network_code',
                    sortAble: false,
                    align: 'center'
                },
                {
                    display: 'Nama Mitra',
                    name: 'member_name',
                    sortAble: false,
                    align: 'left'
                },
                {
                    display: 'Total Diterima (Rp)',
                    name: 'bonus_total_acc',
                    sortAble: false,
                    align: 'right'
                },
                {
                    display: 'Total Dibayar (Rp)',
                    name: 'bonus_total_paid',
                    sortAble: false,
                    align: 'right'
                },
                {
                    display: 'Saldo (Rp)',
                    name: 'saldo',
                    sortAble: false,
                    align: 'right'
                },
                {
                    display: 'Detail',
                    name: 'detail',
                    sortAble: false,
                    align: 'center',
                    width: "70px",
                    action: {
                        function: 'detailKomisi',
                        icon: 'bx bx-book info'
                    }
                },
            ],
            options: {
                limit: [10, 15, 20],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Komisi Mitra',
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
            sortName: "bonus_network_id",
            sortOrder: "asc",
            tableIsResponsive: true,
            buttonAction: []
        });
    });

    function detailKomisi(komisi) {
        let name = komisi.member_name;
        let code = komisi.member_ref_network_code;

        html = `
        <tr>
            <td style="text-align:left">SPONSOR</td>
            <td style="text-align:right">${komisi.bonus_sponsor_acc}</td>
            <td style="text-align:right">${komisi.bonus_sponsor_paid}</td>
            <td style="text-align:right">${komisi.bonus_sponsor_acc - komisi.bonus_sponsor_paid}</td>
        </tr><tr>
            <td style="text-align:left">GENERASI</td>
            <td style="text-align:right">${komisi.bonus_gen_node_acc}</td>
            <td style="text-align:right">${komisi.bonus_gen_node_paid}</td>
            <td style="text-align:right">${komisi.bonus_gen_node_acc - komisi.bonus_gen_node_paid}</td>
        </tr><tr>
            <td style="text-align:left">POWER LEG</td>
            <td style="text-align:right">${komisi.bonus_power_leg_acc}</td>
            <td style="text-align:right">${komisi.bonus_power_leg_paid}</td>
            <td style="text-align:right">${komisi.bonus_power_leg_acc - komisi.bonus_power_leg_paid}</td>
        </tr><tr>
            <td style="text-align:left">MATCHING LEG</td>
            <td style="text-align:right">${komisi.bonus_matching_leg_acc}</td>
            <td style="text-align:right">${komisi.bonus_matching_leg_paid}</td>
            <td style="text-align:right">${komisi.bonus_matching_leg_acc - komisi.bonus_matching_leg_paid}</td>
        </tr><tr>
            <td style="text-align:left">CASH REWARD</td>
            <td style="text-align:right">${komisi.bonus_cash_reward_acc}</td>
            <td style="text-align:right">${komisi.bonus_cash_reward_paid}</td>
            <td style="text-align:right">${komisi.bonus_cash_reward_acc - komisi.bonus_cash_reward_paid}</td>
        </tr>
        <tr style="font-weight:bold; color:#000;">
            <td style="text-align:center">TOTAL</td>
            <td style="text-align:right">Rp ${komisi.bonus_total_acc}</td>
            <td style="text-align:right">Rp ${komisi.bonus_total_paid}</td>
            <td style="text-align:right">Rp ${komisi.saldo}</td>
        </tr>
        `

        $('#data-mitra').html(name + ' | ' + code);
        $('#tbody-detail-komisi').html(html);
        $('#modal-detail').modal('show');
    }

    function setNumberFormat(number, isInt = true) {
        if (!isNaN(number) && Math.floor(number) != number && isInt == false) {
            return numberFormat(number, 2, ',', '.');
        } else {
            return numberFormat(number, 0, ',', '.');
        }
    }

    function numberFormat(number, decimals, decPoint, thousandsPoint) {
        if (number == null || !isFinite(number)) {
            throw new TypeError("number is not valid");
        }

        if (!decimals) {
            var len = number.toString().split('.').length;
            decimals = len > 1 ? len : 0;
        }

        if (!decPoint) {
            decPoint = '.';
        }

        if (!thousandsPoint) {
            thousandsPoint = ',';
        }

        number = parseFloat(number).toFixed(decimals);

        number = number.replace(".", decPoint);

        var splitNum = number.split(decPoint);
        splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsPoint);
        number = splitNum.join(decPoint);

        return number;
    }
</script>