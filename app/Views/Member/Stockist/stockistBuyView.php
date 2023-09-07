<!-- BEGIN: Content-->
<style>
  .cstmHover:hover {
    cursor: pointer;
  }

  .cstmWidth {
    width: calc(100% - 20rem);
  }

  .cstmDnone {
    display: flex !important;
  }

  @media only screen and (min-width: 0px) and (max-width: 1024px) {
    .cstmWidth {
      width: calc(100%);
    }
  }

  @media only screen and (min-width: 0px) and (max-width: 500px) {
    .cstmDnone {
      display: none !important;
    }
  }
</style>
<div class="app-content content" id="vue-app">
  <div class="content-overlay">
  </div>
  <div class="content-loading">
    <i class="bx bx-loader bx-spin"></i>
  </div>
  <div class="content-wrapper">
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
      <!-- <div :class="[alert != '' ? 'alert alert-danger' : 'alert alert-danger d-none']" role="alert" id="alert" style="position: absolute; top: 84px; right: 32px;">{{ alert }}</div> -->
      <div class="alert alert-success d-none" role="alert" id="alert-success" style="position: absolute; top: 84px; right: 32px;"></div>
      <!-- alert -->

      <div class="alert alert-danger" role="alert" id="alert" style="position: absolute; top: 84px; right: 32px; display:none;">{{ alert }}</div>



      <div class=" row" v-show="!next">
        <div class="col-md-12 col-12">
          <div class="row">
            <div class="col-lg-6 col-sm-12 col-md-12 mb-1" v-for="(product, index) in product_list">
              <div class="card card-bordered border select_product p-1 mb-1" style="cursor: pointer;" @click="addToCart($event, product.product_id)">
                <div class="row">
                  <div class="col col-4 text-center">
                    <img class="logo w-100" :src="product.product_image">
                  </div>
                  <div class="col col-8">
                    <p style="font-weight: bold;" class="mb-0">{{product.product_name}}</p>
                    <p class="mb-0">{{formatCurrency(product.product_price)}}</p>
                    <p>{{product.product_weight}} gr</p>
                    <p :class="[product.stockist_product_stock_balance == 0 ? 'text-danger mb-1' : product.stockist_product_stock_balance > 5 ? 'text-success' : 'mb-1']">Stok anda saat ini : {{product.stockist_product_stock_balance}}</p>
                  </div>
                </div>
                <div class="d-flex align-items-center justify-content-center mb-1">
                  <i :class="[inCart(product.product_id) ? 'bx bxs-check-circle text-success' : 'bx bx-circle text-light']" style="font-size: 2rem;"></i> Pilih Produk
                </div>
                <div class="text-center d-inline-flex">
                  <input type="number" min="1" class="form-control text-center" :value="qtyInCart(product.product_id)" @keyup="changeQty(product.product_id)" :id="product.product_code">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="cstmWidth" style="position: fixed; top: calc(100vh - 120px - 3.8rem); height: 120px; padding-left: 1.75rem; padding-right: 1.75rem;">
          <div class="row" id="summary">
            <div class="col col-12 col-md-12 p-0">
              <div class="card p-1 shadow">
                <div class="row align-items-center">
                  <div class="col col-8 d-flex flex-column">
                    <?php if (session('member')['stockist_type'] == 'master') { ?>
                      <div class="m-0 text-info">Transaksi Minimal 100 Paket</div>
                    <?php } else { ?>
                      <div class="m-0 text-info">Transaksi Minimal 30 Paket</div>
                    <?php } ?>
                    <div class="m-0"><b>{{ cart.length }}</b> produk dipilih</div>
                    <div class="m-0">Total <b>{{ formatCurrency(total_price) }}</b></div>
                  </div>
                  <div class="col col-4 text-right"><button class="btn btn-primary" @click="nextStep" :disabled="checkMin">Lanjut</button></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row" v-show="next">
        <div class="col col-12 col-md-12 mb-1 d-none d-lg-block">
          <div class="card p-1 mb-0">
            <div class="row">
              <label class="col col-5 text-left">Produk</label>
              <label class="col col-2 text-center">Jumlah</label>
              <label class="col col-2 text-center">Berat</label>
              <label class="col col-3 text-right">Harga</label>
            </div>
          </div>
        </div>
        <div class="col col-12 col-md-12">
          <div class="card p-1 mb-1 d-none d-lg-block" v-for="data in cart">
            <div class="row align-items-center">
              <div class="col col-5 text-left"><b>{{ data.product_name}}</b><br>@ {{formatWeight(data.product_weight)}}<br>@ {{formatCurrency(data.product_price)}}</div>
              <div class="col col-2 text-center">{{ data.product_qty}}</div>
              <div class="col col-2 text-center">{{ formatWeight(parseInt(data.product_weight * data.product_qty)) }}</div>
              <div class="col col-3 text-right">{{ formatCurrency(data.product_price * data.product_qty) }}</div>
            </div>
          </div>

          <div class="card p-1 mb-1 d-block d-lg-none" v-for="data in cart">
            <div class="row align-items-center">
              <div class="col col-12 text-left pb-1 mb-1" style="border-bottom: 1px solid #ddd;">
                <b class="d-block mb-1">{{ data.product_name }}</b>
                @ {{ formatWeight(data.product_weight) }}<br>
                @ {{ formatCurrency(data.product_price) }}
              </div>
              <div class="col col-12 text-left">
                <div class="d-flex flex-row align-items-center justify-content-between mb-50">
                  <label class="mb-0">Jumlah</label>
                  {{ data.product_qty }}
                </div>
                <div class="d-flex flex-row align-items-center justify-content-between mb-50">
                  <label class="mb-0">Berat</label>
                  {{ formatWeight(parseInt(data.product_weight) * parseInt(data.product_qty)) }}
                </div>
                <div class="d-flex flex-row align-items-center justify-content-between mb-50">
                  <label class="mb-0">Harga</label>
                  {{ formatCurrency(parseInt(data.product_price) * parseInt(data.product_qty)) }}
                </div>
              </div>
            </div>
          </div>

          <div class="card p-1 mb-1 d-none d-lg-block">
            <div class="row align-items-center">
              <label class="col col-5 text-left">Sub Total</label>
              <div class="col col-2 text-center">{{total_product}}</div>
              <div class="col col-2 text-center" id="transaction_total_weight">{{formatWeight(total_weight)}}</div>
              <label class="col col-3 text-right" style="font-size: 1.6rem;">{{formatCurrency(total_price)}}</label>
            </div>
          </div>

          <div class="card p-1 mb-1 d-block d-lg-none">
            <div class="d-flex flex-column">
              <div class="d-flex flex-row align-items-center justify-content-between mb-50">
                <label class="mb-0">Subtotal Produk</label>
                <div id="transaction_total_weight_mobile" style="color: #6a0dad; font-weight: bold;">{{ total_product }}</div>
              </div>
              <div class="d-flex flex-row align-items-center justify-content-between mb-50">
                <label class="mb-0">Subtotal Berat</label>
                <div id="transaction_total_weight_mobile" style="color: #6a0dad; font-weight: bold;">{{ formatWeight(total_weight) }}</div>
              </div>
              <div class="d-flex flex-row align-items-center justify-content-between">
                <label class="mb-0">Subtotal Harga</label>
                <div style="font-size: 1.2rem; color: #6a0dad; font-weight: bold;">{{ formatCurrency(total_price) }}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col col-12 col-md-12 mb-1">
          <div class="card p-1 mb-0">
            <div class="row">
              <label class="col col-12 d-flex align-items-center my-50">Pilih metode pengiriman</label>
              <small class="col col-12 col-lg-8">
                Barang {{ pickup ? "dapat di ambil di" : "dikirim dari" }} <?= COMPANY_ADDRESS ?>
              </small>
              <div class="col col-12 col-lg-4">
                <div class="row">
                  <div class="col col-6 d-flex align-items-center cstmHover" @click="changeMethod(true)">
                    <i :class="[pickup ? 'bx bxs-check-circle text-success mr-1' : 'bx bx-circle text-light mr-1']" style="font-size: 2rem;"></i>
                    <label for="check_pickup">Ambil</label>
                  </div>
                  <div class="col col-6 d-flex align-items-center cstmHover" @click="changeMethod(false)">
                    <i :class="[!pickup ? 'bx bxs-check-circle text-success mr-1' : 'bx bx-circle text-light mr-1']" style="font-size: 2rem;"></i>
                    <label for="check_courier">Kirim</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col col-12 col-md-12 mb-1">
          <div class="card p-1 mb-0">
            <div class="row">
              <label class="col col-12 col-lg-8 d-flex align-items-center my-50">Pilih tempat ambil / pengirim</label>
              <div class="col col-12 col-lg-4">
                <div class="row">
                  <div class="col col-12 col-lg-6 d-flex align-items-center" @click="changeType('warehouse')">
                    <i :class="[type == 'warehouse' ? 'bx bxs-check-circle text-success mr-1' : 'bx bx-circle text-light mr-1']" style="font-size: 2rem;"></i>
                    <label for="check_warehouse">Perusahaan</label>
                  </div>
                  <div class="col col-12 col-lg-6 d-flex align-items-center" @click="changeType('stockist')">
                    <i :class="[type == 'stockist' ? 'bx bxs-check-circle text-success mr-1' : 'bx bx-circle text-light mr-1']" style="font-size: 2rem;"></i>
                    <label for="check_master_stockist">Master Stokis</label>
                  </div>
                </div>
                <div class="row mt-1" v-show="type == 'stockist'">
                  <div class="col col-12 col-lg-12 d-flex align-items-center">
                    <select class="form-control" v-model="stockist_selected" @change="changeStockist()">
                      <option v-for="item in stockist_list" :value="item.stockist_member_id">{{ item.stockist_name }}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col col-12 col-md-12 mb-1" v-show="!pickup">
          <div class="card p-1 mb-0">
            <div class="row">
              <label class="col col-12 col-lg-8 d-flex align-items-center my-50">Alamat Pengiriman</label>
              <div class="col col-12 col-lg-4">
                <div class="row">
                  <div class="col col-6 d-flex align-items-center cstmHover" @click="changeAddress(true)">
                    <i :class="[address_main ? 'bx bxs-check-circle text-success mr-1' : 'bx bx-circle text-light mr-1']" style="font-size: 2rem;"></i>
                    <label for="check_self">Alamat Utama</label>
                  </div>
                  <div class="col col-6 d-flex align-items-center cstmHover" @click="changeAddress(false)">
                    <i :class="[!address_main ? 'bx bxs-check-circle text-success mr-1' : 'bx bx-circle text-light mr-1']" style="font-size: 2rem;"></i>
                    <label for="check_other">Alamat Lain</label>
                  </div>
                </div>
              </div>
              <div class="col col-12 align-items-center" v-show="!address_main">
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="input_transaction_name">Nama Penerima</label>
                      <input type="text" class="form-control" rows="4" v-model="other_address.name">
                      <small class="text-danger alert-input" id="alert_input_transaction_name" style="display: none;"></small>
                    </div>
                  </div>

                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="input_transaction_mobilephone">No. HP Penerima</label>
                      <input type="text" class="form-control" rows="4" v-model="other_address.mobilephone">
                      <small class="text-danger alert-input" id="alert_input_transaction_mobilephone" style="display: none;"></small>
                    </div>
                  </div>

                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="input_transaction_address">Alamat Lengkap</label>
                      <textarea class="form-control" rows="4" v-model="other_address.address" placeholder="" style="height: calc(4.2em + 6.82rem + 20.1px);"></textarea>
                      <small class="text-danger alert-input" id="alert_input_transaction_address" style="display: none;"></small>
                    </div>
                  </div>

                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="input_transaction_province_id">Provinsi</label>
                      <select class="form-control" v-model="other_address.province_id" @change="change_province">
                        <option value="0">Pilih Provinsi</option>
                        <option v-for="data in province_list" :value="data.province_id">{{data.province_name}}</option>
                      </select>
                      <small class="text-danger alert-input" id="alert_input_transaction_province_id" style="display: none;"></small>
                    </div>

                    <div class="form-group">
                      <label for="input_transaction_city_id">Kota</label>
                      <select class="form-control" v-model="other_address.city_id" @change="change_city">
                        <option value="0">Pilih Kota</option>
                        <option v-for="data in city_list" :value="data.city_id">{{data.city_name}}</option>
                      </select>
                      <small class="text-danger alert-input" id="alert_input_transaction_city_id" style="display: none;"></small>
                    </div>

                    <div class="form-group">
                      <label for="input_transaction_subdistrict_id">Kecamatan</label>
                      <select class="form-control" v-model="other_address.subdistrict_id" @change="change_subdistrict">
                        <option value="0">Pilih Kecamatan</option>
                        <option v-for="data in subdistrict_list" :value="data.subdistrict_id">{{data.subdistrict_name}}</option>
                      </select>
                      <small class="text-danger alert-input" id="alert_input_transaction_subdistrict_id" style="display: none;"></small>
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <div class="row">
              <label class="col col-12 d-flex align-items-center my-50">Jasa Pengiriman</label>
              <div class="col-12 col-lg-6 mb-sm-50">
                <div class="form-group mb-0">
                  <label for="input_transaction_courier_code">Kurir</label>
                  <select class="form-control" v-model="courier_select" @change="delivery_choose">
                    <option value="">Pilih Kurir</option>
                    <option v-for="data in courier_list" :value="data.courier_code">
                      {{data.courier_code.toUpperCase()}}
                    </option>
                  </select>
                  <small class="text-danger alert-input" id="alert_input_transaction_courier_code" style="display: none;"></small>
                </div>
              </div>
              <div class="col-12 col-lg-6 mb-sm-50">
                <div class="form-group mb-0">
                  <label for="input_transaction_courier_service">Layanan</label>
                  <select class="form-control" v-model="courier_service_select" @change="delivery_cost">
                    <option value="">Pilih Layanan</option>
                    <option v-for="(data, index) in courier_service" :value="data.service">{{data.description}}</option>
                    <!-- <option v-for="(data, index) in courier_service" :value="data.service">{{data.description}} ({{data.cost[0].etd}} hari)</option> -->
                  </select>
                  <small class="text-primary alert-input" v-if="courier_service_select != ''">untuk informasi biaya pengiriman silahkan hubungi Admin Kimstella</small>
                  <small class="text-danger alert-input" id="alert_input_transaction_courier_service" style="display: none;"></small>
                </div>
              </div>
              <div class="col-12 col-lg-4 mt-1 d-none">
                <div class="form-group">
                  <div class="row">
                    <div class="col-6">
                      <label for="input_transaction_courier_cost">Ongkos Kirim</label>
                    </div>
                    <div class="col-6">
                      <div class="text-right">{{formatCurrency(ongkir)}}</div>
                    </div>
                    <div class="col-6">
                      <label for="input_transaction_courier_cost">Subsidi Ongkir</label>
                    </div>
                    <div class="col-6">
                      <div class="text-right">{{formatCurrency(discount_ongkir)}}</div>
                    </div>
                    <div class="col-6">
                      <label for="input_transaction_courier_cost">Total Ongkir</label>
                    </div>
                    <div class="col-6">
                      <div class="text-right"><b>{{formatCurrency(ongkir - discount_ongkir)}}</b></div>
                    </div>
                  </div>
                  <small class="text-danger alert-input" id="alert_input_transaction_courier_cost" style="display: none;"></small>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col col-12 col-md-12 mb-1">
          <div class="card p-1 mb-0">
            <div class="row">
              <label class="col col-12 d-flex align-items-center">Saldo Stokis</label>
              <div class="col col-12 col-lg-8">
                <p> {{ formatCurrency(saldo) }}</p>
              </div>
              <div class="col col-12 col-lg-4">
                <div class="row">
                  <div class="col col-6 d-flex align-items-center cstmHover" @click="useSaldo">
                    <i :class="[use_saldo ? 'bx bxs-check-circle text-success mr-1' : 'bx bx-circle text-light mr-1']" style="font-size: 2rem;"></i>
                    <label>Gunakan Saldo</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col col-12 col-md-12 mb-1">
          <div class="card p-1 mb-0">
            <div class="row align-items-center">
              <label class="col-12 col-lg-9 mb-0 d-flex align-items-center">Total</label>
              <label class="col-12 col-lg-3 mb-0 text-right" id="transaction_price_total" style="font-size: 1.6rem;">{{ formatCurrency(total_price_final + ongkir - discount_ongkir )}}</label>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-12">
          <div class="row">
            <div class="col col-12 col-md-12 mb-1">
              <div class="row">
                <div class="col col-6 text-left"><button class="btn btn-primary btn-sm-block" @click="nextStep">Kembali</button></div>
                <div class="col col-6 text-right"><button class="btn btn-primary btn-sm-block" @click="process" id="btnProcess">Proses</button></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const app = Vue.createApp({
    data() {
      return {
        member_address: {},
        other_address: {
          name: '',
          mobilephone: '',
          address: '',
          province_id: 0,
          city_id: 0,
          subdistrict_id: 0
        },
        province_list: [],
        city_list: [],
        subdistrict_list: [],
        product_list: [],
        courier_list: [{
          courier_code: 'J&T Cargo',
        }],
        courier_select: '',
        courier_service_select: '',
        courier_service: [],
        cart: [],
        product_detail: [],
        total_price: 0,
        total_price_final: 0,
        total_weight: 0,
        total_product: 0,
        next: false,
        pickup: true,
        ongkir: 0,
        discount_ongkir: 0,
        address_main: true,
        stockist_type: `<?= session('member')['stockist_type'] ?>`,
        checkMin: true,
        alert: '',
        saldo: 0,
        cut: 0,
        use_saldo: false,
        type: 'warehouse',
        stockist_selected: "",
        stockist_list: [],
      }
    },
    methods: {
      useSaldo() {
        this.use_saldo = !this.use_saldo

        if (this.use_saldo) {
          if (this.total_price_final < this.saldo) {
            this.cut = this.total_price_final
            this.saldo = parseInt(this.saldo) - this.total_price_final
            this.total_price_final = this.total_price_final - this.total_price_final
          } else {
            this.cut = this.saldo
            this.total_price_final = this.total_price_final - this.saldo
            this.saldo = 0
          }
        } else {
          this.total_price_final = this.total_price_final + parseInt(this.cut)
          this.saldo = parseInt(this.saldo) + parseInt(this.cut)
          this.cut = 0
        }
      },
      changeQty(product_id) {
        let find_index = app.cart.findIndex(data => data.product_id === product_id);
        let find_product = app.product_list.find(data => data.product_id == product_id)
        let find_index_product = app.product_list.findIndex(data => data.product_id === product_id);

        let new_qty = parseInt($('#' + find_product.product_code).val() === '' ? 0 : parseInt($('#' + find_product.product_code).val()));

        if (find_index == -1) {
          this.cart.push({
            product_id: product_id,
            product_name: find_product.product_name,
            product_qty: new_qty,
            product_price: parseInt(find_product.product_price),
            product_weight: parseInt(find_product.product_weight),
          })
          app.total_product += new_qty
          app.total_price += parseInt(find_product.product_price) * new_qty
          app.total_price_final += (parseInt(find_product.product_price) * new_qty) - this.cut
          app.total_weight += parseInt(find_product.product_weight) * new_qty
        } else {
          app.total_product = new_qty
          app.cart[find_index].product_qty = new_qty
          app.total_price = parseInt(find_product.product_price) * new_qty
          app.total_price_final = (parseInt(find_product.product_price) * new_qty) - this.cut
          app.total_weight = parseInt(find_product.product_weight) * new_qty
        }

        if (new_qty === 0) {
          app.total_product -= app.cart[find_index].product_qty
          app.total_price -= parseInt(find_product.product_price) * app.cart[find_index].product_qty
          app.total_price_final -= (parseInt(find_product.product_price) * app.cart[find_index].product_qty) + this.cut
          app.total_weight -= parseInt(find_product.product_weight) * app.cart[find_index].product_qty
          this.cart.splice(find_index, 1);
          app.checkMin = true
          app.alert = ''
        } else {
          if (app.stockist_type == 'mobile' && app.product_list[find_index_product].warehouse_product_stock_balance < 30) {
            app.checkMin = true
            app.alert = 'Stok ' + find_product.product_name + ' Habis';

            return false
          }

          if (app.stockist_type == 'master' && app.product_list[find_index_product].warehouse_product_stock_balance < 100) {
            app.checkMin = true
            app.alert = 'Stok ' + find_product.product_name + ' Habis';

            return false
          }
        }

        if (app.total_product < 100 && app.stockist_type == 'master') {
          app.checkMin = true
          app.alert = ''
        }

        if (app.total_product < 30 && app.stockist_type == 'mobile') {
          app.checkMin = true
          app.alert = ''
        }

        if (app.total_product >= 100 && app.stockist_type == 'master') {
          app.checkMin = false

          if (new_qty > app.product_list[find_index_product].warehouse_product_stock_balance) {
            app.checkMin = true
            app.alert = 'Kurangi Jumlah Produk Anda';
            return false
          } else {
            app.checkMin = false
            app.alert = ''
          }

          if (app.product_list[find_index_product].warehouse_product_stock_balance < 100) {
            app.checkMin = true
            app.alert = 'Stok Habis'
          }
        }

        if (app.total_product >= 30 && app.stockist_type == 'mobile') {
          app.checkMin = false

          if (new_qty > app.product_list[find_index_product].warehouse_product_stock_balance) {
            app.checkMin = true
            app.alert = 'Kurangi Jumlah Produk Anda';
            return false
          } else {
            app.checkMin = false
            app.alert = ''
          }
        }
      },
      addToCart(event, product_id) {
        let min = 100

        if (app.stockist_type == 'mobile') {
          min = 30
        }

        let find_index = app.cart.findIndex(data => data.product_id === product_id);
        let find_product = app.product_list.find(data => data.product_id == product_id)
        let find_index_product = app.product_list.findIndex(data => data.product_id === product_id);

        if (app.stockist_type == 'mobile' && app.product_list[find_index_product].warehouse_product_stock_balance < 30) {
          app.checkMin = true
          app.alert = 'Stok ' + find_product.product_name + ' Habis';
        } else {
          app.checkMin = false;
        }

        if (app.stockist_type == 'master' && app.product_list[find_index_product].warehouse_product_stock_balance < 100) {
          app.checkMin = true
          app.alert = 'Stok ' + find_product.product_name + ' Habis';
        } else {
          app.checkMin = false;
        }

        if (find_index == -1) {
          this.cart.push({
            product_id: product_id,
            product_name: find_product.product_name,
            product_qty: min,
            product_price: parseInt(find_product.product_price),
            product_weight: parseInt(find_product.product_weight),
          })
          app.total_product += min
          app.total_price += parseInt(find_product.product_price) * min
          app.total_price_final += (parseInt(find_product.product_price) * min) - this.cut
          app.total_weight += parseInt(find_product.product_weight) * min

        } else {
          app.total_product -= app.cart[find_index].product_qty
          app.total_price -= parseInt(find_product.product_price) * app.cart[find_index].product_qty
          app.total_price_final -= (parseInt(find_product.product_price) * app.cart[find_index].product_qty) - this.cut
          app.total_weight -= parseInt(find_product.product_weight) * app.cart[find_index].product_qty
          this.cart.splice(find_index, 1);
          app.alert = ''

          if (app.cart.length < 1) {
            app.checkMin = true
          }
        }
      },
      inCart(product_id) {
        let find_index = app.cart.findIndex(data => data.product_id === product_id);
        if (find_index == -1) {
          return false
        } else {
          return true
        }
      },
      qtyInCart(product_id) {
        let find_index = app.cart.findIndex(data => data.product_id === product_id);

        if (find_index == -1) {
          return 0
        } else {
          return app.cart[find_index].product_qty
        }
      },
      addQty(event, product_id, qty) {
        event.cancelBubble = true;

        let find_index = app.cart.findIndex(data => data.product_id === product_id);
        let find_index_product = app.product_list.findIndex(data => data.product_id === product_id);
        let find_product = app.product_list.find(data => data.product_id == product_id)

        if (app.product_list[find_index_product].warehouse_product_stock_balance == 0) {
          return false
        }

        if (app.product_list[find_index_product].warehouse_product_stock_balance - qty < 0) {
          return false
        }

        app.product_list[find_index_product].warehouse_product_stock_balance -= qty

        if (find_index == -1) {
          this.cart.push({
            product_id: product_id,
            product_name: find_product.product_name,
            product_qty: qty,
            product_price: parseInt(find_product.product_price) * qty,
            product_weight: parseInt(find_product.product_weight * qty),
          })

          app.total_product += qty
          app.total_price += parseInt(find_product.product_price) * qty
          app.total_price_final += (parseInt(find_product.product_price) * qty) - this.cut
          app.total_weight += parseInt(find_product.product_weight) * qty
        } else {
          app.total_product += qty
          app.cart[find_index].product_qty += qty
          app.total_price += parseInt(find_product.product_price) * qty
          app.total_price_final += (parseInt(find_product.product_price) * qty) - this.cut
          app.total_weight += parseInt(find_product.product_weight) * qty
        }

        if (app.total_product >= 100 && app.stockist_type == 'master') {
          app.checkMin = false
        }

        if (app.total_product >= 30 && app.stockist_type == 'mobile') {
          app.checkMin = false
        }
      },
      removeQty(event, product_id, qty) {
        event.cancelBubble = true;

        let find_index_product = app.product_list.findIndex(data => data.product_id === product_id);
        let find_index = app.cart.findIndex(data => data.product_id === product_id);
        let find_product = app.product_list.find(data => data.product_id == product_id)

        if (find_index == -1) {
          return false
        } else if (app.cart[find_index].product_qty - qty <= 0) {
          app.product_list[find_index_product].warehouse_product_stock_balance += app.cart[find_index].product_qty
          this.cart.splice(find_index, 1);
        } else {
          app.cart[find_index].product_qty -= qty
          app.total_product -= qty
          app.total_price -= parseInt(find_product.product_price) * qty
          app.total_price_final -= (parseInt(find_product.product_price) * qty) + this.cut
          app.total_weight -= parseInt(find_product.product_weight) * qty
          app.product_list[find_index_product].warehouse_product_stock_balance += qty
        }

        if (app.total_product < 100 && app.stockist_type == 'master') {
          app.checkMin = true
        }

        if (app.total_product < 30 && app.stockist_type == 'mobile') {
          app.checkMin = true
        }
      },
      getProductList() {
        $(".content-loading").addClass("loadings")
        $.ajax({
          url: "<?= BASEURL ?>/common/get-list-product-with-stock",
          type: "GET",
          data: {
            stockist_id: `<?= session('member')['member_id'] ?>`
          },
          success: (res) => {
            $(".content-loading").removeClass("loadings")
            this.product_list = res.data.results
          },
        })
      },
      getMemberAddress() {
        $.ajax({
          url: "<?= BASEURL ?>/member/transaction/get-member-address",
          type: "GET",
          success: (res) => {
            this.member_address = res.data.results
            this.saldo = parseInt(res.data.results.ewallet_acc)
          },
        })
      },
      getCourierList() {
        $.ajax({
          url: "<?= BASEURL ?>/common/get-list-ref-courier",
          type: "GET",
          success: (res) => {
            this.courier_list = res.data.results
          },
        })
      },
      getStockistList() {
        $.ajax({
          url: "<?= BASEURL ?>/common/get-list-stockist",
          type: "GET",
          async: false,
          data: {
            type: "master",
            member_id: <?= session("member")["member_id"] ?>,
          },
          success: (res) => {
            this.stockist_list = [{
              stockist_member_id: "",
              stockist_name: "Silakan Pilih Stokis",
            }]
            this.stockist_list.push(...res.data.results)
          },
        })
      },
      getProvinceList() {
        $.ajax({
          url: "<?= BASEURL ?>/common/get-list-ref-province",
          type: "GET",
          async: false,
          success: (res) => {
            this.province_list = res.data.results
          },
        })
      },
      getCityList() {
        $.ajax({
          url: "<?= BASEURL ?>/common/get-list-ref-city/?province_id=" + this.other_address.province_id,
          type: "GET",
          async: false,
          success: (res) => {
            this.city_list = res.data.results
          },
        })
      },
      getSubdistrictList() {
        $.ajax({
          url: "<?= BASEURL ?>/common/get-list-ref-subdistrict/?city_id=" + this.other_address.city_id,
          type: "GET",
          async: false,
          success: (res) => {
            this.subdistrict_list = res.data.results
          },
        })
      },
      nextStep() {
        this.next = !this.next

        if (this.next) {
          if (this.courier_select != '') {
            app.delivery_choose()
          }
        } else {
          app.useSaldo()
        }
      },
      changeMethod(params) {
        this.courier_select = ''
        this.courier_service_select = ''
        this.ongkir = 0
        this.discount_ongkir = 0
        this.pickup = params
      },
      changeAddress(params) {
        this.courier_select = ''
        this.courier_service_select = ''
        this.ongkir = 0
        this.discount_ongkir = 0
        this.address_main = params
      },
      changeType(params) {
        this.courier_select = ''
        this.courier_service_select = ''
        this.ongkir = 0
        this.discount_ongkir = 0
        this.type = params
      },
      changeStockist() {
        app.delivery_choose()
      },
      change_province() {
        app.getCityList()
      },
      change_city() {
        app.getSubdistrictList()
      },
      change_subdistrict() {
        app.delivery_choose()
      },
      delivery_choose() {
        this.courier_service_select = ''
        this.ongkir = 0
        this.discount_ongkir = 0
        this.courier_service = [{
          service: 'DFOD',
          description: 'DFOD'
        }]
        // $(".content-loading").addClass("loadings")
        // $.ajax({
        //   url: "<?= BASEURL ?>/common/get-delivery-cost",
        //   type: "GET",
        //   data: {
        //     transaction_type: this.type,
        //     stockist_member_id: this.stockist_selected,
        //     transaction_subdistrict_id: this.address_main ? this.member_address.member_subdistrict_id : this.other_address.subdistrict_id,
        //     transaction_city_id: this.address_main ? this.member_address.member_city_id : this.other_address.city_id,
        //     transaction_total_weight: this.total_weight,
        //     transaction_courier_code: this.courier_select,
        //   },
        //   success: (res) => {
        //     $(".content-loading").removeClass("loadings")
        //     this.courier_service = res.data.results && res.data.results[0] && res.data.results[0].costs ? res.data.results[0].costs : 0
        //   },
        //   error: () => {
        //     $(".content-loading").removeClass("loadings")
        //   },
        //   complete: () => {
        //     $(".content-loading").removeClass("loadings")
        //   }
        // })
      },
      delivery_cost() {
        let find_service = app.courier_service.find(data => data.service == this.courier_service_select)
        // this.ongkir = find_service.cost[0].value
        this.ongkir = 0

        if (this.type == "warehouse") {
          if (this.ongkir < this.total_product * 5000) {
            this.discount_ongkir = parseInt(this.ongkir)
          } else {
            this.discount_ongkir = parseInt(this.total_product) * 5000
          }
        } else {
          this.discount_ongkir = 0
        }
      },
      process() {
        $('#btnProcess').attr('disabled', true)
        $('#btnProcess').html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Memproses...')
        let data = {
          // type: 'warehouse',
          otp: $("#input_otp").val(),
          transaction_delivery_method: this.pickup ? 'pickup' : 'courier',
          transaction_courier_code: this.courier_select,
          transaction_courier_service: this.courier_service_select,
          transaction_delivery_cost: this.ongkir - this.discount_ongkir,
          name: this.address_main ? this.member_address.member_name : this.other_address.name,
          mobilephone: this.address_main ? this.member_address.member_mobilephone : this.other_address.mobilephone,
          address: this.address_main ? this.member_address.member_address : this.other_address.address,
          address_type: this.address_main ? 'self' : 'other',
          province_id: this.address_main ? this.member_address.member_province_id : this.other_address.province_id,
          city_id: this.address_main ? this.member_address.member_city_id : this.other_address.city_id,
          subdistrict_id: this.address_main ? this.member_address.member_subdistrict_id : this.other_address.subdistrict_id,
          detail: this.cart,
          use_saldo: this.use_saldo,
          type: this.type,
          stockist_member_id: this.stockist_selected,
        }

        $.ajax({
          url: "<?= BASEURL ?>/member/stockist/add-transaction",
          type: "POST",
          data: data,
          success: (res) => {
            $('#btnProcess').attr('disabled', false)
            $('#btnProcess').html('Proses')
            data = res.data.results
            $(".alert-input").hide()
            $("#alert-success").html(res.message)
            $("#alert-success").show()
            setTimeout(function() {
              $("#alert-success").hide()
            }, 3000)
            window.location = `/member/stockist/receipt-payment?id=${data.transaction_id}&type=${this.type}`
          },
          error: (err) => {
            $('#btnProcess').attr('disabled', false)
            $('#btnProcess').html('Proses')
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
      },
      formatCurrency(params) {
        let formatter = new Intl.NumberFormat('id-ID', {
          style: 'currency',
          currency: 'IDR',
          minimumFractionDigits: 0,
          maximumFractionDigits: 0,
        })
        return formatter.format(params)
      },
      formatWeight(params) {
        let value = params
        let unit = 'gram'
        if (params >= 1000) {
          value = params / 1000;
          unit = 'kilogram'
        }

        let formatter = new Intl.NumberFormat('id-ID', {
          style: 'unit',
          unit: unit,
          unitDisplay: 'short'
        })
        return formatter.format(value)
      }
    },
    mounted() {
      this.getStockistList()
      this.getProvinceList()
      // this.getCourierList()
      this.getMemberAddress()
      this.getProductList()
    }
  }).mount('#vue-app')
</script>