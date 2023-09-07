<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.10.1/viewer.js" integrity="sha512-ivK7VQrrokmCTgtxpbqExrNaKfOhEdAFL51Ez9+UnKGT7OfCbYKogPJY++EOOvfXUDuPZaL4wkwzJbBO1kaMBQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.10.1/viewer.css" integrity="sha512-Dlf3op7L5ECYeoL6o80A2cqm4F2nLvvK4aH84DxCT690quyOZI8Z0CxVG9PQF3JHmD/aBFqN/W/8SYt7xKLi2w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<section class="invoice-view-wrapper">
    <div class="content-body">
        <div class="card invoice-print-area" style="border-radius: 25px;">
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-center text-sm-left order-2 order-sm-1 py-2">
                        <div class="p-1" style="min-height: 85px">
                            <h4 class="text-primary">Transaksi</h4>
                            <span><?php echo $results->transaction_code ?> -
                                <?php echo $results->transaction_datetime_formatted ?></span>
                        </div>

                        <!-- PEMBELI  -->
                        <!-- <div class="row invoice-info mt-2 p-1" style="background:#F2F4F4; border-radius:25px;">
                            <div class="col-sm-12 col-12 mt-1">
                                <h6 class="invoice-to font--title">Pembeli</h6>
                                <div class="font--title">
                                    <span class="font--text">Nama : <?php echo $results->transaction_member_name ?>
                                        <?php echo  $results->transaction_member_ref_network_code !== '' ? "($results->transaction_member_ref_network_code)" : '' ?>
                                    </span>
                                </div>
                                <div class="mb-1 font--text">
                                    <span>Nomor Telepon : <?php echo $results->transaction_member_mobilephone ?></span>
                                </div>
                            </div>

                        </div> -->

                        <div class="row invoice-info p-1" style="border-radius:25px;">
                            <div class="col-sm-12 col-12">
                                <h6 class="invoice-to font--title mt-1 mb-1">Detail Pembelian</h6>
                                <div class="wrap--product">
                                    <table class="table table-borderless table--product">
                                        <thead style="position: sticky; top: 0; background-color: #fff">
                                            <tr class="border-0">
                                                <th scope="col" class="text-left pl-0">Nama Barang</th>
                                                <!-- <th class="text-center" scope="col">Harga Satuan</th> -->
                                                <th class="text-center pl-0" scope="col">Qty</th>
                                                <th class="text-center pl-0" scope="col" style="display: none;">Total
                                                    Harga</th>
                                                <th scope="col" style="display: none;">Total Diskon</th>
                                                <th scope="col" style="display: none;">Total Charge/Tax</th>
                                                <th class="text-right pl-0" scope="col">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($results->transaction_detail as $key => $value) { ?>
                                                <tr>
                                                    <td class="text-left pl-0">
                                                        <?php echo $value->transaction_detail_product_name ?> <br>
                                                        @<?php echo $value->transaction_detail_unit_price_formatted ?></td>
                                                    <!-- <td  class="text-primary text-center font-weight-bold">
                                                </td> -->
                                                    <td class="text-center pl-0">
                                                        <?php echo $value->transaction_detail_qty ?></td>
                                                    <td style="display: none;" class="text-primary text-center font-weight-bold">
                                                        <?php echo $value->transaction_total_formatted ?></td>
                                                    <td style="display: none;">
                                                        <?php echo $value->transaction_detail_product_discount_formatted ?>
                                                    </td>
                                                    <td style="display: none;">
                                                        <?php echo $value->transaction_detail_product_admin_charge_formatted ?>
                                                    </td>
                                                    <td class="text-primary text-right font-weight-bold pl-0">
                                                        <?php echo $value->transaction_subtotal_formatted ?></td>

                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <hr class="line--grey my-2">
                        </hr>

                        <div class="row px-1">
                            <div class="col-12 col-md-12 col-sm-12">
                                <div class="invoice-subtotal">
                                    <div class="invoice-calc d-flex mb-1 justify-content-between font--title">
                                        <span class="invoice-title pr-2">Harga Kuantitas</span>
                                        <span class="invoice-value"><?php echo $results->transaction_price_formatted ?></span>
                                    </div>
                                    <div class="invoice-calc d-flex mb-1 justify-content-between font--title" style="display: none !important;">
                                        <span class="invoice-title pr-2">Discount</span>
                                        <span class="invoice-value"><?php echo $results->transaction_discount_formatted ?></span>
                                    </div>
                                    <div class="invoice-calc d-flex mb-1 justify-content-between font--title" style="display: none !important;">
                                        <span class="invoice-title pr-2">Charge/Tax</span>
                                        <span class="invoice-value "><?php echo $results->transaction_adm_charge_formatted ?></span>
                                    </div>
                                    <div class="invoice-calc d-flex mb-1 justify-content-between font--title">
                                        <span class="invoice-title pr-2">Ongkir</span>
                                        <span class="invoice-value "><?php echo $results->transaction_expedition_price_formatted ?></span>
                                    </div>

                                    <div class="invoice-calc d-flex justify-content-between mt-4">
                                        <span class="invoice-title pr-2">
                                            <h5 class="font--highlight">Total</h5>
                                        </span>
                                        <span class="invoice-value">
                                            <h5 class="font--highlight">
                                                <?php echo $results->transaction_total_price_formatted ?> </h5>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="line--grey my-2">
                        </hr>

                        <div class="row mb-4">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 ml-auto">
                                <div class="row px-2">

                                    <div class="col-md-6 col-12 col-sm-12 ml-auto">
                                        <a href="/transaction/delivery" class="btn btn-block invoice-print py-1" style="border:1px solid #5A8DEE; border-radius: 8px">
                                            <span class="text-primary font-weight-bold">Kembali</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-md-left text-sm-right order-2 order-sm-1 p-2">
                        <div class="ekspedition--product py-1 d-flex align-items-end" style="min-height: 85px">
                            <div class="text-left">
                                <span style="color:orange; font-size: 22px; font-weight: 600">
                                    <?php echo ucfirst($results->transaction_status) ?> </span>
                            </div>
                        </div>

                        <div class="row mb-1 invoice-info card--info">
                            <div class="col-sm-12 col-md-12 col-lg-12 py-1">
                                <!-- PEMBELI  -->
                                <h6 class="invoice-to font--title">Pembeli</h6>
                                <div class="font--title">
                                    <span class="font--text">Nama : <?php echo $results->transaction_member_name ?>
                                        <?php echo  $results->transaction_member_ref_network_code !== '' ? "($results->transaction_member_ref_network_code)" : '' ?>
                                    </span>
                                </div>
                                <div class="font--text">
                                    <span>Nomor Telepon : <?php echo $results->transaction_member_mobilephone ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-1 card--info">
                            <div class="col-sm-12 col-md-12 col-lg-12 py-1">
                                <div class="ekspedition--product-detail">
                                    <h6 class="font--title text-left mb-2"> Penerima Barang </h6>
                                    <div class="mb-1 d-flex align-center font--text">
                                        <span>Nama : <?php echo $results->transaction_received_name ?></span>
                                        <span> </span>
                                    </div>
                                    <div class="mb-1 d-flex align-center font--text">
                                        <span>Nomor Telepon :
                                            <?php echo $results->transaction_received_mobilephone ?></span>
                                    </div>
                                    <div class="mb-1 font--text">
                                        <div class="d-flex align-start">
                                            <div>Alamat : </div>
                                            <!-- <div style="padding: 0 4px; line-height: 24px !important">
                                                 <?php echo $results->transaction_received_address ?> <br>
                                                Provinsi, <?php echo $results->transaction_received_province_name ?> <br>
                                                Kota/Kabupaten, <?php echo $results->transaction_received_city_name ?> <br>
                                                Kecamatan,<?php echo $results->transaction_received_subdistrict_city_name ?>
                                            </div> -->
                                            <div style="padding: 0 4px; line-height: 24px !important">
                                                <?php echo $results->transaction_received_address ?>,
                                                <?php echo $results->transaction_received_subdistrict_city_name ?> <br>
                                                <?php echo $results->transaction_received_city_name ?>,
                                                <?php echo $results->transaction_received_province_name ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Note -->
                            <?php if ($results->transaction_status == 'Terkirim') { ?>
                                <div class="col-sm-12 col-md-12 col-lg-12 py-1 mb-1">
                                    <h6 class="font--title text-left mb-1"> No Resi: </h6>
                                    <div style="display: flex;">
                                        <input class="form-control" id="receipt" value=" <?php echo $results->transaction_receipt_number !== '' ? $results->transaction_receipt_number : ''  ?> " type="text" disabled>
                                        <button id="receiptBtn" onclick="editReceipt()" class="bx bx-pencil ml-1 btn btn-primary btn-sm"></button>
                                    </div>

                                </div>
                            <?php } ?>
                        </div>

                        <!-- <div class="row mb-2 card--info">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <h6 class="font--title text-left mb-1">Bukti Transaksi</h6>
                                <div style="display: flex; align-items:left; justify-content:left; padding:10px;">
                                    <img id="product-image" class="product-image" style="max-width: 200px"
                                        src="<?php echo $results->transaction_bank_transfer_attachment !== '' ? $results->transaction_bank_transfer_attachment  : base_url() . '/no-image.png' ?>"
                                        alt="Blank">
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>




            </div>
        </div>
    </div>


    <div class="modal fade" id="modalDetail" role="dialog" aria-labelledby="modalAddUpdateTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center" id="modalDetailTitle">
                        <span>Form Approval</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form form-vertical">
                        <div class="form-body">
                            <div class="row ma-5">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="transaction_notes">Masukan Catatan</label>
                                        <textarea class="form-control" name="transaction_notes" id="transaction_notes" cols="20" rows="5" style="height: 64px;">
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                    <btn onclick="changeStatus('<?php echo $results->transaction_code ?>','3')" class="btn btn-primary  invoice-print">
                        <span>Setuju</span>
                    </btn>
                    <btn onclick="changeStatus('<?php echo $results->transaction_code ?>','4')" class="btn btn-light-primary invoice-print">
                        <span>Tolak</span>
                    </btn>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .font--title {
        font-size: 1.2rem;
        margin-bottom: 8px;
        font-weight: 400;
        color: #000;
    }

    .font--text {
        font-size: 1.08rem;
        color: #7E7B7B !important;
        font-weight: 400;
    }

    .font--highlight {
        font-weight: 600;
        color: #5A8DEE;
        font-size: 1.5rem;
    }

    .font--smaller {
        font-size: 0.985rem;
        font-weight: 400;
    }

    .table--product th {
        padding-bottom: 0;
        background: transparent !important;
        font-size: 1.08rem !important;
        text-transform: capitalize;
        font-weight: 400;
        color: #000 !important;
    }

    hr.line--grey {
        width: 95%;
        margin: auto;
        /* height: 20px; */
        border-width: 5px;
        border-radius: 18px;
    }

    .card--info {
        background-color: #F2F4F4;
        padding: 16px;
        border-radius: 18px;
    }

    .wrap--product {
        height: 200px;
        overflow: auto;
    }
</style>

<script>
    $(document).ready(function() {
        const viewer = new Viewer(document.getElementById('product-image'), {
            inline: false,
            toolbar: false
        });
    });

    function openModalApprove() {
        $('#modalDetail').modal();
        setTimeout(function() {
            $('#transaction_notes').focus();
        }, 0);
    }

    function editReceipt() {
        if ($("#receipt").attr("disabled")) {
            $('#receipt').removeAttr("disabled");
            $('#receiptBtn').html('Ubah');
            $("#receiptBtn").attr('class', 'btn btn-primary btn-sm ml-1 ');
        } else {
            Swal.fire({
                title: 'Perhatian!',
                text: 'Apakah anda yakin, akan mengubah resi?',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ok',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = window.location.origin + '/admin/service/transaction/updateStatusTransaction';
                    $.ajax({
                        url,
                        method: 'POST',
                        data: {
                            transaction_code: '<?php echo $results->transaction_code ?>',
                            transaction_status: '3',
                            transaction_receipt_number: $('#receipt').val()
                        },
                        success: function(response) {
                            window.location.reload();
                        },
                        error: function(response) {
                            let msg = response.responseJSON.data.validationMessage.transaction_receipt_number
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: msg
                            }).then(() => {
                                $('#receipt').val('<?php echo $results->transaction_receipt_number ?>')
                            })
                        }
                    });
                }
            })

            $('#receiptBtn').html('');
            $("#receiptBtn").attr('class', 'bx bx-pencil ml-1 btn btn-primary btn-sm ml-1');
            $("#receipt").attr('disabled', 'disabled');
        }
    }

    function changeStatus(transaction_code, transaction_status) {
        $('#modalDetail').modal('hide');

        Swal.fire({
            title: 'Perhatian!',
            text: 'Apakah anda yakin?',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ok',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            let results = await result;
            let url = window.location.origin + '/admin/service/transaction/updateStatusTransaction';
            if (results.isConfirmed) {
                $.ajax({
                    url,
                    method: 'POST',
                    data: {
                        transaction_code,
                        transaction_status,
                        transaction_notes: $('#transaction_notes').val()
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            window.location.replace(window.location.origin +
                                '/transaction/approval');
                        }
                    },

                });
            } else {
                $('#modalDetail').modal('show');
            }
        })
    }
</script>