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
            <select class="form-control" id="member_children" style="width: fit-content;">
            </select>
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
        url: window.location.origin + "/member/reward/get-reward-trip-log/" + $("#member_children").val(),
        selectID: "bonus_log_id",
        colModel: [{
            display: 'Tanggal',
            name: 'reward_point_log_datetime_formatted',
            align: 'left',
          },
          {
            display: 'Value',
            name: 'reward_point_log_value',
            align: 'right',
          },
          {
            display: 'Keterangan',
            name: 'reward_point_log_note',
            align: 'right',
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
          name: 'reward_point_log_datetime',
          type: 'date'
        }, ],
        sortName: "reward_point_log_datetime",
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

    $("#member_children").on("change", () => {
      getBonusLog()
    })

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