<div class="content-wrapper" id="vue-app">
    <div class="breadcrumb-wrap bg-white">
        <div class="container">
            <div class="breadcrumb-title">
                <h2>Artikel</h2>
            </div>
        </div>
    </div>
    <div class="blog-details-wrap ptb-100">
        <div class="container">
            <div class="row gx-5">
                <div class="col-xl-12 col-lg-12">
                    <article>
                        <div class="post-img">
                            <img :src="content_detail.content_image" alt="Image">
                        </div>
                        <ul class="post-metainfo  list-style">
                            <li><i class="ri-calendar-todo-line"></i>{{ content_detail.content_input_datetime }}</li>
                        </ul>
                        <h1>{{content_detail.content_title}}</h1>
                        <div class="post-para" v-html="content_detail.content_body">
                        </div>
                    </article>
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
                content_detail: {},
                content_id: 0,
                content_slug: ''
            }
        },
        methods: {
            getDetailContent() {
                $.ajax({
                    url: "<?= BASEURL ?>/content/get-detail-content/" + this.content_slug,
                    type: "GET",
                    success: (res) => {
                        app.content_detail = res.data.results
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
                this.content_id = decodeURIComponent(results[2].replace(/\+/g, ' '));
            },
            getSlug() {
                var parts = window.location.href.split('/');
                var lastSegment = parts.pop() || parts.pop();

                if (lastSegment == 'detail') {
                    window.location.replace(`<?= BASEURL; ?>/content`)
                }

                this.content_slug = lastSegment
            }
        },
        mounted() {
            this.getSlug()
            this.getDetailContent()
        },
    }).mount("#vue-app");
</script>