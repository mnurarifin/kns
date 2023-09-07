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
                        <div id="response-message"></div>
                        <div id="table-bpjs"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    let dataMember = {
        limit: 5,
        selectedID: 0,
    }

    $(function() {
        $("#table-bpjs").bind("DOMSubtreeModified", function() {
            if ($("#table-bpjs").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        $("#table-bpjs").dataTableLib({
            url: window.location.origin + '/admin/service/member/getDataMember',
            selectID: 'member_id',
            colModel: [{
                    display: 'Kode Mitra',
                    name: 'network_code',
                    sortAble: false,
                    align: 'center',
                    render: (params) => {
                        return params ? params : '-'
                    },
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
                    display: 'Tahun',
                    name: 'tahun_bpjs',
                    sortAble: false,
                    align: 'left',
                    export: true
                },
                {
                    display: 'Bulan',
                    name: 'bulan_bpjs',
                    sortAble: false,
                    align: 'left',
                    export: true
                },
                {
                    display: 'Status',
                    name: 'member_is_bpjs',
                    sortAble: false,
                    align: 'center',
                    render: (params) => {
                        var status;
                        switch (params) {
                            case '1':
                                status = '<span class="badge badge-light-success badge-pill badge-round" title="Aktif" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>';
                                break;
                            case '0':
                                status = '<span class="badge badge-light-danger badge-pill badge-round" title="Non Aktif" data-toggle="tooltip"><i class="bx bx-bulb"></i></span>'
                                break;
                        }
                        return status
                    },
                    export: true
                }
            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Mitra',
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
                    display: 'Status',
                    name: 'member_is_bpjs',
                    type: 'select',
                    option: [{
                        title: 'Aktif',
                        value: '1'
                    }, {
                        title: 'Tidak Aktif',
                        value: '0'
                    }]
                },
            ],
            buttonAction: [{
                    display: 'Aktifkan',
                    icon: 'bx bxs-bulb',
                    style: "success",
                    action: "active",
                    url: window.location.origin + "/admin/service/member/activeBpjsMember"
                },
                {
                    display: 'Non Aktifkan',
                    icon: 'bx bx-bulb',
                    style: "warning",
                    action: "nonactive",
                    url: window.location.origin + "/admin/service/member/nonactiveBpjsMember"
                }
            ],
            sortName: "network_code",
            sortOrder: "DESC",
            tableIsResponsive: true,
            select: true,
            multiSelect: true,
        });
    });
</script>