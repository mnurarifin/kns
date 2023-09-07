<style>
    .cstmColor {
        color: #7e00b3;
    }

    .bgImage {
        top: 0;
        border-radius: 12px;
        z-index: 1;
        width: 100%;
        position: absolute;
        opacity: 0.4;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        height: 100%;
        left: 0;
    }
</style>
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay">
    </div>
    <div class="content-loading">
        <i class="bx bx-loader bx-spin"></i>
    </div>
    <div class="content-wrapper">
        <div class="alert alert-danger" role="alert" id="alert" style="position: absolute; top: 84px; right: 32px; display: none;"></div>
        <div class="alert alert-success" role="alert" id="alert-success" style="position: absolute; top: 84px; right: 32px; display: none;"></div>

        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0"><?= $title ?></h5>

                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="<?= BASEURL ?>/member/dashboard"><i class="bx bx-home-alt"></i></a></li>
                                <li class="breadcrumb-item"><a href="#"><?= $breadcrumbs[0] ?></a></li>
                                <li class="breadcrumb-item active"><?= $breadcrumbs[1] ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row" id="step_1">
                <div class="col-md-12 col-12">
                    <div class="row" id="summary">
                        <div class="col col-12 col-md-12 mb-1">
                            <div class="card p-1">
                                <div class="row">
                                    <div class="col col-6 d-flex flex-column">
                                        <div class="m-0">Total poin : <h1><b class="cstmColor" id="total_point_trip">0</b></h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-12">
                    <div class="row" id="reward_list">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAddUpdate" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdateTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddUpdateTitle">Data Diri</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body" style="max-height: 470px;">
                <div id="response-message"></div>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Nama Lengkap</label>
                        </div>
                        <div class="col-md-8 form-group">
                            <input type="text" class="form-control" placeholder="Nama Lengkap" id="name">
                            <small class="text-danger alertMsg name"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>No Hp</label>
                        </div>
                        <div class="col-md-8 form-group">
                            <input type="text" id="mobilephone" class="form-control" placeholder="No Hp">
                            <small class="text-danger alertMsg mobilephone"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>NIK</label>
                        </div>
                        <div class="col-md-8 form-group">
                            <input type="text" id="identity_no" class="form-control" placeholder="NIK">
                            <small class="text-danger alertMsg identity_no"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <span class="">Tutup</span>
                </button>
                <button onclick="claimReward()" class="btn btn-success" id="submitModal">
                    <div class="d-flex align-center">Claim</div>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        let reward_id = {}
        let reward_data = []
        let point_data = []
        let product_detail = []
        let transaction_total_weight = 0
        let transaction_total_point = 0
        let select_reward = 0

        let OTP = <?php echo session()->has("otp") && session("otp") ? 'true' : 'false' ?>

        getReward = () => {
            $.ajax({
                url: "<?= BASEURL ?>/member/reward/get-reward-trip",
                type: "GET",
                data: {
                    type: "repeatorder"
                },
                success: (res) => {
                    reward_data = res.data.results
                    point_data = res.data.point
                    $("#reward_list").empty()
                    $.each(reward_data, (i, val) => {
                        let is_qualified = point_data.point_trip >= val.reward_condition_point
                        let text = is_qualified ? "Telah memenuhi syarat" : "Belum memenuhi syarat"
                        let btn = is_qualified ? "btn-success btn-login glow" : "btn-secondary"
                        $("#reward_list").append(`
                        <div class="col col-12 col-md-4 mb-1">
                            <div class="card card-bordered border select_reward p-1 mb-1" data-id="${val.reward_id}" style="background-color:#220130;">
                            <span style="background-image: url('${val.reward_image_filename}');" class="bgImage"></span>
    <div style="z-index: 10; color:white;">
    <p style="font-weight: bold;">${val.reward_title}</p>
                                <p style="">Durasi Trip : ${val.reward_duration}</p>
                                <p style="">Uang Saku : ${formatCurrency(val.reward_value)}</p>
                                <small>Syarat Poin : <span>${formatDecimal(parseInt(val.reward_condition_point))}</span></small> <br>
                                <button class="btn ${btn} mt-1" id="select_reward_${val.reward_id}" style="" ${is_qualified ? `` : `disabled`}>${text}</button></div>
                                
                            </div>
                        </div>
                        `)
                    })
                    $("#total_point_trip").html(formatDecimal(parseInt(point_data.point_trip)))
                },
            })
        }

        $("body").on("click", ".select_reward", (ev) => {
            select_reward = $(ev.target).closest(".select_reward").data("id")
            $('#modalAddUpdate').modal()
        })

        claimReward = () => {
            $('.alertMsg').html('')
            let data = {
                reward_id: select_reward,
                name: $('#name').val(),
                mobilephone: $('#mobilephone').val(),
                identity_no: $('#identity_no').val()
            }

            $.ajax({
                url: "<?= BASEURL ?>/member/reward/claim-trip",
                type: "POST",
                data: data,
                success: (res) => {
                    $('#modalAddUpdate').modal('hide')
                    data = res.data.results
                    $(".alert-input").hide()
                    $("#alert-success").html(res.message)
                    $("#alert-success").show()
                    setTimeout(function() {
                        $("#alert-success").hide()
                    }, 3000)
                    getReward()
                },
                error: (err) => {
                    res = err.responseJSON
                    $(".alert-input").hide()
                    $("#alert").html(res.message)
                    $("#alert").show()
                    setTimeout(function() {
                        $("#alert").hide()
                    }, 3000);
                    if (res.error == "validation") {
                        $.each(res.data, (i, val) => {
                            $(`.${i}`).html(val).show()
                        })
                    }
                },
            })
        }

        formatCurrency = ($params) => {
            let formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            })
            return formatter.format($params)
        }

        formatDecimal = ($params) => {
            let formatter = new Intl.NumberFormat('id-ID', {
                style: 'decimal',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            })
            return formatter.format($params)
        }

        getReward()
    })
</script>
<!-- END: Content-->