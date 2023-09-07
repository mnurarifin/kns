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
                        <p class="card-text"></p>
                        <div id="table"></div>
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
    $(document).ready(() => {
        app.hideLoading()
        app.generateTable()
    })

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
                    url: window.location.origin + '/admin/service/saldo/getDataTransferHistory/',
                    selectID: 'ewallet_withdrawal_id',
                    colModel: [{
                            display: 'Detail',
                            name: 'ewallet_withdrawal_status_datetime',
                            sortAble: false,
                            align: 'center',
                            render: (params) => {
                                return `<a onclick="historyTransfer('${params}')"> <i class="bx bx-book info" ></i> </a> `;
                            }
                        },
                        {
                            display: 'Tanggal Transfer',
                            name: 'ewallet_withdrawal_status_datetime',
                            sortAble: true,
                            align: 'left',
                            export: true,
                        },
                        {
                            display: 'Total Saldo',
                            name: 'ewallet_withdrawal_subtotal_formatted',
                            sortAble: false,
                            align: 'right',
                            export: true,
                        },
                        {
                            display: 'Total Admin',
                            name: 'ewallet_withdrawal_adm_charge_value_formatted',
                            sortAble: false,
                            align: 'right',
                            export: true,
                        },
                        {
                            display: 'Total Pajak',
                            name: 'ewallet_withdrawal_tax_value_formatted',
                            sortAble: false,
                            align: 'right',
                            export: true,
                        },
                        {
                            display: 'Total Transfer',
                            name: 'ewallet_withdrawal_nett_formatted',
                            sortAble: true,
                            align: 'right',
                            export: true,
                        }
                    ],
                    options: {
                        limit: [10, 15, 20],
                        currentLimit: 10,
                    },
                    search: true,
                    searchTitle: 'Pencarian',
                    searchItems: [{
                        display: 'Tanggal Transfer',
                        name: 'ewallet_withdrawal_status_datetime',
                        type: 'date'
                    }],
                    sortName: "ewallet_withdrawal_id",
                    sortOrder: "ASC",
                    tableIsResponsive: true,
                    select: false,
                    multiSelect: false,
                    buttonAction: []
                });
            },
        },
        mounted() {

        },
    }).mount('#vue-app')
</script>