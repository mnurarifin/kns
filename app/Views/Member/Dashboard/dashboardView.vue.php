<style>
	.cstmHover:hover {
		background-color: rgb(178 145 37 / 15%);
		color: #6f2abb !important;
	}

	.font-size-14 {
		font-size: 14pt;
	}

	.font-weight-500 {
		font-weight: 500;
	}

	.font-size-10 {
		font-size: 10pt;
	}

	.font-size-11 {
		font-size: 11pt;
	}

	.btnCstm:hover {
		background-color: #e6faf0 !important;
		color: #028b43 !important;
		background-color: #f4ebd2 !important;
		color: #907830 !important;
	}

	.btnCstm {
		color: #028b43 !important;
		border: 1px solid #028b43 !important;
	}

	.mitra-card.mitra-diamond {
		background-color: #c4d3ff;
		background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1600 800'%3E%3Cg fill-opacity='.42'%3E%3Cpath fill='%23bbceff' d='M486 705.8c-109.3-21.8-223.4-32.2-335.3-19.4C99.5 692.1 49 703 0 719.8V800h843.8c-115.9-33.2-230.8-68.1-347.6-92.2-3.4-.7-6.8-1.3-10.2-2z'/%3E%3Cpath fill='%23b1c9ff' d='M1600 0H0v719.8C49 703 99.5 692 150.7 686.3c111.9-12.7 226-2.4 335.3 19.4 3.4.7 6.8 1.4 10.2 2 116.8 24 231.7 59 347.6 92.2H1600V0z'/%3E%3Cpath fill='%23a8c4fe' d='M478.4 581c3.2.8 6.4 1.7 9.5 2.5C684.1 636 876.6 717 1081.4 760.1c174.2 36.6 349.5 29.2 518.6-10.2V0H0v574.9c52.3-17.6 106.5-27.7 161.1-30.9 107.3-6.6 214.6 10.2 317.3 37z'/%3E%3Cpath fill='%239dbffe' d='M0 0v429.4c55.6-18.4 113.5-27.3 171.4-27.7 102.8-.8 203.2 22.7 299.3 54.5 3 1 5.9 2 8.9 3 183.6 62 365.7 146.1 562.4 192.1 186.7 43.7 376.3 34.4 557.9-12.6V0H0z'/%3E%3Cpath fill='%2393bafe' d='M181.8 259.4c98.2 6 191.9 35.2 281.3 72.1 2.8 1.1 5.5 2.3 8.3 3.4 171 71.6 342.7 158.5 531.3 207.7 198.8 51.8 403.4 40.8 597.3-14.8V0H0v283.2a483.5 483.5 0 01181.8-23.8z'/%3E%3Cpath fill='%2388a8ff' d='M1600 0H0v136.3c62.3-20.9 127.7-27.5 192.2-19.2 93.6 12.1 180.5 47.7 263.3 89.6 2.6 1.3 5.1 2.6 7.7 3.9 158.4 81.1 319.7 170.9 500.3 223.2 210.5 61 430.8 49 636.6-16.6V0z'/%3E%3Cpath fill='%237c96ff' d='M454.9 86.3C600.7 177 751.6 269.3 924.1 325c208.6 67.4 431.3 60.8 637.9-5.3 12.8-4.1 25.4-8.4 38.1-12.9V0h-1312c56 21.3 108.7 50.6 159.7 82 2.4 1.4 4.7 2.9 7.1 4.3z'/%3E%3Cpath fill='%236d84ff' d='M1600 0H498c118.1 85.8 243.5 164.5 386.8 216.2 191.8 69.2 400 74.7 595 21.1 40.8-11.2 81.1-25.2 120.3-41.7V0z'/%3E%3Cpath fill='%235b72ff' d='M1397.5 154.8c47.2-10.6 93.6-25.3 138.6-43.8 21.7-8.9 43-18.8 63.9-29.5V0H643.4c62.9 41.7 129.7 78.2 202.1 107.4 174.9 70.7 368.7 88.7 552 47.4z'/%3E%3Cpath fill='%234460ff' d='M1315.3 72.4c75.3-12.6 148.9-37.1 216.8-72.4h-723c157.7 71 335.6 101 506.2 72.4z'/%3E%3C/g%3E%3C/svg%3E");
		background-size: cover;
		background-position: 50%;
	}
</style>
<!-- BEGIN: Content-->
<div class="app-content content" id="app">
	<div class="content-overlay">
	</div>
	<div class="content-wrapper">
		<div class="content-header row">
			<div class="content-header-left col-12 mb-2 mt-1">
				<div class="row breadcrumbs-top">
					<div class="col-12">
						<h5 class="content-header-title float-left pr-1 mb-0"><?= $title ?></h5>

						<div class="breadcrumb-wrapper col-12">
							<ol class="breadcrumb p-0 mb-0">
								<li class="breadcrumb-item"><a href="<?php base_url()  ?>/office/dashboard/show"><i class="bx bx-home-alt"></i></a></li>
							</ol>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="content-body">
			<!-- Dashboard Analytics Start -->
			<section id="dashboard-analytics">
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<div class="card" style="height: 285px;">
							<div class="card-header d-flex justify-content-between pb-0">
								<label>Profile</label>
								<a href="<?= base_url() ?>/member/profile/show" class="btn btn-sm cstmHover cl-primary">Lihat Detail</a>
							</div>
							<div class="card-content">
								<div class="card-body pb-1">
									<div class="row">
										<div class="col-md-3 d-flex justify-content-center">
											<div class="avatar bg-rgba-warning m-0" style="cursor: unset !important; height: fit-content;">
												<div class="">
													<img class="round" src="" alt="avatar" height="100" width="100" id="profile_image">
												</div>
											</div>
										</div>
										<div class="col-md-9">
											<div class="font-size-14 font-weight-500 cl-primary text-uppercase">{{modal.form.member_name}}</div>
											<div class="font-size-12 font-weight-500 cl-primary text-uppercase">{{modal.form.member_account_username}}</div>
											<div class="row">
												<div class="pt-0 pt-md-1 col-sm-12 col-md-6 col-12 pb-10">
													<div class="font-size-10 cl-grey">Nomor HP</div>
													<div class="font-size-11 cl-grey font-weight-500">{{modal.form.member_mobilephone}}</div>
												</div>
												<div class="pt-0 pt-md-1 col-sm-12 col-md-6 col-12 pb-10">
													<div class="font-size-10 cl-grey">Tanggal Gabung</div>
													<div class="font-size-11 cl-grey font-weight-500">{{modal.form.member_join_datetime_formatted}}</div>
												</div>
												<div class="pt-0 pt-md-1 col-sm-12 col-md-6 col-12 pb-10">
													<div class="font-size-10 cl-grey">Sponsor</div>
													<div class="font-size-11 cl-grey font-weight-500">{{modal.form.sponsor}}</div>
												</div>
												<div class="pt-0 pt-md-1 col-sm-12 col-md-6 col-12 pb-10">
													<div class="font-size-10 cl-grey">Upline</div>
													<div class="font-size-11 cl-grey font-weight-500">{{modal.form.upline}}</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-3 col-sm-12">
						<div class="row">
							<div class="col-xl-12 col-12">
								<div class="card">
									<div class="card-header d-flex justify-content-between pb-xl-0 pt-xl-1">
										<div class="conversion-title">
											<p class="pt-0">Saldo Anda</p>
										</div>
									</div>

									<div class="card-body text-left text-success py-0">
										<p class="" style="font-size: 22px; margin-bottom: 0rem;">Rp {{modal.form.saldo}}</p>
									</div>

									<div class="card-footer text-center">
										<a href="<?= base_url() ?>/member/ewallet" class="btn btn-sm cstmHover btn-block cl-primary">Tarik Saldo</a>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xl-12 col-12">
								<div class="card" style="height: 125px;">
									<div class="card-header">
										<div class="row">
											<div class="col-md-12">
												<!-- <i class="bx bx-dollar-circle align-middle mr-25"></i> -->
												<span class="">Batas Penarikan Saldo</span>
											</div>
										</div>
									</div>
									<div class="card-content">
										<div class="card-body text-center pb-2">
											<div class="row">
												<div class="col-md-12 text-left text-info">
													<p class="" style="font-size: 22px; margin-bottom: 0rem;">Rp {{modal.form.limit}}</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-lg-6 col-xl-4 col-sm-12">
						<div class="card">
							<div class="card-header mt-2">
								<h5 class="card-title mb-2">Total Komisi</h5>
								<h1 class="display-6 fw-normal mb-0">Rp {{commision.total_commission}}</h1>
							</div>
							<div class="card-body">
								<div class="progress progress-stacked mb-3 mb-xl-5" style="height:8px;">
									<div class="progress-bar bg-success" role="progressbar" id="sponsor" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
									<div class="progress-bar bg-danger" role="progressbar" id="welcome" style="width: 0%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
									<div class="progress-bar bg-info" role="progressbar" id="unilevel" style="width: 0%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
									<div class="progress-bar bg-warning" role="progressbar" id="annually" style="width: 0%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<ul class="p-0 m-0">
									<li class="mb-2 d-flex justify-content-between">
										<div class="d-flex align-items-center lh-1 me-3">
											<span class="bullet bullet-xs bullet-success mr-50"></span> Sponsor
										</div>
										<div class="d-flex justify-content-between">
											<span>Rp {{commision.sponsor}}</span>
											<span class="pl-1" id="percent_sponsor"><b>0%</b></span>
										</div>
									</li>
									<li class="mb-2 d-flex justify-content-between">
										<div class="d-flex align-items-center">
											<span class="bullet bullet-xs bullet-danger mr-50"></span> Welcome
										</div>
										<div class="d-flex">
											<span>Rp {{commision.welcome}}</span>
											<span class="pl-1" id="percent_welcome"><b>0%</b></span>
										</div>
									</li>
									<li class="mb-2 d-flex justify-content-between">
										<div class="d-flex align-items-center lh-1 me-3">
											<span class="bullet bullet-xs bullet-info mr-50"></span> Unilevel
										</div>
										<div class="d-flex">
											<span>Rp {{commision.unilevel}}</span>
											<span class="pl-1" id="percent_unilevel"><b>0%</b></span>
										</div>
									</li>
									<li class="mb-2 d-flex justify-content-between">
										<div class="d-flex align-items-center lh-1 me-3">
											<span class="bullet bullet-xs bullet-warning mr-50"></span> Tahunan
										</div>
										<div class="d-flex">
											<span>Rp {{commision.annually}}</span>
											<span class="pl-1" id="percent_annually"><b>0%</b></span>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-sm-12 col-xl-8 col-lg-6 d-none">
						<div class="card">
							<div class="card-header d-flex justify-content-between">
								<label>Pertumbuhan Mitra Minggu Ini</label>
							</div>
							<div class="card-body">
								<div id="linearea-member-chart"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="row d-none">

					<div class="col-md-4 col-sm-12">
						<div class="card">
							<div class="card-header text-center">
								<label>Registrasi Mitra Terbaru</label>
							</div>
							<div v-if="modal.form.ten_downline.length > 0" class="py-0 card-body d-flex align-items-center justify-content-between">
								<div class="">
									<div v-for="item in modal.form.ten_downline" class="d-flex align-items-center pb-1">
										<div class="avatar bg-rgba-warning m-0 p-25 mr-75 mr-xl-2">
											<div class="avatar-content">
												<img class="round" :src="item.member_image" alt="avatar" height="40" width="40">
											</div>
										</div>
										<div class="total-amount">
											<h5 class="mb-0">{{item.member_account_username}}</h5>
											<small class="text-muted">{{item.member_name}}</small>
										</div>
									</div>
								</div>
							</div>
							<div class="card-footer text-center" v-if="modal.form.ten_downline.length == 0">
								<label for="">Belum ada Data</label>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- Dashboard Analytics end -->

		</div>
	</div>
</div>
<!-- END: Content-->
<script src="<?php echo base_url(); ?>/app-assets/vendors/js/charts/apexcharts.min.js"></script>

<script>
	var $primary = '#5A8DEE';
	var $success = '#39DA8A';
	var $danger = '#FF5B5C';
	var $warning = '#FDAC41';
	var $info = '#00CFDD';
	var $label_color = '#475f7b';
	var $primary_light = '#E2ECFF';
	var $danger_light = '#ffeed9';
	var $gray_light = '#828D99';
	var $sub_label_color = "#596778";
	var $radial_bg = "#e7edf3";
	var themeColors2 = [$info, $success, $warning, $danger, $primary];

	$(document).ready(function() {
		$('#dashboard_menu').addClass('active')
		app.profile();
		app.dashboard();
	})

	let app = Vue.createApp({
		data: function() {
			return {
				button: {
					formBtn: {
						disabled: false
					}
				},
				modal: {
					data: {
						title: "",
						btnTitle: "",
						btnAction: "",
					},
					form: {
						member_name: '',
						member_account_username: '',
						member_email: '',
						member_mobilephone: '',
						network_code: '',
						member_join_datetime_formatted: '',
						sponsor: '',
						upline: '',
						limit: '0',
						saldo: '0',
						stock_serial: '0',
						ten_downline: []
					},
				},
				alert: {
					success: {
						status: false,
						content: '',
					},
					danger: {
						status: false,
						content: '',
					}
				},
				commision: {
					sponsor: 0,
					welcome: 0,
					unilevel: 0,
					annually: 0,
					total_commission: 0,
				}
			}
		},
		methods: {
			profile() {
				$.ajax({
					url: "<?= BASEURL ?>/member/profile/get",
					type: "GET",
					data: {},
					success: (res) => {
						let data = res.data.results
						$('#profile_image').attr('src', data.member_image)
						app.modal.form.member_account_username = data.member_account_username
						app.modal.form.member_name = data.member_name
						app.modal.form.network_code = data.network_code
						app.modal.form.sponsor = data.sponsor
						app.modal.form.upline = data.upline
						app.modal.form.member_mobilephone = data.member_mobilephone
						app.modal.form.member_join_datetime_formatted = data.member_join_datetime_formatted
					},
					error: (err) => {
						res = err.responseJSON
					},
				})
			},
			dashboard() {
				$.ajax({
					url: "<?= BASEURL ?>/member/dashboard/get",
					type: "GET",
					data: {},
					success: (res) => {
						app.modal.form.ten_downline = res.data.results.ten_downline
						app.modal.form.limit = res.data.results.limit
						app.modal.form.saldo = res.data.results.saldo
						app.modal.form.stock_serial = res.data.results.stock_serial

						renderBonusWeekly(res.data.results.growing_per_week)
					},
					error: (err) => {
						res = err.responseJSON
					},
				})

				$.ajax({
					url: "<?= BASEURL ?>/member/dashboard/commission",
					type: "GET",
					data: {},
					success: (res) => {
						app.commision = res.data.results

						$('#sponsor').css("width", (res.data.results.sponsor / res.data.results.total_commission) * 100 + '%')
						$('#percent_sponsor').html(`<b>${Math.round((isNaN(parseInt(res.data.results.sponsor) / parseInt(res.data.results.total_commission)) ? 0 : parseInt(res.data.results.sponsor) / parseInt(res.data.results.total_commission)) * 100)}%</b>`)
						$('#unilevel').css("width", (res.data.results.unilevel / res.data.results.total_commission) * 100 + '%')
						$('#percent_unilevel').html(`<b>${Math.round((isNaN(parseInt(res.data.results.unilevel) / parseInt(res.data.results.total_commission)) ? 0 : parseInt(res.data.results.unilevel) / parseInt(res.data.results.total_commission)) * 100)}%</b>`)
						$('#annually').css("width", (res.data.results.annually / res.data.results.total_commission) * 100 + '%')
						$('#percent_unilevel').html(`<b>${Math.round((isNaN(parseInt(res.data.results.annually) / parseInt(res.data.results.total_commission)) ? 0 : parseInt(res.data.results.annually) / parseInt(res.data.results.total_commission)) * 100)}%</b>`)
						$('#welcome').css("width", (res.data.results.welcome / res.data.results.total_commission) * 100 + '%')
						$('#percent_unilevel').html(`<b>${Math.round((isNaN(parseInt(res.data.results.welcome) / parseInt(res.data.results.total_commission)) ? 0 : parseInt(res.data.results.welcome) / parseInt(res.data.results.total_commission)) * 100)}%</b>`)
					},
					error: (err) => {
						res = err.responseJSON
					},
				})
			},
		}
	}).mount('#app');

	function renderBonusWeekly(data) {
		var lineAreaOptions2 = {
			chart: {
				height: 320,
				width: '100%',
				type: 'line',
			},
			colors: themeColors2,
			dataLabels: {
				enabled: false
			},
			stroke: {
				width: 3,
				curve: 'smooth'
			},
			series: [{
				name: 'Registrasi',
				data: data.map((el) => el['member_join_count'])
			}, {
				name: 'Aktivasi',
				data: data.map((el) => el['member_activation_count'])
			}],
			legend: {
				offsetY: -10
			},
			yaxis: {
				labels: {
					formatter: function(value) {
						return value;
					}
				},
			},
			xaxis: {
				type: 'date',
				categories: data.map((el) =>
					formatTanggal(el['member_date'])
				),
			},
			fill: {
				type: 'gradient',
				gradient: {
					shade: 'dark',
					gradientToColors: ['#FDD835'],
					shadeIntensity: 1,
					type: 'horizontal',
					opacityFrom: 1,
					opacityTo: 1,
					stops: [0, 100, 100, 100]
				},
			},
			markers: {
				size: 4,
				colors: ["#FFA41B"],
				strokeColors: "#fff",
				strokeWidth: 2,
				hover: {
					size: 7,
				}
			},
			tooltip: {
				x: {
					format: 'dd/MM/yy'
				},
				y: {
					formatter: function(value, series) {
						return value;
					}
				}
			}
		}
		var lineAreaChart2 = new ApexCharts(
			document.querySelector("#linearea-member-chart"),
			lineAreaOptions2
		);

		lineAreaChart2.render();
	}

	function formatTanggal(data) {
		const d = new Date(data)
		const ye = new Intl.DateTimeFormat(['ban', 'id'], {
			year: 'numeric'
		}).format(d)
		const mo = new Intl.DateTimeFormat(['ban', 'id'], {
			month: '2-digit'
		}).format(d)
		const da = new Intl.DateTimeFormat(['ban', 'id'], {
			day: '2-digit'
		}).format(d)

		return `${da}-${mo}-${ye}`;
	}
</script>