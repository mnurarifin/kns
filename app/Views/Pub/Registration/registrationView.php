<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper px-3">
    <div class="alert alert-danger" role="alert" id="alert" style="position: absolute; top: 84px; right: 32px; display: none; z-index:1054;"></div>
    <div class="alert alert-success" role="alert" id="alert-success" style="position: absolute; top: 84px; right: 32px; display: none; z-index:1054;"></div>


    <div class="content-body registration-form" style="margin-bottom: 56px;">
      <div class="container py-3">
        <div class="row">
          <div class="col-md-12 col-12">

            <div class="card card-bordered border p-3 mb-3">
              <div class="row">
                <div class="col-sm-6 px-3">
                  <div class="form-group">
                    <label for="input_sponsor_username">Kode Sponsor</label>
                    <input type="text" class="form-control" id="input_sponsor_username" placeholder="" value="" disabled>
                    <small class="text-danger alert-input" id="alert_input_sponsor_username" style="display: none;"></small>
                  </div>
                </div>

                <div class="col-sm-6 px-3">
                  <div class="form-group">
                    <label for="input_sponsor_name">Nama Sponsor</label>
                    <input type="text" class="form-control" id="input_sponsor_name" placeholder="" value="" disabled>
                    <small class="text-danger alert-input" id="alert_input_sponsor_name" style="display: none;"></small>
                  </div>
                </div>

                <!-- <div class="col-sm-6"> -->
                <!-- <div class="form-group">
                    <label for="input_member_account_username">Username Anda</label>
                    <input type="text" id="input_member_account_username" class="form-control" placeholder=""
                      value="">
                    <small class="text-danger alert-input" id="alert_input_member_account_username"
                      style="display: none;"></small>
                  </div> -->
                <!-- </div> -->

                <div class="col-sm-6 px-3">
                  <div class="form-group">
                    <label for="input_network_slug">Username</label>
                    <input type="text" id="input_network_slug" class="form-control" placeholder="" value="">
                    <small class="text-danger alert-input" id="alert_input_network_slug" style="display: none;"></small>
                  </div>
                </div>

                <div class="col-sm-6 px-3">
                  <div class="form-password-toggle form-group">
                    <label>Password</label>
                    <div for="input_member_account_password" class="input-group input-group-merge">
                      <input type="password" class="form-control" id="input_member_account_password" placeholder="" aria-describedby="basic-toggle-password" value="">
                      <span class="input-group-text cursor-pointer bg-white" id="basic-toggle-password"><i class="bx bx-hide"></i></span>
                    </div>
                    <small class="text-danger alert-input" id="alert_input_member_account_password" style="display: none;"></small>
                  </div>
                </div>

                <div class="col-sm-6 px-3">
                  <div class="form-group">
                    <label for="input_member_name">Nama Lengkap</label>
                    <input type="text" class="form-control" id="input_member_name" placeholder="" value="">
                    <small class="text-danger alert-input" id="alert_input_member_name" style="display: none;"></small>
                  </div>
                </div>

                <div class="col-sm-6 px-3">
                  <div class="form-group">
                    <label for="input_member_email">Email</label>
                    <input type="text" class="form-control" id="input_member_email" placeholder="" value="">
                    <small class="text-danger alert-input" id="alert_input_member_email" style="display: none;"></small>
                  </div>
                </div>

                <div class="col-sm-6 px-3">
                  <div class="form-group">
                    <label for="input_member_mobilephone">Nomor Handphone</label>
                    <div class="input-group input-group-phone">
                      <select class="form-control" id="input_member_phone_code" is="ms-dropdown" data-visible-rows="10"></select>
                      <input placeholder=" Contoh :899123412  " type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" id="input_member_mobilephone">
                    </div>
                    <small class="mb-3" style="color:#6f2abb;">Input Tanpa +62 atau 0 Contoh :899123412</small>
                    <small class="text-danger alert-input" id="alert_input_member_mobilephone" style="display: none;"></small>
                  </div>
                </div>

                <div class="col-sm-6 px-3">
                  <div class="form-group">
                    <label for="input_member_gender">Gender</label>
                    <div class="input-group input-group-phone mb-3">
                      <select class="form-control" id="input_member_gender" is="ms-dropdown">
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                      </select>
                    </div>
                    <small class="text-danger alert-input" id="alert_input_member_gender" style="display: none;"></small>
                  </div>
                </div>

                <div class="col-sm-6 px-3">
                  <div class="form-group">
                    <label for="input_member_identity_type">Jenis Identitas</label>
                    <select class="form-control" id="input_member_identity_type">
                      <option value="" selected="selected">Pilih Jenis Identitas</option>
                      <option value="KTP">KTP</option>
                      <option value="PASPOR">Passport</option>
                    </select>
                    <small class="text-danger alert-input" id="alert_input_member_identity_type" style="display: none;"></small>
                  </div>
                </div>


                <div class="col-sm-6 px-3">
                  <div class="form-group">
                    <label for="input_member_identity_no">Nomor Identitas</label>
                    <input type="text" class="form-control" id="input_member_identity_no" placeholder="" value="">
                    <small class="text-danger alert-input" id="alert_input_member_identity_no" style="display: none;"></small>
                  </div>
                </div>

                <div class="col-sm-6 px-3">
                  <div class="form-group">
                    <label for="input_member_job">Pekerjaan</label>
                    <input type="text" class="form-control" id="input_member_job" placeholder="" value="">
                    <small class="text-danger alert-input" id="alert_input_member_job" style="display: none;"></small>
                  </div>
                </div>

                <div class="col-sm-6 px-3 d-none">
                  <div class="form-group">
                    <label for="input_member_identity_image">Upload KTP</label>
                    <input type="file" class="form-control" id="input_member_identity_image" placeholder="" value="">
                    <small class="text-danger alert-input" id="alert_input_member_identity_image" style="display: none;"></small>
                  </div>
                </div>

                <div class="col-sm-6 px-3 d-none">
                  <div class="form-group">
                    <label for="input_member_identity_image">&nbsp;</label><br />
                    <img id="input_member_identity_image_preview">
                  </div>
                </div>

              </div>
            </div>

            <div class="card card-bordered border p-3 mb-3">
              <div class="row">
                <div class="col-sm-6 px-3">
                  <div class="form-group">
                    <label for="input_member_address">Alamat Sesuai KTP</label>
                    <textarea class="form-control" rows="4" id="input_member_address" placeholder="" style="height: calc(4.2em + 6.82rem + 20.1px);" value=""></textarea>
                    <small class="text-danger alert-input" id="alert_input_member_address" style="display: none;"></small>
                  </div>
                </div>

                <div class="col-sm-6 px-3">
                  <div class="form-group">
                    <label for="input_member_province_id">Provinsi</label>
                    <select class="form-control" id="input_member_province_id">
                      <option value="0">Pilih Provinsi</option>
                    </select>
                    <small class="text-danger alert-input" id="alert_input_member_province_id" style="display: none;"></small>
                  </div>

                  <div class="form-group">
                    <label for="input_member_city_id">Kota</label>
                    <select class="form-control" id="input_member_city_id">
                      <option value="0">Pilih Kota</option>
                    </select>
                    <small class="text-danger alert-input" id="alert_input_member_city_id" style="display: none;"></small>
                  </div>

                  <div class="form-group">
                    <label for="input_member_subdistrict_id">Kecamatan</label>
                    <select class="form-control" id="input_member_subdistrict_id">
                      <option value="0">Pilih Kecamatan</option>
                    </select>
                    <small class="text-danger alert-input" id="alert_input_member_subdistrict_id" style="display: none;"></small>
                  </div>
                </div>

              </div>
            </div>

            <div class="card card-bordered border p-3 mb-3">
              <div class="row">

                <div class="col-sm-6 px-3">
                  <div class="form-group">
                    <label for="input_member_bank_account_name">Nama Akun Rekening</label>
                    <input type="text" class="form-control" id="input_member_bank_account_name" placeholder="" value="" disabled>
                    <small class="text-danger alert-input" id="alert_input_member_bank_account_name" style="display: none;"></small>
                  </div>
                </div>

                <div class="col-sm-6 px-3">
                  <div class="form-group">
                    <label for="input_member_bank_account_no">Nomor Rekening</label>
                    <input type="text" class="form-control" id="input_member_bank_account_no" placeholder="" value="">
                    <small class="text-danger alert-input" id="alert_input_member_bank_account_no" style="display: none;"></small>
                  </div>
                </div>

                <div class="col-sm-6 px-3">
                  <div class="form-group">
                    <label for="input_member_bank_id">Nama Bank</label>
                    <select class="form-control" id="input_member_bank_id">
                      <option value="0">Pilih Bank</option>
                    </select>
                    <small class="text-danger alert-input" id="alert_input_member_bank_id" style="display: none;"></small>
                  </div>
                </div>

              </div>
            </div>

            <style>
              .bx-circle,
              .bx.text-success {
                color: #6a0dad !important;
              }
            </style>
            <div class="card card-bordered border p-3 mb-3">
              <div class="row">

                <div class="col-sm-12 mb-3 px-3">
                  <div class="form-group mb-0">
                    <label for="input_product_id">Paket Registrasi</label>
                    <fieldset class="row pt-1" id="product_list">
                    </fieldset>
                    <small class="text-danger alert-input" id="alert_input_select_product_id" style="display: none;"></small>
                  </div>
                </div>
              </div>
            </div>

            <div class="card card-bordered border p-3 mb-3">
              <div class="row">
                <div class="col col-12 col-md-12 px-3">
                  <div class="row">
                    <label class="col-12 col-md-8 d-flex align-items-center">Pilih metode pengiriman</label>
                    <div class="col-12 col-md-4">
                      <div class="row" id="transaction_method" data-method="pickup">
                        <div class="col-6 d-flex align-items-center" id="method_pickup">
                          <i class="bx bxs-check-circle text-success me-2" id="check_pickup" style="font-size: 2rem;"></i>
                          <label for="check_pickup">Ambil</label>
                        </div>
                        <div class="col-6 d-flex align-items-center" id="method_courier">
                          <i class="bx bx-circle text-light me-2" id="check_courier" style="font-size: 2rem;"></i>
                          <label for="check_courier">Kirim</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card card-bordered border p-3 mb-3">
              <div class="row">
                <div class="col col-12 col-md-12 px-3">
                  <div class="row d-flex align-items-center">
                    <div class="col-12 col-md-8 col-md-8 mb-1 div-stockist" style="display: none;">
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label for="input_stockist_list">Daftar Stokis</label>
                            <select class="form-control" id="input_stockist_list" placeholder="" value=""></select>
                            <small class="text-danger alert-input" id="alert_input_stockist_list" style="display: none;"></small>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-12 col-md-8 col-md-8 mb-1 div-warehouse">
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Diambil di <?= COMPANY_ADDRESS ?></label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-md-4">
                      <div class="row" id="transaction_type" data-type="warehouse">
                        <div class="col col-6 d-flex align-items-center" id="type_warehouse">
                          <i class="bx bxs-check-circle text-success me-2" id="check_warehouse" style="font-size: 2rem;"></i>
                          <label for="check_warehouse">Perusahaan</label>
                        </div>
                        <div class="col col-6 d-flex align-items-center" id="type_stockist">
                          <i class="bx bx-circle text-light me-2" id="check_stockist" style="font-size: 2rem;"></i>
                          <label for="check_stockist">Master Stokis / Stokis</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card card-bordered border p-3 mb-3" id="div_delivery" style="display: none;">
              <div class="row">
                <div class="col col-12 col-md-12 px-3 mb-1">
                  <div class="row d-flex align-items-center">
                    <label class="col-12 col-md-8 d-flex align-items-center mb-1">Alamat Pengiriman</label>
                    <div class="col-12 col-md-4">
                      <div class="row" id="transaction_address" data-address="self">
                        <div class="col col-6 d-flex align-items-center" id="address_self">
                          <i class="bx bxs-check-circle text-success me-2" id="check_self" style="font-size: 2rem;"></i>
                          <label for="check_self">Alamat di Atas</label>
                        </div>
                        <div class="col col-6 d-flex align-items-center" id="address_other">
                          <i class="bx bx-circle text-light me-2" id="check_other" style="font-size: 2rem;"></i>
                          <label for="check_other">Alamat Lain</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card card-bordered border p-2 mb-1 div-courier div-address d-none">
                <div class="col col-12 col-md-12 px-3 mb-1">
                  <div class="row d-flex align-items-center">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="input_transaction_name">Nama Penerima</label>
                        <input type="text" class="form-control" rows="4" id="input_transaction_name" placeholder="" value="" disabled>
                        <small class="text-danger alert-input" id="alert_input_transaction_name" style="display: none;"></small>
                      </div>
                    </div>

                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="input_transaction_mobilephone">No. HP Penerima</label>
                        <input type="text" class="form-control" rows="4" id="input_transaction_mobilephone" placeholder="" value="" disabled>
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
            </div>

            <div class="card card-bordered border p-3 mb-3 div-courier" style="display: none;">
              <div class="row">
                <div class="col col-12 col-md-12 px-3 mb-1">
                  <div class="row">
                    <div class="col-12 col-md-4">
                      <div class="form-group mb-0">
                        <label for="input_transaction_courier_code">Kurir</label>
                        <select class="form-control" id="input_transaction_courier_code">
                          <option value="0">Pilih Kurir</option>
                        </select>
                        <small class="text-danger alert-input" id="alert_input_transaction_courier_code" style="display: none;"></small>
                      </div>
                    </div>

                    <div class="col-12 col-md-4">
                      <div class="form-group mb-0">
                        <label for="input_transaction_courier_service">Layanan</label>
                        <select class="form-control" id="input_transaction_courier_service">
                          <!-- <option value="0">Pilih Layanan</option> -->
                          <option value="DFOD">DFOD</option>
                        </select>
                        <small class="text-danger alert-input" id="alert_input_transaction_courier_service" style="display: none;"></small>
                      </div>
                    </div>

                    <div class="col-12 col-md-4 d-none">
                      <div class="form-group mb-0">
                        <label class="" for="input_transaction_courier_cost">Ongkos Kirim</label>
                        <div class="text-right font-weight-bold label-value" id="input_transaction_courier_cost">Rp 0</div>
                        <small class="text-danger alert-input" id="alert_input_transaction_courier_cost" style="display: none;"></small>
                      </div>
                    </div>
                  </div>
                </div>
                <small style="color: #6a0dad !important;">Pengiriman menggunakan layanan Delivery Fee On Delivery sehingga biaya pengiriman dibayarkan oleh mitra ketika produk sudah sampai ke mitra</small>
              </div>
            </div>

            <div class="card card-bordered border p-3 mb-3">
              <div class="row">
                <div class="col col-12 col-md-12 px-3 mb-1">
                  <div class="form-group row mx-0 align-items-center">
                    <label class="col-12 col-md-9 mb-0 px-0 d-flex align-items-center">Total</label>
                    <label class="col-12 col-md-3 mb-0 px-3 text-right label-value" id="transaction_price_total" style="font-weight: 500; font-size: 1.6rem;">Rp 0</label>
                  </div>
                </div>

              </div>
            </div>

            <div class="content-actions d-flex justify-content-end mb-3">
              <div class="text-right"><button class="btn btn-primary" style="background-color: #6f2abb;" id="btn_submit">Proses</button></div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalAddUpdateStockist" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header ">
            <h5 class="modal-title text-center" id="modalAddUpdateTitle">
              Konfirmasi Transaksi Anda
            </h5>
          </div>
          <div class="modal-body">
            <h5>Data Sponsor</h5>
            <div class="list-group mb-3">
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>Kode Sponsor</b>
                  <small><input type="text" name="" id="sponsor_username_form" class="text-end" style="border: none; background: none;" disabled></small>
                </div>
              </div>
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>Nama Sponsor</b>
                  <small><input type="text" name="" id="sponsor_name_form" class="text-end" style="border: none; background: none;" disabled></small>
                </div>
              </div>
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>Username</b>
                  <small id="modal_label_network_slug"></small>
                </div>
              </div>
            </div>
            <h5>Data Pembeli</h5>
            <div class="list-group mb-3">
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>Nama Pembeli</b>
                  <small id="modal_label_member_name"></small>
                </div>
              </div>
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>No Handphone</b>
                  <small id="modal_label_phone_code"></small>
                </div>
              </div>
              <!-- <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>Gender</b>
                  <small id="modal_label_gender"></small>
                </div>
              </div>
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>Email</b>
                  <small id="modal_label_email"></small>
                </div>
              </div>
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>Jenis Identitas</b>
                  <small id="modal_label_jenis_identitas"></small>
                </div>
              </div>
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>Nomor Identitas</b>
                  <small id="modal_label_nomor_identitas"></small>
                </div>
              </div>
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>Pekerjaan</b>
                  <small id="modal_label_pekerjaan"></small>
                </div>
              </div>
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>Alamat</b>
                  <small id="modal_label_alamat"></small>
                </div>
              </div>
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>Provinsi</b>
                  <small id="modal_label_provinsi"></small>
                </div>
              </div>
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>Kota</b>
                  <small id="modal_label_kota"></small>
                </div>
              </div>
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>Kecamatan</b>
                  <small id="modal_label_kecamatan"></small>
                </div>
              </div>
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>Nama Rekening</b>
                  <small id="modal_label_nama_rekening"></small>
                </div>
              </div>
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>Nomor Rekening</b>
                  <small id="modal_label_nomor_rekening"></small>
                </div>
              </div>
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>Nama Bank</b>
                  <small id="modal_label_nama_bank"></small>
                </div>
              </div> -->
            </div>

            <h5>Pengiriman</h5>
            <div class="list-group mb-3">
              <div href="#" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                  <b>Metode Pengiriman</b>
                  <small id="modal_label_transaction_method"></small>
                </div>
              </div>
              <div href="#" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                  <b id="modal_label_transaction_type"></b>
                  <small id="modal_label_transaction_keterangan"></small>
                </div>
              </div>
              <div href="#" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                  <b>Alamat Pengiriman</b>
                  <small id="modal_label_transaction_address"></small>
                </div>
              </div>
              <div href="#" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                  <b>Kurir</b>
                  <small id="modal_label_kurir"></small>
                </div>
              </div>
            </div>

            <h5>Data Transaksi</h5>
            <div class="list-group">
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b id="modal_label_product"></b>
                  <small id="modal_label_product_cost"></small>
                </div>
              </div>

            </div>
            <div class="list-group">
              <div href="#" class="list-group-item list-group-item-action ">
                <div class="d-flex w-100 justify-content-between">
                  <b>Total</b>
                  <small id="modal_label_transaction_price_total"></small>
                </div>
              </div>

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="$('#modal').modal('hide')">
              <i class="bx bx-x"></i>
              Tidak, Kembali
            </button>
            <button style="background-color: #6f2abb;" class="btn btn-primary" id="submitLanjutkan" type="submit">
              Ya, Lanjutkan
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>



<script>
  $(function() {
    let select_product_id = {}
    let product_data = []
    let product_detail = []
    let delivery_data = {}
    let delivery_cost = 0
    let transaction_total_weight = 0
    let transaction_total_price = 0
    let transaction_nett_price = 0

    let ref = JSON.parse('<?= json_encode(session("ref")) ?: json_encode([""]) ?>')
    if (ref == null) {
      location.href = "<?= BASEURL ?>/404"
    }
    $("#input_sponsor_username").val(ref.member_account_username)
    $("#sponsor_username_form").val(ref.member_account_username)
    $("#input_sponsor_name").val(ref.member_name)
    $("#sponsor_name_form").val(ref.member_name)

    // let params = new URLSearchParams(window.location.search)
    // if (params.get("sponsor")) {
    //   $.ajax({
    //     url: "<?= BASEURL ?>/registration/get-member-name",
    //     type: "GET",
    //     async: false,
    //     data: {
    //       network_slug: params.get("sponsor")
    //     },
    //     success: (res) => {
    //       data = res.data.results
    //       if (data) {
    //         $("#input_sponsor_username").val(data.member_account_username)
    //         $("#input_sponsor_name").val(data.member_name)
    //       } else {
    //         location.href = "<?= BASEURL ?>/404"
    //       }
    //     },
    //   })
    // } else {
    //   location.href = "<?= BASEURL ?>/404"
    // }

    const addData = () => {
      let name
      let mobilephone
      let address
      let province_id
      let city_id
      let subdistrict_id
      if ($("#transaction_address").data("address") == "self") {
        name = $("#input_member_name").val()
        mobilephone = $("#input_member_mobilephone").val()
        address = $("#input_member_address").val()
        province_id = $("#input_member_province_id").val()
        city_id = $("#input_member_city_id").val()
        subdistrict_id = $("#input_member_subdistrict_id").val()
      } else if ($("#transaction_address").data("address") == "other") {
        name = $("#input_transaction_name").val()
        mobilephone = $("#input_transaction_mobilephone").val()
        address = $("#input_transaction_address").val()
        province_id = $("#input_transaction_province_id").val()
        city_id = $("#input_transaction_city_id").val()
        subdistrict_id = $("#input_transaction_subdistrict_id").val()
      }
      let data = new FormData()
      data.append("sponsor_username", $("#input_sponsor_username").val())
      data.append("member_account_username", $("#input_member_account_username").val())
      data.append("network_slug", $("#input_network_slug").val())
      data.append("member_account_password", $("#input_member_account_password").val())
      data.append("select_product_id", JSON.stringify(select_product_id))
      data.append("transaction_delivery_method", $("#transaction_method").data("method"))
      data.append("transaction_courier_code", $("#input_transaction_courier_code").val())
      data.append("transaction_courier_service", $("#input_transaction_courier_service").val())
      data.append("cost", delivery_cost)
      data.append("member_name", $("#input_member_name").val())
      data.append("member_gender", $("#input_member_gender").val())
      data.append("member_identity_type", $("#input_member_identity_type").val())
      // data.append("member_email", $("#input_member_email").val())
      data.append("member_identity_no", $("#input_member_identity_no").val())
      data.append("member_identity_image", $("#input_member_identity_image").prop("files")[0])
      data.append("member_mobilephone", $("#input_member_mobilephone").val())
      data.append("member_email", $("#input_member_email").val())
      data.append("member_job", $("#input_member_job").val())
      // data.append("member_birth_place", $("#input_member_birth_place").val())
      // data.append("member_birth_date", $("#input_member_birth_date").val())
      data.append("member_province_id", $("#input_member_province_id").val())
      data.append("member_city_id", $("#input_member_city_id").val())
      data.append("member_subdistrict_id", $("#input_member_subdistrict_id").val())
      data.append("member_address", $("#input_member_address").val())
      data.append("member_bank_account_name", $("#input_member_bank_account_name").val())
      data.append("member_bank_account_no", $("#input_member_bank_account_no").val())
      data.append("member_bank_id", $("#input_member_bank_id").val())
      // data.append("member_bank_branch", $("#input_member_bank_branch").val())
      data.append("name", name)
      data.append("mobilephone", mobilephone)
      data.append("address", address)
      data.append("province_id", province_id)
      data.append("city_id", city_id)
      data.append("subdistrict_id", subdistrict_id)
      data.append("type", $("#transaction_type").data("type"))
      data.append("stockist_member_id", $("#transaction_type").data("type") == "stockist" ? $("#input_stockist_list").val() : 0)
      data.append("country_phone_code", $("#input_member_phone_code").val())

      $("#btn_submit").prop("disabled", true)
      $("#btn_submit").html(`<i class="bx bx-loader bx-spin bx-md" style="font-size: 18px!important;"></i>`)
      $.ajax({
        url: "<?= BASEURL ?>/registration/save",
        type: "POST",
        processData: false,
        contentType: false,
        data: data,
        success: (res) => {
          data = res.data.results
          $(".alert-input").hide()
          $("#alert-success").html(res.message)
          $("#alert-success").show()
          setTimeout(function() {
            $("#alert-success").hide()
          }, 3000);
          window.location = `/registration/registration-success?member_name=${data.member_name}&sponsor_username=${data.sponsor_username}&sponsor_member_name=${data.sponsor_member_name}&invoice_url=${data.invoice_url}`
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
              if (i.includes(".*")) {
                i = i.replace(".*", "")
              }
              $(`#alert_input_${i}`).html(val).show()
            })
          }
          window.scrollTo(0, 0);
        },
        complete: () => {
          $("#btn_submit").prop("disabled", false)
          $("#btn_submit").html(`Proses`)
        }
      })
    }

    $("#btn_submit").on("click", (ev) => {
      $('#modal').modal('show');

      // tampilkan input
      $('#modal_label_network_slug').html($('#input_network_slug').val())
      $('#modal_label_member_name').html($('#input_member_name').val())
      $('#modal_label_phone_code').html($('#input_member_phone_code').val() + $('#input_member_mobilephone').val());
      // $('#modal_label_gender').html($('#input_member_gender').val())
      // $('#modal_label_email').html($('#input_member_email').val())
      // $('#modal_label_jenis_identitas').html($('#input_member_identity_type').val())
      // $('#modal_label_nomor_identitas').html($('#input_member_identity_no').val())
      // $('#modal_label_pekerjaan').html($('#input_member_job').val())
      // $('#modal_label_alamat').html($('#input_member_address').val())
      // $('#modal_label_provinsi').html($('#input_member_province_id').val())
      // $('#modal_label_kota').html($('#input_member_city_id').val())
      // $('#modal_label_kecamatan').html($('#input_member_subdistrict_id').val())
      // $('#modal_label_nama_rekening').html($('#input_member_bank_account_name').val())
      // $('#modal_label_nomor_rekening').html($('#input_member_bank_account_no').val())
      // $('#modal_label_nama_bank').html($('#input_member_bank_id').val())

      // $('#modal_label_transaction_type').html($("#transaction_type").data("type"))
      // console.log($("#transaction_type").data("type"))

      let method = $("#transaction_method").data("method");
      if (method == "pickup") {
        $('#modal_label_transaction_method').html("Ambil");
        let address = $("#transaction_address").data("address");
        if (address == "self") {
          $('#modal_label_transaction_address').html("-");
        } else if (address == "other") {
          $('#modal_label_transaction_address').html("-");
        }

      } else if (method == "courier") {
        $('#modal_label_transaction_method').html("Kirim");
        let address = $("#transaction_address").data("address");
        if (address == "self") {
          $('#modal_label_transaction_address').html($('#input_member_address').val());
        } else if (address == "other") {
          $('#modal_label_transaction_address').html("Alamat Lain : " + $('#input_transaction_address').val());
        }
      }

      let type = $("#transaction_type").data("type");
      if (type == "warehouse") {
        $('#modal_label_transaction_type').html("Perusahaan");
        $('#modal_label_transaction_keterangan').html("Diambil di Citraland BSB City Cluster Ivy Park Blok A1.27 Kec. Mijen, Kota Semarang, Jawa Tengah");
      } else if (type == "stockist") {
        $('#modal_label_transaction_type').html("Master Stokis / Stokis");
        $('#modal_label_transaction_keterangan').html($(input_stockist_list).val())
        var keterangan = $(input_stockist_list).val();
        var result = "";

        if (keterangan == "9") {
          result = "Dikirim dari Master Stokis/Stokis Eagle Squad (Semarang)";
        } else if (keterangan == "15") {
          result = "Dikirim dari Master Stokis/Stokis Jawara Mbeling (Semarang)";
        } else if (keterangan == "278") {
          result = "Dikirim dari Master Stokis/Stokis Purnama Wulan (Kendal)";
        } else if (keterangan == "450") {
          result = "Dikirim dari Master Stokis/Stokis Bunda Puput (Gowa)";
        }

        $('#modal_label_transaction_keterangan').html(result);

      }
      $('#modal_label_transaction_price_total').html((formatCurrency(transaction_nett_price)))



      var courierCode = $('#input_transaction_courier_code').val();
      let layanan = $('#input_transaction_courier_service').val();
      var uppercaseCourierCode = courierCode.toUpperCase() + `(${layanan})`; // convert the value to uppercase
      $('#modal_label_kurir').html(uppercaseCourierCode); // set the HTML content of the target element to the uppercase value
    })

    $("#submitLanjutkan").on("click", (ev) => {
      addData()
      $('#modal').modal('hide');
    })


    $("#input_member_identity_image").on("change", (ev) => {
      const [file] = ev.target.files
      console.log(file)
      if (file) {
        $("#input_member_identity_image_preview").prop("src", URL.createObjectURL(file))
      }
    })

    $("#input_member_tax_image").on("change", (ev) => {
      const [file] = ev.target.files
      if (file) {
        $("#img_member_tax_image").prop("src", URL.createObjectURL(file))
      }
    })

    $("#input_payment_image").on("change", (ev) => {
      const [file] = ev.target.files
      if (file) {
        $("#img_payment_image").prop("src", URL.createObjectURL(file))
      }
    })

    $("#input_member_name").on("click change keyup", (ev) => {
      if ($("#transaction_address").data("address") == "self") {
        $("#input_transaction_name").val($(ev.target).val()).trigger("change")
      }
    })

    $("#input_member_mobilephone").on("click change keyup", (ev) => {
      if ($("#transaction_address").data("address") == "self") {
        $("#input_transaction_mobilephone").val($(ev.target).val()).trigger("change")
      }
    })

    $("#input_member_address").on("click change keyup", (ev) => {
      if ($("#transaction_address").data("address") == "self") {
        $("#input_transaction_address").val($(ev.target).val()).trigger("change")
      }
    })

    $("#input_member_province_id").on("change", (ev) => {
      if ($("#transaction_address").data("address") == "self") {
        $("#input_transaction_province_id").val($(ev.target).val()).trigger("change")
      }
      getCity($(ev.target).val())
    })

    $("#input_member_city_id").on("change", (ev) => {
      if ($("#transaction_address").data("address") == "self") {
        $("#input_transaction_city_id").val($(ev.target).val()).trigger("change")
      }
      getSubdistrict($(ev.target).val())
    })

    $("#input_member_subdistrict_id").on("change", (ev) => {
      if ($("#transaction_address").data("address") == "self") {
        $("#input_transaction_subdistrict_id").val($(ev.target).val()).trigger("change")
      }
      $("#input_transaction_courier_code").trigger("change")
    })

    $("#input_transaction_province_id").on("click change", (ev) => {
      getCityDelivery($(ev.target).val())
    })

    $("#input_transaction_city_id").on("click change", (ev) => {
      getSubdistrictDelivery($(ev.target).val())
      $("#input_transaction_courier_code").trigger("change")
    })

    getCountry = () => {
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-ref-country",
        type: "GET",
        // async: false,
        success: (res) => {
          data = res.data.results
          $.each(data, (i, val) => {
            $("#input_member_phone_code").append(
              `<option data-image="${val.country_flag}" value="${val.country_phone_code}">${val.country_name} (${val.country_phone_code})</option>`)
          })
          $("#input_member_phone_code").val("+62")
          $(".ms-dd-header").trigger("click")
          $(".ms-dd-header").trigger("click")
        },
      })
    }

    getProvince = () => {
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-ref-province",
        type: "GET",
        // async: false,
        success: (res) => {
          data = res.data.results
          $.each(data, (i, val) => {
            $("#input_member_province_id").append(
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
          async: false,
          success: (res) => {
            data = res.data.results
            $("#input_member_city_id").empty()
            $("#input_member_city_id").append(
              `<option value="0">Pilih Kota</option>`)
            $.each(data, (i, val) => {
              $("#input_member_city_id").append(
                `<option value="${val.city_id}">${val.city_name}</option>`)
            })
            $("#input_member_city_id").trigger("change")
            getSubdistrict($("#input_member_city_id").val())
          },
        })
      }
    }

    getSubdistrict = (city_id) => {
      if (city_id != 0) {
        $.ajax({
          url: "<?= BASEURL ?>/common/get-list-ref-subdistrict/?city_id=" + city_id,
          type: "GET",
          async: false,
          success: (res) => {
            data = res.data.results
            $("#input_member_subdistrict_id").empty()
            $("#input_member_subdistrict_id").append(
              `<option value="0">Pilih Kecamatan</option>`)
            $.each(data, (i, val) => {
              $("#input_member_subdistrict_id").append(
                `<option value="${val.subdistrict_id}">${val.subdistrict_name}</option>`)
            })
            $("#input_member_subdistrict_id").trigger("change")
          },
        })
      }
    }

    getProvinceDelivery = () => {
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

    getCityDelivery = (province_id) => {
      if (province_id != 0) {
        $.ajax({
          url: "<?= BASEURL ?>/common/get-list-ref-city/?province_id=" + province_id,
          type: "GET",
          success: (res) => {
            data = res.data.results
            $("#input_transaction_city_id").empty()
            $("#input_transaction_city_id").append(
              `<option value="0">Pilih Kota</option>`)
            $("#input_transaction_subdistrict_id").empty()
            $("#input_transaction_subdistrict_id").append(
              `<option value="0">Pilih Kecamatan</option>`)
            $.each(data, (i, val) => {
              $("#input_transaction_city_id").append(
                `<option value="${val.city_id}">${val.city_name}</option>`)
            })
            $("#input_transaction_city_id").trigger("change")
            getSubdistrictDelivery($("#input_transaction_city_id").val())
          },
        })
      }
    }

    getSubdistrictDelivery = (city_id) => {
      if (city_id != 0) {
        $.ajax({
          url: "<?= BASEURL ?>/common/get-list-ref-subdistrict/?city_id=" + city_id,
          type: "GET",
          success: (res) => {
            data = res.data.results
            $("#input_transaction_subdistrict_id").empty()
            $("#input_transaction_subdistrict_id").append(
              `<option value="0">Pilih Kecamatan</option>`)
            $.each(data, (i, val) => {
              $("#input_transaction_subdistrict_id").append(
                `<option value="${val.subdistrict_id}">${val.subdistrict_name}</option>`)
            })
            $("#input_transaction_subdistrict_id").trigger("change")
          },
        })
      }
    }

    getBank = () => {
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-ref-bank/",
        type: "GET",
        success: (res) => {
          data = res.data.results
          $("#input_member_bank_id").empty()
          $("#input_member_bank_id").append(
            `<option value="0">Pilih Bank</option>`)
          $.each(data, (i, val) => {
            $("#input_member_bank_id").append(
              `<option value="${val.bank_id}">${val.bank_name}</option>`)
          })
        },
      })
    }

    getStockist = () => {
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-stockist",
        type: "GET",
        success: (res) => {
          data = res.data.results
          $("#input_stockist_list").empty()
          $("#input_stockist_list").append(
            `<option value="0">Pilih Master Stokis / Stockis</option>`)
          $.each(data, (i, val) => {
            $("#input_stockist_list").append(
              `<option value="${val.stockist_member_id}">${val.stockist_name}</option>`)
          })
        },
      })
    }

    formatWeight = ($params) => {
      $params = $params > 1000 ? $params / 1000 : $params
      let formatter = $params.toLocaleString('id-ID', {
        style: 'unit',
        unit: $params > 1000 ? 'kilogram' : 'gram'
      })
      return formatter
    }

    getProduct = () => {
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-all-product",
        type: "GET",
        data: {
          type: "activation"
        },
        success: (res) => {
          product_data = res.data.results
          $("#product_list").empty()
          $.each(product_data, (i, val) => {
            $("#product_list").append(`
              <div class="col col-12 col-md-4 mb-1">
                <div class="card card-bordered border select_product p-3 mb-1" data-id="${val.product_id}" data-price="${val.product_price}" style="cursor: pointer;">
                  <div class="row">
                    <div class="col col-10">
                      <p class="text-primary" style="font-weight: bold; line-height: normal;">${val.product_name}</p>
                    </div>
                    <div class="col col-2 justify-content-end d-flex">
                      <i class="bx bx-circle text-light" id="select_product_${val.product_id}" style="font-size: 2rem;"></i>
                    </div>

                    <div class="col-12 col-md-4">
                    <img src="${val.product_image}" class="w-100">
                    </div>
                    <div class="col-12 col-md-8">
                      <p class="mb-1">${val.product_description == null ? "Tidak ada deskripsi." : val.product_description}</p>
                      <p class="show-value mb-1">Berat <span>${formatWeight(parseInt(val.product_weight))}</span></p>
                      <p class="show-value mb-1">Harga <span>${formatCurrency(val.product_price)}</span></p>
                      <div class="show-action text-center d-flex">
                        <i class="control control-substract bx bxs-minus-square" data-id="${val.product_id}" style="font-size: calc(1.4em + 0.94rem + 3.7px);"></i>
                        <input type="number" min="1" class="form-control text-center" id="quantity_${val.product_id}" value="0" disabled>
                        <i class="control control-add bx bxs-plus-square" data-id="${val.product_id}" style="font-size: calc(1.4em + 0.94rem + 3.7px);"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            `)
            $('#modal_label_product').html(val.product_name)
            $('#modal_label_product_cost').html(formatCurrency(val.product_price))
          })
        },
      })
    }

    getCourier = () => {
      $.ajax({
        url: "<?= BASEURL ?>/common/get-list-ref-courier",
        type: "GET",
        success: (res) => {
          data = res.data.results
          $("#input_transaction_courier_code").empty()
          $("#input_transaction_courier_code").append(
            `<option value="">Pilih Kurir</option>`)
          $.each(data, (i, val) => {
            $("#input_transaction_courier_code").append(
              `<option value="${val.courier_code}">${val.courier_code.toUpperCase()}</option>`)
          })
          // $("#input_transaction_courier_code").val("jne").trigger("change")
        },
      })
    }

    $("body").on("click", ".select_product", (ev) => {
      if (!$(ev.target).before().hasClass("control")) {
        let id = $(ev.target).closest(".select_product").data("id")
        if (!select_product_id.hasOwnProperty(id)) {
          select_product_id[id] = 1
          renderTotal()
          $(`#select_product_${id}`).removeClass("bx-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
          $(`#quantity_${id}`).val(1)
        } else {
          delete select_product_id[id]
          renderTotal()
          $(`#select_product_${id}`).removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
          $(`#quantity_${id}`).val(0)
        }
      }
      $("#input_transaction_courier_code").trigger("change")
    })

    $("body").on("click", ".control-substract", (ev) => {
      let selected_product_id = $(ev.target).closest(".select_product").data("id")
      if ($(`#select_product_${selected_product_id}`).hasClass("bxs-check-circle") && select_product_id[selected_product_id] > 1) {
        select_product_id[selected_product_id] -= 1
        renderTotal()
        $(`#quantity_${selected_product_id}`).val(parseInt($(`#quantity_${selected_product_id}`).val()) - 1)
      }
    })

    $("body").on("click", ".control-add", (ev) => {
      let selected_product_id = $(ev.target).closest(".select_product").data("id")
      if ($(`#select_product_${selected_product_id}`).hasClass("bxs-check-circle") && select_product_id[selected_product_id] < 999999) {
        select_product_id[selected_product_id] += 1
        renderTotal()
        $(`#quantity_${selected_product_id}`).val(parseInt($(`#quantity_${selected_product_id}`).val()) + 1)
      }
    })

    $("#method_pickup").on("click", (ev) => {
      if ($("#transaction_method").data("method") == "courier") {
        $("#transaction_method").data("method", "pickup")
        $("#check_pickup").removeClass("bx-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
        $("#check_courier").removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
        $("#div_delivery").hide()
        $(".div-courier").hide()
        $(".div-warehouse").find(".form-group").html("Diambil di <?= COMPANY_ADDRESS ?>")
        $(".div-stockist").find("label").html("Diambil di Master Stokis / Stokis")
        $("#input_transaction_courier_code").trigger("change")
      }
    })

    $("#method_courier").on("click", (ev) => {
      if ($("#transaction_method").data("method") == "pickup") {
        $("#transaction_method").data("method", "courier")
        $("#check_courier").removeClass("bx-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
        $("#check_pickup").removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
        $("#div_delivery").show()
        $(".div-courier").show()
        $("#input_transaction_courier_code").trigger("change")
        $(".div-warehouse").find(".form-group").html("Dikirim dari <?= COMPANY_ADDRESS ?>")
        $(".div-stockist").find("label").html("Dikirim dari Master Stokis / Stokis")
        $("#input_transaction_courier_code").trigger("change")
      }
    })

    $("#type_warehouse").on("click", (ev) => {
      if ($("#transaction_type").data("type") == "stockist") {
        $("#transaction_type").data("type", "warehouse")
        $("#check_warehouse").removeClass("bx-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
        $("#check_stockist").removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
        $("#input_transaction_courier_code").trigger("change")
        $(".div-stockist").hide()
        $(".div-warehouse").show()
        $("#input_transaction_courier_code").trigger("change")
      }
    })

    $("#type_stockist").on("click", (ev) => {
      if ($("#transaction_type").data("type") == "warehouse") {
        $("#transaction_type").data("type", "stockist")
        $("#check_stockist").removeClass("bx-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
        $("#check_warehouse").removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
        $("#input_transaction_courier_code").trigger("change")
        $(".div-stockist").show()
        $(".div-warehouse").hide()
        $("#input_transaction_courier_code").trigger("change")
      }
    })

    $("#input_stockist_list").on("change", (ev) => {
      $("#input_transaction_courier_code").trigger("change")
    })

    $("#address_self").on("click", (ev) => {
      $(".div-address").addClass("d-none")
      $(".div-address").removeClass("d-flex")
      if ($("#transaction_address").data("address") == "other") {
        $("#transaction_address").data("address", "self")
        $("#check_self").removeClass("bx-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
        $("#check_other").removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
        $("#input_transaction_name").val($("#input_member_name").val()).prop("disabled", true)
        $("#input_transaction_mobilephone").val($("#input_member_mobilephone").val()).prop("disabled", true)
        $("#input_transaction_address").val($("#input_member_address").val()).prop("disabled", true)
        $("#input_transaction_province_id").val($("#input_member_province_id").val()).prop("disabled", true)
        $("#input_transaction_city_id").val($("#input_member_city_id").val()).prop("disabled", true).trigger("change")
        $("#input_transaction_subdistrict_id").val($("#input_member_subdistrict_id").val()).prop("disabled", true)
        $("#input_transaction_courier_code").val('')
        $("#input_transaction_province_id").trigger("change")
      }
    })

    $("#address_other").on("click", (ev) => {
      $(".div-address").addClass("d-flex")
      $(".div-address").removeClass("d-none")
      if ($("#transaction_address").data("address") == "self") {
        $("#transaction_address").data("address", "other")
        $("#check_other").removeClass("bx-circle").addClass("bxs-check-circle").removeClass("text-light").addClass("text-success")
        $("#check_self").removeClass("bxs-check-circle").addClass("bx-circle").removeClass("text-success").addClass("text-light")
        $("#input_transaction_name").val("").prop("disabled", false)
        $("#input_transaction_mobilephone").val("").prop("disabled", false)
        $("#input_transaction_address").val("").prop("disabled", false)
        $("#input_transaction_province_id").val("").prop("disabled", false)
        $("#input_transaction_city_id").val("").prop("disabled", false).trigger("change")
        $("#input_transaction_subdistrict_id").val("").prop("disabled", false)
        $("#input_transaction_courier_code").val('')
      }
    })

    // $("#input_transaction_courier_code").on("change", (ev) => {
    //   $(".content-loading").addClass("loadings")
    //   $.ajax({
    //     url: "<?= BASEURL ?>/common/get-delivery-cost",
    //     type: "GET",
    //     data: {
    //       transaction_type: $("#transaction_type").data("type"),
    //       stockist_member_id: $("#input_stockist_list").val(),
    //       transaction_subdistrict_id: $("#transaction_address").data("address") == "self" ? $("#input_member_subdistrict_id").val() : $("#input_transaction_subdistrict_id").val(),
    //       transaction_city_id: $("#transaction_address").data("address") == "self" ? $("#input_member_city_id").val() : $("#input_transaction_city_id").val(),
    //       transaction_total_weight: transaction_total_weight,
    //       transaction_courier_code: $("#input_transaction_courier_code").val(),
    //     },
    //     success: (res) => {
    //       delivery_cost = 0
    //       $(".content-loading").removeClass("loadings")
    //       let data = res.data.results
    //       $("#input_transaction_courier_service").empty()
    //       $("#input_transaction_courier_service").append(
    //         `<option value="">Pilih Layanan</option>`)
    //       if (0 in data && data[0].hasOwnProperty("costs")) {
    //         $.each(data[0].costs, (i, val) => {
    //           $("#input_transaction_courier_service").append(`
    //           <option value="${val.service}" data-index="${i}">${val.description} (${val.cost[0].etd} hari)</option>
    //           `)
    //         })
    //         delivery_data = data[0]
    //       }
    //       $("#input_transaction_courier_service").trigger("change")
    //     },
    //     error: () => {
    //       $(".content-loading").removeClass("loadings")
    //     },
    //     complete: () => {
    //       $(".content-loading").removeClass("loadings")
    //     }
    //   })
    // })

    // $("#input_transaction_courier_service").on("change", (ev) => {
    //   if (delivery_data && delivery_data.hasOwnProperty("costs") && delivery_data.costs.length > 0) {
    //     if ($("#transaction_type").data("type") == "stockist") {
    //       if ($("#input_transaction_courier_service>option:selected").data("index") >= 0) {
    //         if ($("#input_transaction_courier_service>option:selected").data("index") != undefined) {
    //           delivery_cost = delivery_data.costs[$("#input_transaction_courier_service>option:selected").data("index")].cost[0].value
    //         } else {
    //           delivery_cost = 0
    //         }
    //       } else {
    //         delivery_cost = 0
    //       }
    //     } else {
    //       if ($("#input_transaction_courier_service>option:selected").data("index") != undefined) {
    //         delivery_cost = delivery_data.costs[$("#input_transaction_courier_service>option:selected").data("index")].cost[0].value
    //       } else {
    //         delivery_cost = 0
    //       }
    //     }
    //   } else {
    //     delivery_cost = 0
    //   }
    //   $("#input_transaction_courier_cost").html(formatCurrency(delivery_cost))
    //   renderTotal()
    // })

    renderTotal = () => {
      transaction_total_price = 0
      transaction_total_weight = 0
      delivery_cost = 0
      $.each(select_product_id, (id, qty) => {
        transaction_total_price += parseInt(product_data.find(o => o.product_id == id).product_price) * parseInt(qty)
        transaction_total_weight += parseInt(product_data.find(o => o.product_id == id).product_weight) * parseInt(qty)
      })
      transaction_nett_price = parseInt(transaction_total_price) + ($("#transaction_method").data("method") == "pickup" ? 0 : parseInt(delivery_cost))
      $("#transaction_price_total").html(formatCurrency(transaction_nett_price))
    }

    $("#input_member_name").on("keyup change", (ev) => {
      $("#input_member_bank_account_name").val($(ev.target).val())
    })

    $("#basic-toggle-password").on("click", (ev) => {
      if ($("#input_member_account_password").prop("type") == "password") {
        $("#input_member_account_password").prop("type", "text")
        $("#input_member_account_password").parent().find("i").removeClass("bx-hide").addClass("bx-show")
      } else {
        $("#input_member_account_password").prop("type", "password")
        $("#input_member_account_password").parent().find("i").removeClass("bx-show").addClass("bx-hide")
      }
    })

    formatCurrency = ($params) => {
      let formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      })
      return formatter.format($params)
    }

    getProduct()
    getCountry()
    getProvince()
    getProvinceDelivery()
    getCourier()
    getBank()
    getStockist()
  })

  function onlyNumberKey(evt) {

    // Only ASCII character in that range allowed
    var ASCIICode = (evt.which) ? evt.which : evt.keyCode
    console.log(ASCIICode);
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
      return false;

    return true;
  }
</script>
<!-- END: Content-->