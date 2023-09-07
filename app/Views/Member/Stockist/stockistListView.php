<div class="app-content content" id="app">
    <div class="content-overlay">
    </div>
    <div class="content-loading">
        <i class="bx bx-loader bx-spin"></i>
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
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col col-12 col-md-12 mb-1">
                    <div id="table">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        app.generateTable();
        app.hideLoading();
    })

    let app = Vue.createApp({
        data: function() {
            return {
                button: {
                    formBtn: {
                        disabled: false
                    }
                },
                modal: {
                    data: {
                        title: "",
                        btnTitle: "",
                        btnAction: "",
                    },
                    form: {},
                },
                alert: {
                    success: {
                        status: false,
                        content: '',
                    },
                    danger: {
                        status: false,
                        content: '',
                    }
                },
            }
        },
        methods: {
            hideLoading() {
                $("#pageLoader").hide();
            },
            generateTable() {
                $("#table").dataTableLib({
                    url: window.location.origin + '/member/stockist/get-list',
                    selectID: 'stockist_member_id',
                    colModel: [{
                        display: "Nama Stokis",
                        name: "stockist_name",
                        align: "left",
                    }, {
                        display: "Tipe Stokis",
                        name: "stockist_type",
                        align: "left",
                        render: (params) => {
                            return params == 'mobile' ? 'Stokis' : 'Master Stokis'
                        }
                    }, {
                        display: "No Telpon",
                        name: "stockist_mobilephone",
                        align: "left",
                    }, {
                        display: "Kota/ Kabupaten",
                        name: "stockist_city_name",
                        align: "left",
                    }],
                    buttonAction: [],
                    options: {
                        limit: [10, 25, 50, 100],
                        currentLimit: 10,
                    },
                    search: true,
                    searchTitle: 'Pencarian',
                    searchItems: [{
                        display: "Nama Stokis",
                        name: "stockist_name",
                        type: "text"
                    }, {
                        display: "No Telpon",
                        name: "stockist_mobilephone",
                        type: "text"
                    }, {
                        display: "Kota",
                        name: "stockist_city_name",
                        type: "text"
                    }],
                    sortName: "stockist_member_id",
                    sortOrder: "desc",
                    tableIsResponsive: true,
                    select: false,
                    multiSelect: false,
                })
            },
        }
    }).mount('#app');
</script>