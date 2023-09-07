<!DOCTYPE html>
<html>

<head>
	<title></title>
</head>

<body>
	<button onclick="active()">Active</button>
	<button onclick="nonactive()">Non Active</button>
	<button onclick="updateMember()">Update</button>

</body>

</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
	let active = () => {
		$.ajax({
			url: 'service/Serial_bin/activeSerial',
			type: 'POST',
			data: {
				serial_pin: ['123456']
			},
			success: function(response) {
				alert(response.message)
			},
			error(xhr, status, error) {

			},
			complete(xhr, status) {}
		})
	}
	let nonactive = () => {
		$.ajax({
			url: 'service/Serial_bin/nonActiveSerial',
			type: 'POST',
			data: {
				serial_pin: ['123456']
			},
			success: function(response) {
				alert(response.message)
			},
			error(xhr, status, error) {

			},
			complete(xhr, status) {}
		})
	}
	let updateMember = () => {
		$.ajax({
			url: 'service/member/updateMember',
			type: 'POST',
			data: {
				member_name: 'Budi Jadmiko',
				member_gender: 'Laki-laki',
				member_birth_place: 'jogja',
				member_birth_date: '1999-02-01',
				member_mobilephone: '087278188191',
				member_email: 'budi@email.com',
				member_address: 'jl mangga no 2121',
				member_city_id: '12',
				member_identity_type: 'KTP',
				member_identity_no: '1231231131',
				member_image: '',
				member_bank_name: '',
				member_bank_city: '',
				member_bank_branch: '',
				member_bank_account_name: '',
				member_bank_account_no: '',
				member_identity_image: '',
				member_id: null,
			},
			success: function(response) {
				alert(response.message)
			},
			error(xhr, status, error) {

			},
			complete(xhr, status) {}
		})
	}
</script>