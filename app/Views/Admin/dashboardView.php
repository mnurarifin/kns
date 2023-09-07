<style>
  #buttonType .btn {
    border-radius: 0;
  }

  .donutSize {
    transform: translateX(-46px);
  }

  .bonus-item .sales-item-name {
    border-bottom: 5px solid #5A8DEE;
    padding-bottom: 10px;
    margin-bottom: 20px;
    font-size: .85rem;
  }

  .bonus-item .col-sm-6:nth-child(even) .sales-item-name {
    border-color: #fd7e14;
  }

  .bonus-item .sales-item-amount h6 .text-success {
    font-size: 0.9rem;
    color: #5A8DEE !important;
  }

  .bonus-item .col-sm-6:nth-child(even) .sales-item-amount h6 .text-success {
    color: #fd7e14 !important;
  }

  #ratio-komisi-Chart .apexcharts-legend.position-bottom.center {
    justify-content: flex-start;
    padding: 0;
  }

  #ratio-komisi-Chart .apexcharts-legend-series {
    padding-bottom: 5px;
  }
</style>

<!-- dashboard start -->
<section id="dashboard-ecommerce">
  <div class="row">
    <!-- TOTAL BONUS -->
    <div class="col-xl-12 col-12 dashboard-order-summary">
      <div class="card">
        <div class="row">
          <!-- Order Summary Starts -->
          <div class="col-md-8 col-12 order-summary border-right pr-md-0">
            <div class="card mb-0">
              <h4 style="margin-left: 20px; margin-top: 30px">Perkembangan Jaringan</h4>
              <div class="card-header d-block pt-4">
                <!-- grafik progres jaringan -->
                <div id="linearea-netgrow-chart"></div>
              </div>
            </div>
          </div>

          <!-- BONUS KEMARIN -->
          <div class="col-md-4 col-12 pl-md-0">
            <div class="card mb-0">
              <div class="card-header border-bottom p-0">
                <div id="buttonType">
                  <div class="row m-0">
                    <div class="col-sm-4 px-0 py-0">
                      <button type="button" id="btn-today" class="btn btn-lg btn-light-primary btn-outlined btn-block mr-1" name="Today">Total Komisi</button>
                    </div>
                    <div class="col-sm-4 px-0 py-0">
                      <button type="button" id="btn-yesterday" class="btn btn-lg btn-light-primary btn-outlined btn-block mr-1" name="Yesterday">Laba Kotor</button>
                    </div>
                    <div class="col-sm-4 px-0 py-0">
                      <button type="button" id="btn-stockist" class="btn btn-lg btn-light-primary btn-outlined btn-block mr-1" name="Stockist">Saldo Stokis</button>
                    </div>
                  </div>
                </div>
              </div>
              <form id="bonusYesterday" style="margin-bottom: 10px;">
                <div class="card-header pb-1">
                  <h6>Rincian Omzet :</h6>
                </div>
                <div class="card-content">
                  <div class="card-body pt-0 pb-1">
                    <div class="row bonus-item">
                      <div class="col-sm-6">
                        <div class="sales-item-amount">
                          <h6 class="mb-0"><span class="text-success" id="totalRegistrasi">Rp 0</span></h6>
                        </div>
                        <div class="sales-item-name">
                          <p class="mb-0">Total Registrasi Mitra</p>
                        </div>
                      </div>
                      <!-- <div class="col-sm-6">
                        <div class="sales-item-amount">
                          <h6 class="mb-0"><span class="text-success" id="totalStockist">Rp 0</span></h6>
                        </div>
                        <div class="sales-item-name">
                          <p class="mb-0">Total Transaksi Stokis</p>
                        </div>
                      </div> -->
                    </div>
                  </div>

                  <div class="card-footer border-top pb-15" style="margin-bottom: 5px;">
                    <div class="d-flex align-items-center">
                      <div class="avatar bg-rgba-primary m-0 p-50 mr-75 mr-xl-2">
                        <div class="avatar-content">
                          <i class="bx bx-wallet text-primary" style="font-size:24px;line-height:22px;"></i>
                        </div>
                      </div>
                      <div class="total-amount">
                        <h5 class="mb-0 text-dark">Rp <span class="font-weight-bold" id="totalOmzet">0</span></h5>
                        <h5><small class="text-muted">Total Omzet</small></h5>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer border-top pb-15" style="margin-bottom: 5px;">
                    <div class="d-flex align-items-center">
                      <div class="avatar bg-rgba-warning m-0 p-50 mr-75 mr-xl-2">
                        <div class="avatar-content">
                          <i class="bx bx-select-multiple text-warning" style="font-size:24px;line-height:22px;"></i>
                        </div>
                      </div>
                      <div class="total-amount">
                        <h5 class="mb-0 text-dark">Rp <span class="font-weight-bold" id="totalPayout">0</span></h5>
                        <h5><small class="text-muted">Total Payout</small></h5>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="card-footer border-top pb-15" style="margin-bottom: 5px;">
                    <div class="d-flex align-items-center">
                      <div class="avatar bg-rgba-warning m-0 p-50 mr-75 mr-xl-2">
                        <div class="avatar-content">
                          <i class="bx bx-select-multiple text-warning" style="font-size:24px;line-height:22px;"></i>
                        </div>
                      </div>
                      <div class="total-amount">
                        <h5 class="mb-0 text-dark">Rp <span class="font-weight-bold" id="totalSaldoStockist">0</span></h5>
                        <h5><small class="text-muted">Total Saldo Stokis</small></h5>
                      </div>
                    </div>
                  </div> -->
                </div>
              </form>

              <form id="bonusStockist" style="margin-bottom: 10px;">
                <div class="card-content">
                  <div class="card-footer border-top pb-15" style="margin-bottom: 5px;">
                    <div class="d-flex align-items-center">
                      <div class="avatar bg-rgba-primary m-0 p-50 mr-75 mr-xl-2">
                        <div class="avatar-content">
                          <i class="bx bx-wallet text-primary" style="font-size:24px;line-height:22px;"></i>
                        </div>
                      </div>
                      <div class="total-amount">
                        <h5 class="mb-0 text-dark">Rp <span class="font-weight-bold" id="totalSaldo">0</span></h5>
                        <h5><small class="text-muted">Total Saldo</small></h5>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer border-top pb-15" style="margin-bottom: 5px;">
                    <div class="d-flex align-items-center">
                      <div class="avatar bg-rgba-warning m-0 p-50 mr-75 mr-xl-2">
                        <div class="avatar-content">
                          <i class="bx bx-select-multiple text-warning" style="font-size:24px;line-height:22px;"></i>
                        </div>
                      </div>
                      <div class="total-amount">
                        <h5 class="mb-0 text-dark">Rp <span class="font-weight-bold" id="totalPayoutSaldo">0</span></h5>
                        <h5><small class="text-muted">Total Payout</small></h5>
                      </div>
                    </div>
                  </div>
                </div>
              </form>

              <form id="bonusToday" style="margin-bottom: 10px;">
                <div class="card-header pb-1">
                  <h6>Rincian Saldo Komisi :</h6>
                </div>
                <div class="card-content">
                  <div class="card-body pt-0 pb-1">
                    <div class="row bonus-item" id="saldoBonusItem"></div>
                  </div>
                  <div class="card-footer border-top pb-15" style="margin-bottom: 5px;">
                    <div class="d-flex align-items-center">
                      <div class="avatar bg-rgba-primary m-0 p-50 mr-75 mr-xl-2">
                        <div class="avatar-content">
                          <i class="bx bx-wallet text-primary" style="font-size:24px;line-height:22px;"></i>
                        </div>
                      </div>
                      <div class="total-amount">
                        <h5 class="mb-0 text-dark">Rp <span class="font-weight-bold" id="totalKomisi">0</span></h5>
                        <h5><small class="text-muted">Komisi Mitra</small></h5>
                      </div>
                    </div>
                  </div>

                  <div class="card-footer border-top pb-15" style="margin-bottom: 5px;">
                    <div class="d-flex align-items-center">
                      <div class="avatar bg-rgba-warning m-0 p-50 mr-75 mr-xl-2">
                        <div class="avatar-content">
                          <i class="bx bx-select-multiple text-warning" style="font-size:24px;line-height:22px;"></i>
                        </div>
                      </div>
                      <div class="total-amount">
                        <h5 class="mb-0 text-dark">Rp <span class="font-weight-bold" id="totalPaid">0</span></h5>
                        <h5><small class="text-muted">Total Transfer</small></h5>
                      </div>
                    </div>
                  </div>
              </form>
            </div>

            <div class="card mb-0">
              <div class="card-body border-bottom">
                <form class="form form-horizontal" id="formBonusToday">
                  <h4 class="card-title text-center my-1">SALDO KOMISI MITRA</h4>
                  <div class="form-body">
                    <div class="row">
                      <div id="order-summary-chart" class="mx-auto mb-0">
                        <h2 id="bonusTotal" class="text-primary">Rp <span>0</span></h2>
                      </div>
                    </div>
                  </div>
                </form>

                <form class="form form-horizontal" id="formBonusStockist">
                  <h4 class="card-title text-center my-1">SALDO STOKIS</h4>
                  <div class="form-body">
                    <div class="row">
                      <div id="order-summary-chart" class="mx-auto mb-0">
                        <h2 id="bonusTotalStockist" class="text-primary">Rp <span>0</span></h2>
                      </div>
                    </div>
                  </div>
                </form>

                <form class="form form-horizontal" id="formBonusKemarin">
                  <h4 class="card-title text-center my-1">TOTAL LABA KOTOR</h4>
                  <div class="form-body">
                    <div class="row">
                      <div id="order-summary-chart" class="mx-auto mb-0">
                        <h2 id="TotalBonusKemarin" class="text-primary">Rp <span>0</span></h2>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END -->
    <!-- TOTAL SERIAL -->

    <div class="col-xl-12 col-12 dashboard-visit p-0">
      <div class="row">
        <div class="col-xs-12 col-md-6">
          <h5 class="card-title">Total Kemitraan</h5>
          <div class="card" style="height: 150px;">
            <div class="card-content">
              <div class="card-body align-items-center justify-content-between">
                <div class="my-2">
                  <div class="d-flex align-items-center">
                    <div class="avatar bg-rgba-primary m-0 p-25 mr-75 mr-xl-2">
                      <div class="avatar-content">
                        <i class="bx bx-user text-primary font-medium-2"></i>
                      </div>
                    </div>
                    <div class="activity-progress flex-grow-1">
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="sales-item-name d-inline-block mb-25">
                          <p class="mb-0">Registrasi Mitra</p>
                        </div>
                        <div class="sales-item-amount d-inline-block">
                          <h6 class="mb-0"><span class="text-primary" id="regMitra">0</span></h6>
                        </div>
                      </div>
                      <div class="progress progress-bar-primary progress-sm">
                        <div id="prog-regMitra" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xs-12 col-md-6">
          <h5 class="card-title">Histori Komisi 7 Hari Terakhir</h5>
          <div class="card" style="height: calc(300px + 2.2rem);">
            <div class="card-content">
              <div class="card-body p-25" style="min-height: 342px;">
                <div id="linearea-member-chart"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- dashboard end -->

<script src="<?php echo base_url(); ?>/app-assets/vendors/js/charts/apexcharts.min.js"></script>
<script>
  $("#btn-today").addClass('active');
  $("#bonusYesterday").hide();
  $("#formBonusKemarin").hide();
  $("#formBonusStockist").hide();
  $('#bonusStockist').hide()

  let url = '';
  $(document).ready(function() {
    // get NetworkGrow Weekly
    $.ajax({
      url: window.location.origin + '/admin/service/dashboard/getNetgrowWeek',
      method: 'GET',
      dataType: 'json',
      success: function(netgrow) {
        renderNetworkGrow(netgrow.data);
      }
    })

    // get Komisi Omzet
    $.ajax({
      url: window.location.origin + '/admin/service/dashboard/getKomisiOmzet',
      method: 'GET',
      dataType: 'json',
      success: function(komisiOmzet) {
        let komisi = komisiOmzet.data;
        $('#regMitra').text(komisi.countReg)
        $('#actvMitra').text(komisi.countActiv)
        $('#roMitra').text(komisi.countRO)
        total_komisi = komisi.countReg + komisi.countActiv;
        $('#prog-regMitra').css({
          'width': ((komisi.countReg / total_komisi) * 100) + '%',
          'transition': 'width 2s ease'
        })
        $('#prog-actvMitra').css({
          'width': ((komisi.countActiv / total_komisi) * 100) + '%',
          'transition': 'width 2s ease'
        })

        // omzet = parseInt(komisi.countMember) + parseInt(komisi.countStockist)
        omzet = parseInt(komisi.countMember)
        // Summary Total Komisi vs Omzet
        $('#bonusTotal > span').text(formatRupiah(komisi.totalSaldo));

        // $('#TotalBonusKemarin > span').text(formatRupiah(omzet - (komisi.totalPayout + komisi.saldo_acc)));
        $('#TotalBonusKemarin > span').text(formatRupiah(omzet - komisi.totalPayout));

        $('#totalKomisi').text(formatRupiah(komisi.totalKomisi)); //Total Komii
        $('#totalPaid').text(formatRupiah(komisi.totalPaid)); //Total Transfr
        $('#totalOmzet').text(formatRupiah(omzet)); //Total Omzt
        $('#totalPayout').text(formatRupiah(komisi.totalPayout)); //Total Payot
        $('#totalSaldoStockist').text(formatRupiah(komisi.saldo_acc)); //Total Payot

        // Rincian Omzet
        $('#totalRegistrasi').text('Rp ' + formatRupiah(komisi.countMember));
        $('#totalStockist').text('Rp ' + formatRupiah(komisi.countStockist));

        $('#totalSaldo').text(formatRupiah(komisi.saldo_acc));
        $('#totalPayoutSaldo').text(formatRupiah(komisi.saldo_paid));
        $('#bonusTotalStockist > span').text(formatRupiah(komisi.saldo_acc - komisi.saldo_paid));

        // Rincian Komisi
        let html = '';
        for (let i = 0; i < komisi.bonusName.length; i++) {
          html += `
      <div class="col-sm-6">
        <div class="sales-item-amount">
        <h6 class="mb-0"><span class="text-success" id="total${komisi.bonusName[i]}">Rp ${formatRupiah(komisi.saldoBonus[komisi.bonusName[i]])}</span></h6>
        </div>
        <div class="sales-item-name">
        <p class="mb-0">${komisi.bonusLabel[i]}</p>
        </div>
      </div>
      `;
        }
        $('#saldoBonusItem').html(html);
      }
    })

    // get Bonus Weekly
    $.ajax({
      url: window.location.origin + '/admin/service/dashboard/getBonusWeek',
      method: 'GET',
      dataType: 'json',
      success: function(bonus) {
        renderBonusWeekly(bonus.data);
      }
    })

    // get saldo ewallet
    $.ajax({
      url: window.location.origin + '/admin/service/dashboard/getSaldoEwallet',
      method: 'GET',
      dataType: 'json',
      success: function(saldo) {
        getSaldo(saldo.data)
      }
    })

    // get Ratio Komisi
    // $.ajax({
    //   url: window.location.origin + '/admin/service/dashboard/getRatioKomisi',
    //   method: 'GET',
    //   dataType: 'json',
    //   success: function(ratioKomisi) {
    //   let komisiData = [];
    //   const labels = ratioKomisi.data.label
    //   const keys = Object.keys(ratioKomisi.data.bonus);
    //   for (let x = 0; x < keys.length; x++) {
    //     komisiData.push({
    //     key: keys[x],
    //     label: labels[x],
    //     value: parseFloat(ratioKomisi.data.bonus[keys[x]])
    //     });
    //   }

    //   renderRatioKomisi(komisiData);
    //   }
    // })

  })
</script>

<script>
  var $primary = '#5A8DEE';
  var $success = '#39DA8A';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
  var $info = '#00CFDD';
  var $label_color = '#475f7b';
  var $primary_light = '#E2ECFF';
  var $danger_light = '#ffeed9';
  var $gray_light = '#828D99';
  var $sub_label_color = "#596778";
  var $radial_bg = "#e7edf3";
  var themeColors = [$primary, $warning, $danger, $success, $info];
  var themeColors2 = [$info, $success, $warning, $danger, $primary];

  // =====================================================================================
  function getSaldo(data) {
    var total = 0;

    if (data != null) {
      total = data.royalti;
      $('#persentase').text(data.persentase + ' %');
      $('#omzetThisMonth').text('Rp.' + formatRupiah(data.omzet));
    }
    if (total < 0) {
      $('#totalEwallet').text('Rp. -' + formatRupiah(total));
    } else {
      $('#totalEwallet').text('Rp.' + formatRupiah(total));
    }

    if (total >= 3000000) {
      $('#totalEwallet').addClass('text-primary')
    } else if (total >= 500000) {
      $('#totalEwallet').addClass('text-warning')
    } else if (total < 500000) {
      $('#totalEwallet').addClass('text-danger')
    }
  }

  function renderNetworkGrow(data) {
    var lineAreaOptions = {
      chart: {
        height: 450,
        width: '100%',
        type: 'line',
      },
      colors: themeColors,
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth'
      },
      series: [{
        name: 'Registrasi',
        data: data.map((el) => el['registration'])
      }],
      legend: {
        offsetY: -10
      },
      xaxis: {
        type: 'date',
        categories: data.map((el) =>
          formatTanggal(el['date'])
        ),
      },
      tooltip: {
        x: {
          format: 'dd/MM/yy'
        },
        y: {
          formatter: function(value, series) {
            return parseInt(value);
          }
        }
      }
    }
    var lineAreaChart = new ApexCharts(
      document.querySelector("#linearea-netgrow-chart"),
      lineAreaOptions
    );
    lineAreaChart.render();
  }

  function formatTanggal(data) {
    const d = new Date(data)
    const ye = new Intl.DateTimeFormat(['ban', 'id'], {
      year: 'numeric'
    }).format(d)
    const mo = new Intl.DateTimeFormat(['ban', 'id'], {
      month: '2-digit'
    }).format(d)
    const da = new Intl.DateTimeFormat(['ban', 'id'], {
      day: '2-digit'
    }).format(d)

    return `${da}-${mo}-${ye}`;
  }

  function renderBonusWeekly(data) {
    var lineAreaOptions2 = {
      chart: {
        height: 320,
        width: '100%',
        type: 'line',
      },
      colors: themeColors2,
      dataLabels: {
        enabled: false
      },
      stroke: {
        width: 3,
        curve: 'smooth'
      },
      series: [{
        name: 'Komisi',
        data: data.map((el) => el['bonus'])
      }],
      legend: {
        offsetY: -10
      },
      yaxis: {
        labels: {
          formatter: function(value) {
            return formatRupiah(value);
          }
        },
      },
      xaxis: {
        type: 'date',
        categories: data.map((el) =>
          formatTanggal(el['date'])
        ),
      },
      fill: {
        type: 'gradient',
        gradient: {
          shade: 'dark',
          gradientToColors: ['#FDD835'],
          shadeIntensity: 1,
          type: 'horizontal',
          opacityFrom: 1,
          opacityTo: 1,
          stops: [0, 100, 100, 100]
        },
      },
      markers: {
        size: 4,
        colors: ["#FFA41B"],
        strokeColors: "#fff",
        strokeWidth: 2,
        hover: {
          size: 7,
        }
      },
      tooltip: {
        x: {
          format: 'dd/MM/yy'
        },
        y: {
          formatter: function(value, series) {
            return 'Rp ' + formatRupiah(value);
          }
        }
      }
    }
    var lineAreaChart2 = new ApexCharts(
      document.querySelector("#linearea-member-chart"),
      lineAreaOptions2
    );

    lineAreaChart2.render();
  }

  function renderRatioKomisi(data) {
    var optionsPie = {
      series: data.map((el) => el['value']),
      chart: {
        height: 320,
        width: '100%',
        type: 'pie',
      },
      labels: data.map((el) => el['label']),
      legend: {
        position: 'bottom'
      },
      tooltip: {
        y: {
          formatter: function(value, series, w) {
            if (value != undefined) return 'Rp ' + formatRupiah(value);
          }
        }
      },
      responsive: [{
        breakpoint: 480,
        options: {
          chart: {
            width: 200
          },
          legend: {
            position: 'bottom'
          }
        }
      }]
    };

    var chart = new ApexCharts(document.querySelector("#ratio-komisi-Chart"), optionsPie);
    chart.render();
  }

  function formatRupiah($params) {
    // var ribuan = 0;
    // if (bilangan == null) {
    //   return ribuan;
    // }
    // var reverse = bilangan.toString().split('').reverse().join(''),
    //   ribuan = reverse.match(/\d{1,3}/g);
    // ribuan = ribuan.join('.').split('').reverse().join('');

    // return ribuan;
    let formatter = new Intl.NumberFormat('id-ID')
    return formatter.format($params)
  }

  $("#buttonType").on('click', (e) => {
    if (e.target.name == "Yesterday") {
      $("#btn-today").removeClass('active');
      $("#btn-stockist").removeClass('active');
      $("#btn-yesterday").addClass('active');
      $("#bonusToday").hide();
      $("#bonusStockist").hide();
      $("#bonusYesterday").show();
      $("#formBonusKemarin").show();
      $("#formBonusToday").hide();
      $("#formBonusStockist").hide();
    } else if (e.target.name == "Today") {
      $("#btn-today").addClass('active');
      $("#btn-yesterday").removeClass('active');
      $("#btn-stockist").removeClass('active');
      $("#bonusToday").show();
      $("#bonusYesterday").hide();
      $("#bonusStockist").hide();
      $("#formBonusToday").show();
      $("#formBonusKemarin").hide();
      $("#formBonusStockist").hide();
    } else if (e.target.name == "Stockist") {
      $("#btn-today").removeClass('active');
      $("#btn-yesterday").removeClass('active');
      $("#btn-stockist").addClass('active');
      $("#bonusToday").hide();
      $("#bonusStockist").show();
      $("#bonusYesterday").hide();
      $("#formBonusToday").hide();
      $("#formBonusStockist").show();
      $("#formBonusKemarin").hide();
    }
  })
</script>