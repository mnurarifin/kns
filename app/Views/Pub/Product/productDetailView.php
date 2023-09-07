<div class="content-wrapper">

    <!-- Breadcrumb Start -->
    <div class="breadcrumb-wrap bg-white">
        <div class="container">
            <div class="breadcrumb-title">
                <h2>Detail Produk</h2>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Product Details section start -->
    <section class="product-details-wrap pt-100" id="vue-app">
        <div class="container">
            <div class="row gx-5 ">
                <div class="col-lg-6">
                    <div class="single-product-gallery">
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide single-product-item">
                                    <img :src="product_detail.product_image" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="single-product-details">
                        <div class="single-product-title">
                            <h2>{{ product_detail.product_name }}</h2>
                        </div>
                        <div class="product-more-option">
                            <div class="product-more-option-item">
                                <h5>Kode Produk :</h5>
                                <span>{{ product_detail.product_code }}</span>
                            </div>
                            <div class="product-more-option-item">
                                <h5>Harga :</h5>
                                <span>{{ formatCurrency(product_detail.product_price) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ptb-100">
                <div class="col-lg-12">
                    <ul class="nav nav-tabs product-tablist" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab">Deskripsi</button>
                        </li>
                    </ul>
                    <div class="tab-content product-tab-content">
                        <div class="tab-pane fade show active" id="tab_1" role="tabpanel">
                            <div class="product_desc" v-html="product_detail.product_description">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(() => {
        $('#product_menu').addClass('active')
    })

    const app = Vue.createApp({
        data() {
            return {
                product_detail: {},
                product_id: 0,
                product_code: ''
            }
        },
        methods: {
            getDetailProduct() {
                $.ajax({
                    url: "<?= BASEURL ?>/product/get-detail-product/" + this.product_code,
                    type: "GET",
                    success: (res) => {
                        app.product_detail = res.data.results
                    }
                })
            },
            formatCurrency($params) {
                let formatter = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                })
                return formatter.format($params)
            },
            getParameterByName(name = 'id', url = window.location.href) {
                name = name.replace(/[\[\]]/g, '\\$&');
                var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                    results = regex.exec(url);
                if (!results) return null;
                if (!results[2]) return '';
                this.product_id = decodeURIComponent(results[2].replace(/\+/g, ' '));
            },
            getProductCode() {
                var parts = window.location.href.split('/');
                var lastSegment = parts.pop() || parts.pop();

                if (lastSegment == 'detail') {
                    window.location.replace(`<?= BASEURL; ?>/product`)
                }

                this.product_code = lastSegment
            }
        },
        mounted() {
            this.getProductCode()
            this.getDetailProduct()
        },
    }).mount("#vue-app");
</script>