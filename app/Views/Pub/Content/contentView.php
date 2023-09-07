<div class="content-wrapper">
    <div class="breadcrumb-wrap bg-white">
        <div class="container">
            <div class="breadcrumb-title">
                <h2>Artikel</h2>
            </div>
        </div>
    </div>
    <div class="about-wrap blog-details-wrap ptb-100" id="vue-app">
        <div class="container">
            <div class="row gx-5">
                <div class="col-xl-8 col-lg-12">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-6" v-for="data in content_list">
                            <div class="blog-card style3">
                                <div class="blog-img">
                                    <a :href="detailProduct(data.content_slug)">
                                        <img :src="data.content_image" alt="Image">
                                    </a>
                                </div>
                                <div class="blog-info">
                                    <ul class="blog-metainfo  list-style">
                                        <li><i class="ri-calendar-2-line"></i><a :href="detailProduct(data.content_slug)">{{ data.content_input_datetime }}</a></li>
                                    </ul>
                                    <h3><a :href="detailProduct(data.content_slug)">{{ data.content_title }}</a></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="page-nav list-style mt-20" v-if="content_list.length > 0">
                        <li @click="changePage(pagination.prev)" v-show="pagination.prev != 0"><a><i class="ri-arrow-left-s-line"></i></a></li>
                        <li v-for="data in pagination.detail" @click="changePage(data)"><a :class="[data == page ? 'active' : '']">{{ data }}</a></li>
                        <li><a @click="changePage(pagination.next)" v-show="pagination.next != 0"><i class="ri-arrow-right-s-line"></i></a></li>
                    </ul>
                </div>
                <div class="col-xl-4 col-lg-12">
                    <div class="sidebar">
                        <div class="sidebar-widget popular-post">
                            <h4>Postingan Terbaru</h4>
                            <div class="popular-post-widget">
                                <div class="pp-post-item" v-for="data in content_new_list">
                                    <a :href="detailProduct(data.content_slug)" class="pp-post-img">
                                        <img :src="data.content_image" alt="Image">
                                    </a>
                                    <div class="pp-post-info">
                                        <span>{{ data.content_input_datetime }}</span>
                                        <h6>
                                            <a :href="detailProduct(data.content_slug)">
                                                {{ data.content_title }}
                                            </a>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sidebar-widget categories">
                            <h4>Kategori</h4>
                            <ul class="category-box list-style">
                                <li>
                                    <a @click="filterCategory(0)">
                                        <i class="ri-checkbox-line"></i>
                                        Semua
                                    </a>
                                </li>
                                <li v-for="data in content_category_list">
                                    <a @click="filterCategory(data.content_category_id)">
                                        <i class="ri-checkbox-line"></i>
                                        {{ data.content_category_name }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        $('#content').addClass('active')
    })

    const app = Vue.createApp({
        data() {
            return {
                content_list: [],
                content_new_list: [],
                pagination: [],
                page: 1,
                filter_category_id: 0,
                content_category_list: []
            }
        },
        methods: {
            filterCategory(category_id) {
                this.filter_category_id = category_id
                app.getListContent()
            },
            detailProduct(id) {
                return `<?= BASEURL ?>/content/detail/` + id
            },
            changePage(page) {
                this.page = page
                app.getListContent()
            },
            getListContent() {
                let filter = '';
                if (this.filter_category_id != 0) {
                    filter = filter + "&filter[0][type]=string&filter[0][field]=content_content_category_id&filter[0][value]=" + this.filter_category_id;
                }

                $.ajax({
                    url: "<?= BASEURL ?>/content/get-list-content?limit=10&page=" + this.page + filter,
                    type: "GET",
                    async: false,
                    data: {},
                    success: (res) => {
                        this.content_list = res.data.results
                        this.pagination = res.data.pagination
                    },
                })
            },
            getNewContent() {
                $.ajax({
                    url: "<?= BASEURL ?>/content/get-new-content",
                    type: "GET",
                    async: false,
                    data: {},
                    success: (res) => {
                        this.content_new_list = res.data.results
                    },
                })
            },
            getListCategory() {
                $.ajax({
                    url: "<?= BASEURL ?>/content/get-list-category",
                    type: "GET",
                    async: false,
                    data: {},
                    success: (res) => {
                        this.content_category_list = res.data.results
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
            this.getListContent()
            this.getListCategory()
            this.getNewContent()
        },
    }).mount("#vue-app")
</script>