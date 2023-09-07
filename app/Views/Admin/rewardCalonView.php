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
                <h5 class="modal-title" id="exampleModalLabel">Detail Kualifikasi Reward</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body card mb-0">
                <div class="">
                    <div class="card-header border-bottom p-0 pb-2">
                        <div class=" d-flex align-items-center justify-content-between">
                            <div class="media d-flex align-items-center">
                                <div class="media-body d-flex align-items-center">
                                    <a href="JavaScript:void(0);" class="img-reward-thumbs bg-white border-primary text-center mb-0">
                                        <img id="imgMitra" src="/assets/images/reward/_default.jpg" class="rounded" alt="group image" height="50" width="50">
                                    </a>
                                    <div class="d-block">
                                        <h5 class="text-primary mb-0" id=""><small id="namaMitra"></small></h5>
                                        <h6 class="media-heading mb-0"><small id="mid"></small></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-header border-bottom px-0 py-2">
                        <div class="row">
                            <div class="col-md-12 d-flex align-items-center">
                                <div class="card text-left mb-0 w-100">
                                    <div class="card-body d-flex flex-column align-items-center justify-content-start p-0">
                                        <h5 class="text-primary text-center mb-1 mt-25 text-left w-100" id="rewardTitle"></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pb-1 pt-2 px-0 mb-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-0">
                                    <div class="card-body shadow-lg p-1 d-flex align-items-center justify-content-center" style="position: relative;">
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
            url: window.location.origin + '/admin/service/reward/getDataCalonPenerimaReward/',
            selectID: 'reward_point_member_id',
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
                    display: 'Akumulasi Poin Saat Ini',
                    name: 'reward_point_acc',
                    sortAble: false,
                    align: 'left',
                    export: true
                },
                {
                    display: 'Target Reward',
                    name: 'reward_title',
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
            searchTitle: 'Pencarian Data Calon Penerima Reward',
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
                    display: 'Target Reward',
                    name: 'reward_title',
                    type: 'select',
                    option: [{
                            title: 'START UP',
                            value: 'START UP'
                        },
                        {
                            title: 'SAPPHIRE',
                            value: 'SAPPHIRE'
                        },
                        {
                            title: 'RUBY',
                            value: 'RUBY',
                        },
                        {
                            title: 'PEARL',
                            value: 'PEARL'
                        },
                        {
                            title: 'EMERALD',
                            value: 'EMERALD'
                        },
                        {
                            title: 'DIAMOND',
                            value: 'DIAMOND'
                        },
                        {
                            title: 'BLACK DIAMOND',
                            value: 'BLACK DIAMOND'
                        },
                        {
                            title: 'CROWN',
                            value: 'CROWN'
                        },
                        {
                            title: 'CROWN AMBASSADOR',
                            value: 'CROWN AMBASSADOR'
                        },
                        {
                            title: 'CROWN ROYAL AMBASSADOR',
                            value: 'CROWN ROYAL AMBASSADOR'
                        },
                    ]
                },

            ],
            sortName: "reward_qualified_status_datetime",
            sortOrder: "desc",
            tableIsResponsive: true,
            select: false,
            multiSelect: false,
            buttonAction: [{
                display: 'Export Excel',
                icon: 'bx bxs-file',
                style: 'info',
                action: 'exportExcel',
                url: window.location.origin + "/admin/reward/excelCalonReward/<?= $netType; ?>"
            }, ],
        });
    });

    function detailQualified(idQualified) {
        $('#namaMitra').text(idQualified.network_code)
        $('#mid').text(idQualified.member_name)
        $('#imgMitra').attr('src', idQualified.member_image)
        $('#rewardTitle').text(idQualified.reward_title)
        $('#rewardValue').text(parseInt(idQualified.reward_point_acc));
        $('#rewardLeft').text(formatDecimal(idQualified.reward_point_acc))
        $('#rewardNett').text(idQualified.reward_qualified_reward_value)
        $('#rewardDate').text(idQualified.reward_qualified_datetime)
        $('#adminName').text(idQualified.administrator_name)
        $('#apprvDate').text(idQualified.reward_qualified_status_datetime)

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