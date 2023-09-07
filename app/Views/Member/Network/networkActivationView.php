<!-- BEGIN: Content-->
<div class="app-content content">
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
                                <li class="breadcrumb-item"><a href="#"><?= $breadcrumbs[0] ?></a></li>
                                <li class="breadcrumb-item active"><?= $breadcrumbs[1] ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row" id="step_1">
                <div class="col-md-12 col-12">
                    <div class="row" id="product_list">
                    </div>
                </div>
                <div class="col-md-12 col-12">
                    <div class="row" id="summary">
                        <div class="col col-12 col-md-12 mb-1">
                            <div class="card p-1">
                                <div class="row">
                                    <div class="col col-8 d-flex flex-column">
                                        <div class="m-0"><b id="total_quantity">0</b> produk dipilih</div>
                                        <div class="m-0">Total <b id="total_price">Rp 0</b></div>
                                        <div class="m-0 small"><span id="rank_notification"><span class="text-danger">(Belum memenuhi minimal transaksi.)</span></span></div>
                                    </div>
                                    <div class="col col-4 text-right"><button class="btn btn-primary" id="btn_next" disabled>Lanjut</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="step_2" style="display: none;">
                <div class="col col-12 col-md-12 mb-1">
                    <div class="card p-1 mb-0">
                        <div class="row">
                            <label class="col col-3 text-left">Produk</label>
                            <label class="col col-2 text-center">Poin BV</label>
                            <label class="col col-2 text-center">Jumlah</label>
                            <label class="col col-2 text-center">Berat</label>
                            <label class="col col-3 text-right">Harga</label>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-md-12" id="transaction_list">
                </div>
                <div class="col col-12 col-md-12 mb-1">
                    <div class="card p-1 mb-0">
                        <div class="row">
                            <label class="col col-6 d-flex align-items-center">Masukan Informasi Titik & Upline</label>
                            <div class="col col-6 d-flex align-items-center">
                                <div class="row w-100">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="input_transaction_name">Posisi Jaringan</label>
                                            <div class="row">
                                                <div class="col col-8">
                                                    <div class="row" id="member_position" data-position="left">
                                                        <div class="col col-6 d-flex align-items-center" id="position_left">
                                                            <i class="bx bxs-circle text-light mr-1" id="check_left" style="font-size: 2rem;"></i>
                                                            <label for="check_self">Kiri</label>
                                                        </div>
                                                        <div class="col col-6 d-flex align-items-center" id="position_right">
                                                            <i class="bx bxs-circle text-light mr-1" id="check_right" style="font-size: 2rem;"></i>
                                                            <label for="check_other">Kanan</label>
                                                        </div>
                                                    </div>
                                                    <small class="text-danger alert-input" id="alert_input_network_position" style="display: none;"></small>
                                                    <input type="hidden" id="input_network_position">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="input_upline_username">Username Upline</label>
                                            <input type="text" class="form-control" rows="4" id="input_upline_username" placeholder="" value="">
                                            <small class="text-danger alert-input" id="alert_input_upline_username" style="display: none;"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-md-12 mb-1" id="div_delivery">
                    <div class="card p-1 mb-0">
                        <div class="row">
                            <label class="col col-8 d-flex align-items-center">Alamat Pengiriman</label>
                            <div class="col col-4">
                                <div class="row" id="transaction_address" data-address="self">
                                    <div class="col col-6 d-flex align-items-center" id="address_self">
                                        <i class="bx bxs-check-circle text-success mr-1" id="check_self" style="font-size: 2rem;"></i>
                                        <label for="check_self">Alamat Utama</label>
                                    </div>
                                    <div class="col col-6 d-flex align-items-center" id="address_other">
                                        <i class="bx bxs-circle text-light mr-1" id="check_other" style="font-size: 2rem;"></i>
                                        <label for="check_other">Alamat Lain</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-12 d-flex align-items-center">
                                <div class="row w-100">

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="input_transaction_name">Nama Penerima</label>
                                            <input type="text" class="form-control" rows="4" id="input_transaction_name" placeholder="" value="">
                                            <small class="text-danger alert-input" id="alert_input_transaction_name" style="display: none;"></small>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="input_transaction_mobilephone">No. HP Penerima</label>
                                            <input type="text" class="form-control" rows="4" id="input_transaction_mobilephone" placeholder="" value="">
                                            <small class="text-danger alert-input" id="alert_input_transaction_mobilephone" style="display: none;"></small>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="input_transaction_address">Alamat Lengkap</label>
                                            <textarea class="form-control" rows="4" id="input_transaction_address" placeholder="" style="height: calc(4.2em + 6.82rem + 20.1px);" value="" disabled></textarea>
                                            <small class="text-danger alert-input" id="alert_input_transaction_address" style="display: none;"></small>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="input_transaction_province_id">Provinsi</label>
                                            <select class="form-control" id="input_transaction_province_id" disabled>
                                                <option value="0">Pilih Provinsi</option>
                                            </select>
                                            <small class="text-danger alert-input" id="alert_input_transaction_province_id" style="display: none;"></small>
                                        </div>

                                        <div class="form-group">
                                            <label for="input_transaction_city_id">Kota</label>
                                            <select class="form-control" id="input_transaction_city_id" disabled>
                                                <option value="0">Pilih Kota</option>
                                            </select>
                                            <small class="text-danger alert-input" id="alert_input_transaction_city_id" style="display: none;"></small>
                                        </div>

                                        <div class="form-group">
                                            <label for="input_transaction_subdistrict_id">Kecamatan</label>
                                            <select class="form-control" id="input_transaction_subdistrict_id" disabled>
                                                <option value="0">Pilih Kecamatan</option>
                                            </select>
                                            <small class="text-danger alert-input" id="alert_input_transaction_subdistrict_id" style="display: none;"></small>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row d-none">
                            <label class="col col-12 d-flex align-items-center">Jasa Pengiriman</label>
                            <div class="col col-4">
                                <div class="form-group mb-0">
                                    <label for="input_transaction_courier_code">Kurir</label>
                                    <select class="form-control" id="input_transaction_courier_code">
                                        <option value="0">Pilih Kurir</option>
                                    </select>
                                    <small class="text-danger alert-input" id="alert_input_transaction_courier_code" style="display: none;"></small>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group mb-0">
                                    <label for="input_transaction_courier_service">Layanan</label>
                                    <select class="form-control" id="input_transaction_courier_service">
                                        <option value="0">Pilih Layanan</option>
                                    </select>
                                    <small class="text-danger alert-input" id="alert_input_transaction_courier_service" style="display: none;"></small>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group mb-0">
                                    <label for="input_transaction_courier_cost">Ongkos Kirim</label>
                                    <div class="text-right" id="input_transaction_courier_cost"></div>
                                    <small class="text-danger alert-input" id="alert_input_transaction_courier_cost" style="display: none;"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-md-12 mb-1">
                    <div class="card p-1 mb-0">
                        <div class="row align-items-center">
                            <label class="col col-9 mb-0 d-flex align-items-center">Total</label>
                            <label class="col col-3 mb-0 text-right" id="transaction_price_total" style="font-size: 1.6rem;">0</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-12">
                    <div class="row">
                        <div class="col col-12 col-md-12 mb-1">
                            <div class="row">
                                <div class="col col-6 text-left"><button class="btn btn-primary" id="btn_prev">Kembali</button></div>
                                <div class="col col-6 text-right"><button class="btn btn-primary" id="btn_submit">Proses</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        let product_id = {}
        let product_data = []
        let product_detail = []
        let product_bv = {}
        let member_address = {}
        let delivery_data = {}
        let delivery_cost = 0
        let transaction_total_weight = 0
        let transaction_total_price = 0
        let transaction_nett_price = 0
        let local = false
        let total_bv = 0

        getProduct = () => {
            $.ajax({
                url: "<?= BASEURL ?>/common/get-list-product",
                type: "GET",
                data: {
                    type: "repeatorder"
                },
                success: (res) => {
                    product_data = res.data.results
                    $("#product_list").empty()
                    $.each(product_data, (i, val) => {
                        $("#product_list").append(`
                        <div class="col col-12 col-md-4 mb-1">
                            <div class="card card-bordered border select_product p-1 mb-1" data-id="${val.product_id}" data-price="${val.product_price}" data-bv=${val.product_bv} style="cursor: pointer;">
                            <i class="bx bxs-circle text-light" id="select_product_${val.product_id}" style="position: absolute; right: 1rem; font-size: 2rem;"></i>
                            <p style="font-weight: bold;">${val.product_name}</p>
                            <p class="mb-1">${formatCurrency(val.product_price)}</p>
                            <p class="mb-1">${val.product_bv} Poin BV</p>
                            <div class="text-center d-inline-flex">
                                <i class="control control-substract bx bxs-minus-square" data-id="${val.product_id}" data-bv=${val.product_bv} style="font-size: calc(1.4em + 0.94rem + 3.7px);"></i>
                                <input type="number" min="1" class="form-control text-center" id="quantity_${val.product_id}" value="0" disabled>
                                <i class="control control-add bx bxs-plus-square" data-id="${val.product_id}" data-bv=${val.product_bv} style="font-size: calc(1.4em + 0.94rem + 3.7px);"></i>
                            </div>
                            </div>
                        </div>
                        `)
                    })
                },
            })
        }

        getMemberAddress = () => {
            $.ajax({
                url: "<?= BASEURL ?>/member/transaction/get-member-address",
                type: "GET",
                success: (res) => {
                    member_address = res.data.results
                    $("#input_transaction_name").val(member_address.member_name).prop("disabled", true)
                    $("#input_transaction_mobilephone").val(member_address.member_mobilephone).prop("disabled", true)
                    $("#input_transaction_address").val(member_address.member_address).prop("disabled", true)
                    $("#input_transaction_province_id").val(member_address.member_province_id).prop("disabled", true)
                    $("#input_transaction_city_id").val(member_address.member_city_id).prop("disabled", true)
                    $("#input_transaction_subdistrict_id").val(member_address.member_subdistrict_id).prop("disabled", true)
                    $("#input_transaction_province_id").trigger("change")
                    if (member_address.member_province_id == "34") {
                        local = true
                    }
                },
            })
        }

        $("body").on("click", ".select_product", (ev) => {
            if (!$(ev.target).before().hasClass("control")) {
                let id = $(ev.target).closest(".select_product").data("id")
                let bv = $(ev.target).closest(".select_product").data("bv")
                if (product_id.hasOwnProperty(id)) {
                    $(`#select_product_${id}`).removeClass("bxs-check-circle").addClass("bxs-circle").removeClass("text-success").addClass("text-light")
                    $(`#quantity_${id}`).val(0)
                    delete product_bv[id]
                    delete product_id[id]
                } else {
                    $(`#select_product_${id}`).removeClass("bxs-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
                    $(`#quantity_${id}`).val(1)
                    product_id[id] = 1
                    product_bv[id] = bv
                }
            }
            renderSummary()
        })

        $("body").on("click", ".control-add", (ev) => {
            let id = $(ev.target).data("id")
            let bv = $(ev.target).data("bv")
            if (product_id.hasOwnProperty(id)) {
                let qty = parseInt($(`#quantity_${id}`).val()) + 1
                qty = qty > 999999 ? 999999 : qty
                product_id[id] = qty
                product_bv[id] = bv * qty
                $(`#quantity_${id}`).val(qty)
            }
            renderSummary()
        })

        $("body").on("click", ".control-substract", (ev) => {
            let id = $(ev.target).data("id")
            let bv = $(ev.target).data("bv")
            if (product_id.hasOwnProperty(id)) {
                let qty = parseInt($(`#quantity_${id}`).val()) - 1
                qty = qty < 1 ? 1 : qty
                product_id[id] = qty
                product_bv[id] = bv * qty
                $(`#quantity_${id}`).val(qty)
            }
            renderSummary()
        })

        renderSummary = () => {
            total_quantity = 0
            total_price = 0
            total_bv = 0
            $.each($(".select_product"), (i, val) => {
                if ($(val).children("i").hasClass("bxs-check-circle")) {
                    total_quantity += 1
                    total_price += parseInt($(`#quantity_${$(val).data("id")}`).val()) * parseInt($(val).data("price"))
                }
            })

            $.each(product_bv, (key, val) => {
                total_bv += val
            })
            $("#total_quantity").html(total_quantity)
            $("#total_price").html(formatCurrency(total_price))
            let rank_notification = `<span class="text-danger">(Belum memenuhi minimal transaksi.)`
            let transaction_valid = false

            if (total_bv >= 50) {
                transaction_valid = true
                rank_notification = `<span class="text-success">Transaksi anda menghasilkan <b>${total_bv}</b> Poin BV.</span>`
            }

            $("#rank_notification").html(rank_notification)
            if (transaction_valid) {
                $("#btn_next").prop("disabled", false)
            } else {
                $("#btn_next").prop("disabled", true)
            }
        }

        $("#btn_next").on("click", (ev) => {
            if (parseInt($("#total_quantity").html()) > 0) {
                $("#transaction_list").empty()
                transaction_total_weight = 0
                transaction_total_price = 0
                transaction_total_bv = 0
                product_detail = []

                $.each(product_id, (i, val) => {
                    let product = product_data.find(o => o.product_id == i)
                    product_detail.push({
                        product_id: product.product_id,
                        quantity: val,
                        bv: product.product_bv * val
                    })
                    $("#transaction_list").append(`
                    <div class="card p-1 mb-1">
                        <div class="row align-items-center">
                            <div class="col col-3 text-left"><b>${product.product_name}</b><br>@ ${product.product_weight} gram<br>@ ${formatCurrency(product.product_price)}</div>
                            <div class="col col-2 text-center">${val * product.product_bv}</div>
                            <div class="col col-2 text-center">${val}</div>
                            <div class="col col-2 text-center">${parseInt(product.product_weight) * parseInt(val)} gram</div>
                            <div class="col col-3 text-right">${formatCurrency(parseInt(product.product_price) * parseInt(val))}</div>
                        </div>
                    </div>
                    `)
                    transaction_total_weight += parseInt(product.product_weight) * parseInt(val)
                    transaction_total_price += parseInt(product.product_price) * parseInt(val)
                    transaction_total_bv += parseInt(product.product_bv) * parseInt(val)
                })
                $("#transaction_list").append(`
                <div class="card p-1 mb-1">
                    <div class="row align-items-center">
                        <label class="col col-3 text-left">Sub Total</label>
                        <div class="col col-2 text-center">${transaction_total_bv}</div>
                        <div class="col col-2 text-center"></div>
                        <div class="col col-2 text-center" id="transaction_total_weight">${transaction_total_weight} gram</div>
                        <label class="col col-3 text-right" style="font-size: 1.6rem;">${formatCurrency(transaction_total_price)}</label>
                    </div>
                </div>
                `)
                renderTotal()

                if (!local) {
                    $("#method_courier").trigger("click")
                }

                $("#step_1").hide()
                $("#step_2").show()
            }
        })

        $("#btn_prev").on("click", (ev) => {
            $("#step_1").show()
            $("#step_2").hide()
        })

        $("#position_left").on("click", (ev) => {
            if ($("#member_position").data("position") == "right") {
                $("#member_position").data("position", "left")
                $("#check_left").removeClass("bxs-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
                $("#check_right").removeClass("bxs-check-circle").addClass("bxs-circle").removeClass("text-success").addClass("text-light")
                $("#input_network_position").val('L')
            } else {
                $("#member_position").data("position", "left")
                $("#check_left").removeClass("bxs-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
                $("#check_right").removeClass("bxs-check-circle").addClass("bxs-circle").removeClass("text-success").addClass("text-light")
                $("#input_network_position").val('L')
            }
        })

        $("#position_right").on("click", (ev) => {
            if ($("#member_position").data("position") == "left") {
                $("#member_position").data("position", "right")
                $("#check_right").removeClass("bxs-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
                $("#check_left").removeClass("bxs-check-circle").addClass("bxs-circle").removeClass("text-success").addClass("text-light")
                $("#input_network_position").val('R')
            } else {
                $("#member_position").data("position", "right")
                $("#check_right").removeClass("bxs-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
                $("#check_left").removeClass("bxs-check-circle").addClass("bxs-circle").removeClass("text-success").addClass("text-light")
                $("#input_network_position").val('R')
            }
        })

        $("#address_self").on("click", (ev) => {
            if ($("#transaction_address").data("address") == "other") {
                $("#transaction_address").data("address", "self")
                $("#check_self").removeClass("bxs-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
                $("#check_other").removeClass("bxs-check-circle").addClass("bxs-circle").removeClass("text-success").addClass("text-light")
                $("#input_transaction_name").val(member_address.member_name).prop("disabled", true)
                $("#input_transaction_mobilephone").val(member_address.member_mobilephone).prop("disabled", true)
                $("#input_transaction_address").val(member_address.member_address).prop("disabled", true)
                $("#input_transaction_province_id").val(member_address.member_province_id).prop("disabled", true)
                $("#input_transaction_city_id").val(member_address.member_city_id).prop("disabled", true).trigger("change")
                $("#input_transaction_subdistrict_id").val(member_address.member_subdistrict_id).prop("disabled", true)
                $("#input_transaction_province_id").trigger("change")
            }
        })

        $("#address_other").on("click", (ev) => {
            if ($("#transaction_address").data("address") == "self") {
                $("#transaction_address").data("address", "other")
                $("#check_other").removeClass("bxs-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
                $("#check_self").removeClass("bxs-check-circle").addClass("bxs-circle").removeClass("text-success").addClass("text-light")
                $("#input_transaction_name").val("").prop("disabled", false)
                $("#input_transaction_mobilephone").val("").prop("disabled", false)
                $("#input_transaction_address").val("").prop("disabled", false)
                $("#input_transaction_province_id").val("").prop("disabled", false)
                $("#input_transaction_city_id").val("").prop("disabled", false).trigger("change")
                $("#input_transaction_subdistrict_id").val("").prop("disabled", false)
            }
        })

        $("#btn_submit").on("click", () => {
            Swal.fire({
                title: 'Apakah anda yakin ingin melakukan Transaksi?',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: `<span style="color: #475F7B;">Tidak</span>`,
                cancelButtonColor: '#E6EAEE',
                confirmButtonText: 'Ya',
                confirmButtonColor: '#6f2abb',
            }).then((result) => {
                if (result.isConfirmed) {
                    addTransaction()
                }
            })
        })

        addTransaction = () => {
            let data = {
                transaction_delivery_method: "courier",
                transaction_courier_code: "",
                transaction_courier_service: "",
                transaction_delivery_cost: 0,
                name: $("#input_transaction_name").val(),
                mobilephone: $("#input_transaction_mobilephone").val(),
                address: $("#input_transaction_address").val(),
                province_id: $("#input_transaction_province_id").val(),
                city_id: $("#input_transaction_city_id").val(),
                subdistrict_id: $("#input_transaction_subdistrict_id").val(),
                detail: product_detail,
                network_position: $('#input_network_position').val(),
                upline_username: $('#input_upline_username').val()
            }
            $.ajax({
                url: "<?= BASEURL ?>/member/network/activation-process",
                type: "POST",
                data: data,
                success: (res) => {
                    data = res.data.results
                    $(".alert-input").hide()
                    $("#alert-success").html(res.message)
                    $("#alert-success").show()
                    setTimeout(function() {
                        $("#alert-success").hide()
                    }, 3000)
                    window.location = `/member/network/activation-success?id=${data.transaction_id}`
                },
                error: (err) => {
                    res = err.responseJSON
                    $(".alert-input").hide()
                    $("#alert").html(res.message)
                    $("#alert").show()
                    setTimeout(function() {
                        $("#alert").hide()
                    }, 3000);
                    if (res.error == "validation") {
                        $.each(res.data, (i, val) => {
                            $(`#alert_input_${i}`).html(val).show()
                        })
                    }
                },
            })
        }

        $("#input_transaction_province_id").on("click change", (ev) => {
            getCity($(ev.target).val())
        })

        $("#input_transaction_city_id").on("click change", (ev) => {
            getSubdistrict($(ev.target).val())
            // $("#input_transaction_courier_code").trigger("change")
        })

        $("#input_transaction_subdistrict_id").on("click change", (ev) => {
            // $("#input_transaction_courier_code").trigger("change")
        })

        renderTotal = () => {
            transaction_nett_price = parseInt(transaction_total_price)
            $("#transaction_price_total").html(formatCurrency(transaction_nett_price))
        }

        getProvince = () => {
            $.ajax({
                url: "<?= BASEURL ?>/common/get-list-ref-province",
                type: "GET",
                async: false,
                success: (res) => {
                    data = res.data.results
                    $.each(data, (i, val) => {
                        $("#input_transaction_province_id").append(
                            `<option value="${val.province_id}">${val.province_name}</option>`)
                    })
                },
            })
        }

        getCity = (province_id) => {
            if (province_id != 0) {
                $.ajax({
                    url: "<?= BASEURL ?>/common/get-list-ref-city/?province_id=" + province_id,
                    type: "GET",
                    success: (res) => {
                        data = res.data.results
                        $("#input_transaction_city_id").empty()
                        $.each(data, (i, val) => {
                            $("#input_transaction_city_id").append(
                                `<option value="${val.city_id}">${val.city_name}</option>`)
                        })
                        getSubdistrict($("#input_transaction_city_id").val())
                    },
                })
            }
        }

        getSubdistrict = (city_id) => {
            if (city_id != 0) {
                $.ajax({
                    url: "<?= BASEURL ?>/common/get-list-ref-subdistrict/?city_id=" + city_id,
                    type: "GET",
                    success: (res) => {
                        data = res.data.results
                        $("#input_transaction_subdistrict_id").empty()
                        $.each(data, (i, val) => {
                            $("#input_transaction_subdistrict_id").append(
                                `<option value="${val.subdistrict_id}">${val.subdistrict_name}</option>`)
                        })
                    },
                })
            }
        }

        formatCurrency = ($params) => {
            let formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            })
            return formatter.format($params)
        }

        getMemberAddress()
        getProvince()
        // getCourier()
        getProduct()
    })
</script>
<!-- END: Content-->