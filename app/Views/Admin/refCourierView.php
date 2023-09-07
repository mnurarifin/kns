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
<div class="modal fade" id="modalAddUpdateBank" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdateTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddUpdateTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form class="form form-horizontal" id="formAddUpdateBank">
                <div class="modal-body" style="max-height: 470px;">
                    <div id="response-message"></div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nama Bank</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="bankName" placeholder="Nama Bank">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Logo Bank</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="file" class="form-control" name="bankImage" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tutup</span>
                    </button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                        <div id="response-messages"></div>
                        <div id="table"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(function() {
        $("#table").bind("DOMSubtreeModified", function() {
            if ($("#table").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        $("#table").dataTableLib({
            url: window.location.origin + '/admin/service/ref-courier/getDataCourier',
            selectID: 'courier_id',
            colModel: [{
                    display: 'Nama Kurir',
                    name: 'courier_name',
                    sortAble: false,
                    align: 'left',
                    width: "150px",
                    export: true
                },
                {
                    display: 'Kode Kurir',
                    name: 'courier_code',
                    sortAble: false,
                    align: 'left',
                    width: "150px",
                    export: true
                },
                {
                    display: 'Status',
                    name: 'courier_is_active',
                    sortAble: false,
                    align: 'center',
                    render: (params) => {
                        return params == '1' ? '<span class="badge badge-light-success badge-pill badge-round" title="Aktif" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>' : '<span class="badge badge-light-danger badge-pill badge-round" title="Non Aktif" data-toggle="tooltip"><i class="bx bx-bulb"></i></span>'
                    },
                    export: true
                },
            ],
            buttonAction: [{
                    display: 'Aktifkan',
                    icon: 'bx bxs-bulb',
                    style: "success",
                    action: "active",
                    url: window.location.origin + "/admin/service/ref-courier/activeCourier"
                },
                {
                    display: 'Non Aktifkan',
                    icon: 'bx bx-bulb',
                    style: "warning",
                    action: "nonactive",
                    url: window.location.origin + "/admin/service/ref-courier/nonactiveCourier"
                },
            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Kurir',
            searchItems: [{
                display: 'Nama Kurir',
                name: 'courier_name',
                type: 'text'
            }, ],
            sortName: "courier_id",
            sortOrder: "asc",
            tableIsResponsive: true,
            select: true,
            multiSelect: true,
        });
    });
</script>