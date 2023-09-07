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

<section id="vue-app">
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
                        <div id="table"></div>
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
    $(document).ready(function() {
        app.hideLoading();
        app.generateTable();
    });

    const app = Vue.createApp({
        data() {
            return {

            }
        },
        methods: {
            hideLoading() {
                $("#pageLoader").hide();
            },
            generateTable() {
                $("#table").dataTableLib({
                    url: window.location.origin + '/admin/service/saldo/getDataSaldo/',
                    selectID: 'ewallet_member_id',
                    colModel: [{
                            display: 'Nama Stokis',
                            name: 'ewallet_member_name',
                            sortAble: false,
                            align: 'center',
                        },
                        {
                            display: 'Total Diterima (Rp)',
                            name: 'ewallet_acc_formatted',
                            sortAble: false,
                            align: 'center',
                        },
                        {
                            display: 'Total Dibayar (Rp)',
                            name: 'ewallet_paid_formatted',
                            sortAble: false,
                            align: 'center',
                        },
                        {
                            display: 'Saldo (Rp)',
                            name: 'ewallet_balance_formatted',
                            sortAble: false,
                            align: 'center',
                        }
                    ],
                    options: {
                        limit: [10, 15, 20],
                        currentLimit: 10,
                    },
                    search: true,
                    searchTitle: 'Pencarian',
                    searchItems: [{
                        display: 'Nama Stokis',
                        name: 'ewallet_member_name',
                        type: 'text'
                    }],
                    sortName: "ewallet_member_id",
                    sortOrder: "ASC",
                    tableIsResponsive: true,
                    select: false,
                    multiSelect: false,
                    buttonAction: []
                });
            },
        },
        mounted() {},
    }).mount("#vue-app")
</script>