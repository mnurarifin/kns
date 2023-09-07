<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper px-3">

    <div class="content-body">
      <section id="floating-label-layouts">
        <div class="row match-height">
          <div class="col-md-12 col-12">
            <div class="card">
              <div class="card-header">
                <!-- <small class="card-title cl-primary">ID Mitra Anda : <span id="text_member_account_username"></span></small> -->
                <!-- <br /> -->
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
      if (params.get("username")) {
        $("#text_member_account_username").html(params.get("username"))
      }
      if (params.get("sponsor_username")) {
        $("#text_sponsor_username").html(params.get("sponsor_username"))
      }
      if (params.get("member_name")) {
        $("#text_member_name").html(params.get("member_name"))
      }
      if (params.get("sponsor_member_name")) {
        $("#text_sponsor_member_name").html(params.get("sponsor_member_name"))
      }
      if (params.get("invoice_url")) {
        $("#text_invoice_url").prop("href", params.get("invoice_url"))
        $("iframe").attr("src", params.get("invoice_url"))
      }
    }

    checkRedirect()
  })
</script>