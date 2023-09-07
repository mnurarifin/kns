<style>
  /* Small devices (landscape phones, less than 768px) */
  @media (max-width: 767.98px) {
    .top-title {
      display: flex;
      flex-direction: column;
      line-height: 150%;
      text-align: center;
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
          <div class="card">
            <div class="card-body p-2">
              <div class="row">
                <div class="col-12">
                  <h6 class="mb-2 dark font-weight-bold top-title">Pertumbuhan Jaringan Hari Ini <span>(<span class="span_master_date"></span>)</span></h6>
                  <div class="">
                    <div class="d-none d-md-flex flex-row flex-wrap align-center justify-center">

                      <div class="d-flex flex-grow-1 col-4">
                        <div class="px-1 py-1 mb-1 border hover-shadow rounded-lg text-center d-flex flex-column align-stretch justify-content-between h-100 w-100 card" style="height: 90px!important;">
                          <div class="d-block cl-secondary font-size-10 font-weight-500">Penambahan <span class="d-inline-block font-weight-600" style="color: #c69d27;">Poin Kiri</span>
                          </div>
                          <div class="d-flex flex-column align-center justify-center py-0">
                            <div class="font-size-20 font-weight-500 grey--text span_master_point_left" style="font-size: 16pt!important">0</div>
                          </div>
                        </div>
                      </div>
                      <div class="d-flex flex-grow-1 col-4">
                        <div class="px-1 py-1 mb-1 border hover-shadow rounded-lg text-center d-flex flex-column align-stretch justify-content-between h-100 w-100 card" style="height: 90px!important;">
                          <div class="d-block cl-secondary font-size-10 font-weight-500">Penambahan <span class="d-inline-block font-weight-600" style="color: #c69d27;">Poin Kanan</span>
                          </div>
                          <div class="d-flex flex-column align-center justify-center py-0">
                            <div class="font-size-20 font-weight-500 grey--text span_master_point_right" style="font-size: 16pt!important">0</div>
                          </div>
                        </div>
                      </div>
                      <div class="d-flex flex-grow-1 col-4">
                        <div class="px-1 py-1 mb-1 border hover-shadow rounded-lg text-center d-flex flex-column align-stretch justify-content-between h-100 w-100 card" style="height: 90px!important;">
                          <div class="d-block cl-secondary font-size-10 font-weight-500">Penambahan <span class="d-inline-block font-weight-600" style="color: #c69d27;">Sponsor</span>
                          </div>
                          <div class="d-flex flex-column align-center justify-center py-0">
                            <div class="font-size-20 font-weight-500 grey--text span_master_sponsor" style="font-size: 16pt!important">0</div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row d-flex d-md-none">
                      <div class="col col-12 col-sm-12 col-md-6">
                        <div class="px-3 py-1 border hover-shadow rounded-lg text-center d-flex flex-column align-stretch justify-content-between h-100 w-100 card" style="height: 95px!important;">
                          <div class="d-block cl-secondary font-size-10 font-weight-400" style="color: #999;">Penambahan <span class="d-inline-block font-weight-600" style="color: #c69d27;">Poin Kiri</span>
                          </div>
                          <div class="d-flex flex-column align-center justify-center py-0">
                            <div class="font-size-18 font-weight-500 grey--text span_master_point_left" style="font-size: 17pt!important">0</div>
                          </div>
                        </div>
                      </div>
                      <div class="col col-12 col-sm-12 col-md-6">
                        <div class="px-3 py-1 border hover-shadow rounded-lg text-center d-flex flex-column align-stretch justify-content-between h-100 w-100 card" style="height: 95px!important;">
                          <div class="d-block cl-secondary font-size-10 font-weight-400" style="color: #999;">Penambahan <span class="d-inline-block font-weight-600" style="color: #c69d27;">Poin Kanan</span>
                          </div>
                          <div class="d-flex flex-column align-center justify-center py-0">
                            <div class="font-size-18 font-weight-500 grey--text span_master_point_right" style="font-size: 17pt!important">0</div>
                          </div>
                        </div>
                      </div>
                      <div class="col col-12 col-sm-12 col-md-6">
                        <div class="px-3 py-1 border hover-shadow rounded-lg text-center d-flex flex-column align-stretch justify-content-between h-100 w-100 card" style="height: 95px!important;">
                          <div class="d-block cl-secondary font-size-10 font-weight-400" style="color: #999;">Penambahan <span class="d-inline-block font-weight-600" style="color: #c69d27;">Sponsor</span>
                          </div>
                          <div class="d-flex flex-column align-center justify-center py-0">
                            <div class="font-size-18 font-weight-500 grey--text span_master_sponsor" style="font-size: 17pt!important">0</div>
                          </div>
                        </div>
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

<script>
  $(function() {
    $("#data_kosong").hide()

    getNetgrow = () => {
      $("#table").dataTableLib({
        url: window.location.origin + "/member/network/get-netgrow/",
        selectID: "member_id",
        colModel: [{
            display: "Tanggal",
            name: "master_date",
            align: "center",
          },
          {
            display: "Kiri",
            name: "master_point_left",
            align: "center",
            render: (params) => {
              return formatDecimal(params)
            },
          },
          {
            display: "Kanan",
            name: "master_point_right",
            align: "center",
            render: (params) => {
              return formatDecimal(params)
            },
          },
          {
            display: "Kiri",
            name: "master_wait_left",
            align: "center",
            render: (params) => {
              return formatDecimal(params)
            },
          },
          {
            display: "Kanan",
            name: "master_wait_right",
            align: "center",
            render: (params) => {
              return formatDecimal(params)
            },
          },
          {
            display: "Pasangan",
            name: "master_match",
            align: "center",
          },
          {
            display: "Sponsor",
            name: "master_sponsor",
            align: "center",
          },
        ],
        options: {
          limit: [10, 15, 20, 50, 100],
          currentLimit: 10,
        },
        search: true,
        searchTitle: "Pencarian",
        searchItems: [{
            display: "Tanggal Gabung",
            name: "member_join_datetime",
            type: "date"
          },
          {
            display: "Nama",
            name: "member_name",
            type: "text"
          },
          {
            display: "Kode Mitra",
            name: "network_code",
            type: "text"
          },
          {
            display: "Level",
            name: "level",
            type: "text"
          },
          {
            display: "Posisi",
            name: "position",
            type: "select",
            option: [{
                title: "Kiri",
                value: "L"
              },
              {
                title: "Kanan",
                value: "R"
              },
            ]
          },
        ],
        sortName: "member_join_datetime",
        sortOrder: "DESC",
        tableIsResponsive: true,
        select: false,
        multiSelect: false,
        buttonAction: [],
        success: (params) => {
          $("body").find(".cstm-table-head").empty()
          $("body").find(".cstm-table-head").append(`
          <tr>
          <th rowspan="2" title="Tanggal" style="text-align:center;min-width: ;max-width: ;">Tanggal</th>
          <th colspan="2" title="Penambahan Poin" style="text-align:center;min-width: ;max-width: ;">Penambahan Poin</th>
          <th rowspan="2" title="Sponsor" style="text-align:center;min-width: ;max-width: ;">Sponsor</th>
          </tr>
          <tr>
          <th title="Kiri" style="text-align:center;min-width: ;max-width: ;">Kiri</th>
          <th title="Kanan" style="text-align:center;min-width: ;max-width: ;">Kanan</th>
          </tr>
          `)
          data = params.data
          $(".span_master_date").html(data.master_date)
          $(".span_master_point_left").html(formatDecimal(data.master_point_left))
          $(".span_master_point_right").html(formatDecimal(data.master_point_right))
          $(".span_master_sponsor").html(formatDecimal(data.master_sponsor))

          $("#table-mobile").empty();
          let dataTb = params.data.results
          let dataUI = "<div class='row'>";
          if (dataTb.length > 0) {
            for (let idx = 0; idx < dataTb.length; idx++) {
              dataUI += `
                <div class="col-12 wrapper-row mb-1 px-1">
                  <div class="col-12 heading-row d-flex flex-row">
                    <div class="d-block">
                      <p class="card-text mb-0 dark d-flex align-items-center font-weight-bold"><i class="bx bx-calendar mr-50"></i>${dataTb[idx].master_date}</p>
                    </div>
                  </div>
                  <div class="col-12 data-row">
                    <div class="d-flex flex-row mb-50 justify-content-between align-center p-50" style="background: rgba(0,0,0,0.05); color: #000;">
                      <span>Penambahan Poin</span>
                    </div>
                    <div class="d-flex flex-row mb-50 justify-content-between align-center">
                      <span>Kiri</span>
                      <label class="mb-0 text-lowercase">${dataTb[idx].master_point_left}</label>
                    </div>
                    <div class="d-flex flex-row mb-50 justify-content-between align-center">
                      <span>Kanan</span>
                      <label class="mb-0 text-lowercase">${dataTb[idx].master_point_right}</label>
                    </div>

                    <div class="d-flex flex-row mb-50 justify-content-between align-center">
                      <span>Sponsor</span>
                      <label class="mb-0 text-lowercase">${dataTb[idx].master_sponsor}</label>
                    </div>

                  </div>
                </div>
              `
            }
          } else {
            dataUI += `
                <div class="col-12 wrapper-row mb-1 px-1">
                  <div class="col-12 heading-row text-center">
                    <div class="d-block p-1 text-center">Tidak ada data yang ditampilkan.</div>
                  </div>
                  <div class="col-12 data-row"></div>
                </div>
              `
          }
          dataUI += "</div>";

          $("#table-mobile").append(dataUI);
        }
      })
    }

    formatDecimal = ($params) => {
      let formatter = new Intl.NumberFormat('id-ID', {
        style: 'decimal',
        minimumFractionDigits: 1,
        maximumFractionDigits: 1,
      })
      return formatter.format($params)
    }

    getNetgrow()
  })
</script>
<!-- END: Content-->