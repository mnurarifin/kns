<script src="https://unpkg.com/vue@next"></script>

<section id="app">
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h4 class="card-title"><?= isset($title) ? $title : '' ?></h4>
                </div>
                <div class="card-content">
                    <div id="pageLoader">
                        <div class="text-center text-muted d-flex align-center justify-content-center bg-grey-light p-2">
                            <div class="spinner-border text-info spinner-border-sm" role="status" style="margin-right: 8px;margin-top: 2px;">
                                <span class="sr-only">&nbsp;</span>
                            </div>
                            <span>Sedang memuat informasi, mohon tunggu beberapa saat...</span>
                        </div>
                    </div>


                    <div class="card-body card-dashboard">
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-success " v-show="alert.success.status" style="display: none;">
                                    <span v-html="alert.success.content"></span>
                                </div>
                            </div>
                            <div class="col-12 mt-0">
                                <div id="table"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetail" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title card-title ">Detail Repeat Order</h5>


                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <table class="table">
                            <thead>
                                <tr class="py-5">
                                    <th class="text-left">Member</th>
                                    <th class="text-left">Jalur</th>
                                    <th class="text-center">Level</th>
                                    <th class="text-center">Bonus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="detail_ro.length  == 0">
                                    <td class="text-center" colspan="6" style="height:200px;"> Tidak ada data yang dapat ditampilkan.</td>
                                </tr>
                                <tr v-else v-for="(item, index) in detail_ro">
                                    <td>
                                        {{item.sales_group_member_name}} ({{item.sales_group_network_code}})
                                    </td>
                                    <td>
                                        {{item.sales_group_line_member_name}} ({{item.sales_group_line_network_code}})
                                    </td>
                                    <td class="text-center">
                                        {{item.sales_group_level}}
                                    </td>
                                    <td class="text-center">
                                        {{item.sales_group_bonus_formatted}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tutup</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</section>

<script>
    $(document).ready(function() {
        app.generateTable();
        app.hideLoading();
    });


    // Function That Called 
    let app =
        Vue.createApp({
            data: function() {
                return {
                    modal_detail: {},
                    category: [],
                    year: [],
                    detail_ro: [],
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
                        url: window.location.origin + '/admin/service/report/getRepeatOrderReport/voucherpoint',
                        selectID: 'ro_personal_id',
                        colModel: [{
                                display: 'Tanggal',
                                name: 'ro_personal_datetime_formatted',
                                sortAble: true,
                                export: true,
                                align: 'left'
                            },
                            {
                                display: 'Username',
                                name: 'member_account_username',
                                sortAble: true,
                                export: true,
                                align: 'left'
                            },
                            {
                                display: 'Nama Mitra',
                                name: 'member_name',
                                sortAble: false,
                                export: true,
                                align: 'left'
                            },
                            {
                                display: 'Jumlah',
                                name: 'qty',
                                sortAble: false,
                                export: true,
                                align: 'left'
                            },
                            {
                                display: 'Nilai Poin',
                                name: 'ro_personal_point_value',
                                sortAble: false,
                                export: true,
                                align: 'left'
                            },
                            {
                                display: 'Serial',
                                name: 'serial',
                                sortAble: false,
                                export: true,
                                align: 'left'
                            },
                            {
                                display: 'Nominal',
                                name: 'price',
                                sortAble: false,
                                export: true,
                                align: 'left'
                            },
                            {
                                display: 'Catatan',
                                name: 'ro_personal_note',
                                sortAble: false,
                                export: true,
                                align: 'left'
                            },
                            // {
                            //     display: 'Detail',
                            //     name: 'ro_personal_id',
                            //     sortAble: false,
                            //     align: 'center',
                            //     render: (params) => {
                            //         return `<a onclick="app.detail('${params}')"> <i class="bx bx-receipt success"></i> </a>`;
                            //     }
                            // },
                        ],
                        options: {
                            limit: [10, 15, 20],
                            currentLimit: 10,
                        },
                        search: true,
                        searchTitle: 'Pencarian',
                        searchItems: [{
                                display: 'Tanggal',
                                name: 'sales_personal_datetime',
                                type: 'date'
                            },
                            {
                                display: 'Username',
                                name: 'member_account_username',
                                type: 'text'
                            }
                        ],
                        sortName: "sales_personal_datetime",
                        sortOrder: "DESC",
                        tableIsResponsive: true,
                        select: false,
                        multiSelect: false,
                        buttonAction: [{
                            display: 'Export Excel',
                            icon: 'bx bxs-file',
                            style: 'info',
                            action: 'exportExcel',
                            url: window.location.origin + "/admin/report/excel_repeatorder/"
                        }, ]
                    });
                },
                // detailTransaction(transaction_code) {
                //     window.location.replace(window.location.origin + '/admin/transaction/detail/' + transaction_code);
                // },
                detail(ro_personal_id) {
                    $('#modalDetail').modal('show');

                    $.ajax({
                        url: window.location.origin + '/admin/service/report/getRepeatOrderDetail',
                        method: 'GET',
                        data: {
                            ro_personal_id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                app.detail_ro = response.data.results
                            }
                        },

                    });
                },
            },
            mounted() {}
        }).mount("#app");;
</script>