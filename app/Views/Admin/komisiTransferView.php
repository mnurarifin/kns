<style>
    .table {
        position: relative;
    }

    .table-responsive {
        max-height: calc(100vh - 0px);
    }

    .table th,
    .table td {
        padding: 0.4rem 1rem;
        white-space: nowrap;
    }

    th {
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        background: white;
    }
</style>

<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?php echo isset($title) ? $title : ''; ?></h4>
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
                        <p class="card-text"></p>
                        <div id="table-bonus"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(function() {
        $("#table-bonus").bind("DOMSubtreeModified", function() {
            if ($("#table-bonus").height() > 60) {
                $("#pageLoader").hide();
            }
        });
    });

    let hash = window.location.hash;

    let datatable

    $(document).ready(function() {
        formatCurrency = ($params) => {
            let formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            })
            return formatter.format($params)
        }

        generateTable = () => {
            $("#table-bonus").dataTableLib({
                url: window.location.origin + '/admin/service/komisi/getDataBonusTransfer',
                selectID: 'bonus_member_id',
                colModel: [{
                        display: 'Kode Mitra',
                        name: 'member_account_username',
                        sortAble: true,
                        align: 'center'
                    },
                    {
                        display: 'Nama Mitra',
                        name: 'member_name',
                        sortAble: true,
                        align: 'left'
                    },
                    {
                        display: 'Nama Rekening',
                        name: 'member_bank_account_name',
                        sortAble: true,
                        align: 'left'
                    },
                    {
                        display: 'Nama Bank',
                        name: 'member_bank_name',
                        sortAble: true,
                        align: 'left'
                    },
                    {
                        display: 'Nomor Rekening',
                        name: 'member_bank_account_no',
                        sortAble: true,
                        align: 'left'
                    },
                    {
                        display: 'Sponsor',
                        name: 'bonus_sponsor_balance',
                        sortAble: false,
                        align: 'right',
                        render: (params) => {
                            return formatCurrency(params)
                        }
                    },
                    {
                        display: 'Generasi',
                        name: 'bonus_gen_node_balance',
                        sortAble: false,
                        align: 'right',
                        render: (params) => {
                            return formatCurrency(params)
                        }
                    },
                    {
                        display: 'Power Leg',
                        name: 'bonus_power_leg_balance',
                        sortAble: false,
                        align: 'right',
                        render: (params) => {
                            return formatCurrency(params)
                        }
                    },
                    {
                        display: 'Matching Leg',
                        name: 'bonus_matching_leg_balance',
                        sortAble: false,
                        align: 'right',
                        render: (params) => {
                            return formatCurrency(params)
                        }
                    },
                    {
                        display: 'Cash Reward',
                        name: 'bonus_cash_reward_balance',
                        sortAble: false,
                        align: 'right',
                        render: (params) => {
                            return formatCurrency(params)
                        }
                    },
                    {
                        display: 'Komisi Diterima',
                        name: 'saldo',
                        sortAble: false,
                        align: 'right',
                        render: (params) => {
                            return formatCurrency(params)
                        }
                    },
                    {
                        display: 'Potongan Admin',
                        name: 'bonus_adm_charge_value',
                        sortAble: false,
                        align: 'right',
                        render: (params) => {
                            return formatCurrency(params)
                        }
                    },
                    {
                        display: 'Potongan Pajak',
                        name: 'bonus_total_tax',
                        sortAble: false,
                        align: 'right',
                        render: (params) => {
                            return formatCurrency(params)
                        }
                    },
                    {
                        display: 'Nominal Transfer',
                        name: 'bonus_total_transfer',
                        sortAble: false,
                        align: 'right',
                        render: (params) => {
                            return formatCurrency(params);
                        }
                    },
                ],
                options: {
                    limit: [10, 15, 20, 50, 100],
                    currentLimit: 10,
                },
                search: true,
                searchTitle: 'Pencarian Data Bonus Mitra',
                searchItems: [{
                        display: 'Kode Mitra',
                        name: 'member_ref_network_code',
                        type: 'text'
                    },
                    {
                        display: 'Nama',
                        name: 'member_name',
                        type: 'text'
                    },
                ],
                sortName: "bonus_member_id",
                sortOrder: "asc",
                tableIsResponsive: true,
                select: true,
                multiSelect: true,
                buttonAction: [{
                    display: 'Transfer',
                    icon: 'bx bx-check-circle',
                    style: "success",
                    action: 'processTransfer',
                    message: "transfer saldo member",
                    url: window.location.origin + "/admin/service/komisi/addTransfer"
                }],
                success: (res) => {
                    if (!jQuery.isEmptyObject(res)) {
                        datatable = res.data.results
                    }
                },
                pagination: true,
            });
        }

        generateTable()

        processTransfer = (params) => {
            if (params.formData.data == undefined) {
                Swal.fire('Perhatian!', 'Tidak ada data yang dipilih', 'warning');
                return false;
            }
            Swal.fire({
                title: 'Perhatian!',
                text: 'Apakah anda yakin akan transfer saldo member ?',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ok',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    showLoadProcess();

                    setTimeout(() => {
                        process(params)
                    }, 300);
                }
            });
        }

        process = (params) => {
            let url = "<?= BASEURL ?>/admin/service/komisi/addTransfer"
            let data = []
            $.each(params.formData.data, (i, val) => {
                let data_bonus = datatable.find(o => o.bonus_member_id == val)
                data.push({
                    bonus_transfer_member_id: data_bonus.bonus_member_id,
                    bonus_transfer_sponsor: data_bonus.bonus_sponsor_balance,
                    bonus_transfer_gen_node: data_bonus.bonus_gen_node_balance,
                    bonus_transfer_power_leg: data_bonus.bonus_power_leg_balance,
                    bonus_transfer_matching_leg: data_bonus.bonus_matching_leg_balance,
                    bonus_transfer_cash_reward: data_bonus.bonus_cash_reward_balance,
                    bonus_transfer_total: data_bonus.bonus_total_transfer,
                    bonus_transfer_member_bank_id: data_bonus.member_bank_id,
                    bonus_transfer_member_bank_name: data_bonus.member_bank_name,
                    bonus_transfer_member_bank_account_name: data_bonus.member_bank_account_name,
                    bonus_transfer_member_bank_account_no: data_bonus.member_bank_account_no,
                    bonus_transfer_adm_charge_type: "<?= CONFIG_WITHDRAWAL_ADM_CHARGE_TYPE ?>",
                    bonus_transfer_adm_charge_percent: "<?= CONFIG_WITHDRAWAL_ADM_CHARGE_PERCENT ?>",
                    bonus_transfer_adm_charge_value: data_bonus.bonus_adm_charge_value,
                    bonus_transfer_tax_type: 'percent',
                    bonus_transfer_tax_percent: data_bonus.bonus_percent_tax,
                    bonus_transfer_tax_value: data_bonus.bonus_total_tax,
                })
            })
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    data: data
                },
                success: function(response) {
                    swal.hideLoading();
                    if (response.status == 200) {
                        let $message = '';
                        let title = '';
                        let icon = '';
                        if (response.message != undefined) {
                            $message = response.message;
                        }
                        if (response.data.success > 0 && response.data.failed == 0) {
                            title = 'Berhasil!';
                            $message = $message + '<br>Success : ' + response.data.success + '. Gagal : ' + response.data.failed;
                            icon = 'success';
                            $.refreshTable($(data.tableID).attr('id'));
                        } else if (response.data.success == 0 && response.data.failed > 0) {
                            title = 'Gagal!';
                            $message = $message + '<br>Success : ' + response.data.success + '. Gagal : ' + response.data.failed;
                            icon = 'error';
                            $.refreshTable($(data.tableID).attr('id'));
                        } else {
                            title = 'Berhasil!';
                            $message = $message + '<br>Success : ' + (response.data.success == undefined ? 0 : response.data.success) + '. Gagal : ' + (response.data.failed == undefined ? 0 : response.data.failed);
                            icon = 'warning';
                            $.refreshTable($(data.tableID).attr('id'));
                        }
                        try {
                            Swal.fire({
                                title: title,
                                html: $message,
                                icon: icon
                            });
                        } catch (error) {
                            alert('Berhasil! ' + $message);
                        }
                        generateTable()
                    }
                },
                error: function(err) {
                    let response = err.responseJSON;
                    if (response.message == 'Unauthorized' && response.status == 403) {
                        try {
                            Swal.fire(
                                'Gagal!',
                                'Sesi sudah habis. Silahkan login terlebih dahulu.',
                                'error'
                            );
                        } catch (error) {
                            alert('Gagal! Sesi sudah habis. Silahkan login terlebih dahulu.');
                        }
                    } else {
                        let $message = '';
                        if (response.message != undefined) {
                            $message = response.message;
                        }
                        try {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan sistem.',
                                'error'
                            );
                        } catch (error) {
                            alert('Gagal! Terjadi kesalahan sistem.');
                        }
                    }
                }
            })
        }


    });
</script>