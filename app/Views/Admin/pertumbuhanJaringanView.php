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
                    <div>
                        <div id="div-last-data"></div>
                    </div>
                    <h4 class="card-title">Data Pertumbuhan Jaringan</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <p class="card-text"></p>
                        <div id="table-pertumbuhan-jaringan"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function(){
       $.ajax({
            url: window.location.origin + '/admin/MemberService/getDataPertumbuhanJaringan/',
            method: "GET",
            success: function(res) {
                let lastData = res.data.lastData;
                if ($.makeArray(lastData).length > 0) {
                    let html = `
                                <h5>Data Terakhir Pertumbuhan Jaringan</h5>
                                <div class="table-responsive" style="color:black">
                                    <table class="table">
                                        <thead style="background-color:#e4e0e0;">
                                            <td style="text-align:left;">Tanggal</td>
                                            <td style="text-align:right;">Titik Kiri</td>
                                            <td style="text-align:right;">Titik Kanan</td>
                                            <td style="text-align:right;">Titik Menunggu Kiri</td>
                                            <td style="text-align:right;">Titik Menunggu Kanan</td>
                                            <td style="text-align:right;">Pasangan Terjadi</td>
                                        </thead>
                                        <tbody>
                                            <td style="text-align:left;">${lastData.netgrow_master_date}</td>
                                            <td style="text-align:right;">${lastData.titik_kiri}</td>
                                            <td style="text-align:right;">${lastData.titik_kiri}</td>
                                            <td style="text-align:right;">${lastData.titik_menunggu_kiri}</td>
                                            <td style="text-align:right;">${lastData.titik_menunggu_kanan}</td>
                                            <td style="text-align:right;">${lastData.netgrow_master_match}</td>
                                        </tbody>
                                    </table>
                                </div>
                            `;
                    $('#div-last-data').html(html);
                }
            }
        });

        $("#table-pertumbuhan-jaringan").dataTableLib({
            url: window.location.origin + '/admin/MemberService/getDataPertumbuhanJaringan/',
            selectID : 'netgrow_master_id', 
            colModel : [
                {display: 'Tanggal Pertumbuhan ', name: 'netgrow_master_date', sortAble: false, align: 'center'},
                {display: 'Titik Kiri', name: 'titik_kiri', sortAble: false, align: 'center'},
                {display: 'Titik Kanan', name: 'titik_kanan', sortAble: false, align: 'center'},
                {display: 'Titik Menunggu (Kiri)', name: 'titik_menunggu_kiri', sortAble: false,  },
                {display: 'Titik Menunggu (Kanan)', name: 'titik_menunggu_kanan', sortAble: false,  },
                {display: 'Pasangan Terjadi', name: 'netgrow_master_match', sortAble: false,  },
            ],
            options: {
                limit: [10,15,20],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Bonus Member',
            searchItems:[
                {display: 'Tanggal', name: 'netgrow_master_date', type:'date'},
            ],
            sortName: "netgrow_master_id",
            sortOrder: "asc",
            tableIsResponsive: true,
            select: true,
            multiSelect: true,
            buttonAction: []
        });
    });
</script>
