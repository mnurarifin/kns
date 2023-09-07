<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Services;

class Ewallet extends BaseController
{
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		parent::initController($request, $response, $logger);

		$this->db = \Config\Database::connect();
	}

	public function show()
	{
		$data['arrBreadcrumbs'] = array(
			'Saldo Master / Stokis' => '#',
			'Saldo' => 'ewallet/show',
		);

		$this->template->title('Saldo');
		$this->template->content('Admin/ewalletBalanceView', $data);
		$this->template->show('Template/Admin/main');
	}

	public function excelBalanceEwallet()
	{
		$tableName = 'sys_ewallet';

		$tableHead = array(
			'Nama',
			'Kode',
			'Saldo'
		);

		$columns = [
			'member_name',
			'network_code', //Make The Filter Working 
			'IFNULL((ewallet_acc - ewallet_paid),0) AS ewallet_balance',
		];

		$joinTable = '
            JOIN sys_member ON member_id = ewallet_member_id
            LEFT JOIN sys_network ON network_member_id = ewallet_member_id
        ';

		$whereCondition = '';

		$request = array();
		$request['columns'] = $columns;
		$request['filter'] = '';
		$request['dir'] = 'ASC';
		$request['sort'] = 'network_code';


		$results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition);

		foreach ($results as $key => $value) {
			$results[$key]['ewallet_balance'] = $this->functionLib->format_nominal('Rp. ', $value['ewallet_balance'], 2);
		}
		$title = 'Saldo Mitra';
		Services::DataTableLib()->exportToExcel($tableHead, $results, $title);
	}

	public function excelHistoryBalance($member_id)
	{
		$sql_get_member = "
		SELECT
			member_name
		FROM sys_member
		WHERE member_id = '$member_id'
		";

		$member_name = $this->db->query($sql_get_member)->getRow('member_name');

		$tableName = 'sys_ewallet_product_log';

		$tableHead = array(
			'Saldo',
			'Tipe',
			'Tanggal',
			'Catatan'
		);

		$columns = array(
			'ewallet_product_log_datetime',
			"ewallet_product_log_value",
			'ewallet_product_log_type',
			'ewallet_product_log_note',
		);

		$joinTable = '';

		$whereCondition = "ewallet_product_log_member_id = '$member_id'";

		$request = array();
		$request['columns'] = $columns;
		$request['filter'] = '';
		$request['dir'] = 'ASC';
		$request['sort'] = 'ewallet_product_log_datetime';

		$results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition);

		foreach ($results as $key => $value) {
			$results[$key]['ewallet_product_log_value'] = $this->functionLib->format_nominal('Rp. ', $value['ewallet_product_log_value'], 2);
			$results[$key]['ewallet_product_log_datetime']  =  $this->functionLib->convertDatetime($value['ewallet_product_log_datetime']);
			$results[$key]['ewallet_product_log_type'] = $results[$key]['ewallet_product_log_type'] == 'in' ? 'Masuk' : 'Keluar';
		}

		$title = "Daftar Histori Saldo Mitra An. $member_name";
		Services::DataTableLib()->exportToExcel($tableHead, $results, $title);
	}

	public function excelTransferHistoryBalance($member_id)
	{
		$sql_get_member = "
		SELECT
			member_name
		FROM sys_member
		WHERE member_id = '$member_id'
		";

		$member_name = $this->db->query($sql_get_member)->getRow('member_name');

		$tableName = 'sys_ewallet_product_transfer';

		$tableHead = array(
			'Tanggal',
			'Kode Pengirim',
			'Nama Pengirim',
			'Kode Penerima',
			'Nama Penerima',
			'Saldo',
			'Catatan'
		);

		$columns = array(
			'ewallet_product_transfer_datetime',
			'ewallet_product_transfer_sender_network_code',
			'l1.member_name',
			'ewallet_product_transfer_receiver_network_code',
			'l2.member_name',
			'ewallet_product_transfer_value',
			'ewallet_product_transfer_note'
		);

		$joinTable = "
		    LEFT JOIN sys_member l1 on l1.member_id = ewallet_product_transfer_sender_member_id
            LEFT JOIN sys_member l2 on l2.member_id = ewallet_product_transfer_receiver_member_id
		";

		$whereCondition = "(ewallet_product_transfer_sender_member_id = '$member_id' || ewallet_product_transfer_receiver_member_id = '$member_id')";

		$request = array();
		$request['columns'] = $columns;
		$request['filter'] = '';
		$request['dir'] = 'ASC';
		$request['sort'] = 'ewallet_product_log_datetime';

		$results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition);

		foreach ($results as $key => $value) {
			$results[$key]['ewallet_product_transfer_value'] = $this->functionLib->format_nominal('Rp. ', $value['ewallet_product_transfer_value'], 2);
			$results[$key]['ewallet_product_transfer_datetime']  =  $this->functionLib->convertDatetime($value['ewallet_product_transfer_datetime']);
		}

		$title = "Daftar Histori Transfer Saldo Mitra An. $member_name";

		Services::DataTableLib()->exportToExcel($tableHead, $results, $title);
	}
}
