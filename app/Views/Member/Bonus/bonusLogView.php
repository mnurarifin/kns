<style>
  .slct-riwayat {
    margin-left: 1px;
  }
  @media only screen and (max-width: 768px) {
    .riwayat-month {
      margin-left: -15px;
      margin-top: 15px;
      width: 550px;
    }
    .riwayat-year {
      margin-left: -15px;
      width: 550px;
    }
    button#bonus_log {
    width: 100%;
    }
    #member_children{
      margin-right: 14px;
    }
    .slct-riwayat {
    margin-left: 2px;
    }
  }

  @media only screen and (min-width: 768px) {
    #member_children{
      width: fit-content;
      margin-right: 18px;
    }
  }
  @media only screen and (min-width: 576px) and (max-width: 768px) {
    .riwayat-year {
      margin-top: 15px;
    }
  }
</style>

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
          <div class="form-group">
            <div class="row slct-riwayat">
            <select class="form-control" id="member_children">
            </select>
            <div class="col-md-2 col-sm-6 riwayat-month">
              <div class="form-group">
                  <select id='month' class="form-control">
                    <option value='01' <?= date('m') == '01' ? 'selected' : '' ?>> Januari </option>
                    <option value='02' <?= date('m') == '02' ? 'selected' : '' ?>> Februari </option>
                    <option value='03' <?= date('m') == '03' ? 'selected' : '' ?>> Maret </option>
                    <option value='04' <?= date('m') == '04' ? 'selected' : '' ?>> April </option>
                    <option value='05' <?= date('m') == '05' ? 'selected' : '' ?>> Mei </option>
                    <option value='06' <?= date('m') == '06' ? 'selected' : '' ?>> Juni </option>
                    <option value='07' <?= date('m') == '07' ? 'selected' : '' ?>> Juli </option>
                    <option value='08' <?= date('m') == '08' ? 'selected' : '' ?>> Agustus </option>
                    <option value='09' <?= date('m') == '09' ? 'selected' : '' ?>> September </option>
                    <option value='10' <?= date('m') == '10' ? 'selected' : '' ?>> Oktober </option>
                    <option value='11' <?= date('m') == '11' ? 'selected' : '' ?>> November </option>
                    <option value='12' <?= date('m') == '12' ? 'selected' : '' ?>> Desember </option>
                  </select>
              </div>
              </div>
              <div class="col-md-2 col-sm-6 riwayat-year">
                <div class="form-group">
                  <select id='year' class="form-control">
                    <?php for ($i = 2023; $i <= date('Y'); $i++) { ?>
                      <option value='<?php echo $i ?>'><?php echo $i ?></option>
                      <?php } ?>
                  </select>
              </div>
              </div>
                  <button class="btn btn-icon btn-primary btn-sm mb-1 dtable-action table-bonus-transfer-action mr-1" onClick="myFunction() " type="button" id="bonus_log" title="Riwayat Komisi" data-url="undefined" data-type="export_komisi" data-message="undefined" style="align-items:center;">CARI</button>
                        <!-- <div class="">
                          <p class="d-flex align-items-center" style="padding-top: 9px;">Total Riwayat Komisi</p>
                        </div> -->
                  </div>

          <div class="row">
          <div class="col-12 col-xs-12 col-md-6 col-lg-6">
            <div class="card card-rounded">
                <div class="card-content">
                    <div class="card-footer pb-15" style="margin-bottom: 5px;">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-rgba-primary m-0 p-50 mr-75 mr-xl-2">
                                <div class="avatar-content">
                                    <i class="bx bx-wallet text-primary" style="font-size:24px;line-height:22px;"></i>
                                </div>
                            </div>
                            <div class="total-amount">
                                <h5 class="mb-0 text-dark"><span class="font-weight-bold" id="total_riwayat" >0</span></h5>
                                <h5><small class="text-muted">Total Riwayat Komisi</small></h5>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
          </div>

          <div class="col-12 col-xs-12 col-md-6 col-lg-6">
            <div class="card card-rounded">
                <div class="card-content">
                    <div class="card-footer pb-15" style="margin-bottom: 5px;">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-rgba-primary m-0 p-50 mr-75 mr-xl-2">
                                <div class="avatar-content">
                                    <i class="bx bx-wallet text-primary" style="font-size:24px;line-height:22px;"></i>
                                </div>
                            </div>
                            <div class="total-amount">
                                <h5 class="mb-0 text-dark"><span class="font-weight-bold" id="total_riwayat_filter">0</span></h5>
                                <h5><small class="text-muted" id="hasil" >Total Komisi Bulan Ini </small></h5>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        </div>

          <div class="mb-1" id="table">
          </div>
          <div class="card p-2" id="data_kosong">
            <div class="row">
              <div class="col-md-12 d-flex justify-content-center">
                <img src="<?= BASEURL ?>/app-assets/images/no-data-green.svg" alt="
                  style=" filter: grayscale(100%);">
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

<!-- Modal -->
<div class="modal fade text-left" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="myModalLabel1">Detail Riwayat Komisi Matching</h3>
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
                  <th class="text-center">Tanggal</th>
                  <th class="text-center">Keterangan</th>
                  <th class="text-center">Bonus</th>
                </tr>
              </thead>
              <tbody>
                <tr id="total">
                  <th class="text-right" colspan="2">Total</th>
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
  function myFunction() {
        const month = document.getElementById("month");
        const hasilDiv = document.getElementById("hasil");

            const selectedmonth = month.value;
            switch (selectedmonth) {
                case "01":
                    hasilDiv.innerText = "Total Komisi Januari";
                    break;
                case "02":
                    hasilDiv.innerText = "Total Komisi Februari";
                    break;
                case "03":
                    hasilDiv.innerText = "Total Komisi Maret";
                    break;
                case "04":
                    hasilDiv.innerText = "Total Komisi April";
                    break;
                case "05":
                    hasilDiv.innerText = "Total Komisi Mei";
                    break;
                case "06":
                    hasilDiv.innerText = "Total Komisi Juni";
                    break;
                case "07":
                    hasilDiv.innerText = "Total Komisi Juli";
                    break;
                case "08":
                    hasilDiv.innerText = "Total Komisi Agustus";
                    break;
                case "09":
                    hasilDiv.innerText = "Total Komisi September";
                    break;
                case "10":
                    hasilDiv.innerText = "Total Komisi Oktober";
                    break;
                case "11":
                    hasilDiv.innerText = "Total Komisi November";
                    break;
                case "12":
                    hasilDiv.innerText = "Total Komisi Desember";
                    break;
                default:
                    hasilDiv.innerText = "Teks tidak valid";
            }
        };

  $(function() {
    $("#data_kosong").hide()

    let data

    getMemberChildren = () => {
      $.ajax({
        url: "<?= BASEURL ?>/member/bonus/get-member-children",
        type: "GET",
        async: false,
        success: (res) => {
          data = res.data.results
          $.each(data, (i, val) => {
            $("#member_children").append(`<option value="${val.member_id}">${val.network_code}</option>`)
          })
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

    getBonusLog = () => {
      $("#table").dataTableLib({
        url: window.location.origin + "/member/bonus/get-bonus-log/" + $("#member_children").val() + '/' + $("#month").val() + '/' + $("#year").val() ,
        selectID: "bonus_log_id",
        colModel: [{
            display: 'Tanggal',
            name: 'bonus_log_date_formatted',
            align: 'center',
          },
          {
            display: 'Sponsor',
            name: 'bonus_log_sponsor',
            align: 'center',
            render: (params) => {
              return `<span>${formatCurrency(params)}</span>`
            },
          },
          {
            display: 'Generasi',
            name: 'bonus_log_gen_node',
            align: 'center',
            render: (params) => {
              return `<span>${formatCurrency(params)}</span>`
            },
          },
          {
            display: 'Power Leg',
            name: 'bonus_log_power_leg',
            align: 'center',
            render: (params) => {
              return `<span>${formatCurrency(params)}</span>`
            },
          },
          {
            display: 'Matching Leg',
            name: 'bonus_log_matching_leg',
            align: 'center',
            render: (params, data) => {
              return `<span>${formatCurrency(params)}</span>`
            },
          },
          {
            display: 'Cash Reward',
            name: 'bonus_log_cash_reward',
            align: 'center',
            render: (params, data) => {
              return `<span>${formatCurrency(params)}</span>`
            },
          },
          {
            display: 'Total',
            name: 'total',
            align: 'center',
            render: (params) => {
              return `<span>${formatCurrency(params)}</span>`
            },
          },
        ],
        options: {
          limit: [10, 15, 20, 50, 100],
          currentLimit: 10,
        },
        search: true,
        searchTitle: "Pencarian",
        searchItems: [{
          display: 'Tanggal',
          name: 'bonus_log_date',
          type: 'date'
        }, ],
        sortName: "bonus_log_date",
        sortOrder: "DESC",
        tableIsResponsive: true,
        select: false,
        multiSelect: false,
        buttonAction: [],
        success: (res) => {
          data = res.data.results
          $('#total_riwayat').html(formatCurrency(res.data.bonus_total));
          $('#total_riwayat_filter').html(formatCurrency(res.data.bonus_filter));
        }
      })
    }

    // $("#member_children").on("change", () => {
    //   getBonusLog()
    // })

    $("#bonus_log").click(function(){
      getBonusLog();
    });

    $("body").on("click", ".span_gen_match", (ev) => {
      let data_bonus = data.find(o => o.bonus_log_id == $(ev.target).data("id"))
      total = 0
      $(".detail-gen-match").remove()
      $.each(data_bonus.detail, (i, val) => {
        $("#table-detail #total").before(`
        <tr class="detail-gen-match">
          <td class="text-left">${val.netgrow_gen_match_time}</td>
          <td class="text-left">Komisi Matching dari ${val.trigger_member_account_username} (Gen. ${val.netgrow_gen_match_level}) ${formatCurrency(val.netgrow_gen_match_bonus)} x ${val.match_count} pasang</td>
          <td class="text-right">${formatCurrency(val.netgrow_gen_match_bonus_total)}</td>
        </tr>
        `)
        total += parseInt(val.netgrow_gen_match_bonus)
      })
      $("#total_bonus").html(formatCurrency(total))
      $("#modal-detail").modal("show")
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

    getMemberChildren()
    getBonusLog()
  })
</script>
<!-- END: Content-->