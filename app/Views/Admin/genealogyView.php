<style>
    #treeview-chart {
        display: block;
        position: relative;
        padding: 5px 40px 20px;
        width: 100%;
    }

    #treeview-chart .group-node {
        display: block;
        position: relative;
        padding-left: 60px;
        list-style: none;
    }

    #treeview-chart .group-node li {
        display: block;
        position: relative;
        margin: 10px 0;
    }

    #treeview-chart .group-node li::before {
        height: calc(100% - 70px);
        display: block;
        width: 0.5px;
        background: #888;
        position: absolute;
        left: 32px;
        top: 43px;
        z-index: 0;
        content: "";
    }

    #treeview-chart .group-node li::after {
        height: 0.5px;
        display: block;
        width: 30px;
        background: #888;
        position: absolute;
        left: -28px;
        top: 27px;
        z-index: 0;
        content: "";
    }

    #treeview-chart .group-node li.single-node::before {
        height: calc(100% - 70px);
        display: block;
        width: 0.5px;
        background: #888;
        position: absolute;
        left: 32px;
        top: 43px;
        z-index: 0;
        content: "";
    }

    #treeview-chart .group-node li.single-node::after {
        height: 0.5px;
        display: block;
        width: 30px;
        background: #888;
        position: absolute;
        left: -28px;
        top: 27px;
        z-index: 0;
        content: "";
    }

    #treeview-chart .group-node li.single-node>.group-node::before {
        height: calc(100% - 50px);
        display: block;
        width: 0.5px;
        background: #fff;
        position: absolute;
        left: 32px;
        top: 28px;
        z-index: 0;
        content: "";
    }

    #treeview-chart .group-node li.single-node:last-child::before {
        height: 50px;
        display: block;
        width: 0.5px;
        background: #888;
        position: absolute;
        left: 32px;
        top: 43px;
        z-index: 0;
        content: "";
    }

    #treeview-chart .group-node li.single-node:last-child .group-node::before {
        height: calc(100% - 50px);
        display: block;
        width: 0.5px;
        background: #fff;
        position: absolute;
        left: 32px;
        top: 28px;
        z-index: 0;
        content: "";
    }

    #treeview-chart .group-node li.collapsed ul {
        display: none;
    }

    #treeview-chart .group-node li.collapsed:last-child::before {
        display: none;
    }

    #treeview-chart .group-node.root {
        padding-left: 15px;
    }

    #treeview-chart .group-node.root>li::after {
        display: none;
    }

    #treeview-chart .has-last-single-node .single-node:last-child ul.group-node:before {
        height: calc(100% + 20px) !important;
        display: block !important;
        width: 0.5px !important;
        background: #fff !important;
        position: absolute !important;
        left: -28px !important;
        top: -37px !important;
        z-index: 0 !important;
        content: "" !important;
    }

    .chart-genealogy {
        overflow: auto;
    }

    .chart-genealogy table {
        width: 100%;
        border-collapse: collapse;
    }

    .node {
        height: 225px;
        width: 130px;
        display: inline-block;
        margin: 0 2px;
        padding: 5px;
        font-size: 8pt;
        border-radius: 7px;
        background-repeat: no-repeat;
        position: relative;
    }

    .all-node {
        height: 230px;
        background-color: transparent;
        border-radius: 15px;
        background-size: contain;
        background-position: bottom;
        background-size: 102% auto !important;
        cursor: pointer;
    }

    .node-image {
        height: 70px;
        width: 90px;
        margin-top: 0;
        position: relative;
        display: flex;
        align-items: center;
        margin-right: -5px;
    }

    .node-image::before {
        content: "";
        display: block;
        position: absolute;
        top: 14px;
        left: 3px;
        height: 42px;
        width: 37px;
        background-color: #7e00b3;
        -webkit-clip-path: polygon(75% 10%, 100% 50%, 75% 90%, 25% 90%, 0% 50%, 25% 10%);
        clip-path: polygon(75% 10%, 100% 50%, 75% 90%, 25% 90%, 0% 50%, 25% 10%);
    }

    .node-image.silver-node::before {
        content: "";
        display: block;
        position: absolute;
        top: 12px;
        left: 23px;
        height: 96px;
        width: 84px;
        background-color: #688389;
        -webkit-clip-path: polygon(75% 10%, 100% 50%, 75% 90%, 25% 90%, 0% 50%, 25% 10%);
        clip-path: polygon(75% 10%, 100% 50%, 75% 90%, 25% 90%, 0% 50%, 25% 10%);
    }

    .node-image.gold-node::before {
        content: "";
        display: block;
        position: absolute;
        top: 12px;
        left: 23px;
        height: 96px;
        width: 84px;
        background-color: #efb30c;
        -webkit-clip-path: polygon(75% 10%, 100% 50%, 75% 90%, 25% 90%, 0% 50%, 25% 10%);
        clip-path: polygon(75% 10%, 100% 50%, 75% 90%, 25% 90%, 0% 50%, 25% 10%);
    }

    .node-image.diamond-node::before {
        content: "";
        display: block;
        position: absolute;
        top: 12px;
        left: 23px;
        height: 96px;
        width: 84px;
        background-color: #2a4fd6;
        -webkit-clip-path: polygon(75% 10%, 100% 50%, 75% 90%, 25% 90%, 0% 50%, 25% 10%);
        clip-path: polygon(75% 10%, 100% 50%, 75% 90%, 25% 90%, 0% 50%, 25% 10%);
    }

    .hexa {
        margin-left: 5.5px;
        width: 32px;
        height: 37px;
        -webkit-clip-path: polygon(75% 10%, 100% 50%, 75% 90%, 25% 90%, 0% 50%, 25% 10%);
        clip-path: polygon(75% 10%, 100% 50%, 75% 90%, 25% 90%, 0% 50%, 25% 10%);
    }

    .node-image img {
        background-color: #fff;
    }

    .hexa img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: inherit;
    }

    .hexa img.icon {
        width: 100%;
        height: 100%;
        object-fit: scale-down;
    }

    .node-shadow {
        margin: 20px auto 45px;
        width: 70px;
        box-shadow: 0px 5px 15px 4px #848484;
        height: 0px;
    }

    .chart-genealogy td {
        text-align: center;
    }

    .chart-genealogy .top {
        border-top: 2px dashed gray;
    }

    .chart-genealogy .left {
        border-right: 1px dashed gray;
    }

    .chart-genealogy .right {
        border-left: 2px dashed gray;
    }

    .chart-line {
        height: 20px;
        width: 4px;
    }

    .genealogy-name {
        max-width: 100%;
        max-height: 100%;
        width: 100%;
        text-overflow: ellipsis;
        overflow: hidden;
        line-height: normal;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        font-weight: 600 !important;
    }

    .card-nodes {
        margin: 3px 0;
        width: 170px;
        position: relative;
    }

    .card-nodes .menu-node {
        display: none;
        position: absolute;
        left: 100%;
        top: 0;
        z-index: 100;
        width: 400px;
    }

    .card-nodes:hover .menu-node,
    .card-nodes:focus .menu-node {
        display: flex;
    }

    .menu-node .menu-node-details {
        background: #fff;
        display: block;
        width: 100%;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #ccc;
        line-height: normal;
        font-size: 13px;
        margin-left: 15px;
    }

    .menu-node .menu-node-details .mb-1 {
        margin-bottom: 0.5rem !important;
    }

    .menu-node .menu-node-details .mb-4 {
        margin-bottom: 2rem !important;
    }

    .all-node {
        display: flex;
        flex-direction: row;
        align-items: center;
        height: 55px;
        width: 100%;
        padding: 8px;
        position: relative;
        border: 1px solid #888;
        /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#ffffff+0,f6f6f6+47,ededed+100;
        White+3D+%231 */
        background: #fff;
        /* Old browsers */
        background: -moz-linear-gradient(top, #fff 0%, #f6f6f6 47%, #ededed 100%);
        /* FF3.6-15 */
        background: -webkit-linear-gradient(top, #fff 0%, #f6f6f6 47%, #ededed 100%);
        /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(to bottom, #fff 0%, #f6f6f6 47%, #ededed 100%);
        /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#ededed', GradientType=0);
        /* IE6-9 */
    }

    .node-data {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        width: 100%;
        padding-left: 0px;
        position: relative;
    }

    .node-data .node-item-control {
        position: absolute;
        right: -85px;
        top: 23%;
    }

    .node-data .node-item-control .v-btn {
        width: 32px !important;
        height: 32px !important;
    }

    .node-data .node-item-control::before {
        height: 1px;
        display: block;
        width: 65px;
        border-top: 1px dotted #888;
        position: absolute;
        left: -35px;
        top: 50%;
        z-index: 0;
        content: "";
    }

    .cl-primary {
        color: #7e00b3 !important;
    }

    .cl-secondary {
        color: #2b8186;
    }

    .cl-grey {
        color: #707070 !important;
    }

    .loader-area {
        display: flex;
        height: 50px;
        align-items: center;
        align-self: center;
    }

    .btn-loadmore {
        border-radius: 100%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-top: 0px !important;
    }

    .btn-show {
        height: 24px;
        width: 24px;
        position: absolute;
        top: 19px;
        left: -30px;
        z-index: 5;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-top: 0px !important;
    }

    .d-flex {
        display: flex;
    }

    .align-center {
        align-items: center !important;
    }

    .justify-space-between {
        justify-content: space-between !important;
    }

    .w-100 {
        width: 100% !important;
    }
</style>

<!-- <link rel="stylesheet" href="< ? php echo base_url ( ); ? >/app-assets/css/geneology.css"> -->

<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Genealogy</h4>
                </div>
                <div class="card-content">
                    <div id="pageLoader" class="mb-3">
                        <div class="text-center text-muted d-flex align-center justify-content-center bg-grey-light p-2">
                            <div class="spinner-border text-info spinner-border-sm" role="status" style="margin-right: 8px;margin-top: 2px;">
                                <span class="sr-only">&nbsp;</span>
                            </div>
                            <span>Sedang memuat informasi, mohon tunggu beberapa saat...</span>
                        </div>
                    </div>

                    <div class="row" id="genealogy-section">
                        <div class="col-sm-12" id="searchNodes">
                            <div class="col-sm-12" style="padding: 1.75rem !important;">
                                <div id="response-messages"></div>
                                <form>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div id="upline"></div>
                                        </div>
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-5">
                                            <div class="input-group mb-1">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="bx bx-search-alt-2"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="network_code" id="network_code_input" placeholder="Kode Mitra" aria-describedby="basic-addon2">
                                                <div class="input-group-append">
                                                    <button class="btn btn-sm btn-primary" type="button" onclick="findNodes()">Cari Mitra</button>
                                                    <button id="btn-reset" class="btn btn-sm btn-outline-secondary" type="button" onclick="getGenealogy()">Reset</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div id="treeview-chart" class="mb-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<script>
    $(document).ready(function() {
        $('#searchNodes').hide()
        $('#btn-reset').hide()

        localStorage.setItem("limit", 10);

        $(function() {
            $("#treeview-chart").bind("DOMSubtreeModified", function() {
                if ($("#genealogy-section").height() > 60) {
                    $("#pageLoader").hide();
                    $("#genealogy-section").show();
                    $('#searchNodes').show()
                }
            });
        });

        getGenealogy();
    });


    $('#network_code_input').keypress(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            findNodes();
        }
    });

    function findNodes() {
        const id = $('#network_code_input').val()
        if (id !== '') {
            getGenealogyByParent(id)
        }
    };

    function getGenealogy() {
        $.ajax({
            url: window.location.origin + '/Admin/Service/Genealogy/generateGeneology',
            method: 'GET',
            data: {
                limit: Number(localStorage.getItem('limit'))
            },
            success: function(response) {
                if (response.data.network_sponsor_network_code != '') {
                    $("#upline").html(`<a class="btn btn-outline-primary" href="#" onclick="getGenealogyByParent('${response.data.network_sponsor_network_code}')"><i class="bx bxs-left-arrow-circle mr-50"></i>Lihat jaringan sebelumnya</a>`);
                    $('#btn-reset').show()
                } else {
                    $("#upline").empty()
                    $('#btn-reset').hide()
                }

                let data = loadGenealogyView(response.data)
                $('#treeview-chart').html(data)
                $('#btn-reset').hide()
            },
        });
    }

    function getGenealogyByParent(id) {
        localStorage.setItem("limit", 10);
        $("#upline").empty()
        $('#treeview-chart').empty()
        console.log(id);
        if (id == '-') {
            id = 'BM10000001';
        }
        $.ajax({
            url: window.location.origin + '/Admin/Service/Genealogy/generateGeneology',
            method: 'GET',
            data: {
                parent: id,
                limit: 10,
                offset: 0
            },
            success: function(response) {
                if (response.data.network_sponsor_network_code != '') {
                    $("#upline").html(`<a class="btn btn-outline-primary" href="#" onclick="getGenealogyByParent('${response.data.network_sponsor_network_code}')"><i class="bx bxs-left-arrow-circle mr-50"></i>Lihat jaringan sebelumnya</a>`);
                    $('#btn-reset').show()
                } else {
                    $("#upline").empty()
                    $('#btn-reset').hide()
                }

                let data = loadGenealogyView(response.data)
                $('#treeview-chart').html(data)
            },
            error: (err) => {
                $('#response-messages').html(err.message);
                $('#response-messages').addClass('alert alert-danger');

                setTimeout(function() {
                    $('#response-messages').html('');
                    $('#response-messages').removeClass();
                }, 3000);
            }
        });
    }

    function loadNodes(parent, limit, offset) {
        $.ajax({
            url: window.location.origin + '/service/Genealogy/generateGeneologyMore',
            method: 'GET',
            data: {
                parent: network_id,
                limit: 10,
                offset: 0
            },
            success: function(response) {
                let res = JSON.parse(response);
            },
        });
    }

    function loadGenealogyView(data) {
        let html = '';
        if (data) {
            // console.log('node', data.children.length)
            let parClass = (data.children && data.children.length > 0 && data.children[data.children.length - 1].children.length === 1) ? 'has-last-single-node' : ''
            html = `
                <ul class="group-node root">
                    <li class="` + parClass + `">
                        <div class="d-flex flex-row align-center" style="height: 55px;">
                            ${printNodes(data)}
                            ${printNodeLoader(data)}
                        </div>`;
            if (data.children && data.children.length > 0) {
                html += `
                            <button
                                type="button"
                                class="btn btn-secondary btn-xs btn-show mx-1"
                                title="Tampilkan downline..."
                                onclick="expandNode($(this))"
                            >
                                <i class="bx bx-minus"></i>
                            </button>`;

                if (data.children.length > 0) {
                    let node1class = (data.children && data.children.length > 0 && data.children[data.children.length - 1].children.length === 1) ? 'has-last-single-node' : ''
                    html += `<ul class="group-node first ${node1class}">`;
                    let node1 = data.children;
                    for (let n1 = 0; n1 < node1.length; n1++) {
                        html += `
                                        <li>
                                            <div class="d-flex flex-row align-center" style="height: 55px;">
                                                ${printNodes(node1[n1])}
                                                ${printNodeLoader(node1[n1])}
                                            </div>`;
                        if (node1[n1].children && node1[n1].children.length > 0) {
                            let node2class = (node1[n1].children && node1[n1].children.length > 0 && node1[n1].children[node1[n1].children.length - 1].children.length === 1) ? 'has-last-single-node' : ''
                            html += `
                                                    <button
                                                        type="button"
                                                        class="btn btn-secondary btn-xs btn-show mx-1"
                                                        title="Tampilkan downline..."
                                                        onclick="expandNode($(this))"
                                                    >
                                                        <i class="bx bx-minus"></i>
                                                    </button>
                                                
                                                    <ul class="group-node second ${node2class}">
                                                    `;
                            let node2 = node1[n1].children;
                            for (let n2 = 0; n2 < node2.length; n2++) {
                                html += `
                                                        <li>
                                                            <div class="d-flex flex-row align-center" style="height: 55px;">
                                                                ${printNodes(node2[n2])}
                                                                ${printNodeLoader(node2[n2])}
                                                            </div>
                                                        `;

                                if (node2[n2].children && node2[n2].children.length > 0) {
                                    let node3class = (node2[n2].children && node2[n2].children.length === 1) ? 'has-last-single-node' : ''
                                    html += `
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-secondary btn-xs btn-show mx-1"
                                                                    title="Tampilkan downline..."
                                                                    onclick="expandNode($(this))"
                                                                >
                                                                    <i class="bx bx-minus"></i>
                                                                </button>
                                                            
                                                                <ul class="group-node third ${node3class}">
                                                            `;
                                    let node3 = node2[n2].children;
                                    for (let n3 = 0; n3 < node3.length; n3++) {
                                        html += `
                                                                <li>
                                                                    <div class="d-flex flex-row align-center" style="height: 55px;">
                                                                        ${printNodes(node3[n3])}
                                                                    </div>
                                                                </li>`;
                                    }

                                    html += `</ul>`;
                                }
                                html += `</li>`;
                            }
                            html += `</ul>`;
                        }
                        html += `</li>`;
                    }
                    html += `</ul>`;
                }
            }
            html += '</li></ul>';
        }

        return html;
    }

    function printNodes(data) {
        var urlAsset = "<?php echo $imagePath; ?>";
        let node = `
            <div class="position-relative card-nodes">
                <div class="node node-filled all-node" id="${data.network_code}" onclick="getGenealogyByParent('${data.network_code}')">
                    <div class="node-image d-flex justify-center">
                        <div class="hexa">
                            <img src="${(data.member_image) ? data.member_image : urlAsset + '/profile-pict.png'}">
                        </div>
                    </div>

                    <div class="node-data">
                        <div class="pb-0 node-info text-left">
                            <div class="font-size-8 cl-primary text-uppercase font-weight-bold genealogy-name">${ (data.member_name !== undefined) ? data.member_name : 'ANDA' }</div>
                            <div class="font-size-8 cl-grey">${ (data.network_code !== undefined ? data.network_code : '-') }</div>
                        </div>
                    </div>
                </div>

                <div class="menu-node">
					<div class="menu-node-details d-block w-100">
						<div class="card mb-0" width="250">
							<div class="font-size-12 text-uppercase cl-primary font-weight-600">${ data.member_name }</div>
							<div class="pt-1 d-flex justify-space-between">
								<div class="d-block w-100 font-size-8 cl-grey">
									<div class="mb-25 d-flex align-center justify-space-between w-100">
										<span>Kode Mitra</span>
										<div class="">
											<span class="font-weight-500">${ (data.network_code !== undefined) ? data.network_code : '-' }</span>`;
        if (data.network_code !== '') {
            node += `<button
                                                    class="btn btn-outline-secondary btn-xs ml-1 px-1"
													title="Salin Kode Mitra"
													style="text-align: center;"
													onclick="copyThis('${data.network_code}')"
												><i class="bx bx-copy" style="font-size: 14px;"></i></button>`
        }
        node += `</div>
									</div>

									<div class="mb-25 d-flex align-center justify-space-between w-100">
										<span>Kode Sponsor</span>
										<div class="">
											<span class="font-weight-500">${ (data.network_sponsor_network_code) ? data.network_sponsor_network_code : '-' }</span>`;
        if (data.network_sponsor_network_code !== '') {
            node += `<button
													class="btn btn-outline-secondary btn-xs ml-1 px-1"
													title="Salin Kode Upline"
													style="text-align: center;"
													onclick="copyThis('${data.network_sponsor_network_code}')"
												><i class="bx bx-copy" style="font-size: 14px;"></i></button>`;
        }
        node += `</div>
									</div>

									<div class="mb-25 d-flex align-center justify-space-between w-100">
										<span>Bergabung</span>
										<div class="font-weight-500 black--text">${ data.network_activation_datetime }</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>
        `;
        return node;
    }

    function printNodeLoader(data) {
        let loader = ``;

        if (data.children_more) {
            loader = `
                <div class="loader-area">
                    <button
                        type="button"
                        class="btn btn-outline-dark btn-xs btn-loadmore mx-1"
                        title="Tampilkan lebih banyak..."
                        onclick="getMoreMember()"
                    >
                        <i class="bx bx-revision"></i>
                    </button>
                </div>
            `;
        }

        return loader;
    }

    function getMoreMember() {
        localStorage.setItem("limit", Number(localStorage.getItem('limit')) + 10);
        getGenealogy();
    }

    function expandNode(obj) {
        // console.log($(obj).parent())
        // console.log('expandNode', event.target.parentElement.parentElement.parentElement)
        // const parents = obj.target.parentElement.parentElement.parentElement
        // parents.classList.toggle('collapsed')
        // if(obj.target.classList.contains('mdi')) obj.target.classList.toggle('mdi-plus')

        $(obj).parent().toggleClass('collapsed')
        $(obj).find('.bx').toggleClass('bx-plus')
    }

    function showInfo(data) {
        // console.log('hover', data)
        $(data).find('.menu-node').show()
    }

    function hideDetails(data) {
        $(data).find('.menu-node').hide()
    }

    function copyThis(text) {
        const copyText = text
        const el = document.createElement('textarea')
        el.value = text
        document.body.appendChild(el)
        el.select()
        document.execCommand('copy')
        document.body.removeChild(el)
    }
</script>