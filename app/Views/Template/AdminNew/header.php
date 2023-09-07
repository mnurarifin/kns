<!-- BEGIN: Header-->
<div class="header-navbar-shadow"></div>
<nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top">
	<div class="navbar-wrapper">
		<div class="navbar-container content">
			<div class="navbar-collapse" id="navbar-mobile">
				<div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
					<ul class="nav navbar-nav">
						<li class="nav-item mobile-menu d-xl-none mr-auto">
							<a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#">
								<i class="ficon bx bx-menu"></i>
							</a>
						</li>
					</ul>
					<h5 class="brand-text text-white mb-0">Administrator Area</h5>
				</div>
				<ul class="nav navbar-nav float-right">
					<li class="nav-item d-none d-lg-block">
						<a class="nav-link nav-link-expand">
							<i class="ficon bx bx-fullscreen"></i>
						</a>
					</li>
					<?php if (false) { ?>
					<li class="nav-item d-none d-lg-block">
						<a class="nav-link nav-link-expand">
							<i class="ficon bx bx-envelope"></i>
						</a>
					</li>
					<li class="nav-item d-none d-lg-block">
						<a class="nav-link nav-link-expand">
							<i class="ficon bx bx-message"></i>
						</a>
					</li>
					<?php } ?>
					<li class="dropdown dropdown-user nav-item">
						<a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
							<div class="user-nav d-sm-flex d-none">
								<span class="user-name"><?= session('admin')['admin_name'] ?></span>
								<span class="user-status text-muted"><?= session('admin')['admin_account_username'] ?></span>
							</div>
							<span>
								<img class="round" src="<?= session('admin')['admin_image'] ?>" alt="avatar" height="40" width="40">
							</span>
						</a>
						<div class="dropdown-menu dropdown-menu-right pb-0">
							<a class="dropdown-item" href="<?= BASEURL ?>/admin/profile">
								<i class="bx bxs-user mr-50"></i>Profil
							</a>
							<a class="dropdown-item" href="<?= BASEURL ?>/admin/profile/password">
								<i class="bx bxs-key mr-50"></i>Ubah Password
							</a>
							<div class="dropdown-divider mb-0"></div>
							<a class="dropdown-item" href="<?= BASEURL ?>/admin/logout">
								<i class="bx bx-power-off mr-50"></i>Logout
							</a>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</nav>
<!-- END: Header-->