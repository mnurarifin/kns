<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Services;

class Bonus extends BaseController
{

	public function show()
	{
		$data['bonusConfig'] = json_decode(BIN_CONFIG_BONUS);
		$data['arrBreadcrumbs'] = array(
			'Komisi' => '#',
			'Komisi Mitra' => 'bonus/show'
		);

		$this->template->title('Komisi Mitra');
		$this->template->content('Admin/bonusMemberListView', $data);
		$this->template->show('Template/Admin/main');
	}

	public function summary()
	{
		$data['arrBreadcrumbs'] = array(
			'Komisi' => '#',
			'Komisi Mitra' => 'bonus/summary'
		);

		$this->template->title('Komisi Mitra');
		$this->template->content("Admin/bonusSummaryMitraView", $data);
		$this->template->show('Template/Admin/main');
	}

	public function withdrawal()
	{
		$data['arrBreadcrumbs'] = array(
			'Saldo Master / Stokis' => '#',
			'Approval Transfer Saldo' => 'bonus/show'
		);

		$this->template->title('Approval Transfer Saldo');
		$this->template->content('Admin/bonusWithdrawal', $data);
		$this->template->show('Template/Admin/main');
	}

	public function history()
	{
		$data['bonusConfig'] = json_decode(BIN_CONFIG_BONUS);

		$data['arrBreadcrumbs'] = array(
			'Saldo' => '#',
			'Histori Komisi Mitra' => '#'
		);

		$this->template->title('Histori Komisi Mitra');
		$this->template->content("bonusHistoryView", $data);
		$this->template->show('Template/Admin/main');
	}

	public function history_withdrawal()
	{
		$data['arrBreadcrumbs'] = array(
			'Saldo Master / Stokis' => '#',
			'Riwayat Transfer Saldo' => 'bonus/withdrawal_history'
		);

		$this->template->title('Riwayat Transfer Saldo');
		$this->template->content('Admin/bonusWithdrawalHistory', $data);
		$this->template->show('Template/Admin/main');
	}

	public function history_withdrawal_charge()
	{
		$data['arrBreadcrumbs'] = array(
			'Saldo' => '#',
			'Histori Potongan Admin' => 'bonus/history_withdrawal_charge'
		);

		$this->template->title('Histori Potongan Admin');
		$this->template->content('Admin/bonusWithdrawalHistoryCharge', $data);
		$this->template->show('Template/Admin/main');
	}

	public function excel_charge()
	{
		$tableName = 'sys_bonus_withdrawal';
		$joinTable = 'JOIN bin_member_ref ON bonus_withdrawal_member_id = member_ref_member_id';

		$whereCondition = "bonus_withdrawal_status != 'success'";

		$tableHead = json_decode($this->request->getPost('display'));

		$columns = json_decode($this->request->getPost('columns'));

		$columns = array_replace($columns, [0 => 'bonus_withdrawal_datetime ', 1 => 'member_ref_network_code', 3 => 'bonus_withdrawal_adm_charge_value']);

		array_push($columns, 'bonus_withdrawal_status');

		$request = array();
		$request['columns'] = $columns;
		$request['filter'] =   (array) $this->request->getGet('filter');
		$request['dir'] = json_decode(strtoupper($this->request->getPost('dir')));
		$request['sort'] = json_decode($this->request->getPost('sort'));

		$results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition);

		foreach ($results as $key => $value) {
			unset($results[$key]['bonus_withdrawal_status']);
		}

		$fieldToReplace = '';
		$valueToReplace = '';

		$title = 'Histori Potongan Admin';
		Services::DataTableLib()->exportToExcel($tableHead, $results, $title);
	}

	public function excel($status = '')
	{

		$tableName = 'sys_ewallet_withdrawal';
		$joinTable = 'JOIN sys_network ON network_member_id = ewallet_withdrawal_member_id';

		$whereCondition = "ewallet_withdrawal_status != 'pending'";

		if ($status) {
			$whereCondition = "ewallet_withdrawal_status = '$status'";
		}
		$tableHead = json_decode($this->request->getPost('display'));
		array_push($tableHead, 'Status');

		$columns = json_decode($this->request->getPost('columns'));
		// $columns = array_replace($columns, [0 => 'ewallet_withdrawal_datetime ', 1 => 'network_code']);

		array_push($columns, 'ewallet_withdrawal_status');

		$request = array();
		$request['columns'] = $columns;
		$request['filter'] =   (array) $this->request->getGet('filter');
		$request['dir'] = json_decode(strtoupper($this->request->getPost('dir')));
		$request['sort'] = json_decode($this->request->getPost('sort'));

		$results = Services::DataTableLib()->getListDataExcelCustom($request, $tableName, $joinTable, $whereCondition);
		foreach ($results as $key => $value) {
			$results[$key]['ewallet_withdrawal_bank_account_no'] = (string) " " . $value['ewallet_withdrawal_bank_account_no'];

			if ($status != 'pending') {
				$results[$key]['ewallet_withdrawal_status'] = $results[$key]['ewallet_withdrawal_status'] == 'success' ? 'Berhasil' : 'Ditolak';
			} else {
				$results[$key]['ewallet_withdrawal_status'] = 'Menunggu Approval';
			}
		}

		$fieldToReplace = '';
		$valueToReplace = '';

		$title = 'Histori Withdrawal';
		Services::DataTableLib()->exportToExcel($tableHead, $results, $title);
	}
}
