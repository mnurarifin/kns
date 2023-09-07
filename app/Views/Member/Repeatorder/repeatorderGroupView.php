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
          <button class="btn btn-outline-primary active ml-2" id="group">Grup</button>
          <button class="btn btn-outline-primary" id="personal">Personal</button>
          <div class="mb-1" id="table">
          </div>
          <div class="mb-1 d-none" id="table-personal">
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
        url: window.location.origin + "/member/repeatorder/get-repeat-order-group/",
        selectID: "ro_group_id",
        colModel: [{
            display: 'Tanggal',
            name: 'ro_group_datetime_formatted',
            align: 'left',
          },
          {
            display: 'Jalur',
            name: 'ro_group_downline_username',
            align: 'left',
          },
          {
            display: 'Downline',
            name: 'ro_group_trigger_username',
            align: 'left',
          },
          {
            display: 'Posisi',
            name: 'ro_group_position',
            align: 'left',
            render: (params) => {
              return params == "L" ? "Kiri" : "Kanan"
            }
          },
          {
            display: 'Poin',
            name: 'ro_group_bv',
            align: 'left',
          },
          {
            display: 'Level',
            name: 'ro_group_level',
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
      })

      $("#table-personal").dataTableLib({
        url: window.location.origin + "/member/repeatorder/get-repeat-order-personal/",
        selectID: "ro_personal_id",
        colModel: [{
            display: 'Tanggal',
            name: 'ro_personal_datetime_formatted',
            align: 'left',
          },
          {
            display: 'Poin',
            name: 'ro_personal_bv',
            align: 'left',
          }, {
            display: 'Keterangan',
            name: 'ro_personal_note',
            align: 'left',
          }
        ],
        options: {
          limit: [10, 15, 20, 50, 100],
          currentLimit: 10,
        },
        search: true,
        searchTitle: "Pencarian",
        searchItems: [{
          display: 'Tanggal',
          name: 'ro_personal_datetime',
          type: 'date'
        }],
        sortName: "ro_personal_datetime",
        sortOrder: "DESC",
        tableIsResponsive: true,
        select: false,
        multiSelect: false,
        buttonAction: [],
      })
    }

    getDownline()
  })

  $('#personal').on('click', function() {
    $('#group').removeClass('active');
    $('#personal').addClass('active');

    $('#table').addClass('d-none')
    $('#table-personal').removeClass('d-none')
  })

  $('#group').on('click', function() {
    $('#group').addClass('active');
    $('#personal').removeClass('active');
    $('#table-personal').addClass('d-none')
    $('#table').removeClass('d-none')
  })
</script>
<!-- END: Content-->