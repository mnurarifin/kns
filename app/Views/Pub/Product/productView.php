<div class="content-wrapper">
    <div class="breadcrumb-wrap bg-white">
        <div class="container">
            <div class="breadcrumb-title">
                <h2>Produk</h2>
            </div>
        </div>
    </div>
    <div class="shop-wrap ptb-100" id="vue-app">
        <div class="container">
            <div class="product-filter-wrap">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <div class="product-result">
                            <p>Showing {{ pagination.total_display }} of {{ pagination.total_data }} Results</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-4 col-md-6" v-for="data in product_list">
                    <div class="product-card style6">
                        <div class="product-img">
                            <img :src="data.product_image" alt="Image">
                            <a :href="detailProduct(data.product_code)" type="button" class="btn style2 add-cart">Lihat Detail</a>
                        </div>
                        <div class="product-info">
                            <p class="price">{{ formatCurrency(data.product_price) }}</p>
                            <h3><a :href="detailProduct(data.product_code)">{{ data.product_name }}</a></h3>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="page-nav list-style mt-10" v-if="product_list.length > 0">
                <li @click="changePage(pagination.prev)" v-show="pagination.prev != 0"><a><i class="ri-arrow-left-s-line"></i></a></li>
                <li v-for="data in pagination.detail" @click="changePage(data)"><a :class="[data == page ? 'active' : '']">{{ data }}</a></li>
                <li><a @click="changePage(pagination.next)" v-show="pagination.next != 0"><i class="ri-arrow-right-s-line"></i></a></li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        $('#product_menu').addClass('active')
    })

    const app = Vue.createApp({
        data() {
            return {
                product_list: [],
                pagination: [],
                page: 1
            }
        },
        methods: {
            detailProduct(id) {
                return `<?= BASEURL ?>/product/detail/` + id
            },
            changePage(page) {
                this.page = page
                app.getListProduct()
            },
            getListProduct() {
                $.ajax({
                    url: "<?= BASEURL ?>/product/get-list-product",
                    type: "GET",
                    async: false,
                    data: {
                        limit: 10,
                        page: this.page
                    },
                    success: (res) => {
                        this.product_list = res.data.results
                        this.pagination = res.data.pagination
                    },
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
            }
        },
        mounted() {
            this.getListProduct()
        },
    }).mount("#vue-app")
</script>