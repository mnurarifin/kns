<script src="https://unpkg.com/vue@next"></script>


<section id="serial_wallet_top_up">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?= isset($title) ? $title : '' ?></h4>
                </div>
                <div class="card-content">
                    <div id="pageLoader">
                        <div
                            class="text-center text-muted d-flex align-center justify-content-center bg-grey-light p-2">
                            <div class="spinner-border text-info spinner-border-sm" role="status"
                                style="margin-right: 8px;margin-top: 2px;">
                                <span class="sr-only">&nbsp;</span>
                            </div>
                            <span>Sedang memuat informasi, mohon tunggu beberapa saat...</span>
                        </div>
                    </div>
                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-1 py-2">
                                    <label for="iconLeft">Masukan Jumlah Top Up</label>
                                    <fieldset>  
                                        <div class="input-group">
                                            <input v-model="form.ewallet_serial_log_value" type="text" class="form-control" placeholder="Masukan Jumlah Top Up, Contoh : 10.000" aria-describedby="button-addon2">
                                            <div class="input-group-append" id="button-addon2">
                                                <button class="btn btn-primary" @click="openModal()" type="button">Top Up !</button>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>    

                                <div class="col-12">
                                    <div id="table-wallet"></div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>


<script>
    $(document).ready(function () {
        serialWalletTopUp.generateTable('');
    });

    let serialWalletTopUp  = 
        Vue.createApp({ 
            data: function (){
                return {
                    form : {
                        ewallet_serial_log_value : '',
                    }
                }
            },
            methods:{
                openModal(status = 'open'){
                    Swal.fire({
                        title: 'Perhatian!',
                        text: "Apakah Anda yakin ?",
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Lanjut',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.value) {
                        $.ajax({
                            url: '<?php echo site_url('admin/service/serial/addSerialWallet') ?>',
                            method: 'POST',
                            data: serialWalletTopUp.form,
                            success: function (response) {
                                if (response.status == 200) {
                                    let data = response.data;

                                }
                            },
                            error: function (res) {
                                let response = res.responseJSON;
                                // productView.button.formBtn.disabled = false;

                                // if (response.status == 400 && response.message == "validationError") {
                                //     let resValidation = Object.values(response.data.validationMessage);

                                //     if (resValidation.length > 0) {
                                //         productView.alert.danger.content = `<ul>`;
                                //         resValidation.forEach((data) => {
                                //             productView.alert.danger.content +=
                                //                 `<li> ${data} </li>`;
                                //         });
                                //         productView.alert.danger.content += `</ul>`;
                                //         productView.alert.danger.status = true;

                                //         setTimeout(() => {
                                //             productView.alert.danger.status = false;
                                //         }, 3000);
                                //     }

                                // }
                            },
                        });
                        } else {
                        
                        }
                    });
                },
                 hideLoading() {
                    $("#pageLoader").hide();
                },
                generateTable(){
                    $("#table-wallet").dataTableLib({
                        url: window.location.origin + '/admin/service/serial/getDataHistorySerialWallet',
                        colModel: [
                            {
                                display: 'Tanggal',
                                name: 'ewallet_serial_log_datetime_formatted',
                                sortAble: true,
                                align: 'left'
                            },
                            {
                                display: 'Harga',
                                name: 'ewallet_serial_log_value_formatted',
                                sortAble: true,
                                align: 'center'
                            },
                            {
                                display: 'Persentase',
                                name: 'ewallet_serial_log_fee',
                                sortAble: true,
                                align: 'center'
                            },
                            {
                                display: 'Status',
                                name: 'ewallet_serial_log_type_formatted',
                                sortAble: true,
                                align: 'center'
                            },
                             {
                                display: 'Note',
                                name: 'ewallet_serial_log_note',
                                sortAble: true,
                                align: 'center'
                            },
                            

                        ],
                        options: {
                            limit: [10, 15, 20],
                            currentLimit: 10,
                        },
                        search: true,
                        searchTitle: 'Pencarian Data Produk',
                        searchItems: [
                            {
                                display: 'Tanggal',
                                name: 'ewallet_serial_log_datetime',
                                type: 'date'
                            },
                            {
                                display: 'Status',
                                name: 'ewallet_serial_log_type',
                                type: 'select',
                                option: [{
                                        title: 'Masuk',
                                        value: 'in'
                                    },
                                    {
                                        title: 'Keluar',
                                        value: 'out'
                                    },
                                ]
                            },
                        ],
                        sortName: "ewallet_serial_log_datetime_formatted",
                        sortOrder: "DESC",
                        tableIsResponsive: true,
                        buttonAction: [
                

                        ]
                    });
                }
            },
            mounted(){
                this.hideLoading();
            }

        }).mount("#serial_wallet_top_up");

</script>