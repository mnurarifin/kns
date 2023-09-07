<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="content-wrapper" style="background-color: white; color: black;">
    <div class="content-header row">
      <div class="content-header-left col-12 mb-2 mt-1">
        <div class="row breadcrumbs-top float-right">
          <div class="col-12">
            <button class="btn btn-primary" id="btn_print_statement">Cetak</button>
          </div>
        </div>
      </div>
    </div>
    <div class="content-body">
      <div class="row">
        <div class="col-12">
          <img class="logo" src="<?php echo base_url(); ?>/app-assets/images/logo.png" / width="60px">
        </div>
        <div class="col-md-12 col-12 pt-1">
          <div class="row ml-0 justify-content-between">
            <div class="pl-0 col-8">
              <p>
                <b><?= COMPANY_NAME ?></b> <br>
                Alamat : <?= DELIVERY_WAREHOUSE_ADDRESS ?><br>
                Hp : <?= WA_CS_NUMBER ?>
              </p>
            </div>
            <div class="pl-0 col-4 text-right">
              <h5>DETAIL KOMISI</h5>
              <p>Komisi Statement</p>
            </div>
          </div>
        </div>
        <hr width="97%">
        <div class="col-12">
          <div class="row ml-0 justify-content-between">
            <div class="col-6 pl-0">
              Periode <br>
              <span class="bonus_transfer_datetime">
            </div>
            <div class="col-6 pl-0 text-right">
              Status Transfer <br>
              <span id="status_transfer"></span>
            </div>
            <div class="col-12 text-right py-1">
              Tanggal Transfer <br>
              <span class="bonus_transfer_datetime"></span>
            </div>
            <div class="col-6 pl-0">
              Kode Mitra <br>
              <span id="bonus_transfer_network_code"></span>
            </div>
            <div class="col-6 pl-0 text-right">
              Nama Bank <br>
              <span id="bonus_transfer_bank_name"></span>
            </div>
            <div class="col-6 pl-0 py-1">
              Nama Mitra <br>
              <span id="bonus_transfer_member_bank_account_name"></span>
            </div>
            <div class="col-6 pl-0 text-right py-1">
              Nomor Rekening <br>
              <span id="bonus_transfer_bank_account_no"></span>
            </div>
            <div class="col-6 pl-0 pb-1">
              Jenis Komisi
            </div>
            <div class="col-6 pl-0 text-right pb-1">
              Jumlah (Rp)
            </div>
            <div class="col-6 pl-0">
              Sponsor
            </div>
            <div class="col-6 pl-0 text-right">
              <span id="bonus_transfer_sponsor"></span>
            </div>
            <hr width="100%">
            <div class="col-6 pl-0">
              Generasi
            </div>
            <div class="col-6 pl-0 text-right">
              <span id="bonus_transfer_gen_node"></span>
            </div>
            <hr width="100%">
            <div class="col-6 pl-0">
              Power Leg
            </div>
            <div class="col-6 pl-0 text-right">
              <span id="bonus_transfer_power_leg"></span>
            </div>
            <hr width="100%">
            <div class="col-6 pl-0">
              Matching Leg
            </div>
            <div class="col-6 pl-0 text-right">
              <span id="bonus_transfer_matching_leg"></span>
            </div>
            <hr width="100%">
            <div class="col-6 pl-0">
              Cash Reward
            </div>
            <div class="col-6 pl-0 text-right">
              <span id="bonus_transfer_cash_reward"></span>
            </div>
            <hr width="100%">
            <div class="col-12">
              <div class="row">
                <div class="col-6"></div>
                <div class="col-6">
                  <div class="row">
                    <div class="col-6 pl-0">
                      Total Bonus
                    </div>
                    <div class="col-6 pl-0 text-right">
                      <span id="bonus_transfer_total_bonus"></span>
                    </div>
                    <hr width="100%">
                    <div class="col-6 pl-0 text-danger">
                      Potongan Admin
                    </div>
                    <div class="col-6 pl-0 text-right text-danger">
                      <span id="bonus_transfer_adm_charge_value"></span>
                    </div>
                    <hr width="100%">
                    <div class="col-6 pl-0 text-danger">
                      Potongan Pajak
                    </div>
                    <div class="col-6 pl-0 text-right text-danger">
                      <span id="bonus_transfer_tax_value"></span>
                    </div>
                    <hr width="100%">
                    <div class="col-6 pl-0">
                      Total Transfer
                    </div>
                    <div class="col-6 pl-0 text-right">
                      <span id="bonus_transfer_nett"></span>
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

<iframe class="iframe-print d-none" id="print-delivery" src="/member/bonus/statement-print"></iframe>

<script>
  $(function() {
    let id
    let bonus_transfer

    checkRedirect = () => {
      let params = new URLSearchParams(window.location.search)
      if (params.get("id")) {
        id = params.get("id")

        getBonusTransfer(id)
      }
    }

    getBonusTransfer = (id) => {
      $.ajax({
        url: "<?= BASEURL ?>/member/bonus/get-bonus-transfer/" + id,
        type: "GET",
        success: (res) => {
          data = res.data.results
          $(".bonus_transfer_datetime").html(data.bonus_transfer_date_formatted)
          $("#bonus_transfer_network_code").html(data.member_account_username)
          $("#bonus_transfer_bank_name").html(data.bonus_transfer_member_bank_name)
          $("#bonus_transfer_member_bank_account_name").html(data.bonus_transfer_member_bank_account_name)
          $("#bonus_transfer_bank_account_no").html(data.bonus_transfer_member_bank_account_no)
          $("#bonus_transfer_sponsor").html(data.bonus_transfer_sponsor)
          $("#bonus_transfer_gen_node").html(data.bonus_transfer_gen_node)
          $("#bonus_transfer_power_leg").html(data.bonus_transfer_power_leg)
          $("#bonus_transfer_matching_leg").html(data.bonus_transfer_matching_leg)
          $("#bonus_transfer_cash_reward").html(data.bonus_transfer_cash_reward)
          $("#bonus_transfer_total_bonus").html(data.total)
          $("#bonus_transfer_adm_charge_value").html(data.bonus_transfer_adm_charge_value)
          $("#bonus_transfer_tax_value").html(data.bonus_transfer_tax_value)
          $("#bonus_transfer_nett").html(data.total - data.bonus_transfer_adm_charge_value - data.bonus_transfer_tax_value)
          $('#status_transfer').html(data.bonus_transfer_status == 'success' ? `<button class="btn btn-outline-success rounded-pill btn-sm py-25 mt-25" style="border: 1px solid #39DA8A; background-color: transparent; color: #39DA8A !important;">Sukses</button>` : `<button class="btn btn-outline-danger rounded-pill btn-sm py-25 mt-25" style="border: 1px solid #da3939; background-color: transparent; color: #da3939 !important;">Gagal</button>`)
          bonus_transfer = data
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

    $("#btn_print_statement").on("click", (ev) => {
      var frame_ = document.querySelector("iframe");
      let footer = frame_.contentDocument.querySelector("footer");
      footer.remove();

      let frame = $('#print-delivery').get(0).contentWindow
      frame.generateTable(bonus_transfer)
      frame.print()
      return false
    })

    checkRedirect()
  })
</script>