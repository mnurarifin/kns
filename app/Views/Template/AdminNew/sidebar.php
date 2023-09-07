<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
  <div class="navbar-header">
    <ul class="nav navbar-nav flex-row">
      <li class="nav-item mx-auto">
        <a class="navbar-brand" href="<?php echo BASEURL; ?>/admin">
          <div class="brand-logo">
            <img class="logo-expanded" src="<?php echo BASEURL; ?>/app-assets/images/logo.png" />
            <img class="logo" src="<?php echo BASEURL; ?>/app-assets/images/logo-expand-hide.png" />
          </div>
        </a>
      </li>
      <li class="nav-item nav-toggle">
        <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
          <i class="bx bx-x d-block d-xl-none font-medium-4 primary toggle-icon"></i>
          <i class="toggle-icon bx bx-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary"
            data-ticon="bx-disc"></i>
        </a>
      </li>
    </ul>
  </div>
  <div class="shadow-bottom"></div>
  <div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="">
      <li class="nav-item" id="dashboard_menu">
        <a href="<?php BASEURL  ?>/admin/dashboard">
          <i class="bx bxs-home"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>
      <?php
      $menu_all = session("admin")["admin_menu"];
      $menu_child = [];
      $menu_show = "";
      foreach ($menu_all as $menu) {
        if ($menu->administrator_menu_par_id != "0") {
          if (array_key_exists($menu->administrator_menu_par_id, $menu_child)) {
            $menu_child[$menu->administrator_menu_par_id] .= "
            <li>
              <a href='/admin/".$menu->administrator_menu_link."'>
                <i class='bx bx-right-arrow-alt'></i>
                <span class='menu-item'>{$menu->administrator_menu_title}</span>
              </a>
            </li>
            ";
          } else {
            $menu_child[$menu->administrator_menu_par_id] = "
            <li>
              <a href='/admin/".$menu->administrator_menu_link."'>
                <i class='bx bx-right-arrow-alt'></i>
                <span class='menu-item'>{$menu->administrator_menu_title}</span>
              </a>
            </li>
            ";
          }
        }
      }
      foreach ($menu_all as $menu) {
        if ($menu->administrator_menu_par_id == "0") {
          $child = "";
          if (array_key_exists($menu->administrator_menu_id, $menu_child)) {
            $child = "
            <ul class='menu-content'>
            {$menu_child[$menu->administrator_menu_id]}
            </ul>
            ";
          }
          $link = $menu->administrator_menu_link == "#" ? "#" : "/".$menu->administrator_menu_link;
          $menu_show .= "
          <li class='nav-item' id='".slugify($menu->administrator_menu_title)."'>
            <a href='{$link}' class='m-0 px-1'>
              <i class='bx {$menu->administrator_menu_icon}'></i>
              <span class='menu-title'>{$menu->administrator_menu_title}</span>
            </a>
            {$child}
          </li>
          ";
        }
      }
      echo $menu_show;
      ?>
  </div>
</div>

<script>
	$(function() {
		$("a[href='" + window.location.pathname + "']").closest(".nav-item").addClass("open").closest("li").addClass("active")
		$("a[href='" + window.location.pathname + "']").closest("li").addClass("active")
	})
</script>
<!-- END: Main Menu-->