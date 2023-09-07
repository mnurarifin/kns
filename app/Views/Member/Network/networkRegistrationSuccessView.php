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
                <h4 class="card-title cl-primary">Registrasi Mitra Berhasil!</h4>
              </div>
              <div class="card-content">
                <div class="card-body">
                  <div class="card border mb-1">
                    <div class="row py-3 align-center">
                      <div class="col-sm-12 col-md-6 col-6 justify-content-center my-25">
                        <img src="/app-assets/images/register-ok.png" style="height: 240px;display: block; margin: 1rem auto;">
                      </div>
                      <div class="col-sm-12 col-md-6 col-6 my-25">
                        <h5 class="cl-primary mb-2" style="line-height: 1.75rem;">Mitra baru Anda telah berhasil didaftarkan.</h5>

                        <p class="mb-50 font-weight-bold"><span style="width: 100px;display: inline-block;">Username </span>: <span class="text-black" id="text_member_account_username"></span></p>
                        <p class="mb-50 font-weight-bold"><span style="width: 100px;display: inline-block;">Nama </span>: <span class="text-black" id="text_member_name"></span></p>
                        <p class="mb-50 font-weight-bold"><span style="width: 100px;display: inline-block;">Sponsor </span>: <span class="text-black" id="text_sponsor_username"></span> (<span class="text-black" id="text_sponsor_member_name"></span>)</p>
                      </div>
                    </div>
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
    let id

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
    }

    goto = () => {
      checkRedirect()
      location.href = `/member/transaction/payment?id=${id}`
    }

    getBankCompany = () => {
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-bank-company",
        type: "GET",
        success: (res) => {
          $.each(res.data.results, (i, val) => {
            $("#bank_company").append(`
            <div class="col text-center">
              <img src="${val.bank_logo}" style="width: 100px;"><br/>
              <label>${val.bank_name}</label><br/>
              <label>${val.bank_company_bank_acc_number}</label><br/>
              <label>a.n. ${val.bank_company_bank_acc_name}</label><br/>
            </div>
            `)
          })
        },
      })
    }

    checkRedirect()
    getBankCompany()
  })
</script>