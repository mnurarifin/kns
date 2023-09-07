<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand mt-0 d-inline-block" href="<?= base_url('admin/dashboard') ?>">
                    <div class="brand-logo" style="width: auto; height: auto;">
                        <img class="logo logo-expanded" src="<?php echo base_url(); ?>/logo.png" style="width :100px; height:auto;opacity: .7;display: block; left: 45px; top: -20px;" />
                        <img class="logo logo-collapsed" src="<?php echo base_url(); ?>/logo-small.png" style="width :40px; height:auto;opacity: .7;left: -6px;display: none;" />
                    </div>
                    <!-- <h2 class="brand-text mb-0">Frest</h2> -->
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="bx bx-x d-block d-xl-none font-medium-4 primary toggle-icon"></i><i class="toggle-icon bx bx-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary" data-ticon="bx-disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="">

            <?php
            $arrMenu = session("admin")["admin_menu"];
            $generateMenu = '';
            if (array_key_exists('0', $arrMenu)) {

                $class_dashboard = (strpos((strtolower((string)current_url(true))), 'dashboard')) ? " active" : "";
                $generateMenu = '<li class="nav-item' . $class_dashboard . '"><a href="' . base_url() . '/admin/dashboard"><i class="menu-livicon livicon-evo" data-options="name: desktop.svg; size: 120px;"></i><span class="menu-title" data-i18n="Email">Dashboard</span></a></li>';

                // urutkan root menu berdasarkan menu_order_by
                ksort($arrMenu[0]);

                // ekstrak root menu
                foreach ($arrMenu[0] as $rootmenu_sort => $rootmenu_value) {
                    $rootmenu_value = (object)$rootmenu_value;
                    $rootmenu_link = ($rootmenu_value->administrator_menu_link == '#') ? '#' : base_url() . '/' . $rootmenu_value->administrator_menu_link;
                    $class_mr = (array_key_exists($rootmenu_value->administrator_menu_id, $arrMenu)) ? ' mr-1' : '';

                    $generateMenu .= '<li class="nav-item" title="' . $rootmenu_value->administrator_menu_description . '">
                <a href="' . $rootmenu_link . '">
                  <i class="menu-livicon livicon-evo' . $class_mr . '" data-options="name: ' . $rootmenu_value->administrator_menu_icon . '; size: 24px;"></i>
                  <span class="menu-title" data-i18n="' . $rootmenu_value->administrator_menu_title . '">' . $rootmenu_value->administrator_menu_title . '</span>
                </a>';

                    // cari submenu 1
                    if (array_key_exists($rootmenu_value->administrator_menu_id, $arrMenu)) {
                        $generateMenu .= '<ul class="menu-content">';

                        // urutkan submenu 1 berdasarkan menu_order_by
                        ksort($arrMenu[$rootmenu_value->administrator_menu_id]);

                        // ekstrak submenu 1 yang par_id adalah menu_id dari root menu
                        foreach ($arrMenu[$rootmenu_value->administrator_menu_id] as $submenu_1_sort => $submenu_1_value) {
                            $submenu_1_value = (object)$submenu_1_value;
                            $submenu_1_link = ($submenu_1_value->administrator_menu_link == '#') ? '#' : '/admin/' . $submenu_1_value->administrator_menu_link;
                            $class_sub = "";
                            $current_url = strtolower((string)current_url(true));

                            if (strpos($current_url, $submenu_1_value->administrator_menu_link) !== false) {
                                $is_active = true;
                                $class_sub = "class='active'";
                            } else {
                                $class_sub = "";
                            }

                            $generateMenu .= '<li title="' . $submenu_1_value->administrator_menu_description . '"' . $class_sub . '>
                      <a href="' . $submenu_1_link . '">
                        <i class="bx bx-chevron-right"></i>
                        <span class="menu-item" data-i18n="' . $submenu_1_value->administrator_menu_title . '">' . $submenu_1_value->administrator_menu_title . '</span>
                      </a></li>';
                        }
                        $generateMenu .= '</ul>';
                    }
                    $generateMenu .= '</li>';
                }
            }
            echo $generateMenu;
            ?>

        </ul>
    </div>
</div>