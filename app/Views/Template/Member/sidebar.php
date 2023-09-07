<style>
  .menu-item {
    white-space: pre-line;
  }
</style>
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
  <div class="navbar-header">
    <ul class="nav navbar-nav flex-row h-100">
      <li class="nav-item mr-auto h-100"><a class="navbar-brand mt-0 d-inline-block h-100" href="<?php BASEURL  ?>/member/dashboard">
          <div class="brand-logo h-100" style="width: auto; height: auto;">
            <img class="logo logo-expanded" src="<?php echo base_url(); ?>/logo.png" style="width :85px; height:auto; opacity: .7; left: 0px; margin-left: 4rem; margin-top: -0.5rem;" />
            <img class="logo logo-collapsed" src="<?php echo base_url(); ?>/logo-small.png" style="width :40px; height:auto;opacity: .7;left: -6px;" />
          </div>
          <!-- <h2 class="brand-text mb-0">Frest</h2> -->
        </a></li>
      <li class="nav-item nav-toggle h-100"><a class="nav-link modern-nav-toggle pr-0 h-100" data-toggle="collapse"><i class="bx bx-x d-block d-xl-none font-medium-4 primary toggle-icon"></i><i class="toggle-icon bx bx-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary" data-ticon="bx-disc"></i></a></li>
    </ul>
  </div>
  <div class="shadow-bottom"></div>
  <div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="">
      <li class="nav-item" id="dashboard_menu">
        <a href="<?php BASEURL  ?>/member/dashboard" class="m-0 px-1">
          <i class=" bx bxs-home"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>

      <li class="nav-item">
        <a href="<?php BASEURL  ?>/member/stockist/list" class="m-0 px-1">
          <i class=" bx bxs-store"></i>
          <span class="menu-title">Daftar Master/Stokis</span>
        </a>
      </li>

      <li class=" nav-item">
        <a href="#" class="m-0 px-1">
          <i class="bx bxs-box"></i>
          <span class="menu-title">Penerimaan Paket</span>
        </a>
        <ul class="menu-content">
          <li>
            <a href="<?php BASEURL  ?>/member/transaction/receivement-warehouse">
              <i class="bx bx-right-arrow-alt"></i>
              <span class="menu-title">Perusahaan</span>
            </a>
          </li>
          <li>
            <a href="<?php BASEURL  ?>/member/transaction/receivement-stockist">
              <i class="bx bx-right-arrow-alt"></i>
              <span class="menu-title">Master/Stokis</span>
            </a>
          </li>
        </ul>
      </li>

      <?php if (session('member')['member_is_network']) { ?>
        <li class=" nav-item">
          <a href="#" class="m-0 px-1">
            <i class="bx bxs-group"></i>
            <span class="menu-title">Jaringan</span>
          </a>
          <ul class="menu-content">
            <li>
              <a href="<?php BASEURL  ?>/member/network/genealogy">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Pohon Jaringan</span>
              </a>
            </li>
            <li>
              <a href="<?php BASEURL  ?>/member/network/sponsor">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Sponsor Pribadi</span>
              </a>
            </li>
            <li>
              <a href="<?php BASEURL  ?>/member/network/downline">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Downline</span>
              </a>
            </li>
            <!-- <li>
							<a href="<?php BASEURL  ?>/member/network/netgrow">
								<i class="bx bx-right-arrow-alt"></i>
								<span class="menu-item">Pertumbuhan Jaringan</span>
							</a>
						</li> -->
            <!-- <li>
							<a href="<?php BASEURL  ?>/member/network/registration">
								<i class="bx bx-right-arrow-alt"></i>
								<span class="menu-item">Registrasi</span>
							</a>
						</li> -->
          </ul>
        </li>

        <li class=" nav-item">
          <a href="#" class="m-0 px-1">
            <i class="bx bxs-data"></i>
            <span class="menu-title">Komisi</span>
          </a>
          <ul class="menu-content">
            <li>
              <a href="<?php BASEURL  ?>/member/bonus/summary">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Summary Komisi</span>
              </a>
            </li>
            <li>
              <a href="<?php BASEURL  ?>/member/bonus/log">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Riwayat Komisi</span>
              </a>
            </li>
            <li>
              <a href="<?php BASEURL  ?>/member/bonus/log-transfer">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Riwayat Transfer Komisi</span>
              </a>
            </li>
          </ul>
        </li>

        <!-- reward -->
        <li class=" nav-item">
          <a href="#" class="m-0 px-1">
            <i class="bx bxs-gift"></i>
            <span class="menu-title">Poin Reward</span>
          </a>
          <ul class="menu-content">
            <li>
              <a href="<?php BASEURL  ?>/member/reward/">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Daftar Poin Reward</span>
              </a>
            </li>
            <li>
              <a href="<?php BASEURL  ?>/member/reward/log">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Riwayat Poin Reward</span>
              </a>
            </li>
            <li>
              <a href="<?php BASEURL  ?>/member/reward/log-qualified">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Riwayat Perolehan Poin Reward</span>
              </a>
            </li>
          </ul>
        </li>
        <li class=" nav-item">
          <a href="#" class="m-0 px-1">
            <i class="bx bxs-gift"></i>
            <span class="menu-title">Poin Trip</span>
          </a>
          <ul class="menu-content">
            <li>
              <a href="<?php BASEURL  ?>/member/reward/trip">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Daftar Poin Trip</span>
              </a>
            </li>
            <li>
              <a href="<?php BASEURL  ?>/member/reward/log-trip">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Riwayat Poin Trip</span>
              </a>
            </li>
            <li>
              <a href="<?php BASEURL  ?>/member/reward/log-trip-qualified">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Riwayat Perolehan Poin Trip</span>
              </a>
            </li>
          </ul>
        </li>
        <li class=" nav-item">
          <a href="#" class="m-0 px-1">
            <i class='bx bxs-analyse'></i>
            <span class="menu-title">Belanja Ulang</span>
          </a>
          <ul class="menu-content">
            <li>
              <a href="<?php BASEURL  ?>/member/transaction/">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Belanja</span>
              </a>
            </li>
          </ul>
          <ul class="menu-content">
            <li>
              <a href="<?php BASEURL  ?>/member/transaction/log">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Riwayat Belanja</span>
              </a>
            </li>
          </ul>
          <!-- <ul class="menu-content">
						<li>
							<a href="<?php BASEURL  ?>/member/repeatorder/group">
								<i class="bx bx-right-arrow-alt"></i>
								<span class="menu-item">Omzet</span>
							</a>
						</li>
					</ul> -->
        </li>
        <li class=" nav-item">
          <a href="#" class="m-0 px-1">
            <i class='bx bxs-envelope'></i>
            <span class="menu-title">Pesan</span>
          </a>
          <ul class="menu-content">
            <li>
              <a href="<?php BASEURL  ?>/member/message/send">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Buat Pesan</span>
              </a>
            </li>
          </ul>
          <ul class="menu-content">
            <li>
              <a href="<?php BASEURL  ?>/member/message/inbox">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Pesan Masuk</span>
              </a>
            </li>
          </ul>
          <ul class="menu-content">
            <li>
              <a href="<?php BASEURL  ?>/member/message/log">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Pesan Terkirim</span>
              </a>
            </li>
          </ul>
          <ul class="menu-content">
            <li>
              <a href="<?php BASEURL  ?>/member/message/archive">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Arsip</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="<?php BASEURL  ?>/member/testimony/show">
            <i class="bx bx-conversation"></i>
            <span class="menu-title">Testimoni</span>
          </a>
        </li>
      <?php } ?>
      <hr>
      <?php if (session('member')['is_stockist']) { ?>
        <li class=" nav-item">
          <a href="#" class="m-0 px-1">
            <i class="bx bxs-store"></i>
            <span class="menu-title">Transaksi <?= session('member')['stockist_type'] == 'master' ? 'Master' : '' ?> Stokis</span>
          </a>
          <ul class="menu-content">
            <li>
              <a href="<?php BASEURL  ?>/member/stockist/buy">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Transaksi Produk</span>
              </a>
            </li>
            <li>
              <a href="<?php BASEURL  ?>/member/stockist/receipt-payment">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Konfirmasi Pembayaran</span>
              </a>
            </li>
            <li>
              <a href="<?php BASEURL  ?>/member/stockist/receivement">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Penerimaan Stok</span>
              </a>
            </li>
            <li>
              <a href="<?php BASEURL  ?>/member/stockist/stock">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Stok Produk</span>
              </a>
            </li>
            <li>
              <a href="<?php BASEURL  ?>/member/stockist/stock-log">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Riwayat Stok Produk</span>
              </a>
            </li>
            <li>
              <a href="<?php BASEURL  ?>/member/stockist/log">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Riwayat Transaksi</span>
              </a>
            </li>
          </ul>
        </li>

        <li class=" nav-item">
          <a href="#" class="m-0 px-1">
            <span class="badge badge-pill badge-danger badge-up d-none" style="top: 8px;padding-right: 8px;padding-left: 8px;right: 25px;" id="main-menu-stockist">0</span>
            <i class="bx bx-store"></i>
            <span class="menu-title">Transaksi Mitra</span>
          </a>
          <ul class="menu-content">
            <li>
              <a href="<?php BASEURL ?>/member/stockist/transaction">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Data Transaksi Mitra</span>
              </a>
            </li>
            <li>
              <a href="<?php BASEURL ?>/member/stockist/packaging">
                <span class="badge badge-pill badge-danger badge-up d-none" id="sub-menu-stockist-packaging" style="top: 8px;padding-right: 8px;padding-left: 8px;right: 5px;">0</span>
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Pengemasan</span>
              </a>
            </li>
            <li>
              <a href="<?php BASEURL ?>/member/stockist/delivery">
                <span class="badge badge-pill badge-danger badge-up d-none" id="sub-menu-stockist-delivery" style="top: 8px;padding-right: 8px;padding-left: 8px;right: 5px;">0</span>
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Pengiriman</span>
              </a>
            </li>
            <li>
              <a href="<?php BASEURL  ?>/member/stockist/member-log">
                <i class="bx bx-right-arrow-alt"></i>
                <span class="menu-item">Riwayat Transaksi</span>
              </a>
            </li>
          </ul>
        </li>

        <li class=" nav-item">
          <a href="<?php BASEURL  ?>/member/stockist/saldo" class="m-0 px-1">
            <i class="bx bx-money"></i>
            <span class="menu-title">Saldo Stokis</span>
          </a>
        </li>
      <?php } ?>
    </ul>
  </div>
</div>

<script>
  $(function() {
    $("a[href='" + window.location.pathname + "']").closest(".nav-item").addClass("open").closest("li").addClass("active")
    $("a[href='" + window.location.pathname + "']").closest("li").addClass("active")

    $.ajax({
      url: "<?= BASEURL ?>/member/stockist/get-transaction",
      type: "GET",
      data: {},
      success: (res) => {
        let data = res.data

        if (data.total > 0) {
          $('#main-menu-stockist').removeClass('d-none')
          $('#main-menu-stockist').text(`${data.total}`)
        }

        if (data.packaging > 0) {
          $('#sub-menu-stockist-packaging').removeClass('d-none')
          $('#sub-menu-stockist-packaging').text(`${data.packaging}`)
        }

        if (data.delivery > 0) {
          $('#sub-menu-stockist-delivery').removeClass('d-none')
          $('#sub-menu-stockist-delivery').text(`${data.delivery}`)
        }
      },
      error: (err) => {
        res = err.responseJSON
      },
    })
  })
</script>
<!-- END: Main Menu-->