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
                          <h5 class="mb-0 dark font-weight-bold"><?= session('member')['network_code']; ?></h5>
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

    getDownline = () => {
      $("#table").dataTableLib({
        url: window.location.origin + "/member/network/get-downline/",
        selectID: "member_id",
        colModel: [{
            display: 'Nama',
            name: 'member_name',
            align: 'left',
          },
          {
            display: 'Username',
            name: 'network_code',
            align: 'left',
          },
          {
            display: 'Tanggal Gabung',
            name: 'member_join_datetime_formatted',
            align: 'left',
          },
          // {
          //   display: 'Peringkat',
          //   name: 'network_rank_name',
          //   align: 'left',
          //   render: (params) => {
          //     switch (params) {
          //       case 'Silver':
          //         return `<span class="badge badge-light-secondary badge-pill">${params}</span>`
          //         break;

          //       case 'Gold':
          //         return `<span class="badge badge-light-warning badge-pill">${params}</span>`
          //         break;

          //       case 'Bronze':
          //         return `<span class="badge badge-light-bronze badge-pill">${params}</span>`
          //         break;

          //       default:
          //         return `<span class="badge badge-light-primary badge-pill">${params}</span>`
          //         break;
          //     }
          //   }
          // },
          {
            display: 'Generasi',
            name: 'netgrow_node_level',
            align: 'left',
            render: (params) => {
              let returned = `
                <div class="d-flex justify-content-center rounded-pill white" style="width: 35px; height: 35px; background-color: #6f2abb;">
                    <div class="my-auto">${params}</div>
                </div>
                `;
              return returned;
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
            display: 'Tanggal Gabung',
            name: 'member_join_datetime',
            type: 'date'
          },
          {
            display: 'Nama',
            name: 'member_name',
            type: 'text'
          },
          {
            display: 'Username',
            name: 'member_account_username',
            type: 'text'
          },
          {
            display: 'Level',
            name: 'level',
            type: 'text'
          },
        ],
        sortName: "member_join_datetime",
        sortOrder: "DESC",
        tableIsResponsive: true,
        select: false,
        multiSelect: false,
        buttonAction: [],
        success: (res) => {
          $('#span_sponsor').html(res.data.pagination.total_data);
        }
      })
    }

    getDownline()
  })
</script>
<!-- END: Content-->