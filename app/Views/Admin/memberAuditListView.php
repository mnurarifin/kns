<style>
    .noHover {
        pointer-events: none;
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
                        <div id="response-message"></div>
                        <div id="table-member"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modalDetailAudit" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailAudit"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form class="form form-horizontal" id="formAddUpdateReward">
                <div class="modal-body" style="max-height: 470px;">
                    <div id="response-message"></div>
                    <div class="form-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>Log Auditrail</strong>
                            </div>
                            <table class="table table-striped noHover" style=" margin: 0">
                                <tbody>
                                    <tr>
                                        <td style="width: 20%">Nama Editor</td>
                                        <td><span id="audit-name"></span> (<span id="audit-type"></span>) </td>
                                    </tr>
                                    <tr>
                                        <td>Dari</td>
                                        <td><span id="audit-from"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal</td>
                                        <td><span id="audit-datetime"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Target member</td>
                                        <td><span id="audit-network-code"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Keterangan</td>
                                        <td><span id="audit-note"></span></td>
                                    </tr>
                                    <tr id="data-change"></tr>
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
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    let dataMember = {
        limit: 5,
        selectedID: 0,
    }

    $(function() {
        $("#table-member").bind("DOMSubtreeModified", function() {
            if ($("#table-member").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        $("#table-member").dataTableLib({
            url: window.location.origin + '/admin/service/member/getDataMemberAudit',
            selectID: 'member_audit_trail_id',
            colModel: [{
                    display: 'Detail',
                    name: 'detail',
                    sortAble: false,
                    align: 'center',
                    width: "70px",
                    action: {
                        function: 'detailMember',
                        icon: 'bx bx-book info'
                    }
                },
                {
                    display: 'Username Editor',
                    name: 'member_audit_trail_username',
                    sortAble: false,
                    align: 'center',
                    export: true
                },
                {
                    display: 'Nama Editor',
                    name: 'member_audit_trail_name',
                    sortAble: false,
                    align: 'left',
                    export: true
                },
                {
                    display: 'Type',
                    name: 'member_audit_trail_type',
                    sortAble: false,
                    align: 'left',
                    export: true
                },
                {
                    display: 'Dari Halaman',
                    name: 'member_audit_trail_page',
                    sortAble: false,
                    align: 'left',
                    export: true
                },
                {
                    display: 'Target Member',
                    name: 'member_audit_trail_network_code',
                    sortAble: false,
                    align: 'left',
                    export: true
                },
                {
                    display: 'Tanggal',
                    name: 'member_audit_trail_datetime',
                    sortAble: false,
                    align: 'left',
                    export: true
                },
                {
                    display: 'Keterangan',
                    name: 'member_audit_trail_note',
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
            searchTitle: 'Pencarian Data Audit',
            searchItems: [{
                    display: 'Kode Mitra',
                    name: 'member_audit_trail_network_code',
                    type: 'text'
                },
                {
                    display: 'Tanggal Edit',
                    name: 'member_audit_trail_datetime',
                    type: 'date'
                },
            ],
            buttonAction: [],
            sortName: "member_audit_trail_datetime",
            sortOrder: "DESC",
            tableIsResponsive: true,
            select: false,
            multiSelect: false,
        });
    });

    function detailMember(member) {
        $('#modalDetailAudit').text('Detail Audit Member');
        $('#modal-detail').modal('show');
        $('#audit-name').text(member.member_audit_trail_username)
        $('#audit-type').text(member.member_audit_trail_type)
        $('#audit-from').text(member.member_audit_trail_page)
        $('#audit-datetime').text(member.member_audit_trail_datetime)
        $('#audit-network-code').text(member.member_audit_trail_network_code)
        $('#audit-note').text(member.member_audit_trail_note)
        // let dataLog = JSON.parse(member.member_audit_trail_change);
        let html = `
            <td>Perubahan</td>
                <td>
                <table id="data-change-member" class="table table-striped"">
                    <thead>
                        <tr>
                            <th>Data Perubahan</th>
                            <th class='text-center'>Sebelum</th>
                            <th class='text-center'>Sesudah</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        member.member_audit_trail_change.forEach(function(value, index) {
            var valueBefore = '-';
            if (value.audit_before != null && value.audit_before != '') {
                valueBefore = value.audit_before;
            }
            var valueAfter = '-';
            if (value.audit_after != null && value.audit_after != '') {
                valueAfter = value.audit_after;
            }
            if (value.audit_before != value.audit_after) {
                html += `
                    <tr>
                        <td>${value.audit_field_name}</td>
                        <td class='text-center'>${valueBefore}</td>
                        <td class='text-center'>${valueAfter}</td>
                    </tr>   
                `;
                $('#data-change').html(html);
            }
        })
    }
</script>