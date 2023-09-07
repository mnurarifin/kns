<div class="header-navbar-shadow"></div>
<nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top ">
  <div class="navbar-wrapper">
    <div class="navbar-container content">
      <div class="navbar-collapse" id="navbar-mobile">
        <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
          <ul class="nav navbar-nav">
            <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon bx bx-menu"></i></a></li>
          </ul>
          <nav aria-label="breadcrumb" style="padding-top: 7px;">
            <ol class="breadcrumb p-0 m-0">
              <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard"><i class="bx bx-home"></i></a></li>
              <?php
              if (isset($arrBreadcrumbs) && !empty($arrBreadcrumbs)) {
                $c = 1;
                foreach ($arrBreadcrumbs as $breadcrumbs => $linkBreadcrumbs) {
                  if (count($arrBreadcrumbs) == $c) {
                    // echo '<li class="breadcrumb-item active" aria-current="page"><a href="#">' . $breadcrumbs . '</a></li>';
                    echo '<li class="breadcrumb-item active" aria-current="page"><span>' . $breadcrumbs . '</span></li>';
                  } else {
                    $linkBreadcrumbs = ($linkBreadcrumbs == '#') ? '#' :  base_url() . '/' . $linkBreadcrumbs;
                    echo '<li class="breadcrumb-item"><a href="' . $linkBreadcrumbs . '">' . $breadcrumbs . '</a></li>';
                  }
                  $c++;
                }
              }
              ?>
            </ol>
          </nav>
          <!-- <ul class="nav navbar-nav bookmark-icons">
            <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-email.html" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pesan"><i class="ficon bx bx-envelope"></i></a></li>
            <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-calendar.html" data-toggle="tooltip" data-placement="top" title="" data-original-title="Calendar"><i class="ficon bx bx-calendar-alt"></i></a></li>
          </ul> -->
        </div>
        <ul class="nav navbar-nav float-right">
          <!-- <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon bx bx-envelope bx-tada bx-flip-horizontal"></i><span id="message-badge" class="badge badge-pill badge-danger badge-up">0</span></a>
            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
              <li class="dropdown-menu-header">
                <div onclick="readNotification()" class="dropdown-header px-1 py-75 d-flex justify-content-between">
                  <span class="notification-title" id="message-badge-title">0 Pesan Baru</span>
                </div>
              </li>
              <li id="message-container" class="scrollable-container media-list">
                <a class="d-flex justify-content-between cursor-pointer" href="javascript:void(0);">
                  <div class="media d-flex align-items-center py-2">
                    <div class="media-left pr-0"><img class="mr-1" src="../../../app-assets/images/icon/sketch-mac-icon.png" alt="avatar" height="39" width="39">
                    </div>
                    <div class="media-body">
                      <h6 class="media-heading"><span class="text-bold-500">Pesan tidak ditemukan</span></h6><small class="notification-text">untuk saat ini pesan kosong</small>
                    </div>

                  </div>
                </a>
              </li>
              <li class="dropdown-menu-footer"><a class="dropdown-item p-50 text-primary justify-content-center" href="<?php echo base_url('/pesan') ?>">Lihat semua pesan</a></li>
            </ul>
          </li> -->
          <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i class="ficon bx bx-fullscreen"></i></a></li>
          <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
              <div class="user-nav d-sm-flex d-none"><span class="user-name"><?= session('admin')['admin_name']; ?></span><span class="user-status text-muted"><?= session('admin')['admin_name']; ?></span></div><span><img class="round" src="<?php echo !empty(session('admin')['admin_image']) ?  session('admin')['admin_image'] :  "/no-image-profile.png" ?>" alt="avatar" height="40" width="40"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right pb-0">
              <a class="dropdown-item" href="<?= base_url(); ?>/admin/profile/edit"><i class="bx bx-user mr-50"></i> Ubah Profil
              </a>
              <a class="dropdown-item" href="<?= base_url(); ?>/admin/profile/password"><i class="bx bx-lock-open mr-50"></i>
                Ubah Password </a>
              <div class="dropdown-divider mb-0"></div><a class="dropdown-item" href="<?= base_url(); ?>/admin/logout"><i class="bx bx-power-off mr-50"></i> Logout</a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<script>
  $(document).ready(function() {
    getNotification();
    getCountNotification();
  });

  function getCountNotification() {
    $.ajax({
      url: window.location.origin + '/admin/service/message/getDataMessageCount/',
      method: 'GET',
      success: function(response) {
        if (response.status == 200) {
          let {
            notification
          } = response.data.results;
          $('#message-badge').text(notification);
          $('#message-badge-title').text(`${notification} Pesan Baru`)
        }
      }
    });
  }

  function getNotification() {
    $.ajax({
      url: window.location.origin + '/admin/service/message/getDataMessage/notification?page=1',
      method: 'GET',
      success: function(response) {
        if (response.status == 200) {
          let html = '';
          let data = response.data.results;

          data.forEach(res => {
            let {
              message_id,
              message_sender_name,
              message_sender_network_code,
              message_content,
              message_status,
              message_datetime
            } = res;

            html += `
                <a href="<?php echo base_url('/message/show') ?>?id=${message_id}" class="d-flex justify-content-between ${message_status == 1 ? 'read-notification' : ''} cursor-pointer message-content">
                  <div class="media d-flex align-items-center">
                    <div class="media-body">
                      <h6 class="media-heading mb-25">
                        <span class="d-block pb-25 text-bold-500">${message_sender_network_code ? message_sender_network_code : message_sender_name}</span>
                        <span class="text-muted">${message_content}</span>
                      </h6>
                      <div class="d-flex align-items-center justify-content-between mt-25">
                        <small class="notification-text d-flex align-items-center mb-0"><i
                            class="bx bx-user-circle bx-xs text-muted mr-25" style="font-size: 0.8rem!important;"></i>
                          <b class="text-primary">${message_sender_name}</b></small>
                        <small class="notification-text d-flex align-items-center mb-0"><i
                            class="bx bx-time bx-xs text-muted mr-25" style="font-size: 0.8rem!important;"></i> ${message_datetime}</small>
                      </div>
                    </div>
                  </div>
                </a>
            `;
          });

          if (data.length > 0) {
            $('#message-container').html('');
            $('#message-container').append(html)
          }

        }
      }
    });
  }
</script>