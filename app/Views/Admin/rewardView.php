<style>
    .alert-position {
        transform: translateY(5px);
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

<div class="modal fade" id="modalAddUpdateReward" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdateTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddUpdateTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form class="form form-horizontal" id="formAddUpdateReward">
                <div class="modal-body" style="max-height: 470px;">
                    <div id="response-message"></div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nama Reward</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="rewardTitle" placeholder="Nama Reward">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nilai Reward (Rp)</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input class="form-control money" name="rewardBonusValue" id="rewardBonusValue" placeholder="Nilai Bonus Reward (Rp)">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Syarat Poin Kiri</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input class="form-control" name="rewardCondLeft" id="rewardCondLeft" placeholder="Syarat Poin Kiri">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Syarat Poin Kanan</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input class="form-control" name="rewardCondRight" id="rewardCondRight" placeholder="Syarat Poin Kanan">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Syarat Peringkat</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="" id="select_rank" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Gambar Reward</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <img src="http://localhost:9090/exportimages/reward/reward20220422103817.png" style="width:60px; height:auto" id="previewImage">
                                <label for="upload-files" class="btn btn-sm btn-light-primary ml-50 mb-50 mb-sm-0">
                                    <span>Upload File</span>
                                    <input onChange="previewFiles()" id="upload-files" type="file" name="rewardImageFilename" hidden accept="image/png, image/jpeg">
                                </label>
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

<!-- MODAL DETAIL IMAGE -->
<div id="modalDetailImage" class="modal" tabindex="-1" role="dialog" aria-labelledby="modalDetailImageTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalDetailImageTitle">Detail Gambar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
        </div>
        <div class="modal-body" id="imageDetail"></div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<script>
    $(function() {
        $("#table-reward").bind("DOMSubtreeModified", function() {
            if ($("#table-reward").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    $(document).ready(function() {
        $('.money').maskMoney({
            thousands: '.',
            decimal: '.',
            allowZero: true,
            prefix: '',
            affixesStay: true,
            allowNegative: false,
            precision: 0
        });
        $("#table-reward").dataTableLib({
            url: window.location.origin + '/admin/service/reward/getDataReward/<?= $netType; ?>/',
            selectID: 'reward_id',
            colModel: [{
                    display: '',
                    name: 'reward_image_file_url',
                    sortAble: false,
                    align: 'left',
                    render: (params) => {
                        return `<img src="${params}" style="width:60px; height:auto" class="detailImage">`
                    }
                },
                {
                    display: 'Reward',
                    name: 'reward_title',
                    sortAble: false,
                    align: 'left'
                },
                {
                    display: 'Nominal (Rp)',
                    name: 'reward_bonus_value',
                    sortAble: false,
                    align: 'right'
                },
                {
                    display: 'Poin Kiri',
                    name: 'reward_condition_point_left',
                    sortAble: false,
                    align: 'center'
                },
                {
                    display: 'Poin Kanan',
                    name: 'reward_condition_point_right',
                    sortAble: false,
                    align: 'center'
                },
                {
                    display: 'Syarat Peringkat',
                    name: 'reward_condition_rank_name',
                    sortAble: false,
                    align: 'center'
                },
                {
                    display: 'Status',
                    name: 'reward_is_active',
                    sortAble: false,
                    align: 'center',
                    render: (params) => {
                        return params == '1' ? '<span class="badge badge-light-success badge-pill badge-round" title="Ya" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>' : '<span class="badge badge-light-danger badge-pill badge-round" title="Tidak" data-toggle="tooltip"><i class="bx bxs-bulb"></i></span>'
                    }
                },
                {
                    display: 'Ubah',
                    name: 'edit',
                    sortAble: false,
                    align: 'center',
                    action: {
                        function: 'updateReward',
                        icon: 'bx bx-edit-alt warning',
                        class: 'warning'
                    }
                },
            ],
            options: {
                limit: [10, 15, 20],
                currentLimit: 10,
            },
            search: true,
            searchTitle: 'Pencarian Data Reward',
            searchItems: [{
                    display: 'Nama Reward',
                    name: 'reward_title',
                    type: 'text'
                },
                {
                    display: 'Status Reward',
                    name: 'reward_is_active',
                    type: 'select',
                    option: [{
                            title: 'Aktif',
                            value: 1
                        },
                        {
                            title: 'Tidak Aktif',
                            value: 0
                        }
                    ]
                },
            ],

            sortName: "reward_condition_node_left",
            sortOrder: "asc",
            tableIsResponsive: true,
            select: true,
            multiSelect: true,
            buttonAction: [{
                    display: 'Tambah',
                    icon: 'bx bx-plus',
                    style: "info",
                    action: "addReward"
                },
                {
                    display: 'Aktifkan',
                    icon: 'bx bxs-bulb',
                    style: "success",
                    action: "active",
                    url: window.location.origin + "/admin/service/reward/activeReward/<?= $netType; ?>"
                },
                {
                    display: 'Non-Aktifkan',
                    icon: 'bx bxs-bulb',
                    style: "danger",
                    action: "nonactive",
                    url: window.location.origin + "/admin/service/reward/notActiveReward/<?= $netType; ?>"
                },
                {
                    display: 'Hapus',
                    icon: 'bx bx-trash',
                    style: "danger",
                    action: "remove",
                    url: window.location.origin + "/admin/service/reward/removeReward/<?= $netType; ?>",
                    message: 'Hapus'
                }
            ]
        })

        getSelectBank = () => {
            $.ajax({
                url: window.location.origin + '/admin/service/reward/getDataRank',
                method: 'GET',
                success: function(response) {
                    let data = response.data
                    if (response.status == 200) {
                        html = `<option value=''>PILIH</option>`
                        $.each(data, (key, val) => {
                            html += `<option value="${val.rank_id}">${val.rank_name}</option>`
                        })

                        $('#select_rank').html(html)
                    }
                },
            });
        }

        getSelectBank()
    })

    $('#formAddUpdateReward').on('submit', (e) => {
        e.preventDefault();
        $('#response-message').html('');
        $('#formAddUpdateReward button[type=submit]').prop('disabled', true)
        $('#formAddUpdateReward button[type=submit]').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memuat...')

        let formData = new FormData(e.target);
        formData.set('rewardBonusValue', $('#rewardBonusValue').val().replace(/\./g, ""));
        formData.set('rewardCondLeft', $('#rewardCondLeft').val().replace(/\./g, ""));
        formData.set('rewardCondRight', $('#rewardCondRight').val().replace(/\./g, ""));
        formData.set('rewardCondRank', $('#select_rank').val())

        let url = stateReward.baseUrl + stateReward.addUrl;
        if (stateReward.formAction == 'update') {
            formData.append('rewardId', stateReward.selectID);
            formData.append('oldImage', stateReward.selectedOldImage);
            url = stateReward.baseUrl + stateReward.updateUrl;
        }

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#formAddUpdateReward button[type=submit]').prop('disabled', false)
                $('#formAddUpdateReward button[type=submit]').html('Simpan')
                if (response.status == 200) {
                    $('#modalAddUpdateReward').modal('hide');
                    $('#response-messages').html(response.data.message);
                    $('#response-messages').addClass('alert alert-success');
                } else {
                    $('#modalAddUpdateReward').modal('hide');
                    $('#response-messages').html(response.data.message);
                    $('#response-messages').addClass('alert alert-danger');
                }
                setTimeout(function() {
                    $('#response-messages').html('');
                    $('#response-messages').removeClass();
                }, 2000);
                $.refreshTable('table-reward');
            },
            error: function(err) {
                $('#formAddUpdateReward button[type=submit]').prop('disabled', false)
                $('#formAddUpdateReward button[type=submit]').html('Simpan')
                let response = err.responseJSON
                $('#response-message').show()
                if (response.message == "validationError") {
                    let message = '<ul>';
                    for (let key in response.data.validationMessage) {
                        message += `<li>${response.data.validationMessage[key]}</li>`
                    }
                    message += '</ul>'
                    $('#response-message').html(`
                        <div class="alert border-danger alert-dismissible mb-2" role="alert">
                            <div class="d-flex align-items-center">
                                <span class="alert-position">
                                    ${message}
                                </span>
                            </div>
                        </div>
                    `);
                    setTimeout(function() {
                        $('#response-message').hide('blind', {}, 500)
                    }, 5000);
                } else if (response.message == 'Unauthorized' && response.status == 403) {
                    location.reload();
                }
            }
        });
    });

    let stateReward = {
        formAction: 'add',
        baseUrl: window.location.origin,
        addUrl: '/admin/service/reward/actAddReward/<?= $netType; ?>',
        updateUrl: '/admin/service/reward/actUpdateReward/<?= $netType; ?>',
        selectID: '',
        selectedOldImage: ''
    }

    function addReward() {
        $('#response-message').html('')
        $('#modalAddUpdateTitle').text('Form Tambah Data Reward')
        stateReward.selectID = '';
        stateReward.formAction = 'Add'
        $('#formAddUpdateReward').trigger('reset');
        $('#modalAddUpdateReward').modal('show')
        $('#previewImage').prop('src', '');
        $(".money").maskMoney('mask');
    }

    function updateReward(reward) {
        $('#response-message').html('');
        $('#modalAddUpdateTitle').text('Form Ubah Data Reward');
        stateReward.selectID = reward.reward_id;
        stateReward.selectedOldImage = reward.reward_image_filename;
        stateReward.formAction = 'update';

        let rewardVal = reward.reward_bonus_value;
        rewardVal = rewardVal.replace(/,/g, '');

        $('#formAddUpdateReward input[name=rewardTitle]').val(reward.reward_title);
        $('#formAddUpdateReward input[name=rewardBonusValue]').val(rewardVal);
        $('#formAddUpdateReward input[name=rewardCondLeft]').val(reward.reward_condition_point_left);
        $('#formAddUpdateReward input[name=rewardCondRight]').val(reward.reward_condition_point_right);
        $('#formAddUpdateReward select[name=admChargeType]').val(reward.reward_adm_charge_type);
        $('#formAddUpdateReward input[name=admCharge]').val(reward.reward_adm_charge);
        $('#select_rank').val(reward.reward_condition_rank_id)
        // $('#formAddUpdateReward input[name=rewardImageFilename]').val(reward.reward_image_filename);
        $('#previewImage').prop('src', reward.reward_image_file_url);
        $('#modalAddUpdateReward').modal('show');
        $(".money").maskMoney('mask');
    }

    function previewFiles() {
        let files = document.getElementById("upload-files").files[0];

        var reader = new FileReader();
        let temp_url = reader.readAsDataURL(files);
        reader.onload = function(e) {
            $('#previewImage').prop('src', e.target.result);
        };

    }
</script>