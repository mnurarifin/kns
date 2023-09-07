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

    .img-reward-thumbs {
        width: 60px;
        height: 60px;
        margin-right: 1.3rem;
        border-radius: 100%;
        border: 3px solid rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
</style>
<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?= isset($title) ? $title : '' ?></h4>
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
                        <div id="response-messages"></div>
                        <div id="table-reward"></div>
                        <div id="table-xo"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Detail-->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Qualifikasi Reward Cash</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body card mb-0" style="overflow-y: auto;">
                <div class="">
                    <div class="card-header">
                        <h5 class="text-primary mb-1 mt-25 text-left w-100">Data Mitra</h5>

                        <div class=" d-flex align-items-center justify-content-between">
                            <div class="media d-flex align-items-center">
                                <div class="media-body d-flex align-items-center">
                                    <div class="d-block">
                                        <h5 class="text-primary mb-0" id=""><small id="namaMitra"></small></h5>
                                        <h6 class="media-heading mb-0"><small id="mid"></small></h6>
                                    </div>
                                </div>
                            </div>

                            <div class="d-block">
                                <label class="mb-0">Tgl. Kualifikasi</label>
                                <span class="text-muted d-block text-right" id="rewardDate"></span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="card-header">
                        <div class="row">

                            <div class="col-md-12 d-flex align-items-center">
                                <div class="card text-left mb-0 w-100">
                                    <div class="card-body d-flex flex-column align-items-center justify-content-start p-0">
                                        <h5 class="text-primary mb-1 mt-25 text-left w-100"> Reward Yang Diterima</h5>
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <div>
                                                <label class="mb-0 text-primary " id="rewardTitle">Nilai Reward</label> <br>
                                                <label class="mb-0">Nilai Reward</label>
                                            </div>

                                            <div>
                                                <label for=""></label> <br>
                                                <h6 class="text-success font-weight-bolder mb-0" id="rewardValue"></h6>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="card-header">
                        <h5 class="text-primary mb-1 mt-25 text-left w-100">Syarat Yang Dipenuhi</h5>

                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-0">
                                    <div class="card-body shadow-sm p-1 d-flex align-items-center justify-content-center" style="position: relative;">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="d-block avatar bg-rgba-primary m-0 p-25 mx-auto mb-1">
                                                <div class="avatar-content">
                                                    <i class="bx bx-user text-primary font-medium-2"></i>
                                                </div>
                                            </div>
                                            <div class="total-amount text-center">
                                                <h5 class="mb-0"><span id="rewardLeft">0</span></h5>
                                                <small class="text-muted">Poin</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
    $(function() {
        $("#table-reward").bind("DOMSubtreeModified", function() {
            if ($("#table-reward").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        $("#table-reward").dataTableLib({
            url: window.location.origin + '/admin/service/reward/getDataApprovalReward/',
            selectID: 'reward_qualified_id',
            colModel: [{
                    display: 'Aksi',
                    name: 'detail',
                    sortAble: false,
                    align: 'center',
                    render: (params, args) => {
                        return `<span class="cstmHover px-25" onclick='detailQualified(${JSON.stringify(args)})' title="Detail" data-toggle="tooltip"><i class="bx bx-book info"></i></span>`
                    },
                    export: false
                }, {
                    display: 'Kode Mitra',
                    name: 'network_code',
                    sortAble: false,
                    align: 'center',
                    export: true
                },
                {
                    display: 'Nama Mitra',
                    name: 'member_name',
                    sortAble: false,
                    align: 'left',
                    export: true
                },

                {
                    display: 'Reward',
                    name: 'reward_qualified_reward_title',
                    sortAble: false,
                    align: 'left',
                    export: true
                },
                {
                    display: 'Nominal',
                    name: 'reward_qualified_reward_value',
                    sortAble: false,
                    align: 'center',
                    export: true
                },
                {
                    display: 'Nama Bank',
                    name: 'member_bank_name',
                    sortAble: false,
                    align: 'center',
                    export: true
                },
                {
                    display: 'No Rekening',
                    name: 'member_bank_account_no',
                    sortAble: false,
                    align: 'center',
                    export: true
                },
                {
                    display: 'Bank Atas Nama',
                    name: 'member_bank_account_name',
                    sortAble: false,
                    align: 'center',
                    export: true
                },
                {
                    display: 'Tanggal',
                    name: 'reward_qualified_datetime',
                    sortAble: false,
                    align: 'left',
                    export: true
                },

            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Approval Reward',
            searchItems: [{
                    display: 'Kode Mitra',
                    name: 'network_code',
                    type: 'text'
                },
                {
                    display: 'Nama Mitra',
                    name: 'member_name',
                    type: 'text'
                },
                {
                    display: 'Reward',
                    name: 'reward_qualified_reward_title',
                    type: 'text'
                },
                {
                    display: 'Tanggal',
                    name: 'reward_qualified_datetime',
                    type: 'date'
                },
            ],
            sortName: "reward_qualified_id",
            sortOrder: "asc",
            tableIsResponsive: true,
            select: true,
            multiSelect: true,
            buttonAction: [{
                    display: 'Export Excel',
                    icon: 'bx bxs-file',
                    style: 'info',
                    action: 'exportExcel',
                    url: window.location.origin + "/admin/reward/excelApproval"
                },
                {
                    display: 'Terima',
                    icon: 'bx bx-check-circle',
                    style: "success",
                    action: "accept",
                    url: window.location.origin + "/admin/service/reward/actApproveReward",
                    message: "Approve"
                },
                {
                    display: 'Tolak',
                    icon: 'bx bx-x-circle',
                    style: "danger",
                    action: "reject",
                    url: window.location.origin + "/admin/service/reward/actRejectReward"
                },
            ]
        });
    });

    function detailQualified(idQualified) {
        $('#namaMitra').text(idQualified.member_name)
        $('#mid').text(idQualified.network_code)
        $('#imgMitra').attr('src', idQualified.member_image)
        $('#rewardTitle').text(idQualified.reward_qualified_reward_title)
        $('#rewardValue').text(idQualified.reward_qualified_reward_value)
        $('#rewardLeft').text(formatDecimal(idQualified.reward_qualified_condition_point))
        $('#rewardNett').text(idQualified.reward_qualified_reward_value)
        $('#rewardDate').text(idQualified.reward_qualified_datetime)

        $('#modalDetail').modal('show');
    }

    function errFiles() {
        $('#imgMitra').attr('src', '/assets/images/reward/_default.jpg')
        $('#imgMitra').parent().addClass('err-img');
    }

    formatDecimal = ($params) => {
        let formatter = new Intl.NumberFormat('id-ID', {
            style: 'decimal',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        })
        return formatter.format($params)
    }
</script>