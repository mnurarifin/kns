<div class="content-wrapper" style="background-color: white; color: black;">
  <div class="content-header row">
    <div class="content-header-left col-12 mb-2 mt-1">
      <div class="row breadcrumbs-top float-right">
        <div class="col-12">
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
            <button class="btn btn-outline-success rounded-pill btn-sm py-25 mt-25" style="border: 1px solid #39DA8A; background-color: transparent; color: #39DA8A !important;">Sukses</button>
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

<script>
  $(function() {
    generateTable = (data) => {
      $(".bonus_transfer_datetime").html(data.bonus_transfer_date_formatted)
      $("#bonus_transfer_network_code").html(data.member_account_username)
      $("#bonus_transfer_bank_name").html(data.bonus_transfer_member_bank_name)
      $("#bonus_transfer_member_bank_account_name").html(data.bonus_transfer_member_bank_account_name)
      $("#bonus_transfer_bank_account_no").html(data.bonus_transfer_member_bank_account_no)
      $("#bonus_transfer_sponsor").html(formatDecimal(data.bonus_transfer_sponsor))
      $("#bonus_transfer_gen_node").html(formatDecimal(data.bonus_transfer_gen_node))
      $("#bonus_transfer_power_leg").html(formatDecimal(data.bonus_transfer_power_leg))
      $("#bonus_transfer_matching_leg").html(formatDecimal(data.bonus_transfer_matching_leg))
      $("#bonus_transfer_cash_reward").html(formatDecimal(data.bonus_transfer_cash_reward))
      $("#bonus_transfer_total_bonus").html(formatDecimal(data.total))
      $("#bonus_transfer_adm_charge_value").html(formatDecimal(data.bonus_transfer_adm_charge_value))
      $("#bonus_transfer_tax_value").html(formatDecimal(data.bonus_transfer_tax_value))
      $("#bonus_transfer_nett").html(formatDecimal(data.total - data.bonus_transfer_adm_charge_value - data.bonus_transfer_tax_value))
    }
  })

  formatDecimal = ($params) => {
    let formatter = new Intl.NumberFormat('id-ID', {
      style: 'decimal',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    })
    return formatter.format($params)
  }
</script>