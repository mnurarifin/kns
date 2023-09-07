<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="content-wrapper">

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
      <section id="floating-label-layouts">
        <div class="row match-height">
          <div class="col-md-12 col-12">
            <div class="card">
              <div class="card-header">
                <small class="card-title cl-primary">Jika halaman pembayaran tidak muncul, klik <a href="" id="text_invoice_url">disini</a>!</small>
              </div>
              <div class="card-content">
                <div class="card-body">
                  <div class="row align-center" style="height: 640px;">
                    <iframe src="" frameBorder="0" style="width: 100%;"></iframe>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>
<!-- END: Content-->

<script>
  $(function() {
    checkRedirect = () => {
      let params = new URLSearchParams(window.location.search)
      if (params.get("invoice_url")) {
        $("#text_invoice_url").prop("href", params.get("invoice_url"))
        $("iframe").attr("src", params.get("invoice_url"))
      }
    }

    checkRedirect()
  })
</script>