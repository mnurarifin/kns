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
                    <h4 class="card-title">Histori Komisi Mitra</h4>
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

<script>
    $(function() {
        $("#table-bonus").bind("DOMSubtreeModified", function() {
            if ($("#table-bonus").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        $("#table-bonus").dataTableLib({
            url: window.location.origin + '/admin/service/bonus/getHistoryBonus',
            selectID: 'bonus_log_id',
            colModel: [
                {
                    display: 'Tanggal',
                    name: 'bonus_log_date',
                    sortAble: true,
                    align: 'left'
                },
                {
                    display: 'Kode Mitra',
                    name: 'member_ref_network_code',
                    sortAble: true,
                    align: 'center'
                },
                {
                    display: 'Nama Mitra',
                    name: 'member_name',
                    sortAble: true,
                    align: 'left'
                },
                <?php foreach ($bonusConfig as $key => $row) {
                    if ($row->active_bonus) {?>
                        {
                            display: '<?php echo $row->label ?>',
                            name: '<?php echo 'bonus_log_'.$row->name ?>',
                            sortAble: true,
                            align: 'right'
                        },
                    <?php }
                } ?>
            ],
            options: {
                limit: [10, 15, 20],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Bonus Mitra',
            searchItems: [
                {
                    display: 'Tanggal',
                    name: 'bonus_log_date',
                    type: 'date'
                },
                {
                    display: 'Kode Mitra',
                    name: 'member_ref_network_code',
                    type: 'text'
                },
                {
                    display: 'Nama Mitra',
                    name: 'member_name',
                    type: 'text'
                },
            ],
            sortName: "bonus_log_id",
            sortOrder: "DESC",
            tableIsResponsive: true,
            select: false,
            multiSelect: false,
            buttonAction: []
        });
    });
</script>