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
          <div class="card bg-primary bg-light mb-1">
            <div class="card-body p-1">
              <div class="row">
                <div class="col-md-8 col-12">
                  <div class="row">
                    <div class="col-md-4 col-12">
                      <div class="d-flex">
                        <i class="bx bx-user-circle my-auto" style="font-size: 3rem;"></i>
                        <div class="pl-1">
                          <p class="card-text mb-0 dark" style="padding-bottom: 5px;">Username Mitra</p>
                          <h5 class="mb-0 dark font-weight-bold" id="span_member_account_username"></h5>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 col-12">
                      <div class="d-flex">
                        <i class="bx bx-bar-chart-alt my-auto" style="font-size: 3rem;"></i>
                        <div class="pl-1">
                          <p class="card-text mb-0 dark" style="padding-bottom: 5px;">Level</p>
                          <h5 class="mb-0 dark font-weight-bold" id="span_level">0</h5>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 col-12">
                      <div class="d-flex">
                        <i class="bx bx-group my-auto" style="font-size: 3rem;"></i>
                        <div class="pl-1">
                          <p class="card-text mb-0 dark" style="padding-bottom: 5px;">Jumlah Downline</p>
                          <h5 class="mb-0 dark font-weight-bold" id="span_sponsor">0</h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-12 my-auto text-right" id="previous">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-12">
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

    getSponsor = (username) => {
      $("#table").dataTableLib({
        url: window.location.origin + "/member/network/get-sponsor/" + (username ? "?username=" + username : ""),
        selectID: "member_id",
        colModel: [{
            display: "",
            name: "image",
            align: "left",
            render: (params) => {
              return `<img src="${params}" style="width: 50px;" />`
            },
          },
          {
            display: "",
            name: "arr_member",
            align: "left",
            render: (params) => {
              return `
              <p class="card-text mb-0 primary font-weight-bold" style="padding-bottom: 2px;">${params.network_code}</p>
              <p class="card-text mb-0 dark" style="padding-bottom: 2px;">${params.member_name}</p>
              <p class="card-text mb-0 dark">${params.city_name}</p>
              `
            },
          },
          {
            display: "No. HP / Email",
            name: "arr_mobilephone_email",
            align: "left",
            render: (params) => {
              return `
              <p class="card-text mb-0 secondary" style="padding-bottom: 2px;">${params.member_mobilephone}</p>
              <p class="card-text mb-0 secondary" style="padding-bottom: 2px;">${params.member_email}</p>
              `
            },
          },
          {
            display: "Tanggal Gabung",
            name: "member_join_datetime_formatted",
            align: "left",
          },
          {
            display: "Sponsoring",
            name: "network_total_sponsoring",
            align: "center",
          },
          // {
          //   display: "Kemitraan",
          //   name: "network_rank_name",
          //   align: "left",
          // },
          {
            display: "",
            name: "",
            sortAble: false,
            align: "center",
            width: "70px",
            action: {
              function: "next",
              icon: "bx bx-right-arrow-alt btn btn-primary"
            }
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
            display: "Username Mitra",
            name: "member_account_username",
            type: "text"
          },
        ],
        sortName: "member_join_datetime",
        sortOrder: "DESC",
        tableIsResponsive: true,
        select: false,
        multiSelect: false,
        buttonAction: [],
        success: (res) => {
          data = res.data
          $("#span_member_account_username").html(data.member_account_username)
          $("#span_level").html(data.level)
          $("#span_sponsor").html(data.network_total_sponsoring)
          $("#previous").empty()
          if (data.member_account_username != '<?= session("member")["member_account_username"] ?>') {
            $("#previous").append(`
            <button type="button" class="btn btn-white" onclick="getSponsor('${data.sponsor_username}')" ${(data.sponsor_username == '' ? 'disabled' : '')}>
              <span class="pr-1 primary">Level Sebelumnya</span>
              <i class="bx bx-up-arrow-circle primary"></i>
            </button>
            `)
          }
        }
      })
    }

    next = (params) => {
      getSponsor(params.member_account_username)
    }

    getSponsor()
  })
</script>
<!-- END: Content-->