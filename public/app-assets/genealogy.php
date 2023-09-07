<?php
	include "components/header.php";
	include "components/sidebar.php";
?>

<!-- BEGIN: Content-->
<div class="app-content content">
	<div class="content-overlay"></div>
	<div class="content-wrapper">
		<div class="content-header row">
			<div class="content-header-left col-12 mb-2 mt-1">
				<div class="row breadcrumbs-top">
					<div class="col-12">
						<h5 class="content-header-title float-left pr-1 mb-0">Genealogy</h5>

						<div class="breadcrumb-wrapper col-12">
							<ol class="breadcrumb p-0 mb-0">
								<li class="breadcrumb-item"><a href="dashboard.html"><i class="bx bx-home-alt"></i></a></li>
								<li class="breadcrumb-item"><a href="#">Jaringan</a></li>
								<li class="breadcrumb-item active">Genealogy</li>
							</ol>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="content-body">
			<div class="row">
				<div class="col-md-12 col-12">

					<!-- vertical Wizard start-->
					<section id="vertical-wizard">
					
						<div class="card">
							<div class="card-header">
								<div class="card card-bordered border p-2">
									<div class="row">
										<div class="col-6">
											<form class="form-inline">
												<div class="form-group">
													<label for="staticEmail2">Pencarian Mitra</label>
												</div>
												<div class="form-group ml-sm-3 mr-sm-50">
													<input type="text" class="form-control" id="inputKodeMitra" placeholder="Masukkan kode mitra">
												</div>
												<button type="submit" class="btn btn-primary">Cari</button>
											</form>
										</div>

										<div class="col-6 d-flex flex-row justify-content-end">
											<button type="button" class="btn btn-outline-secondary mx-25">
												<i class="bx bx-left-arrow-alt"></i>
												<span class="pl-25">Jaringan Sebelumnya</span>
											</button>
											<button type="button" class="btn btn-outline-secondary mx-25">
												<i class="bx bx-user-pin "></i>
												<span class="pl-25">Jaringan Saya</span>
											</button>
										</div>
									</div>
								</div>
							</div>

							<div class="card-content">
								<div class="card-body">

									<div class="chart-genealogy">
										<table class="bg-light-primary">
											<tbody>
												<tr>
													<td colspan="4" class="bg-light-primary">
														<div>
															<div class="position-relative">
																<div class="node node-filled all-node diamond-node">
																	<button type="button" class="btn btn-dark btn-node-details" data-toggle="tooltip" data-placement="top" title="Lihat Detail Mitra" onclick="openGenealogyModal()">
																		<i class="bx bx-user"></i>
																	</button>

																	<div role="button" aria-haspopup="true" aria-expanded="false" onclick="openNetwork()">
																		<div role="button" class="node-image d-flex justify-center diamond-node">
																			<div class="hexa"><img src="./images/profile-pict.png" /></div>
																		</div>
																		<div class="py-25 node-info">
																			<div class="font-size-9 cl-primary text-uppercase font-weight-600 genealogy-name">BOS MJA</div>
																			<div class="font-size-9 cl-grey">admin</div>
																		</div>
																		<div class="d-flex justify-center node-growth">
																			<div class="font-size-9 cl-grey">31.444</div>
																			<div class="px-25 my-auto">
																				<div class="d-block v-img-growth" style="background-image: url('./images/themevo/image/genealogy/ic-node-diamond.svg'); background-position: center center;"></div>
																			</div>
																			<div class="font-size-9 cl-grey">49.351</div>
																		</div>
																	</div>
																</div>
																
															</div>
															<!----><!---->
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="4" class="bg-light-primary"><div class="node-shadow"></div></td>
												</tr>
												<tr>
													<td class="chart-line left"></td>
													<td class="chart-line right top"></td>
													<td class="chart-line left top"></td>
													<td class="chart-line right"></td>
												</tr>
												<tr>
													<td colspan="2" class="bg-light-primary">
														<table class="bg-light-primary">
															<tbody>
																<tr>
																	<td colspan="4" class="bg-light-primary">
																		<div>
																			<div class="position-relative">
																				<div class="node node-filled all-node diamond-node">
																					<button type="button" class="btn btn-dark btn-node-details" data-toggle="tooltip" data-placement="top" title="Lihat Detail Mitra" onclick="openGenealogyModal()">
																						<i class="bx bx-user"></i>
																					</button>

																					<div role="button" aria-haspopup="true" aria-expanded="false" onclick="openNetwork()">
																						<div class="node-image d-flex justify-center diamond-node">
																							<div class="hexa"><img src="./images/profile-pict.png" /></div>
																						</div>
																						<div class="py-25 node-info">
																							<div class="font-size-9 cl-primary text-uppercase font-weight-600 genealogy-name">MJA02</div>
																							<div class="font-size-9 cl-grey">GNI0000223</div>
																						</div>
																						<div class="d-flex justify-center node-growth">
																							<div class="font-size-9 cl-grey">21.765</div>
																							<div class="px-25 my-auto">
																								<div class="d-block v-img-growth" style="background-image: url('./images/themevo/image/genealogy/ic-node-diamond.svg'); background-position: center center;"></div>
																							</div>
																							<div class="font-size-9 cl-grey">9.679</div>
																						</div>
																					</div>
																				</div>
																				
																			</div>
																			<!----><!---->
																		</div>
																	</td>
																</tr>
																<tr>
																	<td colspan="4" class="bg-light-primary"><div class="node-shadow"></div></td>
																</tr>
																<tr>
																	<td class="chart-line left"></td>
																	<td class="chart-line right top"></td>
																	<td class="chart-line left top"></td>
																	<td class="chart-line right"></td>
																</tr>
																<tr>
																	<td colspan="2" class="bg-light-primary">
																		<table class="bg-light-primary">
																			<tbody>
																				<tr>
																					<td colspan="4" class="bg-light-primary">
																						<div>
																							<div class="position-relative">
																								<div class="node node-filled all-node gold-node">
																									<button type="button" class="btn btn-dark btn-node-details" data-toggle="tooltip" data-placement="top" title="Lihat Detail Mitra" onclick="openGenealogyModal()">
																										<i class="bx bx-user"></i>
																									</button>

																									<div role="button" aria-haspopup="true" aria-expanded="false" onclick="openNetwork()">
																										<div class="node-image d-flex justify-center gold-node">
																											<div class="hexa"><img src="./images/profile-pict.png" /></div>
																										</div>
																										<div class="py-25 node-info">
																											<div class="font-size-9 cl-primary text-uppercase font-weight-600 genealogy-name">MJA04</div>
																											<div class="font-size-9 cl-grey">GNI0000224</div>
																										</div>
																										<div class="d-flex justify-center node-growth">
																											<div class="font-size-9 cl-grey">2.930</div>
																											<div class="px-25 my-auto">
																												<div class="d-block v-img-growth" style="background-image: url('./images/themevo/image/genealogy/ic-node-gold.svg');  background-position: center center;"></div>
																											</div>
																											<div class="font-size-9 cl-grey">18.815</div>
																										</div>
																									</div>
																								</div>
																								
																							</div>
																							<!----><!---->
																						</div>
																					</td>
																				</tr>
																				<tr>
																					<td colspan="4" class="bg-light-primary"><div class="node-shadow"></div></td>
																				</tr>
																				<tr class="">
																					<td class="chart-line left"></td>
																					<td class="chart-line right top"></td>
																					<td class="chart-line left top"></td>
																					<td class="chart-line right"></td>
																				</tr>
																				<tr class="">
																					<td colspan="2" class="bg-light-primary">
																						<table class="bg-light-primary">
																							<tbody>
																								<tr>
																									<td colspan="4" class="bg-light-primary">
																										<div>
																											<div class="position-relative">
																												<div class="node node-filled all-node silver-node">
																													<button type="button" class="btn btn-dark btn-node-details" data-toggle="tooltip" data-placement="top" title="Lihat Detail Mitra" onclick="openGenealogyModal()">
																														<i class="bx bx-user"></i>
																													</button>

																													<div role="button" aria-haspopup="true" aria-expanded="false" onclick="openNetwork()">
																														<div class="node-image d-flex justify-center silver-node">
																															<div class="hexa"><img src="./images/profile-pict.png" /></div>
																														</div>
																														<div class="py-25 node-info">
																															<div class="font-size-9 cl-primary text-uppercase font-weight-600 genealogy-name">MJA08</div>
																															<div class="font-size-9 cl-grey">GNI0000225</div>
																														</div>
																														<div class="d-flex justify-center node-growth">
																															<div class="font-size-9 cl-grey">2.918</div>
																															<div class="px-25 my-auto">
																																<div class="d-block v-img-growth" style="background-image: url('./images/themevo/image/genealogy/ic-node-silver.svg');  background-position: center center;"></div>
																															</div>
																															<div class="font-size-9 cl-grey">12</div>
																														</div>
																													</div>
																												</div>
																												
																											</div>
																											<!----><!---->
																										</div>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td colspan="2" class="bg-light-primary">
																						<table class="bg-light-primary">
																							<tbody>
																								<tr>
																									<td colspan="4" class="bg-light-primary">
																										<div>
																											<div class="position-relative">
																												<div class="node node-filled all-node silver-node">
																													<button type="button" class="btn btn-dark btn-node-details" data-toggle="tooltip" data-placement="top" title="Lihat Detail Mitra" onclick="openGenealogyModal()">
																														<i class="bx bx-user"></i>
																													</button>

																													<div role="button" aria-haspopup="true" aria-expanded="false" onclick="openNetwork()">	
																														<div class="node-image d-flex justify-center silver-node">
																															<div class="hexa"><img src="./images/profile-pict.png" /></div>
																														</div>
																														<div class="py-25 node-info">
																															<div class="font-size-9 cl-primary text-uppercase font-weight-600 genealogy-name">MJA09</div>
																															<div class="font-size-9 cl-grey">MJA0002949</div>
																														</div>
																														<div class="d-flex justify-center node-growth">
																															<div class="font-size-9 cl-grey">24</div>
																															<div class="px-25 my-auto">
																																<div class="d-block v-img-growth" style="background-image: url('./images/themevo/image/genealogy/ic-node-silver.svg');  background-position: center center;"></div>
																															</div>
																															<div class="font-size-9 cl-grey">18.790</div>
																														</div>
																													</div>
																												</div>
																												
																											</div>
																											<!----><!---->
																										</div>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																	<td colspan="2" class="bg-light-primary">
																		<table class="bg-light-primary">
																			<tbody>
																				<tr>
																					<td colspan="4" class="bg-light-primary">
																						<div>
																							<div class="position-relative">
																								<div class="node node-filled all-node gold-node">
																									<button type="button" class="btn btn-dark btn-node-details" data-toggle="tooltip" data-placement="top" title="Lihat Detail Mitra" onclick="openGenealogyModal()">
																										<i class="bx bx-user"></i>
																									</button>

																									<div role="button" aria-haspopup="true" aria-expanded="false" onclick="openNetwork()">
																										<div class="node-image d-flex justify-center gold-node">
																											<div class="hexa"><img src="./images/profile-pict.png" /></div>
																										</div>
																										<div class="py-25 node-info">
																											<div class="font-size-9 cl-primary text-uppercase font-weight-600 genealogy-name">MJA05</div>
																											<div class="font-size-9 cl-grey">MJA0002714</div>
																										</div>
																										<div class="d-flex justify-center node-growth">
																											<div class="font-size-9 cl-grey">385</div>
																											<div class="px-25 my-auto">
																												<div class="d-block v-img-growth" style="background-image: url('./images/themevo/image/genealogy/ic-node-gold.svg');  background-position: center center;"></div>
																											</div>
																											<div class="font-size-9 cl-grey">9.294</div>
																										</div>
																									</div>
																								</div>
																								
																							</div>
																							<!----><!---->
																						</div>
																					</td>
																				</tr>
																				<tr>
																					<td colspan="4" class="bg-light-primary"><div class="node-shadow"></div></td>
																				</tr>
																				<tr class="">
																					<td class="chart-line left"></td>
																					<td class="chart-line right top"></td>
																					<td class="chart-line left top"></td>
																					<td class="chart-line right"></td>
																				</tr>
																				<tr class="">
																					<td colspan="2" class="bg-light-primary">
																						<table class="bg-light-primary">
																							<tbody>
																								<tr>
																									<td colspan="4" class="bg-light-primary">
																										<div>
																											<div class="position-relative">
																												<div class="node node-filled all-node cursor-pointer">
																													<div class="node-image d-flex justify-center">
																														<div class="hexa">
																															<img src="./images/themevo/image/profile_add_4.svg" class="icon">
																														</div>
																													</div>
																													
																													<div class="py-25 px-25 node-info">
																														<button type="button" class="btn btn-block btn-outline-success btn-sm my-25 p-25">Pasang Baru</button>
																														<button type="button" class="btn btn-block btn-outline-dark btn-sm mt-25 p-25">Kloning Akun</button>
																													</div>
																												</div>
																											</div>
																											<!----><!---->
																										</div>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td colspan="2" class="bg-light-primary">
																						<table class="bg-light-primary">
																							<tbody>
																								<tr>
																									<td colspan="4" class="bg-light-primary">
																										<div>
																											<div class="position-relative">
																												<div class="node node-filled all-node cursor-pointer">
																													<div class="node-image d-flex justify-center">
																														<div class="hexa">
																															<img src="./images/themevo/image/profile_add_4.svg" class="icon">
																														</div>
																													</div>
																													
																													<div class="py-25 px-25 node-info">
																														<button type="button" class="btn btn-block btn-outline-success btn-sm my-25 p-25">Pasang Baru</button>
																														<button type="button" class="btn btn-block btn-outline-dark btn-sm mt-25 p-25">Kloning Akun</button>
																													</div>
																												</div>
																												
																											</div>
																											<!----><!---->
																										</div>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td colspan="2" class="bg-light-primary">
														<table class="bg-light-primary">
															<tbody>
																<tr>
																	<td colspan="4" class="bg-light-primary">
																		<div>
																			<div class="position-relative">
																				<div class="node node-filled all-node diamond-node">
																					<button type="button" class="btn btn-dark btn-node-details" data-toggle="tooltip" data-placement="top" title="Lihat Detail Mitra" onclick="openGenealogyModal()">
																						<i class="bx bx-user"></i>
																					</button>

																					<div role="button" aria-haspopup="true" aria-expanded="false" onclick="openNetwork()">
																						<div class="node-image d-flex justify-center diamond-node">
																							<div class="hexa"><img src="./images/profile-pict.png" /></div>
																						</div>
																						<div class="py-25 node-info">
																							<div class="font-size-9 cl-primary text-uppercase font-weight-600 genealogy-name">MJA003</div>
																							<div class="font-size-9 cl-grey">GNI0000001</div>
																						</div>
																						<div class="d-flex justify-center node-growth">
																							<div class="font-size-9 cl-grey">16.266</div>
																							<div class="px-25 my-auto">
																								<div class="d-block v-img-growth" style="background-image: url('./images/themevo/image/genealogy/ic-node-diamond.svg'); background-position: center center;"></div>
																							</div>
																							<div class="font-size-9 cl-grey">33.083</div>
																						</div>
																					</div>
																				</div>
																				
																			</div>
																			<!----><!---->
																		</div>
																	</td>
																</tr>
																<tr>
																	<td colspan="4" class="bg-light-primary"><div class="node-shadow"></div></td>
																</tr>
																<tr>
																	<td class="chart-line left"></td>
																	<td class="chart-line right top"></td>
																	<td class="chart-line left top"></td>
																	<td class="chart-line right"></td>
																</tr>
																<tr>
																	<td colspan="2" class="bg-light-primary">
																		<table class="bg-light-primary">
																			<tbody>
																				<tr>
																					<td colspan="4" class="bg-light-primary">
																						<div>
																							<div class="position-relative">
																								<div class="node node-filled all-node gold-node">
																									<button type="button" class="btn btn-dark btn-node-details" data-toggle="tooltip" data-placement="top" title="Lihat Detail Mitra" onclick="openGenealogyModal()">
																										<i class="bx bx-user"></i>
																									</button>

																									<div role="button" aria-haspopup="true" aria-expanded="false" onclick="openNetwork()">
																										<div class="node-image d-flex justify-center gold-node">
																											<div class="hexa"><img src="./images/profile-pict.png" /></div>
																										</div>
																										<div class="py-25 node-info">
																											<div class="font-size-9 cl-primary text-uppercase font-weight-600 genealogy-name">MJA06</div>
																											<div class="font-size-9 cl-grey">GNI0000002</div>
																										</div>
																										<div class="d-flex justify-center node-growth">
																											<div class="font-size-9 cl-grey">608</div>
																											<div class="px-25 my-auto">
																												<div class="d-block v-img-growth" style="background-image: url('./images/themevo/image/genealogy/ic-node-gold.svg');  background-position: center center;"></div>
																											</div>
																											<div class="font-size-9 cl-grey">15.658</div>
																										</div>
																									</div>
																								</div>
																								
																							</div>
																							<!----><!---->
																						</div>
																					</td>
																				</tr>
																				<tr>
																					<td colspan="4" class="bg-light-primary"><div class="node-shadow"></div></td>
																				</tr>
																				<tr class="">
																					<td class="chart-line left"></td>
																					<td class="chart-line right top"></td>
																					<td class="chart-line left top"></td>
																					<td class="chart-line right"></td>
																				</tr>
																				<tr class="">
																					<td colspan="2" class="bg-light-primary">
																						<table class="bg-light-primary">
																							<tbody>
																								<tr>
																									<td colspan="4" class="bg-light-primary">
																										<div>
																											<div class="position-relative">
																												<div class="node node-filled all-node empty-node">
																													<div class="node-image d-flex justify-center empty-node">
																														<div class="hexa"><img src="./images/themevo/image/profile_kosong.svg" class="icon"></div>
																													</div>
																													<div class="py-2 node-info">
																														<div class="font-size-9 cl-grey">Kosong</div>
																													</div>
																												</div>
																												
																											</div>
																											<!----><!---->
																										</div>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td colspan="2" class="bg-light-primary">
																						<table class="bg-light-primary">
																							<tbody>
																								<tr>
																									<td colspan="4" class="bg-light-primary">
																										<div>
																											<div class="position-relative">
																												<div class="node node-filled all-node empty-node">
																													<div class="node-image d-flex justify-center empty-node">
																														<div class="hexa"><img src="./images/themevo/image/profile_kosong.svg" class="icon"></div>
																													</div>
																													<div class="py-2 node-info">
																														<div class="font-size-9 cl-grey">Kosong</div>
																													</div>
																												</div>
																											</div>
																											<!----><!---->
																										</div>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																	<td colspan="2" class="bg-light-primary">
																		<table class="bg-light-primary">
																			<tbody>
																				<tr>
																					<td colspan="4" class="bg-light-primary">
																						<div>
																							<div class="position-relative">
																								<div class="node node-filled all-node cursor-pointer">
																									<div class="node-image d-flex justify-center">
																										<div class="hexa">
																											<img src="./images/themevo/image/profile_add_4.svg" class="icon">
																										</div>
																									</div>
																									
																									<div class="py-25 px-25 node-info">
																										<button type="button" class="btn btn-block btn-outline-success btn-sm my-25 p-25">Pasang Baru</button>
																										<button type="button" class="btn btn-block btn-outline-dark btn-sm mt-25 p-25">Kloning Akun</button>
																									</div>
																								</div>
																							</div>
																							<!----><!---->
																						</div>
																					</td>
																				</tr>
																				<tr>
																					<td colspan="4" class="bg-light-primary"><div class="node-shadow"></div></td>
																				</tr>
																				<tr class="">
																					<td class="chart-line left"></td>
																					<td class="chart-line right top"></td>
																					<td class="chart-line left top"></td>
																					<td class="chart-line right"></td>
																				</tr>
																				<tr class="">
																					<td colspan="2" class="bg-light-primary">
																						<table class="bg-light-primary">
																							<tbody>
																								<tr>
																									<td colspan="4" class="bg-light-primary">
																										<div>
																											<div class="position-relative">
																												<div class="node node-filled all-node empty-node">
																													<div class="node-image d-flex justify-center empty-node">
																														<div class="hexa"><img src="./images/themevo/image/profile_kosong.svg" class="icon"></div>
																													</div>
																													<div class="py-2 node-info">
																														<div class="font-size-9 cl-grey">Kosong</div>
																													</div>
																												</div>
																											</div>
																											<!----><!---->
																										</div>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td colspan="2" class="bg-light-primary">
																						<table class="bg-light-primary">
																							<tbody>
																								<tr>
																									<td colspan="4" class="bg-light-primary">
																										<div>
																											<div class="position-relative">
																												<div class="node node-filled all-node empty-node">
																													<div class="node-image d-flex justify-center empty-node">
																														<div class="hexa"><img src="./images/themevo/image/profile_kosong.svg" class="icon"></div>
																													</div>
																													<div class="py-2 node-info">
																														<div class="font-size-9 cl-grey">Kosong</div>
																													</div>
																												</div>
																											</div>
																											<!----><!---->
																										</div>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</div>

								</div>
							</div>
						</div>
					</section>
					<!-- vertical Wizard end-->

				</div>
			</div>
		</div>
	</div>
</div>
<!-- END: Content-->

<!-- Modal -->
<div class="modal fade text-left" id="modal-genealogy" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel1">BOS MJA</h3>
				<button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
					<i class="bx bx-x"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="row mb-50 align-center">
					<div class="col-6">
						<p class="mb-0">Kode Mitra</p>
					</div>
					<div class="col-6 text-right d-flex flex-row justify-content-end align-center">
						<p id="kodemitra" class="mb-0 text-black font-weight-bold">GNI0000003</p>
						<button type="button" class="d-flex align-center btn btn-light-secondary btn-sm py-25 px-50 ml-1" onclick="copyText('GNI0000003')">
							<i class="bx bx-copy"></i>
						</button>
					</div>
				</div>

				<div class="row mb-50 align-center">
					<div class="col-6">
						<p class="mb-0">Kode Sponsor</p>
					</div>
					<div class="col-6 text-right d-flex flex-row justify-content-end align-center">
						<p id="kodesponsor" class="mb-0 text-black font-weight-bold">GNI0000001</p>
						<button type="button" class="d-flex align-center btn btn-light-secondary btn-sm py-25 px-50 ml-1" onclick="copyText('GNI0000001')">
							<i class="bx bx-copy"></i>
						</button>
					</div>
				</div>

				<div class="row mb-2 align-center">
					<div class="col-6">
						<p class="mb-0">Kode Upline</p>
					</div>
					<div class="col-6 text-right d-flex flex-row justify-content-end align-center">
						<p id="kodeupline" class="mb-0 text-black font-weight-bold">GNI0000001</p>
						<button type="button" class="d-flex align-center btn btn-light-secondary btn-sm py-25 px-50 ml-1" onclick="copyText('GNI0000001')">
							<i class="bx bx-copy"></i>
						</button>
					</div>
				</div>

				<div class="row mb-50 align-center">
					<div class="col-6">
						<p class="mb-0">PV Kiri</p>
					</div>
					<div class="col-6 text-right">
						<p class="mb-0 text-dark">32.942</p>
					</div>
				</div>

				<div class="row mb-50 align-center">
					<div class="col-6">
						<p class="mb-0">PV Kanan</p>
					</div>
					<div class="col-6 text-right">
						<p class="mb-0 text-dark">113</p>
					</div>
				</div>
				<div class="row mb-50 align-center">
					<div class="col-6">
						<p class="mb-0">Level Jaringan</p>
					</div>
					<div class="col-6 text-right">
						<p class="mb-0 text-dark">1</p>
					</div>
				</div>
				<div class="row mb-50 align-center">
					<div class="col-6">
						<p class="mb-0">Bergabung pada</p>
					</div>
					<div class="col-6 text-right">
						<p class="mb-0 text-dark">25 April 2020</p>
					</div>
				</div>
				<div class="row mb-50 align-center">
					<div class="col-6">
						<p class="mb-0">Jenis Kemitraan</p>
					</div>
					<div class="col-6 text-right">
						<p class="mb-0 text-primary font-weight-bold">DIAMOND</p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light-secondary" data-dismiss="modal">
					<i class="bx bx-x d-block d-sm-none"></i>
					<span class="d-none d-sm-block">Tutup</span>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- End Modal -->

<script>
	$(document).ready(function() {
		
	});

	function openNetwork() {
		alert('open network clicked!!')
	}

	function openGenealogyModal() {
		$('#modal-genealogy').modal('show');
	}

	function copyText(text) {
		const el = document.createElement('textarea')
		el.value = text
		document.body.appendChild(el)
		el.select()
		document.execCommand('copy')
		document.body.removeChild(el)
	}
</script>

<?php
include "components/footer.php";
?>