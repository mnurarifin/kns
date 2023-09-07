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
      <div class="row">
        <div class="col-md-12 col-12">
          <div class="accordion mb-2" id="mitradetail">
            <div class="card" style="border-radius: 0.8rem!important;">
              <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                  <button class="btn btn-link btn-block text-left px-0 py-25 text-primary collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <div class="label-header label-collapsed">
                      <div class="d-flex flex-row align-items-center"><i class="ficon bx bx-user-circle mr-50" style="top: unset;"></i> Info Detail Mitra</div>

                      <div class="btn btn-light btn-collapse">
                        <i class="ficon bx bx-chevron-up bx-md mr-0"></i>
                      </div>
                    </div>

                    <div class="label-header label-open">
                      <div class="d-flex flex-row align-items-center">
                        <i class="ficon bx bx-user-circle bx-lg mr-50" style="top: unset;"></i>
                        <div class="d-flex flex-column">
                          <div class="d-flex flex-row align-items-center">
                            <div id="text_member_name_label" class="mr-50 font-weight-bold"></div> (<span id="text_username_label"></span>)
                          </div>
                          <div class="text-muted"><span id="text_network_rank_name_label"></span></div>
                        </div>
                      </div>

                      <div class="btn btn-light btn-collapse">
                        <i class="ficon bx bx-chevron-down bx-md mr-0"></i>
                      </div>
                    </div>
                  </button>
                </h2>
              </div>

              <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#mitradetail">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4 col-12 mb-75">
                      <p class="card-text mb-50 d-flex align-items-center"><i class="ficon bx bx-user my-auto mr-50"></i>Data Mitra</p>
                      <div class="d-flex card-text font-weight-bold secondary">
                        <div style="padding-left: 25px;">
                          <div class="d-flex flex-row align-items-center">
                            <div id="text_member_name" class="mr-50"></div> (<span id="text_member_account_username"></span>)
                          </div>
                          <div><span id="text_network_rank_name"></span></div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 col-12 mb-75">
                      <p class="card-text mb-50 d-flex align-items-center"><i class="ficon bx bx-phone my-auto mr-50"></i>Kontak</p>
                      <div class="d-flex card-text font-weight-bold secondary">
                        <div style="padding-left: 25px;">
                          <div id="text_member_mobilephone"></div>
                          <div id="text_member_email"></div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 col-12 mb-75">
                      <p class="card-text mb-50 d-flex align-items-center"><i class="ficon bx bx-credit-card my-auto mr-50"></i>Akun Bank</p>
                      <div class="d-flex card-text font-weight-bold secondary">
                        <div style="padding-left: 25px;">
                          <div id="text_member_bank_name"></div>
                          <div class="d-flex flex-row align-items-center">
                            <div id="text_member_bank_account_name" class="mr-25"></div> /
                            <div id="text_member_bank_account_no" class="ml-25"></div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4 col-12 mb-25">
                      <p class="card-text mb-50 d-flex align-items-center"><i class="ficon bx bx-calendar my-auto mr-50"></i>Tanggal Gabung</p>
                      <div class="d-flex card-text font-weight-bold secondary">
                        <span style="padding-left: 25px;" id="text_member_join_datetime_formatted"></span>
                      </div>
                    </div>
                    <div class="col-md-4 col-12 mb-25">
                      <p class="card-text mb-50 d-flex align-items-center"><i class="ficon bx bx-group my-auto mr-50"></i>Sponsor</p>
                      <div class="d-flex card-text font-weight-bold secondary">
                        <span style="padding-left: 25px;" id="text_sponsor_network_code"></span>
                      </div>
                    </div>
                    <div class="col-md-4 col-12 mb-25">
                      <p class="card-text mb-50 d-flex align-items-center"><i class="ficon bx bx-user-plus my-auto mr-50"></i>Upline</p>
                      <div class="d-flex card-text font-weight-bold secondary">
                        <span style="padding-left: 25px;" id="text_upline_network_code"></span>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-0 mb-2">
        <div class="col-md-12 col-12 mb-2">
          <!-- <h5 class="mb-0 dark font-weight-bold">Summary Komisi</h5> -->
          <div class="row ml-0">
            <div class="pl-0 col-12 col-md-4 col-lg-4">
              <div class="card bg-info bg-light mb-0 mt-1">
                <div class="card-body p-1">
                  <h5 class="mb-50 dark font-weight-bold" id="text_total_bonus">Rp 0</h5>
                  <p class="card-text mb-0 dark pb-0" style="padding-bottom: 5px;">Total Komisi</p>
                </div>
              </div>
            </div>
            <div class="pl-0 col-12 col-md-4 col-lg-4">
              <div class="card bg-info bg-light mb-0 mt-1">
                <div class="card-body p-1">
                  <h5 class="mb-50 dark font-weight-bold" id="text_total_reward">Rp 0</h5>
                  <p class="card-text mb-0 dark pb-0" style="padding-bottom: 5px;">Total Cash Reward</p>
                </div>
              </div>
            </div>
            <div class="pl-0 col-12 col-md-4 col-lg-4">
              <div class="card mb-0 mt-1" style="background-color: #cbb2e6;">
                <div class="card-body p-1">
                  <h5 class="mb-50 dark font-weight-bold" id="text_total_bonus_acc">Rp 0</h5>
                  <p class="card-text mb-0 dark pb-0" style="padding-bottom: 5px;">Total Diperoleh</p>
                </div>
              </div>
            </div>
            <div class="pl-0 col-12 col-md-4 col-lg-4">
              <div class="card bg-warning bg-light mb-0 mt-1">
                <div class="card-body p-1">
                  <h5 class="mb-50 font-weight-bold" id="text_total_bonus_paid">Rp 0</h5>
                  <p class="card-text mb-0 dark pb-0" style="padding-bottom: 5px;">Total Dibayarkan</p>
                </div>
              </div>
            </div>
            <div class="pl-0 col-12 col-md-4 col-lg-4">
              <div class="card bg-success bg-light mb-0 mt-1">
                <div class="card-body p-1">
                  <h5 class="mb-50 font-weight-bold" id="text_total_bonus_balance">Rp 0</h5>
                  <p class="card-text mb-0 dark pb-0" style="padding-bottom: 5px;">Komisi Menunggu
                  </p>
                </div>
              </div>
            </div>
            <div class="pl-0 col-12 col-md-4 col-lg-4">
              <div class="card bg-danger bg-light mb-0 mt-1">
                <div class="card-body p-1">
                  <h5 class="mb-50 font-weight-bold" id="text_total_bonus_limit">Rp 0</h5>
                  <p class="card-text mb-0 dark pb-0" style="padding-bottom: 5px;">Batas Transfer Komisi
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-12">
          <div class="card">
            <div class="card-body p-2">
              <div class="row">
                <div class="col-12">
                  <h6 class="mb-0 dark text-primary font-weight-bold mb-1">Potensi Komisi Hari Ini (<?= convertDate(date('Y-m-d'), 'id') ?>)</h6>
                  <div class="row" id="bonus_potency">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-12">
          <div class="card">
            <div class="card-body p-2">
              <div class="row">
                <div class="col-12">
                  <h6 class="mb-0 dark text-black font-weight-bold mb-1">Rincian Komisi yang Diperoleh</h6>
                  <div class="row" id="bonus_detail">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12 col-12">
          <div class="row">
            <div class="col-md-12 col-12">
              <div class="mb-1" id="table">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade text-left" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="myModalLabel1"></h3>
        <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
          <i class="bx bx-x"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="row mb-50 align-center">
          <div class="col-12 d-flex flex-row align-center p-0">
            <table class="table w-100" id="table-detail">
              <thead>
                <tr>
                  <th class="text-center">No</th>
                  <th class="text-center">Waktu</th>
                  <th class="text-center">Keterangan</th>
                  <th class="text-center">Bonus</th>
                </tr>
              </thead>
              <tbody>
                <tr id="total">
                  <th class="text-right" colspan="3">Total</th>
                  <th class="text-right"><span id="total_bonus">0</span></th>
                </tr>
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
    </div>
  </div>
</div>
<!-- End Modal -->

<script>
  let expand = (id) => {
    $('#detail_children_' + id).removeClass('d-none').addClass('d-flex')
    $('#expand_' + id).removeClass('d-none').addClass('d-flex')
    $('#shrink_' + id).removeClass('d-flex').addClass('d-none')
  }

  let shrink = (id) => {
    $('#detail_children_' + id).addClass('d-none').removeClass('d-flex')
    $('#expand_' + id).addClass('d-none').removeClass('d-flex')
    $('#shrink_' + id).addClass('d-flex').removeClass('d-none')
  }

  $(function() {
    let data

    getMemberDetail = () => {
      $.ajax({
        url: "<?= BASEURL ?>/member/bonus/get-member-detail",
        type: "GET",
        success: (res) => {
          data = res.data.results
          $("#text_member_name").html(data.member_name)
          $("#text_member_account_username").html(data.member_account_username)
          $("#text_member_name_label").html(data.member_name)
          $("#text_username_label").html(data.member_account_username)
          $("#text_network_rank_name").html(data.network_rank_name)
          $("#text_network_rank_name_label").html(data.network_rank_name)
          $("#text_member_mobilephone").html(data.member_mobilephone)
          $("#text_member_email").html(data.member_email)
          $("#text_member_join_datetime_formatted").html(data.member_join_datetime_formatted)
          $("#text_sponsor_network_code").html(data.sponsor_network_code)
          $("#text_upline_network_code").html(data.upline_network_code)
          $("#text_member_bank_name").html(data.member_bank_name)
          $("#text_member_bank_account_name").html(data.member_bank_account_name)
          $("#text_member_bank_account_no").html(data.member_bank_account_no)
        },
        error: (err) => {
          res = err.responseJSON
          $("#alert").html(res.message)
          $("#alert").show()
          setTimeout(function() {
            $("#alert").hide()
          }, 3000);
          if (res.error == "validation") {
            $.each(res.data, (i, val) => {
              $(`#alert_input_${i}`).html(val).show()
            })
          }
        },
      })
    }

    getBonus = () => {
      $.ajax({
        url: "<?= BASEURL ?>/member/bonus/get-bonus",
        type: "GET",
        success: (res) => {
          data = res.data.results
          $("#text_total_bonus").html(formatCurrency(data.summary.bonus))
          $("#text_total_reward").html(formatCurrency(data.summary.reward))
          $("#text_total_bonus_acc").html(formatCurrency(data.summary.acc))
          $("#text_total_bonus_paid").html(formatCurrency(data.summary.paid))
          $("#text_total_bonus_balance").html(formatCurrency(data.summary.balance))
          $("#text_total_bonus_limit").html(formatCurrency(data.summary.limit))
          $.each(data.detail, (i, val) => {
            $("#bonus_detail").append(`
            <div class="col-12 col-sm-12 col-md-6">
              <div class="card border mb-1">
                <div class="card-body p-1">
                  <p class="card-text font-size-16 secondary font-weight-bold">${i}</p>
                  <h6 class="mb-0 warning font-weight-bold">${formatCurrency(val)}</h6>
                </div>
              </div>
            </div>
            `)
          })
          $.each(data.potency, (i, val) => {
            let detail = ``
            if (val.hasOwnProperty("detail")) {
              detail = `
              <button class="btn btn-primary btn-detail-potency" data-detail="${val.detail}" style="position: absolute; right: 1rem; bottom: 1rem; padding: 0px; border-radius: 50%; width: 2rem; height: 2rem;">
                <i class='bx bx-list-ul'></i>
              </button>
              `
            }
            $("#bonus_potency").append(`
            <div class="col-12 col-sm-12 col-md-6">
              <div class="card border mb-1">
                <div class="card-body p-1">
                  <p class="card-text font-size-16 secondary font-weight-bold">${i}</p>
                  <h6 class="mb-0 primary font-weight-bold">${formatCurrency(val.value)}</h6>
                  ${detail}
                </div>
              </div>
            </div>
            `)
          })
          $.each(data.children, (i, val) => {
            $("#bonus_children").append(`
            <div class="col-12 col-sm-12 col-md-12">
              <div class="card border mb-1">
                <div class="card-body px-2 pt-1 pb-0 row d-flex align-items-center">
                  <p class="col-md-2 col-sm-12 card-text font-size-16 secondary font-weight-bold"><b>${val.network_code}</b></p>
                </div>
                <div class="card-body px-2 pt-0 pb-1 row d-flex align-items-center">
                  <p class="col-md-2 col-sm-12 py-1 mb-0">Sponsor<br/><span class="mb-0 primary font-weight-bold">${formatCurrency(val.bonus_sponsor_acc)}</span></p>
                  <p class="col-md-2 col-sm-12 py-1 mb-0">Generasi<br/><span class="mb-0 primary font-weight-bold">${formatCurrency(val.bonus_gen_node_acc)}</span></p>
                  <p class="col-md-2 col-sm-12 py-1 mb-0">Power Leg<br/><span class="mb-0 primary font-weight-bold">${formatCurrency(val.bonus_power_leg_acc)}</span></p>
                  <p class="col-md-2 col-sm-12 py-1 mb-0">Matching Leg<br/><span class="mb-0 primary font-weight-bold">${formatCurrency(val.bonus_matching_leg_acc)}</span></p>
                  <p class="col-md-2 col-sm-12 py-1 mb-0">Cash Reward<br/><span class="mb-0 primary font-weight-bold">${formatCurrency(val.bonus_cash_reward_acc)}</span></p>
                  <p class="col-md-2 col-sm-12 py-1 mb-0" style="background-color: #6f2abb !important; color: white; border-radius: 8px;">Total<br/><span class="mb-0 white font-weight-bold">${formatCurrency(val.bonus_total_acc)}</span></p>
                </div>
              </div>
            </div>
            `)
          })
        },
        error: (err) => {
          let res = err.responseJSON
          $("#alert").html(res.message)
          $("#alert").show()
          setTimeout(function() {
            $("#alert").hide()
          }, 3000);
          if (res.error == "validation") {
            $.each(res.data, (i, val) => {
              $(`#alert_input_${i}`).html(val).show()
            })
          }
        },
      })
    }

    $("body").on("click", ".btn-detail-potency", (ev) => {
      $(".detail").remove()
      let detail = $(ev.target).closest("button").data("detail")
      $.ajax({
        url: `<?= BASEURL ?>/member/bonus/get-member-bonus-detail-${detail}`,
        type: "GET",
        success: (res) => {
          data = res.data.results
          $(".modal-title").html(res.data.title)
          total = 0
          $(".detail-bonus").remove()
          $.each(data, (i, val) => {
            $("#table-detail #total").before(`
            <tr class="detail">
              <td class="text-left">${val.seq}</td>
              <td class="text-left">${val.bonus_time}</td>
              <td class="text-left">${val.bonus_note}</td>
              <td class="text-right">${formatCurrency(val.bonus_value)}</td>
            </tr>
            `)
            total += parseInt(val.bonus_value)
          })
          $("#total_bonus").html(formatCurrency(total))
          $("#modal-detail").modal("show")
        },
        error: (err) => {
          let res = err.responseJSON
          $("#alert").html(res.message)
          $("#alert").show()
          setTimeout(function() {
            $("#alert").hide()
          }, 3000);
          if (res.error == "validation") {
            $.each(res.data, (i, val) => {
              $(`#alert_input_${i}`).html(val).show()
            })
          }
        },
      })
    })

    formatCurrency = ($params) => {
      let formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      })
      return formatter.format($params)
    }

    formatProperCase = (params) => {
      return params.replace(
        /\w\S*/g,
        function(txt) {
          return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        }
      );
    }

    getBonusTransfer = () => {
      $("#table").dataTableLib({
        url: window.location.origin + "/member/bonus/get-detail-bonus",
        selectID: "member_id",
        colModel: [{
          display: 'Tanggal',
          name: 'bonus_transfer_date_formatted',
          align: 'left',
        }],
        options: {
          limit: [5, 15, 20, 50, 100],
          currentLimit: 5,
        },
        search: false,
        searchTitle: "Pencarian",
        searchItems: [],
        sortName: "member_id",
        sortOrder: "ASC",
        tableIsResponsive: true,
        select: false,
        multiSelect: false,
        buttonAction: [],
        success: (res) => {
          let data = res.data.results
          $('.table-responsive').css("max-height", "calc(200vh + 175px)")
          $('.table-responsive').html(``)

          let html = ''

          $.each(data, (i, val) => {
            let card_summary = ``
            $.each(val.summary, (i, summary) => {
              card_summary += `
              <div class="col-12 col-sm-12 col-md-6">
                <div class="card border mb-1">
                  <div class="card-body p-1">
                    <p class="card-text font-size-16 secondary font-weight-bold">${summary.label}</p>
                    <h6 class="mb-0 warning font-weight-bold">${formatCurrency(summary.value)}</h6>
                  </div>
                </div>
              </div>
              `
            })
            let card_potency = ``
            $.each(val.potency, (i, potency) => {
              card_potency += `
              <div class="col-12 col-sm-12 col-md-6">
                <div class="card border mb-1">
                  <div class="card-body p-1">
                    <p class="card-text font-size-16 secondary font-weight-bold">${potency.label}</p>
                    <h6 class="mb-0 primary font-weight-bold">${formatCurrency(potency.value)}</h6>
                  </div>
                </div>
              </div>
              `
            })
            html += `<div class="col-12 col-sm-12 col-md-12">
              <div class="card border mb-1 pb-1">

                <div class="card-body px-3 pt-1 pb-0 row d-flex align-items-center justify-content-between">
                  <p class="col-md-6 col-sm-12 card-text font-size-16 secondary font-weight-bold m-0"><b>${val.network_code}</b></p>

                  <div class="btn btn-light btn-collapse d-none" id="expand_${i}" onclick="shrink('${i}')">
                    <i class="ficon bx bx-chevron-up bx-md mr-0"></i>
                  </div>
                  <div class="btn btn-light btn-collapse" id="shrink_${i}" onclick="expand('${i}')">
                    <i class="ficon bx bx-chevron-down bx-md mr-0"></i>
                  </div>
                </div>

                <div class="card-body px-2 pt-0 pb-0 row d-flex align-items-center">
                  <div class="col-12 col-md-4 col-lg-4">
                    <div class="card bg-info bg-light mb-0 mt-1">
                      <div class="card-body p-1">
                        <h5 class="mb-50 dark font-weight-bold">${formatCurrency(val.bonus_total_bonus)}</h5>
                        <p class="card-text mb-0 dark pb-0" style="padding-bottom: 5px;">Total Komisi</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-md-4 col-lg-4">
                    <div class="card bg-info bg-light mb-0 mt-1">
                      <div class="card-body p-1">
                        <h5 class="mb-50 dark font-weight-bold">${formatCurrency(val.bonus_total_reward)}</h5>
                        <p class="card-text mb-0 dark pb-0" style="padding-bottom: 5px;">Total Cash Reward</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-md-4 col-lg-4">
                    <div class="card mb-0 mt-1" style="background-color: #cbb2e6;">
                      <div class="card-body p-1">
                        <h5 class="mb-50 dark font-weight-bold">${formatCurrency(val.bonus_total_acc)}</h5>
                        <p class="card-text mb-0 dark pb-0" style="padding-bottom: 5px;">Total Diperoleh</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-md-4 col-lg-4">
                    <div class="card bg-warning bg-light mb-0 mt-1">
                      <div class="card-body p-1">
                        <h5 class="mb-50 font-weight-bold">${formatCurrency(val.bonus_total_paid)}</h5>
                        <p class="card-text mb-0 dark pb-0" style="padding-bottom: 5px;">Total Dibayarkan</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-md-4 col-lg-4">
                    <div class="card bg-success bg-light mb-0 mt-1">
                      <div class="card-body p-1">
                        <h5 class="mb-50 font-weight-bold">${formatCurrency(val.bonus_total_acc - val.bonus_total_paid)}</h5>
                        <p class="card-text mb-0 dark pb-0" style="padding-bottom: 5px;">Komisi Menunggu
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-md-4 col-lg-4">
                    <div class="card bg-danger bg-light mb-0 mt-1">
                      <div class="card-body p-1">
                        <h5 class="mb-50 font-weight-bold">${formatCurrency(val.bonus_limit)}</h5>
                        <p class="card-text mb-0 dark pb-0" style="padding-bottom: 5px;">Batas Transfer Komisi
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card-body px-2 pt-0 pb-0 row align-items-start d-none" id="detail_children_${i}">
                  <div class="col-md-6 col-12">
                    <div class="card mb-0">
                      <div class="card-body mt-1 p-0">
                        <div class="row">
                          <div class="col-12">
                            <h6 class="mb-0 dark text-primary font-weight-bold mb-1">Potensi Komisi Hari Ini (<?= convertDate(date('Y-m-d'), 'id') ?>)</h6>
                            <div class="row">
                            ${card_potency}
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6 col-12">
                    <div class="card mb-0">
                      <div class="card-body mt-1 p-0">
                        <div class="row">
                          <div class="col-12">
                            <h6 class="mb-0 dark text-black font-weight-bold mb-1">Rincian Komisi yang Diperoleh</h6>
                            <div class="row">
                            ${card_summary}
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>`
          })

          $('.table-responsive').append(`<div class="row mx-0 px-0"><h6 class="pl-1 mb-0 dark text-black font-weight-bold mb-1">Rincian Komisi Grup Kloning</h6>${html}</div>`)
        }
      })
    }

    getBonusTransfer()
    getMemberDetail()
    getBonus()
  })
</script>
<!-- END: Content-->



<!-- <div class="card-body px-2 pt-0 pb-1 row d-flex align-items-center">
                  <p class="col-md-2 col-sm-12 py-1 mb-0">Sponsor<br/><span class="mb-0 primary font-weight-bold">${formatCurrency(val.detail.bonus_sponsor_acc)}</span></p>
                  <p class="col-md-2 col-sm-12 py-1 mb-0">Generasi<br/><span class="mb-0 primary font-weight-bold">${formatCurrency(val.detail.bonus_gen_node_acc)}</span></p>
                  <p class="col-md-2 col-sm-12 py-1 mb-0">Power Leg<br/><span class="mb-0 primary font-weight-bold">${formatCurrency(val.detail.bonus_power_leg_acc)}</span></p>
                  <p class="col-md-2 col-sm-12 py-1 mb-0">Matching Leg<br/><span class="mb-0 primary font-weight-bold">${formatCurrency(val.detail.bonus_matching_leg_acc)}</span></p>
                  <p class="col-md-2 col-sm-12 py-1 mb-0">Cash Reward<br/><span class="mb-0 primary font-weight-bold">${formatCurrency(val.detail.bonus_cash_reward_acc)}</span></p>
                  <p class="col-md-2 col-sm-12 py-1 mb-0" style="background-color: #6f2abb !important; color: white; border-radius: 8px;">Total<br/><span class="mb-0 white font-weight-bold">${formatCurrency(val.detail.bonus_total_acc)}</span></p>
                </div> -->