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
                    <h4 class="card-title"><?=isset($title) ? $title : ''?></h4>
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
                        <div id="table-province"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

    $(function() {
        $("#table-province").bind("DOMSubtreeModified", function() {
            if ($("#table-province").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        $("#table-province").dataTableLib({
            url: window.location.origin+'/admin/service/ref-area/getDataProvince',
            selectID: 'province_id',
            colModel: [
            {display: 'Nama Provinsi', name: 'province_name', sortAble: false, align: 'left'},
            {display: 'Provinsi Latitude', name: 'province_latitude', sortAble: false, align: 'center', render: (params) => {
                return params ? params : '-'
            }},
            {display: 'Provinsi Longitude', name: 'province_longitude', sortAble: false, align: 'center', render: (params) => {
                return params ? params : '-'
            }},
            ],
            buttonAction: [],
            options: {
                limit: [10,25,50,100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Provinsi',
            searchItems:[
            {display: 'Nama Provinsi', name: 'province_name', type:'text'},
            ],
            sortName: "province_id",
            sortOrder: "asc",
            tableIsResponsive: true,
            select: false,
            multiSelect: false,
        });

    });
</script>