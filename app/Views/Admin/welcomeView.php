<style>
    #buttonType .btn {
        border-radius: 0;
    }
</style>

<!-- dashboard start -->
<section id="dashboard-ecommerce">
    <div class="row">
        <!-- TOTAL BONUS -->
        <div class="col-xl-12 col-12 dashboard-order-summary">
            <div class="card">
                <div class="row">
                    <!-- Order Summary Starts -->
                    <div class="col-md-8 col-12 order-summary  pr-md-0">
                        <div class="card mb-0">
                            <h4 style="margin-left: 20px; margin-top: 30px">Selamat Datang <?= $admin_name; ?>,</h4>
                            <div class="card-header d-block pt-4">
                                <p>Login terakhir Anda: <?= $last_login ? date("d-m-Y", strtotime($last_login)) : "-"; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END -->
</section>
<!-- dashboard end -->