<style>
  .table {
    position: relative;
  }

  .table-responsive {
    max-height: calc(100vh - 0px);
  }

  .table th,
  .table td {
    padding: 0.4rem 1rem;
    white-space: nowrap;
  }

  th {
    position: -webkit-sticky;
    position: sticky;
    top: 0;
    background: white;
  }
</style>
<section id="horizontal-vertical">
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
            <div id="table-member"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .table-member-dtTable-limit {
    display: none;
  }
</style>

<script type="text/javascript">
  $(function() {
    $("#table-member").bind("DOMSubtreeModified", function() {
      if ($("#table-member").height() > 60) {
        $("#pageLoader").hide();
      }
    });
  });

  let dataMember = {
    limit: 5,
    selectedID: 0,
  }

  $(document).ready(function() {
    $("#table-member").dataTableLib({
      url: window.location.origin + '/admin/service/member/getDataSponsor',
      selectID: 'member_id',
      colModel: [{
          display: 'Tanggal Update',
          name: 'update_datetime',
          sortAble: true,
          align: 'left',
          export: false
        },
        {
          display: 'Kode Mitra',
          name: 'network_code',
          sortAble: false,
          align: 'left',
          export: false
        },
        {
          display: 'Nama Mitra',
          name: 'member_name',
          sortAble: false,
          align: 'left',
          export: false
        },
        {
          display: 'Kota Asal',
          name: 'city_name',
          sortAble: false,
          align: 'left',
          render: (param, args) => {
            let city = args.city_name == '' ? '-' : args.city_name;

            return `${city}`;
          },
          export: false
        },
        {
          display: 'Total Sponsoring Registrasi',
          name: 'count',
          sortAble: true,
          align: 'left',
          export: false
        },
      ],
      options: {
        limit: [10, 25, 50, 100],
        currentLimit: 10
      },
      search: true,
      searchTitle: 'Pencarian Data Top Komisi Mitra',
      searchItems: [{
          display: 'Tanggal Update',
          name: 'update_datetime',
          type: 'date'
        },
        {
          display: 'Kode Mitra',
          name: 'network_code',
          type: 'text'
        },
        {
          display: 'Nama Mitra',
          name: 'member_name',
          type: 'text'
        },
        {
          display: 'Peringkat',
          name: 'network_rank',
          type: 'text'
        },
        {
          display: 'Kota Asal',
          name: 'city_name',
          type: 'text'
        }
      ],
      buttonAction: [],
      sortName: 'count',
      sortOrder: "desc ,update_datetime ASC",
      tableIsResponsive: true
    });
  });
</script>