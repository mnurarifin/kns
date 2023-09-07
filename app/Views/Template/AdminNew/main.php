<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="<?= PROJECT_DESCRIPTION; ?>">
    <meta name="keywords" content="">
    <meta name="author" content="PIXINVENT">
    <title>ADMINISTRATOR - <?= COMPANY_NAME;?></title>
    <link rel="apple-touch-icon" href="<?= BASEURL; ?>/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?= BASEURL; ?>/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700"
        rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?= BASEURL; ?>/app-assets/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css"
        href="<?= BASEURL; ?>/app-assets/vendors/css/extensions/dragula.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?= BASEURL; ?>/app-assets/vendors/css/editors/quill/quill.snow.css">
    <link rel="stylesheet" type="text/css"
        href="<?= BASEURL; ?>/app-assets/vendors/css/tables/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?= BASEURL; ?>/app-assets/vendors/css/pickers/pickadate/pickadate.css">
    <link rel="stylesheet" type="text/css"
        href="<?= BASEURL; ?>/app-assets/vendors/css/pickers/daterange/daterangepicker.css">
    <link rel="stylesheet" type="text/css"
        href="<?= BASEURL; ?>/app-assets/tree.js/tree.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/app-assets/css/themes/semi-dark-layout.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css"
        href="<?= BASEURL; ?>/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/app-assets/css/plugins/forms/wizard.css">
    <!-- <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/app-assets/css/pages/dashboard-analytics.css">
		<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/app-assets/css/pages/app-email.css"> -->
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/app-assets/css/style.css?id=<?= strtotime(date("Y-m-d H:i:s")) ?>">
    <!-- END: Custom CSS-->

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- BEGIN: Vendor JS-->
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/vendors.min.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
    <!-- BEGIN Vendor JS-->
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns  navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- BEGIN: Header-->
    <?php include('header.php') ?>
    <!-- END: Header-->

    <!-- BEGIN: Main Menu-->
    <?php include('sidebar.php') ?>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <?= isset($content) ? $content : ''; ?>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <?php include('footer.php') ?>
    <!-- END: Footer-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/charts/apexcharts.min.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/extensions/dragula.min.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/editors/quill/quill.min.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/extensions/jquery.steps.min.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Datatables JS-->
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/js/scripts/datatables/datatable-extended.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/js/scripts/datatables/datatable.js"></script>
    <!-- END: Datatables JS-->

    <!-- BEGIN: Datepicker JS-->
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/pickers/daterange/moment.min.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/vendors/js/pickers/daterange/daterangepicker.js"></script>
    <!-- <script src="<?= BASEURL; ?>/app-assets/js/scripts/pickers/dateTime/pick-a-datetime.js"></script> -->
    <!-- END: Datepicker JS-->

    <!-- BEGIN: Theme JS-->
    <script src="<?= BASEURL; ?>/app-assets/js/core/app-menu.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/js/core/app.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/js/scripts/components.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/js/scripts/footer.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <!-- <script src="<?= BASEURL; ?>/app-assets/js/scripts/pages/dashboard-analytics.js"></script> -->
    <script src="<?= BASEURL; ?>/app-assets/js/scripts/forms/wizard-steps.js"></script>
    <script src="<?= BASEURL; ?>/app-assets/tree.js/tree.js"></script>
    <!-- END: Page JS-->

</body>
<!-- END: Body-->

</html>