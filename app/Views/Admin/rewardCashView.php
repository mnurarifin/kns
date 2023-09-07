<style>
    .spinnerLoad {
        display: none;
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 99999;
        background: rgba(95, 82, 82, 0.58);
    }

    .center {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
</style>

<div class="spinnerLoad">
    <div class="center">
        <div class="spinner-border spinner-border-lg text-success" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>

<section id="app">
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
                        <div class="alert alert-success " v-show="alert.success.status" style="display: none;">
                            <span v-html="alert.success.content"></span>
                        </div>
                        <div id="table"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAddUpdateReward" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdateTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddUpdateTitle">{{data.title}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 470px;">
                    <div id="response-message"></div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nama Reward</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" v-model="form.reward_title" placeholder="Nama Reward">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nilai Reward (Rp)</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input id="reward_value" class="form-control money" placeholder="Nilai Bonus Reward (Rp)">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Syarat Poin</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input id="reward_condition_point" class="form-control money" placeholder="Syarat Poin">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Poin Trip</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input id="reward_trip_point" class="form-control money" placeholder="Poin Trip">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Gambar Reward</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <img src="<?= BASEURL ?>/app-assets/images/icon/cup.png" style="width:60px; height:auto" id="previewImage">
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
                    <button onclick="app.save()" class="btn btn-success" :disabled="button.formBtn.disabled" id="submitModal">
                        <div class="d-flex align-center">{{ data.btnTitle }}</div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

<script>
    let app =
        Vue.createApp({
            data: function() {
                return {
                    button: {
                        formBtn: {
                            disabled: false
                        }
                    },
                    data: {
                        title: "",
                        btnTitle: "",
                        btnAction: "",
                    },
                    form: {
                        reward_id: '',
                        reward_title: '',
                        reward_image_filename: '',
                        reward_condition_point: '',
                        reward_trip_point: '',
                        reward_value: ''
                    },
                    alert: {
                        success: {
                            status: false,
                            content: '',
                        },
                        danger: {
                            status: false,
                            content: '',
                        }
                    },
                }
            },
            methods: {
                hideLoading() {
                    $("#pageLoader").hide();
                },
                generateTable() {
                    $("#table").dataTableLib({
                        url: window.location.origin + '/admin/service/reward/getDataReward/',
                        selectID: 'reward_id',
                        colModel: [{
                                display: 'Aksi',
                                name: 'reward_id',
                                sortAble: false,
                                align: 'center',
                                width: "50px",
                                render: (params, args) => {
                                    return `<span class="cstmHover px-25" onclick='app.update(${JSON.stringify(args)})' title="Ubah" data-toggle="tooltip"><i class="bx bx-edit-alt warning"></i></span>`;
                                }
                            }, {
                                display: 'Gambar',
                                name: 'reward_image_file_url',
                                sortAble: false,
                                align: 'center',
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
                                name: 'reward_value_formatted',
                                sortAble: false,
                                align: 'right'
                            },
                            {
                                display: 'Syarat Poin',
                                name: 'reward_condition_point',
                                sortAble: false,
                                align: 'center'
                            },
                            {
                                display: 'Poin Trip',
                                name: 'reward_trip_point',
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
                        ],
                        options: {
                            limit: [10, 25, 50, 100],
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
                                action: "add"
                            },
                            {
                                display: 'Aktifkan',
                                icon: 'bx bxs-bulb',
                                style: "success",
                                action: "active",
                                url: window.location.origin + "/admin/service/reward/activeReward/sys"
                            },
                            {
                                display: 'Non-Aktifkan',
                                icon: 'bx bxs-bulb',
                                style: "danger",
                                action: "nonactive",
                                url: window.location.origin + "/admin/service/reward/notActiveReward/sys"
                            },
                            {
                                display: 'Hapus',
                                icon: 'bx bx-trash',
                                style: "danger",
                                action: "remove",
                                url: window.location.origin + "/admin/service/reward/removeReward/sys",
                                message: 'Hapus'
                            }
                        ]
                    })
                },
                save() {
                    $('#submitModal').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memproses...')
                    let actionUrl = this.data.btnAction == 'insert' ? window.location.origin +
                        '/admin/service/reward/add' : window.location.origin + '/admin/service/reward/edit'

                    let formData = new FormData();

                    formData.set('reward_value', $('#reward_value').val().replace(/\./g, ""));
                    formData.set('reward_condition_point', $('#reward_condition_point').val().replace(/\./g, ""));
                    formData.set('reward_trip_point', $('#reward_trip_point').val().replace(/\./g, ""));
                    formData.set('reward_image_filename', document.getElementById("upload-files").files[0]);
                    formData.set('reward_title', this.form.reward_title);

                    if (this.data.btnAction != 'insert') {
                        formData.set('reward_id', this.form.reward_id);
                    }

                    $.ajax({
                        url: actionUrl,
                        method: 'POST',
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function(response) {
                            if (response.status == 200) {
                                $('#submitModal').html('<div class="d-flex align-center">Ubah</div>')
                                if (app.data.btnAction == 'insert') {
                                    $('#submitModal').html('<div class="d-flex align-center">Tambah</div>')
                                    let data = response.data.results;
                                    app.form = {
                                        reward_id: '',
                                        reward_title: '',
                                        reward_image_filename: '',
                                        reward_condition_point: '',
                                        reward_trip_point: '',
                                        reward_value: ''
                                    };
                                }
                                app.alert.success.content = response.message;
                                app.alert.success.status = true;

                                $('#modalAddUpdateReward').modal('hide');

                                setTimeout(() => {
                                    app.alert.success.status = false;
                                }, 5000);
                                $.refreshTable('table');
                            }
                        },
                        error: function(res) {
                            if (app.data.btnAction == 'insert') {
                                $('#submitModal').html('<div class="d-flex align-center">Tambah</div>')
                            } else {
                                $('#submitModal').html('<div class="d-flex align-center">Ubah</div>')
                            }
                            let response = res.responseJSON;
                            if (response.status == 400 && response.message == "validationError") {
                                let resValidation = Object.values(response.data.validationMessage);

                                if (resValidation.length > 0) {
                                    app.alert.danger.content = `<ul>`;
                                    resValidation.forEach((data) => {
                                        app.alert.danger.content +=
                                            `<li> ${data} </li>`;
                                    });
                                    app.alert.danger.content += `</ul>`;
                                    app.alert.danger.status = true;

                                    setTimeout(() => {
                                        app.alert.danger.status = false;
                                    }, 5000);
                                }

                            }
                        },

                    })
                },

                update(data) {
                    $('#reward_value').val(data.reward_value)
                    $('#reward_condition_point').val(data.reward_condition_point)
                    $('#reward_trip_point').val(data.reward_trip_point)
                    $('#previewImage').prop('src', data.reward_image_file_url);
                    app.form = data

                    this.data.title = "Ubah Data Reward";
                    this.data.btnTitle = "Ubah";
                    this.data.btnAction = "update";
                    $('#submitModal').html('<div class="d-flex align-center">Ubah</div>')
                    this.openModal();
                },

                openModal() {
                    $('#modalAddUpdateReward').modal();
                },
                add() {
                    $('#submitModal').html('<div class="d-flex align-center">Tambah</div>')
                    $('#upload-files').val('');
                    $('#previewImage').prop('src', `<?= BASEURL ?>/app-assets/images/icon/cup.png`);
                    $('#reward_value').val('')
                    $('#reward_condition_point').val('')
                    $('#reward_trip_point').val('')

                    this.data.title = "Tambah Reward";
                    this.data.btnTitle = "Tambah";
                    this.data.btnAction = "insert";

                    app.form = {
                        reward_id: '',
                        reward_title: '',
                        reward_image_filename: '',
                        reward_condition_point: '',
                        reward_trip_point: '',
                        reward_value: ''
                    };

                    this.openModal();
                }
            },
        }).mount("#app");

    function add() {
        app.add();
    }


    $(document).ready(function() {
        app.hideLoading();
        app.generateTable();

        $('.money').maskMoney({
            thousands: '.',
            decimal: '.',
            allowZero: true,
            prefix: '',
            affixesStay: true,
            allowNegative: false,
            precision: 0
        });
    });

    function previewFiles() {
        let files = document.getElementById("upload-files").files[0];

        var reader = new FileReader();
        let temp_url = reader.readAsDataURL(files);
        reader.onload = function(e) {
            $('#previewImage').prop('src', e.target.result);
        };

    }
</script>