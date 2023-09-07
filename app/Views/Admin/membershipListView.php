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
                        <div id="table-member"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="modal-detail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-radius: 7px 7px 0 0; border: 1px solid #ccc; background-color: #f5f5f5;">
                <h4 class="modal-title" id="modal-label">Detail Data Pra-Mitra</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body p-0" style="overflow-y: scroll;">
                <div class="panel panel-default mb-2">
                    <div class="panel-body p-0">
                        <div class="card rounded-0 mb-1" style="box-shadow: none;">
                            <div class="card-header rounded-0 bg-dark text-white text-center">
                                <i class='bx bxs-user bx-lg bx-border-circle border-primary text-primary'></i>
                                <h5 class="text-primary mt-50 mb-50"><span id="data-member-name" class="text-capitalize"></span></h5>
                                <div class="d-flex align-items-center justify-content-center row">
                                    <div class="col-sm-12 col-md-6 justify-content-center d-flex">
                                        <i class="d-flex align-items-center bx bx-phone text-white mr-50"></i><span id="data-member-mobilephone"></span>
                                    </div>
                                    <div class="col-sm-12 col-md-6 justify-content-center d-flex">
                                        <i class="d-flex align-items-center bx bx-edit text-white mr-50"></i><span id="data-member-join-date">Bergabung Sejak :</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card widget-state-multi-radial">
                            <div class="card-header d-sm-flex align-items-center justify-content-between flex-wrap">
                                <h4 class="card-title mb-1">Biodata Pra-Mitra</h4>
                                <ul class="nav nav-tabs border-0 mt-sm-0 mt-50 mb-0" role="tablist">
                                    <li class="nav-item mr-0 ml-1">
                                        <a class="nav-link active d-flex flex-row align-items-center" id="biodata-tab" data-toggle="tab" href="#biodata" aria-controls="biodata" role="tab" aria-selected="true">
                                            <i class="d-flex align-items-center bx bx-user mr-50"></i>Biodata
                                        </a>
                                    </li>
                                    <li class="nav-item mr-0 ml-1">
                                        <a class="nav-link d-flex flex-row align-items-center" id="bank-tab" data-toggle="tab" href="#bank" aria-controls="bank" role="tab" aria-selected="false">
                                            <i class="d-flex align-items-center bx bxs-bank mr-50"></i>Info Bank
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body py-1">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="biodata" aria-labelledby="biodata-tab" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ul class="list-group list-group-flush d-flex flex-row">
                                                    <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                        <div class="list-left d-flex">
                                                            <div class="list-icon mr-1">
                                                                <div class="avatar bg-rgba-primary m-0">
                                                                    <div class="avatar-content">
                                                                        <i class="bx bx-user text-primary font-size-base"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-content">
                                                                <small class="text-muted d-block">Tgl. Bergabung</small>
                                                                <span class="list-title"><span id="data-member-join-datetime"></span></span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                        <div class="list-left d-flex">
                                                            <div class="list-icon mr-1">
                                                                <div class="avatar bg-rgba-primary m-0">
                                                                    <div class="avatar-content">
                                                                        <i class="bx bx-calendar-event text-primary font-size-base"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-content">
                                                                <small class="text-muted d-block">Tempat / Tanggal Lahir</small>
                                                                <span class="list-title"><span id="data-member-birth-place"></span>, <span id="data-member-birth-date"></span></span>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                        <div class="list-left d-flex">
                                                            <div class="list-icon mr-1">
                                                                <div class="avatar bg-rgba-primary m-0">
                                                                    <div class="avatar-content">
                                                                        <i class="bx bx-id-card text-primary font-size-base"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-content">
                                                                <small class="text-muted d-block">No. Identitas (<span id="data-member-identity-type"></span>)</small>
                                                                <span class="list-title"><span id="data-member-identity-number"></span></span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="col-md-12">
                                                <ul class="list-group list-group-flush d-flex flex-row">
                                                    <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                        <div class="list-left d-flex">
                                                            <div class="list-icon mr-1">
                                                                <div class="avatar bg-rgba-primary m-0">
                                                                    <div class="avatar-content">
                                                                        <i class="bx bx-map text-primary font-size-base"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-content">
                                                                <small class="text-muted d-block">Alamat</small>
                                                                <div class="list-title">
                                                                    <span id="data-member-address" class="d-block"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item list-group-item-action border-0 p-1 d-flex align-items-center justify-content-between">
                                                        <div class="list-left d-flex">
                                                            <div class="list-icon mr-1">
                                                                <div class="avatar bg-rgba-primary m-0">
                                                                    <div class="avatar-content">
                                                                        <i class="bx bx-map text-primary font-size-base"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-content">
                                                                <small class="text-muted d-block">Kota / Kabupaten</small>
                                                                <div class="list-title">
                                                                    <span id="data-member-city" class="d-block"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="bank" aria-labelledby="bank-tab" role="tabpanel">
                                        <div class="row justify-content-center">
                                            <div class="col-md-8">
                                                <div class="card border mb-50">
                                                    <div class="card-header p-1 d-flex justify-content-start align-items-center">
                                                        <i class="bx bx-receipt bx-lg bx-border-circle text-muted font-weight-light mr-2"></i>
                                                        <div class="card-title-content">
                                                            <h4 class="card-title text-dark mb-50">Rekening (<span id="data-member-bank-name" class="text-primary font-weight-bolder"></span>)</h4>
                                                            <h5 id="data-member-bank-account-number" class="d-block text-primary mb-0"></h5>
                                                            <span id="data-member-bank-account-name" class="d-block text-muted"></span>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Tutup</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalShowImage" tabindex="-1" role="dialog" aria-labelledby="modalShowImage" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalShowImageTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="moda-body">
                <div class="row pt-2 pl-1">
                    <div class="col-3">Nama</div>
                    <div class="col-6" id="name"></div>
                </div>
                <div class="row pl-1">
                    <div class="col-3">NIK</div>
                    <div class="col-6" id="nik"></div>
                </div>
                <div class="row pl-1">
                    <div class="col-3">Username</div>
                    <div class="col-6" id="username"></div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="modal-body text-center" id="image-body"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $("#table-member").bind("DOMSubtreeModified", function() {
            if ($("#table-member").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    let dataMember = {
        limit: 5,
        selectedID: 0,
    }

    $(document).ready(function() {
        $("#table-member").dataTableLib({
            url: window.location.origin + '/admin/service/member/getDataPramitra',
            selectID: 'member_id',
            colModel: [
                // {
                //     display: 'Login',
                //     name: 'login',
                //     sortAble: false,
                //     align: 'center',
                //     width: "60px",
                //     action: {
                //         function: 'login',
                //         icon: 'btn-outline-info rounded bx bx-log-in'
                //     }
                // },
                {
                    display: 'Aksi',
                    name: 'login',
                    sortAble: false,
                    align: 'center',
                    width: '80px',
                    render: (params, args) => {
                        return `
                        <span class="cstmHover px-25" onclick='detailMembership(${JSON.stringify(args)})' title="Detail" data-toggle="tooltip"><i class="bx bx-book info"></i></span>`
                        // <span class="cstmHover" onclick='showImage(${JSON.stringify(args)})' title="Foto KTP" data-toggle="tooltip"><i class="bx bx-show info"></i></span>
                    }
                },
                // {
                //     display: 'Username',
                //     name: 'member_registration_username',
                //     sortAble: false,
                //     align: 'center',
                //     export: true
                // },
                {
                    display: 'Nama Pramitra',
                    name: 'member_name',
                    sortAble: true,
                    align: 'left',
                    export: true
                },
                {
                    display: 'No. Handphone',
                    name: 'member_mobilephone',
                    sortAble: false,
                    align: 'left',
                    export: true
                },
                {
                    display: 'Sponsor',
                    name: 'member_registration_sponsor_username',
                    sortAble: true,
                    align: 'center',
                    export: true
                },
                {
                    display: 'Invoice',
                    name: 'invoice_url',
                    sortAble: false,
                    align: 'center',
                    export: false,
                    render: (params) => {
                        return `<span class="copy" style="cursor: pointer;">${params}</span>`
                    }
                },
                {
                    display: 'Tanggal Registrasi',
                    name: 'member_registration_datetime',
                    sortAble: true,
                    align: 'center',
                    export: true,
                    render: (params, args) => {
                        return `${args.member_registration_datetime_formatted}`
                    }
                },
            ],
            options: {
                limit: [10, 25, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Pra-Mitra',
            searchItems: [{
                    display: 'Nama Mitra',
                    name: 'member_name',
                    type: 'text'
                },
                {
                    display: 'No. Handphone',
                    name: 'member_mobilephone',
                    type: 'number'
                },
                {
                    display: 'Tanggal Gabung',
                    name: 'member_registration_datetime',
                    type: 'date'
                },
            ],
            buttonAction: [{
                display: 'Export Excel',
                icon: 'bx bxs-file',
                style: 'info',
                action: 'exportExcel',
                url: window.location.origin + "/admin/member/excelMembership"
            }, ],
            sortName: "member_id",
            sortOrder: "desc",
            tableIsResponsive: true,
        });

    });

    $("body").on("click", ".copy", (ev) => {
        var inp = document.createElement('input')
        document.body.appendChild(inp)
        inp.value = $(ev.target).html()
        inp.select()
        document.execCommand('copy', false)
        inp.remove()
        $("#copy_notif").show()
        setTimeout(function() {
            $("#copy_notif").hide()
        }, 1000)
    })

    function login(member) {
        var formData = new FormData();
        formData.append('member_id', member.member_id);
        $.ajax({
            url: window.location.origin + '/admin/service/member/getTokenLogin',
            data: formData,
            method: 'POST',
            processData: false,
            contentType: false,
        }).done(function(res) {
            let link = `<?= BASEURL ?>/${res.data}`
            if (res.message == "OK") {
                window.open(link);
            } else {
                $('#response-message').addClass('alert alert-danger');
                $('#response-message').html(res.data.message);
            }
            setTimeout(function() {
                $('#response-message').html('');
                $('#response-message').removeClass();
            }, 2000);
        }).fail(function(err) {
            console.log(err)
        })
    }

    function detailMembership(member) {
        $('#modal-detail').modal('show');
        $('#title-data-member-code').text(member.network_code)
        $('#data-member-name').text(member.member_name)
        $('#data-member-sex').text(member.member_gender)
        $('#data-member-birth-place').text(member.member_birth_place)
        $('#data-member-birth-date').text(member.member_birth_date)
        $('#data-member-mobilephone').text(member.member_mobilephone)
        $('#data-member-identity-type').text(member.member_identity_type)
        $('#data-member-identity-number').text(member.member_identity_no)
        $('#data-member-email').text(member.member_email)
        $('#data-member-address').text(member.member_address)
        $('#data-member-city').text(member.city_name)
        $('#data-member-bank-name').text(member.member_bank_name)
        $('#data-member-bank-city').text(member.member_bank_city)
        $('#data-member-bank-branch').text(member.member_bank_branch)
        $('#data-member-bank-account-number').text(member.member_bank_account_no)
        $('#data-member-bank-account-name').text(member.member_bank_account_name)
        $('#data-member-join-date').html('Bergabung : ' + diffDates(member.member_registration_datetime));
        $('#data-member-join-datetime').html(member.member_registration_datetime_formatted);
    }

    function diffDates(dates) {
        moment.locale('id');
        const vdate = new Date(dates);

        return moment(vdate, "YYYYMMDD").fromNow()
    }

    function showImage(params) {
        console.log(params);
        $('#name').text(': ' + params.member_name)
        $('#nik').text(': ' + params.member_identity_no)
        $('#username').text(': ' + params.member_registration_username)
        $('#modalShowImage').modal('show');
        $('#modalShowImageTitle').html('FOTO KTP')
        $('#image-body').html('')
        var htmlBody = '';

        htmlBody = `<img src="${params.member_identity_image_url}" width="450px">`
        $('#image-body').html(htmlBody)
    }
</script>