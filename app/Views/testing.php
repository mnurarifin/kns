<script src="<?= BASEURL; ?>/app-assets/vendors/js/vendors.min.js"></script>
<script>
  $(function () {

    transaction = () => {
        // let data = {
        //     otp: $("#input_otp").val(),
        //     transaction_delivery_method: $("#transaction_method").data("method"),
        //     transaction_courier_code: $("#input_transaction_courier_code").val(),
        //     transaction_courier_service: $("#input_transaction_courier_service").val(),
        //     transaction_delivery_cost: delivery_cost,
        //     name: $("#input_transaction_name").val(),
        //     mobilephone: $("#input_transaction_mobilephone").val(),
        //     address: $("#input_transaction_address").val(),
        //     province_id: $("#input_transaction_province_id").val(),
        //     city_id: $("#input_transaction_city_id").val(),
        //     subdistrict_id: $("#input_transaction_subdistrict_id").val(),
        //     detail: product_detail,
        // }

        product_detail = [{
            product_id: 2,
            quantity: 2,
        }]
        let data = {
            otp: "",
            transaction_delivery_method: "courier",
            transaction_courier_code: "",
            transaction_courier_service: "",
            transaction_delivery_cost: 0,
            name: "Nama Penerima",
            mobilephone: "085743340579",
            address: "Alamat Penerima",
            province_id: "11",
            city_id: "1101",
            subdistrict_id: "110101",
            detail: product_detail,
        }

        $.ajax({
            url: "<?= BASEURL ?>/member/transaction/add-transaction",
            type: "POST",
            data: data,
            success: (res) => {
                data = res.data.results
                $(".alert-input").hide()
                $("#alert-success").html(res.message)
                $("#alert-success").show()
                setTimeout(function () {
                    $("#alert-success").hide()
                }, 3000)
                // window.location = `/member/transaction/transaction-success?id=${data.transaction_id}`
            },
            error: (err) => {
                res = err.responseJSON
                new JsonViewer({
                container: document.body, 
                data: err.responseText, 
                theme: 'dark', 
                expand: false
                });
                $(".alert-input").hide()
                $("#alert").html(res.message)
                $("#alert").show()
                setTimeout(function () {
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

    registration = () => {
        let data = new FormData()
        // data.append("serial_id", "ESMA22CFTG87215")
        // data.append("serial_pin", "RPHHEH")
        // data.append("sponsor_username", "ESM1000001")
        // data.append("upline_username", "ESM1000001")
        // data.append("network_position", "R")
        // data.append("member_account_password", '123456')
        // data.append("member_name", "testing")
        // data.append("member_gender", "Laki-laki")
        // data.append("member_identity_no", "1234123412341234")
        // data.append("member_mobilephone", "085743340579")
        // data.append("member_province_id", "11")
        // data.append("member_city_id", "1101")
        // data.append("member_subdistrict_id", "110101")
        // data.append("member_address", "address")
        // data.append("member_bank_account_name", "test")
        // data.append("member_bank_account_no", "1234123412341235")
        // data.append("member_bank_id", "1")
        // data.append("name", "penerima")
        // data.append("mobilephone", "081000000")
        // data.append("address", "addr")
        // data.append("province_id", "11")
        // data.append("city_id", "1101")
        // data.append("subdistrict_id", "110101")

        data.append("serial_id", $("#serial_id").val())
        data.append("serial_pin", $("#serial_pin").val())
        data.append("sponsor_username", $("#input_sponsor_username").val())
        data.append("member_account_password", $("#input_member_account_password").val())
        data.append("member_name", $("#input_member_name").val())
        data.append("member_gender", $("#input_member_gender_male").prop("checked") ? "Laki-laki" : $("#input_member_gender_female").prop("checked") ? "Perempuan" : "")
        data.append("member_identity_no", $("#input_member_identity_no").val())
        data.append("member_mobilephone", $("#input_member_mobilephone").val())
        data.append("member_province_id", $("#input_member_province_id").val())
        data.append("member_city_id", $("#input_member_city_id").val())
        data.append("member_subdistrict_id", $("#input_member_subdistrict_id").val())
        data.append("member_address", $("#input_member_address").val())
        data.append("member_bank_account_name", $("#input_member_bank_account_name").val())
        data.append("member_bank_account_no", $("#input_member_bank_account_no").val())
        data.append("member_bank_id", $("#input_member_bank_id").val())
        data.append("name", $("#input_transaction_name").val())
        data.append("mobilephone", $("#input_transaction_mobilephone").val())
        data.append("address", $("#input_transaction_address").val())
        data.append("province_id", $("#input_transaction_province_id").val())
        data.append("city_id", $("#input_transaction_city_id").val())
        data.append("subdistrict_id", $("#input_transaction_subdistrict_id").val())
        data.append("transaction_delivery_method", "courier")
        data.append("cost", 0)

        $.ajax({
        url: "<?= BASEURL ?>/member/network/register",
        type: "POST",
        processData: false,
        contentType: false,
        data: data,
        success: (res) => {
            data = res.data.results
            $(".alert-input").hide()
            $("#alert-success").html(res.message)
            $("#alert-success").show()
            setTimeout(function () {
            $("#alert-success").hide()
            }, 3000);
            window.location = `/member/network/registration-success?username=${data.member_account_username}&sponsor_username=${data.sponsor_username}&id=${data.transaction_id}`
        },
        error: (err) => {
            res = err.responseJSON
            new JsonViewer({
            container: document.body, 
            data: err.responseText, 
            theme: 'dark', 
            expand: false
            });
            $(".alert-input").hide()
            $("#alert").html(res.message)
            $("#alert").show()
            setTimeout(function () {
            $("#alert").hide()
            }, 3000);
            if (res.error == "validation") {
            $.each(res.data, (i, val) => {
                $(`#alert_input_${i}`).html(val).show()
            })
            }
            window.scrollTo(0, 0);
        },
        })
    }

    transaction()
  })
</script>
<style>

.add-height{
    height: auto !important;
}

.rotate90{
    transform: rotate(0deg) !important;
}

.jv-wrap{
    display: flex;
}

.jv-folder{
    cursor: pointer;
}

/* for light them */

.jv-light-symbol{
    color: #000;
    font-weight: bold;
}

.jv-light-con{
    background: #fff;
    color: #000;
    font-family: monospace;
    overflow: auto;
    height: 100%;
    width: 100%;
}

.jv-light-current {
    line-height: 30px;
    padding-left: 20px;
    position: relative;
}

.jv-light-left {
    display: inline-block;
}

.jv-light-rightString {
    display: inline-block;
    color: #7a3e9d;
}

.jv-light-rightBoolean {
    display: inline-block;
    color: #448c27;
}

.jv-light-rightNumber {
    display: inline-block;
    color: #f53232;
}

.jv-light-rightNull {
    display: inline-block;
    color: #9c5d27;
}

.jv-light-rightObj {
    display: block !important;
    overflow: hidden;
    height: 0;
}

.jv-light-folder {
    width: 0px;
    display: inline-block;
    margin-left: -15px;
    text-align: center;
    cursor: pointer;
    height: 0px;
    border: 4px solid transparent;
    border-top: 8px solid #484d50;
    position: absolute;
    top: 11px;
    transform-origin: 50% 25%;
    transform: rotate(-90deg);
}




/* for dark theme */

.jv-dark-con{
    background: #272822;
    color: #fff;
    font-family: monospace;
    overflow: auto;
    height: 100%;
    width: 100%;
}

.jv-dark-symbol{
    color: #fff;
    font-weight: bold;
}

.jv-dark-current {
    line-height: 30px;
    padding-left: 20px;
    position: relative;
}

.jv-dark-left {
    display: inline-block;
}

.jv-dark-rightString {
    display: inline-block;
    color: #66d9ef;
}

.jv-dark-rightBoolean {
    display: inline-block;
    color: #a6e22e;
}

.jv-dark-rightNumber {
    display: inline-block;
    color: #f92672;
}

.jv-dark-rightNull {
    display: inline-block;
    color: #e6db74;
}

.jv-dark-rightObj {
    display: block !important;
    overflow: hidden;
    height: 0;
}

.jv-dark-folder {
    width: 0px;
    display: inline-block;
    margin-left: -15px;
    text-align: center;
    cursor: pointer;
    height: 0px;
    border: 4px solid transparent;
    border-top: 8px solid #fff;
    position: absolute;
    top: 11px;
    transform: rotate(-90deg);
    transform-origin: 50% 25%;
}

</style>
<script>
const toString = Object.prototype.toString;

function isString(val) {
    return typeof val === 'string';
}

function isNumber(val) {
    return typeof val === 'number';
}

function isBoolean(val) {
    return typeof val === 'boolean';
}

function isUndefined(val) {
    return typeof val === 'undefined';
}

function isArray(val) {
    return toString.call(val) === '[object Array]';
}

function isObject(val) {
    return toString.call(val) === '[object Object]';
}

function isNull(val) {
    return toString.call(val) === '[object Null]';
}

function JsonViewer(options) {
    const defaults = {
        theme: 'light',
        container: null,
        data: '{}',
        expand: false,
    };
    this.options = Object.assign(defaults, options);
    if (isNull(options.container)) {
        throw new Error('Container: dom element is required');
    }
    this.render();
}

JsonViewer.prototype.renderRight = function(theme, right, val) {
    if (isNumber(val)) {
        right.setAttribute('class', theme + 'rightNumber');
    } else if (isBoolean(val)) {
        right.setAttribute('class', theme + 'rightBoolean');
    } else if (val === 'null') {
        right.setAttribute('class', theme + 'rightNull');
    } else {
        right.setAttribute('class', theme + 'rightString');
    }
    right.innerText = val;
}

JsonViewer.prototype.renderChildren = function(theme, key, val, right, indent, left) {
    let self = this;
    let folder = this.createElement('span');
    let rotate90 = this.options.expand ? 'rotate90' : '';
    let addHeight = this.options.expand ? 'add-height' : '';
    folder.setAttribute('class', theme + 'folder ' + rotate90);
    folder.onclick = function (e) {
        let nextSibling = e.target.parentNode.nextSibling;
        self.toggleItem(nextSibling, e.target);
    }
    let len = 0;
    let isObj = false;
    if (isObject(val)) {
        len = Object.keys(val).length;
        isObj = true;
    } else {
        len = val.length;
    }
    left.innerHTML = isObj ? key + '&nbsp;&nbsp{' + len + '}' : key + '&nbsp;&nbsp[' + len + ']';
    left.prepend(folder);
    right.setAttribute('class', theme + 'rightObj ' + addHeight);
    self.parse(val, right, indent + 0, theme);
}
  
JsonViewer.prototype.parse = function(dataObj, parent, indent, theme) {
    const self = this;
    this.forEach(dataObj, function (val, key) {
        const { left, right } = self.createItem(indent, theme, parent, key, typeof val !== 'object');
        if (typeof val !== 'object') {
            self.renderRight(theme, right, val);
        } else {
            self.renderChildren(theme, key, val, right, indent, left);
        }
    });
}

JsonViewer.prototype.createItem = function(indent, theme, parent, key, basicType) {
    let self = this;
    let current = this.createElement('div');
    let left = this.createElement('div');
    let right = this.createElement('div');
    let wrap = this.createElement('div');

    current.style.marginLeft = indent * 2 + 'px';
    left.innerHTML = `${key}<span class="jv-${theme}-symbol">&nbsp;:&nbsp;</span>`;
    if (basicType) {
        current.appendChild(wrap);
        wrap.appendChild(left);
        wrap.appendChild(right);
        parent.appendChild(current);
        current.setAttribute('class', theme + 'current');
        wrap.setAttribute('class', 'jv-wrap');
        left.setAttribute('class', theme + 'left');
    } else {
        current.appendChild(left);
        current.appendChild(right);
        parent.appendChild(current);
        current.setAttribute('class', theme + 'current');
        left.setAttribute('class', theme + 'left jv-folder');
        left.onclick = function (e) {
            let nextSibling = e.target.nextSibling;
            self.toggleItem(nextSibling, e.target.querySelector('span'));
        }
    }
    
    return {
        left,
        right,
        current,
    };
}

JsonViewer.prototype.render = function () {
    let data = this.options.data;
    let theme = 'jv-' + this.options.theme + '-';
    let indent = 0;
    let parent = this.options.container;
    let key = 'object';
    let dataObj;
    
    parent.setAttribute('class', theme + 'con');
    try {
        dataObj = JSON.parse(data);
    } catch (error) {
        throw new Error('It is not a json format');
    }
    if (isArray(dataObj)) {
        key = 'array';
    }
    const { left, right } = this.createItem(indent, theme, parent, key);
    this.renderChildren(theme, key, dataObj, right, indent, left);
}

JsonViewer.prototype.toggleItem = function (ele, target) {
    ele.classList.toggle('add-height');
    target.classList.toggle('rotate90');
}

JsonViewer.prototype.createElement = function (type) {
    return document.createElement(type);
}

JsonViewer.prototype.forEach = function (obj, fn) {
    if (isUndefined(obj) || isNull(obj)) {
        return;
    }
    if (typeof obj === 'object' && isArray(obj)) {
        for (let i = 0, l = obj.length; i < l; i++) {
            fn.call(null, obj[i], i, obj);
        }
    } else {
        for (let key in obj) {
            if (obj.hasOwnProperty(key)) {
                fn.call(null, obj[key] ?? 'null', key, obj);
            }
        }
    }
}
</script>