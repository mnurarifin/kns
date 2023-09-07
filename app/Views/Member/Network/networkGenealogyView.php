<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-overlay">
  </div>
  <div class="content-wrapper">
    <div class="alert alert-danger" role="alert" id="alert" style="position: absolute; top: 84px; right: 32px; display: none;"></div>
    <div class="alert alert-success" role="alert" id="alert-success" style="position: absolute; top: 84px; right: 32px; display: none;"></div>

    <div class="content-header row">
      <div class="content-header-left col-12 mb-2 mt-1">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h5 class="content-header-title float-left pr-1 mb-0"><?= $title ?></h5>

            <div class="breadcrumb-wrapper col-12">
              <ol class="breadcrumb p-0 mb-0">
                <li class="breadcrumb-item"><a href="<?= BASEURL ?>/member/dashboard"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="#"><?= $breadcrumbs[0] ?></a></li>
                <li class="breadcrumb-item active"><?= $breadcrumbs[1] ?></li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="content-body">
      <div class="row">
        <div class="col-md-12 col-12">
          <div class="card mb-1 p-1">
            <div class="row">
              <div class="col col-9 col-md-3">
                <input class="form-control" id="input_search">
                <small class="text-danger alert-input" id="alert_input_network_code" style="display: none;"></small>
              </div>
              <div class="col col-3">
                <button class="btn btn-primary px-1" id="btn_search">Cari</button>
                <button class="btn btn-light-secondary" id="btn_reset" style="display: none;">Reset</button>
              </div>
            </div>
          </div>
          <div class="card mb-1 p-1 d-none">
            <div id="tree"></div>
          </div>
          <div>
            <div class="card">
              <div class="card-body" id="unilevel-tree">
                <div class="row">
                  <style>
                    .item-left {
                      height: 200px;
                      margin-top: 20px;
                      margin-bottom: 20px;
                      display: flex;
                      justify-content: center;
                      align-items: center;
                    }

                    .item-left:not(:first-child) {
                      border-top: 1px solid #6f2abb;
                    }

                    .item-right {
                      height: 200px;
                      padding-top: 20px;
                      display: flex;
                      align-items: start;
                      overflow: auto;
                    }

                    .item-right:not(:first-child) {
                      border-top: 1px solid #6f2abb;
                    }

                    .item-right:before,
                    .item-right:after {
                      content: '';
                      margin: auto;
                    }

                    .item-level {
                      padding-left: 20px;
                      padding-right: 20px;
                      width: 200px;
                      height: 120px;
                      display: flex;
                      align-items: center;
                      flex-direction: column;
                      position: relative;
                    }

                    .item-img {
                      cursor: pointer;
                      width: 60px;
                      height: 60px;
                    }

                    .level-text {
                      width: 60px;
                      height: 60px;
                      border-radius: 30px;
                      background-color: #6f2abb;
                      display: flex;
                      justify-content: center;
                      align-items: center;
                      color: white;
                      font-weight: bold;
                      font-size: 1rem;
                    }

                    .item-detail {
                      position: absolute;
                      top: 40px;
                      right: 30px;
                      background-color: #6f2abb;
                      color: white;
                      width: 24px;
                      height: 24px;
                      border-radius: 12px;
                      display: flex;
                      justify-content: center;
                      align-items: center;
                      cursor: pointer;
                    }

                    .popover-title {
                      font-size: 1.2rem;
                      font-weight: bold;
                      color: #6f2abb;
                    }

                    .circle {
                      border: 2px dashed #6f2abb;
                      width: 60px;
                      height: 60px;
                      border-radius: 50%;
                      -moz-border-radius: 50%;
                      -webkit-border-radius: 50%;
                      cursor: pointer;
                    }
                  </style>
                  <div class="col col-2 container-left px-0">
                  </div>
                  <div class="col col-10 container-right px-0">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(function() {
    countdown = (countDownDate) => {
      var x = setInterval(function() {
        var now = new Date().getTime();
        var distance = (15 * 60 * 1000) - (now - countDownDate);
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        $(`#countdown`).html(`${minutes} Menit ${Math.abs(seconds)} Detik`);
      }, 1000);
    }

    let placement = false
    let params = new URLSearchParams(window.location.search)
    if (params.get("member_registration_id")) {
      placement = params.get("member_registration_id")
      $("body").append(`
      <div class="alert alert-danger m-0 p-1" id="alert-countdown" style="position: absolute; top: 80px; right: 2.25rem;">Sisa Waktu : <span  id="countdown"></span></div>
      `)
      countdown(params.get("countdown"))
    }
    if (params.get("member_name")) {
      member_name = params.get("member_name")
    }

    let max_level = 0

    let network_code = "<?= session("member")["member_account_username"] ?>"

    getGenealogy = (network_code, type) => {
      $.ajax({
        url: "<?= BASEURL ?>/member/network/get-genealogy",
        type: "GET",
        data: {
          network_code: network_code,
          type: type,
        },
        success: (res) => {
          let data = res.data.results
          let items = ``

          if (data.genealogy.length > 0) {
            max_level = data.level + 1
            for (let index = data.level; index < max_level; index++) {
              let others = $(`.item-level[data-code!="${network_code}"]`).filter(function() {
                return $(this).data("level") >= data.level - 1;
              })
              others.hide({
                duration: "slow",
                complete: () => {
                  others.remove()
                }
              })
              let others_left = $(`.item-left`).filter(function() {
                return $(this).data("level") > data.level - 1;
              })
              others_left.hide({
                duration: "slow",
                complete: () => {
                  others_left.remove()
                }
              })
            }

            $(`.item-right[data-level="${data.level}"]`).remove()
            let popover_options = []

            let last_leg = 1
            $.each(data.genealogy, (i, val) => {
              items += `
              <div class="item-level" data-code="${val.network_code}" data-level="${data.level}" style="display: none;">
                <div class="text-center" style="color: #6f2abb;">${val.network_upline_leg_position_text}</div>
                <div class="text-center" style="color: #6f2abb;">${val.member_name_short}</div>
                <div class="netcode" style="font-weight: bold;font-size: 0.75rem;">${val.network_code}</div>
                <img src="${val.member_image}" data-code="${val.network_code}" data-level="${data.level}" class="item-img my-50">

                <div class="d-none d-lg-block w-100">
                  <div class="d-info d-flex w-100 align-items-center justify-content-between" style="font-size: 0.75rem;">
                    <span style="width: 40%;text-align: left;">S : ${val.network_total_sponsoring}</span>
                    <span style="width: 10%;text-align: center;"></span>
                    <span style="width: 40%;text-align: right;">D : ${val.network_total_downline}</span>
                  </div>
                  <div class="d-info d-flex w-100 align-items-center justify-content-between" style="font-size: 0.75rem;">
                    <span style="width: 40%;text-align: left;">R : ${val.reward_point}</span>
                    <span style="width: 10%;text-align: center;"><i class="bx bxs-down-arrow"></i></span>
                    <span style="width: 40%;text-align: right;">T : ${val.reward_trip_point}</span>
                  </div>
                </div>

                <div class="d-block w-100 d-lg-none">
                  <div class="d-info d-flex w-100 align-items-center justify-content-between"><b>S</b> <span class="separator">:</span> <span>${val.network_total_sponsoring}</span></div>
                  <div class="d-info d-flex w-100 align-items-center justify-content-between"><b>D</b> <span class="separator">:</span> <span>${val.network_total_downline}</span></div>
                  <div class="d-info d-flex w-100 align-items-center justify-content-between"><b>R</b> <span class="separator">:</span> <span>${val.reward_point}</span></div>
                  <div class="d-info d-flex w-100 align-items-center justify-content-between"><b>T</b> <span class="separator">:</span> <span>${val.reward_trip_point}</span></div>
                  <div class="d-flex w-100 align-items-center justify-content-center mt-50"><i class="bx bxs-down-arrow"></i></div>
                </div>
              </div>
              `
              last_leg += 1

              popover_options.push({
                code: val.network_code,
                level: data.level,
                placement: "left",
                trigger: "hover",
                container: "body",
                html: true,
                content: () => {
                  return `
                  <div class="row">
                    <div class="col col-12 mb-2 popover-title">${val.network_code}</div>
                    <div class="col col-3">Nama</div><div class="col col-9 text-right">${val.member_name}</div>
                    <div class="col col-3">Generasi</div><div class="col col-9 text-right">${data.level}</div>
                  </div>
                  `
                }
              })
            })

            if (data.level > 0 && placement) {
              items += `
              <div class="item-level" data-code="0" data-level="${data.level}" style="display: none;">
                <div style="color: #6f2abb;">LEG ${last_leg}</div>
                <div>&nbsp;</div>
                <div data-code="0" data-level="${data.level}" class="circle d-flex justify-content-center align-items-center" onclick="addMember(${data.level},${last_leg},'${network_code}')">
                <i class="bx bxs-user-plus" style="font-size: 2rem; color: #6f2abb;"></i>
                </div>
                <div>&nbsp;</div>
              </div>
              `
            }

            $(".container-right").append(`
            <div class="item-right" data-level="${data.level}">
              ${items}
            </div>
            `)

            $(".container-left").append(`
            <div class="item-left my-0" data-level="${data.level}" style="${parseInt(data.level) == 0 ? `visibility: hidden;` : ``}"><span class="level-text">GEN ${data.level}</span></div>
            `)

            let others = $(`.item-level[data-level="${data.level}"]`).show("slow")
            $.each($(".item-right"), (i, el) => {
              if ($(el).data("level") > data.level) {
                $(el).remove()
              }
            })

            // $.each(popover_options, (i, options) => {
            //   $(`.item-img[data-code="${options.code}"][data-level="${options.level}"]`).popover(options)
            // })

            // $($("#unilevel-tree img")[0]).trigger("click")
            if (!$("#unilevel-tree img")[1]) {
              $($("#unilevel-tree img")[0]).trigger("click")
            }
          } else {
            if (placement) {
              max_level = data.level + 1
              for (let index = data.level; index < max_level; index++) {
                let others = $(`.item-level[data-code!="${network_code}"]`).filter(function() {
                  return $(this).data("level") >= data.level - 1;
                })
                others.hide({
                  duration: "slow",
                  complete: () => {
                    others.remove()
                  }
                })
                let others_left = $(`.item-left`).filter(function() {
                  return $(this).data("level") > data.level - 1;
                })
                others_left.hide({
                  duration: "slow",
                  complete: () => {
                    others_left.remove()
                  }
                })
              }

              $(`.item-right[data-level="${data.level}"]`).remove()

              let last_leg = 1
              last_leg += 1
              items += `
              <div class="item-level" data-code="0" data-level="${data.level}" style="display: none;">
                <div style="color: #6f2abb;">LEG 1</div>
                <div>&nbsp;</div>
                <div data-code="0" data-level="${data.level}" class="circle d-flex justify-content-center align-items-center" onclick="addMember(${data.level},1,'${network_code}')">
                <i class="bx bxs-user-plus" style="font-size: 2rem;"></i>
                </div>
                <div>&nbsp;</div>
              </div>
              `

              $(".container-right").append(`
              <div class="item-right" data-level="${data.level}">
                ${items}
              </div>
              `)

              $(".container-left").append(`
              <div class="item-left my-0" data-level="${data.level}" style="${parseInt(data.level) == 0 ? `visibility: hidden;` : ``}"><span class="level-text">GEN ${data.level}</span></div>
              `)

              let others = $(`.item-level[data-level="${data.level}"]`).show("slow")
              $.each($(".item-right"), (i, el) => {
                if ($(el).data("level") > data.level) {
                  $(el).remove()
                }
              })
            }

            // $($("#unilevel-tree img")[0]).trigger("click")
          }
        },
        error: (err) => {
          res = err.responseJSON
          $("#alert").html(res.message)
          $("#alert").show()
          setTimeout(function() {
            $("#alert").hide()
          }, 3000);
          if (res.error == "validation") {
            $.each(res.data, (i, val) => {
              $(`#alert_input_${i}`).html(val).show()
            })
          }
        },
      })
    }

    $("#btn_search").on("click", () => {
      network_code = $("#input_search").val()
      $(`.item-left`).remove()
      $(`.item-right`).remove()
      $("#btn_reset").show()
      $(`.alert-input`).html("").hide()
      getGenealogy(network_code, "upline")
    })

    $("#btn_reset").on("click", () => {
      $("#input_search").val("")
      $("#btn_reset").hide()
      $(`.alert-input`).html("").hide()
      getGenealogy("<?= session("member")["member_account_username"] ?>", "upline")
    })

    $("body").on("click", ".item-img", (ev) => {
      let el = $(ev.target)
      let network_code = el.data("code")
      getGenealogy(network_code, "downline")
    })

    $("#input_search").keyup(function(event) {
      if (event.keyCode === 13) {
        $("#btn_search").click()
      }
    })

    addMember = (level, leg, network_upline_network_code) => {
      Swal.fire({
        title: 'Penambahan downline baru.',
        html: `<span style="font-weight: bold; color: #6f2abb;">${member_name}</span> akan ditambahkan<br />
        di <span style="font-weight: bold; color: #6f2abb;">Level ${level}</span> dan <span style="font-weight: bold; color: #6f2abb;">Leg ${leg}</span> dalam jaringan anda.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6f2abb',
        cancelButtonColor: '#FF5B5C',
        confirmButtonText: 'Konfirmasi',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "<?= BASEURL ?>/member/network/activation",
            type: "POST",
            data: {
              member_registration_id: placement,
              network_upline_network_code: network_upline_network_code,
            },
            success: (res) => {
              window.history.replaceState("OK", "Title", "<?= BASEURL ?>/member/network/genealogy")
              $(`.item-left`).remove()
              $(`.item-right`).remove()
              $("#btn_reset").show()
              $("#alert-countdown").remove()
              $("#alert-success").html(res.message)
              $("#alert-success").show()
              setTimeout(function() {
                $("#alert-success").hide()
              }, 3000)
              placement = false
              getGenealogy(network_upline_network_code, "upline")
            },
            error: function(err) {
              res = err.responseJSON
              $("#alert").html(res.message)
              $("#alert").show()
              setTimeout(function() {
                $("#alert").hide()
              }, 3000);
              if (res.error == "validation") {
                $.each(res.data, (i, val) => {
                  $(`#alert_input_${i}`).html(val).show()
                })
              }
            }
          });
        }
      });
    }

    getGenealogy(network_code, "upline")
  })
</script>
<!-- END: Content-->