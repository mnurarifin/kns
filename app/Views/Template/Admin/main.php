<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta name="google" content="notranslate">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Frest admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Frest admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Administrator - <?php echo isset($title) ? $title : ''; ?></title>
    <link rel="apple-touch-icon" href="<?= BASEURL; ?>/admin-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?= BASEURL; ?>/logo-small.png">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/admin-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/admin-assets/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/admin-assets/vendors/css/extensions/dragula.min.css">
    <script src="<?= BASEURL; ?>/admin-assets/vendors/js/sweetalert2.all.min.js"></script>
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/admin-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/admin-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/admin-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/admin-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/admin-assets/css/themes/dark-layout.css">
    <!-- <link rel="stylesheet" type="text/css" href="< ?php echo base_url(); ?>/admin-assets/css/themes/semi-dark-layout.css"> -->
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/admin-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/admin-assets/css/pages/dashboard-analytics.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/admin-assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/admin-assets/css/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/admin-assets/css/jquery.fileManager.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/admin-assets/css/custom.css">
    <!-- END: Custom CSS-->

    <!-- BEGIN:  CHARTS CSS -->
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/admin-assets/css/Chart.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/admin-assets/css/Chart.min.css">
    <!-- END: CHARTS CSS -->
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/app-assets/vendors/css/forms/select/select2.min.css">
    <style>
        /* custom scrollbar */
        ::-webkit-scrollbar {
            width: 20px;
        }

        ::-webkit-scrollbar-track {
            background-color: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #d6dee1;
            border-radius: 20px;
            border: 6px solid transparent;
            background-clip: content-box;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #a8bbbf;
        }
    </style>
    <!-- BEGIN: Vendor JS-->
    <script src="<?= BASEURL; ?>/admin-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->
    <script src="<?= BASEURL; ?>/app-assets/js/vue@3.2.36.js"></script>

    <script type="text/javascript">
        function ajaxCallback(property) {
            $(document).ready(function() {
                let contentType = property.contentType == undefined ? 'application/x-www-form-urlencoded; charset=UTF-8' : property.contentType;
                let async = property.async == undefined ? true : property.async;
                $.ajax({
                    url: property.url,
                    type: property.method,
                    contentType: contentType,
                    async: async,
                    data: property.data,
                    success: function(response) {
                        property.success(response);
                    },
                    error: function(err) {
                        let response = err.responseJSON;
                        if (response.status == 403 && response.message == 'Unauthorized') {
                            try {
                                Swal.fire({
                                    title: 'Gagal!',
                                    html: 'Session habis. Silahkan login kembali!',
                                    icon: 'error'
                                }).then(() => {
                                    window.location.href = '/login';
                                });
                            } catch (error) {
                                if (confirm('Session habis. Silahkan login kembali!')) {
                                    window.location.href = '/login';
                                } else {
                                    window.location.href = '/login';
                                }
                            }
                        } else {
                            property.error(response);
                        }
                    }
                });
            });
        }

        function checkValidity(form) {
            $(form).addClass('was-validated');
            return form.checkValidity();
        }

        function showAlert(element, type, message) {
            $(element).html(`
                <div class="alert border-${type} alert-dismissible mb-2" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <div class="d-flex align-items-center">
                <i class="bx bx-error"></i>
                <span>
                ${message}
                </span>
                </div>
                </div>
                `);
        }
    </script>

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- Navbar -->
    <?php include('header.php') ?>
    <!-- END: Header-->

    <!-- BEGIN: Main Menu-->
    <?php include('sidebar.php') ?>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <!-- <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top" style="font-size: 12px;">
                        <div class="col-12">
                            <h6 class="content-header-title float-left pr-1 mb-0">Administrator</h6>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="< ?=base_url()?>/dashboard"><i class="bx bx-home-alt"></i></a></li>
                                    < ?php
                                        if(isset($arrBreadcrumbs) && !empty($arrBreadcrumbs)){
                                            $c=1;
                                            foreach($arrBreadcrumbs as $breadcrumbs => $linkBreadcrumbs) {
                                                if(count($arrBreadcrumbs) == $c) {
                                                    echo '<li class="breadcrumb-item active">' . $breadcrumbs . '</li>';
                                                } else {
                                                    echo '<li class="breadcrumb-item"><a href="' . base_url() . '/' . $linkBreadcrumbs . '">' . $breadcrumbs . '</a></li>';
                                                }
                                                $c++;
                                            }
                                        }
                                    ?>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="content-body">
                <div><?php echo isset($content) ? $content : ''; ?></div>
            </div>
        </div>
    </div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-left d-inline-block"><?= date("Y") ?> &copy; KIMSTELLA</span>
            <button class="btn btn-primary btn-icon scroll-top" type="button"><i class="bx bx-up-arrow-alt"></i></button>
        </p>
    </footer>
    <!-- END: Footer-->

    <script src="<?= BASEURL; ?>/admin-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js" defer></script>
    <script src="<?= BASEURL; ?>/admin-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js" defer></script>
    <script src="<?= BASEURL; ?>/admin-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js" defer></script>

    <!-- BEGIN: Page Vendor JS-->
    <script src="<?= BASEURL; ?>/admin-assets/vendors/js/extensions/dragula.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="<?= BASEURL; ?>/admin-assets/js/scripts/configs/vertical-menu-light.js"></script>
    <script src="<?= BASEURL; ?>/admin-assets/js/core/app-menu.js"></script>
    <script src="<?= BASEURL; ?>/admin-assets/js/core/app.js"></script>
    <script src="<?= BASEURL; ?>/admin-assets/js/scripts/components.js"></script>
    <script src="<?= BASEURL; ?>/admin-assets/js/scripts/footer.js"></script>
    <script src="<?= BASEURL; ?>/admin-assets/js/jquery-ui.min.js"></script>
    <script src="<?= BASEURL; ?>/admin-assets/js/dataTableLib.js"></script>
    <script src="<?= BASEURL; ?>/admin-assets/js/moment.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/forms/select/select2.min.js"></script>
    <script type="text/javascript">
        function toTanggal(datetime) {
            var month_text = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

            var datetime = new Date(datetime);
            var day = datetime.getDate().toString();
            var month_index = datetime.getMonth();
            var year = datetime.getFullYear();
            var hour = datetime.getHours().toString();
            var minute = datetime.getMinutes().toString();
            var second = datetime.getSeconds().toString();

            if (day.length < 2) day = '0' + day;
            if (hour.length < 2) hour = '0' + hour;
            if (minute.length < 2) minute = '0' + minute;
            if (second.length < 2) second = '0' + second;

            return [day, month_text[month_index], year].join(' ') + " " + [hour, minute, second].join(':');
        };
    </script>

    <script>
        $(document).ready(function() {
            if ($(window).width() <= 425) {
                $('.dtable-action > span ').hide()
                $('.bx-search').next().hide()
            } else {
                $('.dtable-action > span ').show()
                $('.bx-search').next().show()
            }

            $.ajax({
                url: window.location.origin + '/admin/service/investor/getTotalWithdrawalPending',
                method: 'GET',
                dataType: 'json',
                success: function(res) {
                    let data = res.data;
                    if (data.results.total > 0) {
                        $("span:contains('Investor')").parent('a').parent('li.nav-item').append(`<span id="parent-member-trx" class="badge badge-pill badge-danger badge-up" style="top: 8px;padding-right: 8px;padding-left: 8px;right: 0px;">${data.results.total}</span>`)
                        $("span:contains('Investor')").parent().siblings().children("li").children("a").children("span:contains('Approval Withdrawal')").append(`<span class="badge badge-pill badge-danger badge-up" style="top: 8px;padding-right: 8px;padding-left: 8px;right: -4px;">${data.results.total}</span>`)
                    }

                }
            });

            $.ajax({
                url: window.location.origin + '/admin/service/transaction/get-count-trx',
                method: 'GET',
                dataType: 'json',
                success: function(res) {
                    let data = res.data

                    if (parseInt(data.member) > 0) {
                        $("span:contains('Transaksi Mitra')").parent('a').parent('li.nav-item').append(`<span id="parent-member-trx" class="badge badge-pill badge-danger badge-up" style="top: 8px;padding-right: 8px;padding-left: 8px;right: -13px;">${data.member}</span>`)
                    }

                    if (parseInt(data.stockist) > 0) {
                        $("span:contains('Transaksi Stokis')").parent('a').parent('li.nav-item').append(`<span id="parent-stockist-trx" class="badge badge-pill badge-danger badge-up" style="top: 8px;padding-right: 8px;padding-left: 8px;right: -13px;">${data.stockist}</span>`)
                    }

                    if (parseInt(data.withdraw_ewallet) > 0) {
                        $("span:contains('Saldo Master/Stokis')").parent('a').parent('li.nav-item').append(`<span id="parent-stockist-withdrawal" class="badge badge-pill badge-danger badge-up" style="top: 8px;padding-right: 8px;padding-left: 8px;right: -13px;">${data.withdraw_ewallet}</span>`)
                    }

                    if (parseInt(data.message) > 0) {
                        $("span:contains('Pesan')").parent('a').parent('li.nav-item').append(`<span id="parent-message" class="badge badge-pill badge-danger badge-up" style="top: 8px;padding-right: 8px;padding-left: 8px;right: -13px;">${data.message}</span>`)
                    }

                    setTimeout(function() {
                        // if ($("span:contains('Data Transaksi Mitra')").closest(".nav-item").hasClass('open') || $("span:contains('Data Transaksi Mitra')").closest(".nav-item").hasClass('hover')) {
                        //     $('#parent-member-trx').remove()
                        // }

                        if (parseInt(data.detail_member.pengemasan) > 0) {
                            $("span:contains('Transaksi Mitra')").parent().siblings().children("li").children("a").children("span:contains('Pengemasan')").append(`<span class="badge badge-pill badge-danger badge-up" style="top: 8px;padding-right: 8px;padding-left: 8px;right: 5px;">${data.detail_member.pengemasan}</span>`)
                        }

                        if (parseInt(data.detail_member.pengiriman) > 0) {
                            $("span:contains('Transaksi Mitra')").parent().siblings().children("li").children("a").children("span:contains('Pengiriman')").append(`<span class="badge badge-pill badge-danger badge-up" style="top: 8px;padding-right: 8px;padding-left: 8px;right: 5px;">${data.detail_member.pengiriman}</span>`)
                        }

                        if (parseInt(data.detail_stockist.pengemasan) > 0) {
                            $("span:contains('Transaksi Stokis')").parent().siblings().children("li").children("a").children("span:contains('Pengemasan')").append(`<span class="badge badge-pill badge-danger badge-up" style="top: 8px;padding-right: 8px;padding-left: 8px;right: 5px;">${data.detail_stockist.pengemasan}</span>`)
                        }

                        if (parseInt(data.detail_stockist.pengiriman) > 0) {
                            $("span:contains('Transaksi Stokis')").parent().siblings().children("li").children("a").children("span:contains('Pengiriman')").append(`<span class="badge badge-pill badge-danger badge-up" style="top: 8px;padding-right: 8px;padding-left: 8px;right: 5px;">${data.detail_stockist.pengiriman}</span>`)
                        }

                        if (parseInt(data.withdraw_ewallet) > 0) {
                            $("span:contains('Saldo Master/Stokis')").parent().siblings().children("li").children("a").children("span:contains('Approval Transfer Saldo')").append(`<span class="badge badge-pill badge-danger badge-up" style="top: 8px;padding-right: 8px;padding-left: 8px;right: 5px;">${data.withdraw_ewallet}</span>`)
                        }

                        if (parseInt(data.message) > 0) {
                            $("span:contains('Pesan')").parent().siblings().children("li").children("a").children("span:contains('Kotak Masuk')").append(`<span class="badge badge-pill badge-danger badge-up" style="top: 8px;padding-right: 8px;padding-left: 8px;right: 5px;">${data.message}</span>`)
                        }
                    }, 500);
                }
            })
        })
    </script>
    <!-- <script src="< ?php echo base_url(); ?>/admin-assets/js/jquery.ui.plupload/jquery.ui.plupload.min.js"></script>
        <script src="< ?php echo base_url(); ?>/admin-assets/js/jquery.plupload.queue/jquery.plupload.queue.min.js"></script> -->
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <!-- <script src="< ? php echo base_url(); ?>/admin-assets/js/scripts/pages/dashboard-analytics.js"></script> -->
    <!-- END: Page JS-->

</body>
<!-- END: Body-->

</html>