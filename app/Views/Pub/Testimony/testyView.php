<div class="content-wrapper">

    <!-- Breadcrumb Start -->
    <div class="breadcrumb-wrap bg-white">
        <div class="container">
            <div class="breadcrumb-title">
                <h2>Testimoni</h2>
                </ul>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Testimonial Section Start -->
    <section class="testimonial-wrap pt-100 pb-75 bg-albastor" id="vue-app">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-6" v-for="data in testimony_list">
                    <div class="testimonial-card style3">
                        <p class="client-quote">{{ data.testimony_content }}</p>
                        <div class="client-info-area">
                            <div class="client-info-wrap">
                                <div class="client-img">
                                    <img :src="data.member_image" alt="Image">
                                </div>
                                <div class="client-info">
                                    <h3>{{ data.member_name }}</h3>
                                    <span>{{ data.subdistrict_name }}, {{ data.city_name }}</span>
                                </div>
                            </div>
                            <div class="quote-icon">
                                <i class="flaticon-right-quote-sign"></i>
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
        $('#testimony').addClass('active')
    })

    const app = Vue.createApp({
        data() {
            return {
                testimony_list: [],
            }
        },
        methods: {
            getListGallery() {
                $.ajax({
                    url: "<?= BASEURL ?>/testimony/get-list-testimony",
                    type: "GET",
                    async: false,
                    success: (res) => {
                        this.testimony_list = res.data
                    },
                })
            },
        },
        mounted() {
            this.getListGallery()
        },
    }).mount("#vue-app")
</script>