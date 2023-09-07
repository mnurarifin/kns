<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-overlay">
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
                <li class="breadcrumb-item"><a href="<?= BASEURL ?>/admin/dashboard"><i class="bx bx-home-alt"></i></a></li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="content-body">
      <div class="row">
        <div class="col-lg-6 col-md-12">
          <div class="card card-profile" style="height: 325px;">
            <div class="card-header d-flex justify-content-between pb-0">
              <label>Profil</label>
              <a href="<?= base_url() ?>/member/profile/show" class="btn btn-sm btn-primary">Lihat Detail</a>
            </div>
            <div class="card-content">
              <div class="card-body pb-1">
                <div class="row">
                  <div class="col-md-3 d-flex justify-content-center">
                    <div class="avatar bg-rgba-warning m-0" style="cursor: unset !important; height: fit-content;">
                      <div class="">
                        <img class="round" src="" alt="avatar" height="100" width="100" id="profile_image">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="font-size-14 font-weight-500 text-uppercase" id="text_member_name"></div>
                    <div class="font-size-12 text-uppercase" id="text_member_account_username"></div>
                    <div class="row">
                      <div class="pt-0 pt-md-1 col-sm-12 col-md-6 col-12 pb-10">
                        <div class="font-size-10 cl-grey">Nomor HP</div>
                        <div class="font-size-11 cl-grey font-weight-500" id="text_member_mobilephone"></div>
                      </div>
                      <div class="pt-0 pt-md-1 col-sm-12 col-md-6 col-12 pb-10">
                        <div class="font-size-10 cl-grey">Tanggal Gabung</div>
                        <div class="font-size-11 cl-grey font-weight-500" id="text_member_join_datetime_formatted"></div>
                      </div>
                      <div class="pt-0 pt-md-1 col-sm-12 col-md-6 col-12 pb-10">
                        <div class="font-size-10 cl-grey">Sponsor</div>
                        <div class="font-size-11 cl-grey font-weight-500" id="text_sponsor_member_account_username"></div>
                      </div>
                    </div>
                  </div>

                </div>
                <form class="form form-horizontal d-none" id="rank">
                  <div class="justify-content-between p-1 border-bottom" id="rank_name">
                    <span style="font-weight:400; color: #475F7B; font-size: 1.3rem;">Peringkat Saat ini : </span>
                  </div>
                  <div class="row">

                    <div class="col-sm-12">
                      <div class="card">
                        <div class="card-content">
                          <div class="card-body py-0">
                            <div class="d-flex activity-content pt-1">
                              <div class="avatar bg-rgba-success m-0 mr-75" style="height: 32px;">
                                <div class="avatar-content">
                                  <i class="bx bx-shield text-success"></i>
                                </div>
                              </div>
                              <div class="activity-progress flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                  <div class="sales-item-name d-inline-block mb-25">
                                    <p class="mb-0" id="next_rank"></p>
                                  </div>
                                  <div class="sales-item-amount d-inline-block">
                                    <h6 class="mb-0"><span class="text-success" id="poin_bv"></span></h6>
                                  </div>
                                </div>
                                <div class="progress progress-bar-success progress-sm">
                                  <div id="prog-bv" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="card" style="height: calc(120px - (1.75rem / 2));">
            <div class="card-header pb-0">
              <label>Link Refferal</label>
            </div>
            <div class="card-body pt-0 d-flex align-items-center">
              <small id="url_referral"><?= BASEURL ?>/ref/<?= session("member")["network_slug"] ?></small><i class="bx bx-copy-alt mx-1" style="cursor: pointer;" id="copy"></i><small class="text-success" id="copy_notif" style="display: none">Disalin.</small>
            </div>
          </div>
        </div>

        <div class="col-lg-6 col-md-12">
          <div class="row">

            <div class="col col-12 d-none">
              <div class="card" style="height: calc(120px - (1.75rem / 2));">
                <div class="card-header pb-0">
                  <label>Link Refferal</label>
                </div>
                <div class="card-body pt-0 d-flex align-items-center">
                  <small id="url_referral"><?= BASEURL ?>/registration?sponsor=<?= session("member")["network_slug"] ?></small><i class="bx bx-copy-alt mx-1" style="cursor: pointer;" id="copy"></i><small class="text-success" id="copy_notif" style="display: none">Dikopi</small>
                </div>
              </div>
            </div>

            <div class="col col-12">
              <div id="member_level_card" class="card">
                <div class="card-header pb-0">
                  <label>Peringkat Anda Saat Ini</label>
                </div>
                <div class="card-body pt-0 d-flex align-items-center card-frame">
                  <div class="card card-level">
                    <div class="bg-card-level"></div>
                    <div class="card-body px-2 pt-2 pb-0 d-flex flex-column" style="flex:none;">
                      <h1 class="mb-0">CERTIFICATE</h1>
                      <h5 class="mb-1">OF APPRECIATION</h5>
                    </div>

                    <div class="card-body px-2 pb-2 pt-0 flex-row align-items-center certificate-frame">
                      <div class="badge-level py-1">
                        <div class="badge-icon"></div>
                        <div class="badge-img"><img src="" alt="member-img"></div>
                      </div>
                      <div class="desc-level pb-2 pl-2">
                        <p>Selamat Kepada</p>
                        <h4 class="member-name">-</h4>
                        <p class="mb-50">Total Bonus Anda Sebesar</p>
                        <h3 class="member-bonus">Rp 0</h3>
                        <div class="divider"></div>
                        <p class="company-name">PT. Kimstella Network Sejahtera</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12 col-sm-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between pb-0">
              <label>Mitra Baru Menunggu Placement</label>
            </div>
            <div class="card-content">
              <div class="card-body pb-1">
                <div class="row">
                  <div class="col-md-12">
                    <div class="mb-1" id="table">
                    </div>
                    <div class="card p-2" id="data_kosong">
                      <div class="row">
                        <div class="col-md-12 d-flex justify-content-center">
                          <img src="<?= BASEURL ?>/app-assets/images/no-data-green.svg" alt="" style=" filter: grayscale(100%);">
                        </div>
                        <div class="col-md-12 d-flex justify-content-center mt-3">
                          <label>Tidak ada informasi yang ditampilkan</label>
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
</div>

<script>
  $(function() {
    $("#data_kosong").hide()

    let data

    getWaitPlacement = () => {
      $("#table").dataTableLib({
        url: window.location.origin + "/member/network/get-wait-placement/",
        selectID: "bonus_log_id",
        colModel: [{
            display: 'Nama',
            name: 'member_name',
            align: 'left',
          },
          {
            display: 'Kode',
            name: 'member_account_username',
            align: 'left',
          },
          {
            display: 'Tanggal',
            name: 'member_join_datetime_formatted',
            align: 'right',
          },
          {
            display: 'Sisa Waktu',
            name: 'member_join_datetime_formatted',
            align: 'right',
            render: (params, data) => {
              countdown(params, `countdown-${data.member_id}`)
              return `<span id="countdown-${data.member_id}"></span>`
            }
          },
          {
            display: 'Placement',
            name: 'member_registration_id',
            align: 'center',
            render: (params, data) => {
              let countdown = Date.parse(data.member_join_datetime)
              return `<button class="btn text-info" onclick="location.href = '/member/network/genealogy?member_registration_id=${params}&member_name=${data.member_name.replace(/['"]/g, "\\'")}&countdown=${countdown}';">
              <i class="bx bxs-user-plus"></i>
              </button>`
            },
          },
        ],
        options: {
          limit: [10, 15, 20, 50, 100],
          currentLimit: 10,
        },
        search: false,
        searchTitle: "Pencarian",
        searchItems: [],
        sortName: "member_join_datetime",
        sortOrder: "DESC",
        tableIsResponsive: true,
        select: false,
        multiSelect: false,
        buttonAction: [],
        success: (res) => {
          data = res.data.results
        }
      })
    }

    getCertificateData = () => {
      $.ajax({
        url: "<?= BASEURL ?>/member/dashboard/get_data_certificate",
        type: "GET",
        async: false,
        success: (res) => {
          data = res.data.results
          let badge = data.rank.toLowerCase().replaceAll(' ', '');
          $('.card-level .badge-icon').addClass('lv-' + badge)
          $('.card-level .badge-img img').prop("src", data.member_image)
          $('.card-level .member-name').html(data.member_name.toLowerCase())
          $('.card-level .member-bonus').html(data.total_bonus)

          setTimeout(() => {
            $('.card-level .certificate-frame').addClass('d-flex')
          }, 500);
        },
      })
    }

    getProfile = () => {
      $.ajax({
        url: "<?= BASEURL ?>/member/dashboard/get-profile",
        type: "GET",
        async: false,
        success: (res) => {
          data = res.data.results
          $("#text_member_name").html(data.member_name)
          $("#member_name").text(data.member_name + ',')
          $("#text_member_account_username").html(`<b>${data.member_account_username}</b>`)
          $("#text_member_mobilephone").html(`<b>${data.member_mobilephone}</b>`)
          $("#text_sponsor_member_account_username").html(`<b>${data.sponsor_member_account_username}</b>`)
          $("#text_member_join_datetime_formatted").html(`<b>${data.member_join_datetime_formatted}</b>`)
          $("#profile_image").prop("src", data.member_image)
        },
      })
    }

    $("#copy").on("click", (ev) => {
      var inp = document.createElement('input')
      document.body.appendChild(inp)
      inp.value = $("#url_referral").html()
      inp.select()
      document.execCommand('copy', false)
      inp.remove()
      $("#copy_notif").show()
      setTimeout(function() {
        $("#copy_notif").hide()
      }, 1000)
    })

    countdown = (date, id) => {
      // Parse the input date to ensure it's a valid date
      const targetDate = new Date(date);

      if (isNaN(targetDate.getTime())) {
        console.error("Invalid date format");
        return;
      }

      var x = setInterval(function() {
        var now = new Date().getTime();
        var distance = targetDate - now;

        if (distance <= 0) {
          // If the countdown has ended, clear the interval and display a message
          clearInterval(x);
          $(`#${id}`).html("Countdown has ended!");
        } else {
          // Calculate minutes and seconds remaining
          var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((distance % (1000 * 60)) / 1000);

          $(`#${id}`).html(`${minutes} Menit ${seconds} Detik`);
        }
      }, 1000);
    }

    getProfile()
    getCertificateData()
    getWaitPlacement()
  })
</script>
<!-- END: Content-->