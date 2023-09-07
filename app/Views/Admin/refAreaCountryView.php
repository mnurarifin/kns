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
                    <div class="card-body card-dashboard">
                        <div id="table-country"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

    $(document).ready(function() {
        $("#table-country").dataTableLib({
            url: window.location.origin+'/admin/service/ref-area/getDataCountry',
            selectID: 'country_id',
            colModel: [
            {display: 'Nama Negara', name: 'country_name', sortAble: false,align: 'center', width: "150px", export: true},
            {display: 'Kode ISO', name: 'country_iso_code', sortAble: false,align: 'center', width: "150px", export: true},
            {display: 'Kode Negara', name: 'country_phone_code', sortAble: false,align: 'center', width: "150px", export: true},
            {display: 'Status', name: 'country_is_active', sortAble: false, align: 'center', render: (params) => {
                var status;
                switch (params) {
                    case '1':
                    status = "Aktif";
                    break;
                    default:
                    status = 'Tidak Aktif'
                    break;
                }
                return status
            }, export: true},
            ],
            buttonAction: [
            {display: 'Aktifkan', icon: 'bx bxs-bulb', style:"success", action:"active", url : window.location.origin+"/admin/service/ref-area/activeCountry"},
            {display: 'Non Aktifkan', icon: 'bx bx-bulb', style:"warning", action:"nonactive", url : window.location.origin+"/admin/service/ref-area/nonactiveCountry"},
            {display: 'Export Excel', icon: 'bx bxs-file',style: 'info', action: 'exportExcel', url: window.location.origin+"/ref-area/excel"},
            ],
            options: {
                limit: [10,25,50,100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Negara',
            searchItems:[
            {display: 'Nama Negara', name: 'country_name', type:'text'},
            {display: 'Kode Negara', name: 'country_iso_code', type:'text'},
            ],
            sortName: "country_id",
            sortOrder: "asc",
            tableIsResponsive: true,
            select: true,
            multiSelect: true,
        });

    });
</script>