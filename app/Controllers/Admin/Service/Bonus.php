<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\BonusModel;
use Config\Services;

class Bonus extends BaseServiceController
{

	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		parent::initController($request, $response, $logger);

		helper(['form', 'url']);
		$this->BonusModel = new BonusModel();
		$this->db = \Config\Database::connect();
		$this->payment_service = service("Payment");
	}

	public function getDataBonus()
	{
		$tableName = 'sys_bonus';
		$columns = array(
			'bonus_acc',
			'bonus_paid',
			'bonus_acc - bonus_paid AS saldo',
			'member_ref_network_code',
			'member_name'
		);
		$joinTable = '
        JOIN sys_member ON sys_member.member_id = sys_bonus.bonus_member_id
        JOIN bin_member_ref ON bin_member_ref.member_ref_member_id = sys_bonus.bonus_member_id
        ';
		$whereCondition = '';
		$data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
		$this->createRespon(200, 'Data Serial Registrasi', $data);
	}

	public function getListWithdrawal($status = '')
	{
		$filter = (array) $this->request->getGet('filter');

		$whereCondition = "ewallet_withdrawal_status != 'pending'";

		switch ($status) {
			case 'pending':
				$whereCondition = "ewallet_withdrawal_status = 'pending'";
				break;
			case 'success':
				$whereCondition .= "AND ewallet_withdrawal_status = 'success'";
				break;
			case 'failed':
				$whereCondition .= "AND ewallet_withdrawal_status = 'failed'";
				break;
		}

		$tableName = 'sys_ewallet_withdrawal';
		$columns = [
			'ewallet_withdrawal_id',
			'ewallet_withdrawal_member_id',
			'ewallet_withdrawal_member_name',
			'ewallet_withdrawal_value',
			'ewallet_withdrawal_adm_charge_value',
			'ewallet_withdrawal_tax_value',
			'ewallet_withdrawal_nett',
			'ewallet_withdrawal_bank_id',
			'ewallet_withdrawal_bank_name',
			'ewallet_withdrawal_bank_account_name',
			'ewallet_withdrawal_bank_account_no',
			'ewallet_withdrawal_status',
			'ewallet_withdrawal_note',
			'ewallet_withdrawal_datetime',
			'ewallet_withdrawal_status_datetime',
			'stockist_name',
		];

		$joinTable = '
		JOIN inv_stockist ON stockist_member_id = ewallet_withdrawal_member_id
		';

		$data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

		$data['tax'] = 0;
		$data['charge'] = 0;
		$data['charge_month'] = 0;
		$data['komisi'] = 0;

		$data['tax'] = $this->BonusModel->getTaxWithdrawal();
		$data['charge'] = $this->BonusModel->getChargeWithdrawal();
		$data['charge_month'] = $this->BonusModel->getChargeWithdrawal(date('m'));
		$data['komisi'] = $this->db->query("SELECT SUM(ewallet_withdrawal_nett) as total FROM sys_ewallet_withdrawal
        JOIN sys_member_account ON member_account_member_id = ewallet_withdrawal_member_id WHERE ewallet_withdrawal_status = 'success'")->getRow('total');

		if (count($data['results']) > 0) {
			foreach ($data['results'] as $key => $row) {
				if ($row['ewallet_withdrawal_datetime']) {
					$data['results'][$key]['ewallet_withdrawal_datetime'] = $this->functionLib->convertDatetime($row['ewallet_withdrawal_datetime'], 'id');
				}
				if ($row['ewallet_withdrawal_status_datetime']) {
					$data['results'][$key]['ewallet_withdrawal_status_datetime'] = $this->functionLib->convertDatetime($row['ewallet_withdrawal_status_datetime'], 'id');
				}

				$data['results'][$key]['ewallet_withdrawal_adm_charge_value'] = CONFIG_WITHDRAWAL_ADM_CHARGE_TYPE == "percent" ? CONFIG_WITHDRAWAL_ADM_CHARGE_PERCENT / 100 * $data["results"][$key]["ewallet_withdrawal_value"] : CONFIG_WITHDRAWAL_ADM_CHARGE_VALUE;

				$data['results'][$key]['ewallet_withdrawal_nett'] = $this->functionLib->format_nominal("Rp ", ($row['ewallet_withdrawal_value'] - $data['results'][$key]['ewallet_withdrawal_adm_charge_value']), 2);
				// $data['results'][$key]['ewallet_withdrawal_tax_value'] = $this->functionLib->format_nominal("Rp ", $row['ewallet_withdrawal_tax_value'], 2);
				$data['results'][$key]['ewallet_withdrawal_tax_value'] = $this->functionLib->format_nominal("Rp ", 0, 2);
				$data['results'][$key]['ewallet_withdrawal_value'] = $this->functionLib->format_nominal("Rp ", $row['ewallet_withdrawal_value'], 2);
				$data['results'][$key]['ewallet_withdrawal_adm_charge_value'] = $this->functionLib->format_nominal("Rp ", $data['results'][$key]['ewallet_withdrawal_adm_charge_value'], 2);

				if ($row['ewallet_withdrawal_status'] == 'success') {
					$data['results'][$key]['ewallet_withdrawal_status'] = '<span class="badge badge-light-success badge-pill">Berhasil</span>';
				}
				if ($row['ewallet_withdrawal_status'] == 'failed') {
					$data['results'][$key]['ewallet_withdrawal_status'] = '<span class="badge badge-light-danger badge-pill">Ditolak</span>';
				}
			}
		}

		$data['tax'] = $this->functionLib->format_nominal("Rp ", ($data['tax']), 2);
		$data['charge'] = $this->functionLib->format_nominal("Rp ", ($data['charge']), 2);
		$data['charge_month'] = $this->functionLib->format_nominal("Rp ", ($data['charge_month']), 2);
		$data['komisi'] = $this->functionLib->format_nominal("Rp ", ($data['komisi']), 2);

		$this->createRespon(200, 'Data widrawal', $data);
	}

	public function getChargeWithdrawalTotal()
	{
		try {
			$data = array();
			$data['result']['charge'] = $this->functionLib->format_nominal("Rp ", $this->BonusModel->getChargeWithdrawal(), 2);
			$data['result']['charge_month'] = $this->functionLib->format_nominal("Rp ", $this->BonusModel->getChargeWithdrawal(date('m')), 2);

			$this->createRespon(200, 'Sukses mengambil data', $data);
		} catch (\Throwable $th) {
			$this->createRespon(400, $th->getMessage());
		}
	}


	public function getDataSummaryKomisiMitra()
	{
		$tableName = 'sys_bonus';
		$columns = array(
			'bonus_member_id',
			'bonus_sponsor_acc',
			'bonus_sponsor_paid',
			'bonus_gen_node_acc',
			'bonus_gen_node_paid',
			'bonus_power_leg_acc',
			'bonus_power_leg_paid',
			'bonus_matching_leg_acc',
			'bonus_matching_leg_paid',
			'bonus_cash_reward_acc',
			'bonus_cash_reward_paid',
			'network_code' => 'member_ref_network_code',
			'member_name'
		);
		$joinTable = 'join sys_network on network_member_id = bonus_member_id
		join sys_member on member_id = bonus_member_id';
		$whereCondition = '';
		$data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);

		if (count($data['results']) > 0) {
			foreach ($data['results'] as $key => $value) {
				$total_acc = $value['bonus_sponsor_acc']
					+ $value['bonus_gen_node_acc']
					+ $value['bonus_power_leg_acc']
					+ $value['bonus_matching_leg_acc']
					+ $value['bonus_cash_reward_acc'];

				$total_paid = $value['bonus_sponsor_paid']
					+ $value['bonus_gen_node_paid']
					+ $value['bonus_power_leg_paid']
					+ $value['bonus_matching_leg_paid']
					+ $value['bonus_cash_reward_paid'];

				$data['results'][$key]['bonus_total_acc'] = $this->functionLib->setNumberFormat($total_acc);

				$data['results'][$key]['bonus_total_paid'] = $this->functionLib->setNumberFormat($total_paid);

				$data['results'][$key]['saldo'] = $this->functionLib->setNumberFormat($total_acc - $total_paid);
			}
		}
		$this->createRespon(200, 'Data Komisi Mitra', $data);
	}

	public function getDetailKomisi()
	{
		$arr_response['status'] = 200;
		$arr_response['data'] = array();
		$network_id = $this->request->getGet('network_id');
		$bonus_config = json_decode(BIN_CONFIG_BONUS);
		$detail_komisi = $this->BonusModel->get_detail_komisi($network_id, $bonus_config);
		if (!empty($detail_komisi)) {
			foreach ($bonus_config as $key => $row) {
				if ($row->active_bonus == TRUE) {
					$arr_response['data'][] = array(
						'label' => strtoupper($row->label),
						'name_acc' => "DITERIMA",
						'value_acc' => $this->functionLib->setNumberFormat($detail_komisi['bonus_' . $row->name . '_acc']),
						'name_paid' => "DIBAYARKAN",
						'value_paid' => $this->functionLib->setNumberFormat($detail_komisi['bonus_' . $row->name . '_paid']),
						'saldo' => $this->functionLib->setNumberFormat($detail_komisi['bonus_' . $row->name . '_acc'] - $detail_komisi['bonus_' . $row->name . '_paid']),
					);
				}
			}
		} else {
			$arr_response['status'] = 400;
		}
		echo json_encode($arr_response);
	}

	public function getHistoryBonus()
	{
		$tableName = "bin_bonus_log";
		$arr_columns1 = $arr_columns2 = array();
		$bonus_config = json_decode(BIN_CONFIG_BONUS);
		if (count($bonus_config) > 0) {
			foreach ($bonus_config as $key => $row) {
				if ($row->active_bonus) {
					$arr_columns1[] = 'bonus_log_' . $row->name;
				}
			}
		}
		$arr_columns2 = array(
			'bonus_log_id',
			'bonus_log_network_id',
			'bonus_log_is_transferred',
			'bonus_log_date',
			'member_ref_network_code',
			'member_name',
		);
		$columns = array_merge($arr_columns2, $arr_columns1);
		$joinTable = "
            JOIN bin_member_ref ON member_ref_network_id = bonus_log_network_id
            JOIN sys_member ON member_ref_member_id = member_id
        ";
		$whereCondition = '';
		$data = Services::DataTableLib()->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition);
		if (count($data['results']) > 0) {
			foreach ($data['results'] as $key => $row) {
				if ($row['bonus_log_sponsor']) {
					$data['results'][$key]['bonus_log_sponsor'] = $this->functionLib->setNumberFormat($row['bonus_log_sponsor']);
				}

				if ($row['bonus_log_gen_node']) {
					$data['results'][$key]['bonus_log_gen_node'] = $this->functionLib->setNumberFormat($row['bonus_log_gen_node']);
				}

				if ($row['bonus_log_power_leg']) {
					$data['results'][$key]['bonus_log_power_leg'] = $this->functionLib->setNumberFormat($row['bonus_log_power_leg']);
				}

				if ($row['bonus_log_matching_leg']) {
					$data['results'][$key]['bonus_log_matching_leg'] = $this->functionLib->setNumberFormat($row['bonus_log_matching_leg']);
				}

				if ($row['bonus_log_date']) {
					$data['results'][$key]['bonus_log_date'] = $this->functionLib->convertDatetime($row['bonus_log_date'], 'id');
				}
			}
		}
		$this->createRespon(200, 'Data Serial Registrasi', $data);
	}

	public function rejectWithdrawal()
	{
		try {
			//code...
			$this->db->transBegin();

			$data = $this->request->getPost('data');

			$success = $failed = 0;

			if (count($data) < 0) {
				throw new \Exception("Error Processing Request", 1);
			}

			$datetime = date('Y-m-d H:i:s');

			foreach ($data as $key => $bonus_withdrawal_id) {
				$bonus_withdrawal_result = $this->BonusModel->getBonus($bonus_withdrawal_id);
				$this->BonusModel->addBalance($bonus_withdrawal_result->ewallet_withdrawal_member_id, ($bonus_withdrawal_result->ewallet_withdrawal_value));

				if ($this->db->affectedRows() <= 0) {
					throw new \Exception("Proses tidak ditemukan.", 4);
				}

				$data_bonus_withdrawal = array();
				$data_bonus_withdrawal['ewallet_withdrawal_status'] = 'failed';
				$data_bonus_withdrawal['ewallet_withdrawal_status_administrator_id'] = $this->session->administrator_id;
				$data_bonus_withdrawal['ewallet_withdrawal_status_datetime'] = $datetime;

				$this->db->table('sys_ewallet_withdrawal')->update($data_bonus_withdrawal, ['ewallet_withdrawal_id' => $bonus_withdrawal_id]);

				if ($this->db->affectedRows() <= 0) {
					throw new \Exception("Proses tidak ditemukan.", 4);
				}

				$data_bonus_log['ewallet_log_member_id'] = $bonus_withdrawal_result->ewallet_withdrawal_member_id;
				$data_bonus_log['ewallet_log_value'] =  $bonus_withdrawal_result->ewallet_withdrawal_value;
				$data_bonus_log['ewallet_log_type'] = 'in';
				$data_bonus_log['ewallet_log_note'] = 'Withdraw saldo ditolak';
				$data_bonus_log['ewallet_log_datetime'] = $datetime;

				$this->db->table('sys_ewallet_log')->insert($data_bonus_log);

				if ($this->db->affectedRows() <= 0) {
					throw new \Exception("Proses tidak ditemukan.", 4);
				}

				// $tax_data = $this->db->table("tax_member")->getWhere(["tax_member_member_id" => $bonus_withdrawal_result->ewallet_withdrawal_member_id])->getRow("tax_member_arr_data");
				// $tax_data = json_decode($tax_data);
				// $new_data = [];
				// $tax_member_income = 0;
				// $tax_member_income_pkp = 0;
				// $tax_member_tax_real_value = 0;
				// $tax_member_tax_npwp_value = 0;
				// $tax_member_tax_nonnpwp_value = 0;
				// foreach ($tax_data as $key => $value) {
				// 	if ($value->ref_id == $bonus_withdrawal_id) {
				// 		continue;
				// 	}
				// 	$tax_member_income += $value->income;
				// 	$tax_member_income_pkp += $value->income_pkp;
				// 	$tax_member_tax_real_value += $value->tax_real_value;
				// 	$tax_member_tax_npwp_value += $value->tax_npwp_value;
				// 	$tax_member_tax_nonnpwp_value += $value->tax_nonnpwp_value;
				// 	$new_data[] = $value;
				// }
				// $this->db->table("tax_member")->update([
				// 	"tax_member_income" => $tax_member_income,
				// 	"tax_member_income_pkp" => $tax_member_income_pkp,
				// 	"tax_member_tax_real_value" => $tax_member_tax_real_value,
				// 	"tax_member_tax_npwp_value" => $tax_member_tax_npwp_value,
				// 	"tax_member_tax_nonnpwp_value" => $tax_member_tax_nonnpwp_value,
				// 	"tax_member_arr_data" => json_encode($new_data),
				// ], ["tax_member_member_id" => $bonus_withdrawal_result->ewallet_withdrawal_member_id]);
				// if ($this->db->affectedRows() <= 0) {
				// 	throw new \Exception("Gagal update data pajak.", 4);
				// }

				if ($this->db->affectedRows() <= 0) {
					$failed++;
				} else {
					$success++;
				}
			}

			$dataArchive = [
				'success' => $success,
				'failed' => $failed
			];
			$message = 'Berhasil reject withdraw!';
			if ($success == 0) {
				$message = 'Gagal reject withdraw';
			}
			$this->db->transCommit();
			$this->createRespon(200, $message, $dataArchive);
		} catch (\Throwable $th) {
			$this->db->transRollback();
			$this->createRespon(400, $th->getMessage());
		}
	}

	public function approveWithdrawal()
	{
		try {
			$this->db->transBegin();

			$data = $this->request->getPost('data');

			$success = $failed = 0;

			$this->db->table("payment_disbursement")->insert([
				"disbursement_datetime" => date("Y-m-d H:i:s"),
				"disbursement_type_disbursement" => "stockist"
			]);
			if ($this->db->affectedRows() <= 0) {
				throw new \Exception("Gagal menambah data disbursement.", 1);
			}

			$disbursement_id = $this->db->insertID();

			$disbursement_uploaded_count = $disbursement_uploaded_amount = 0;

			if (count($data) < 0) {
				throw new \Exception("Error Processing Request", 1);
			}

			$datetime = date('Y-m-d H:i:s');
			$arr_disbursements = [];

			foreach ($data as $key => $bonus_withdrawal_id) {
				$withdrawal = $this->db->table("sys_ewallet_withdrawal")->select("ewallet_withdrawal_value, ewallet_withdrawal_member_id, member_bank_id, member_bank_name, member_bank_account_name, member_bank_account_no")->join('sys_member', 'member_id = ewallet_withdrawal_member_id')->getWhere(["ewallet_withdrawal_id" => $bonus_withdrawal_id])->getRow();
				$data_bonus_withdrawal = array();
				$data_bonus_withdrawal['ewallet_withdrawal_status_administrator_id'] = $this->session->administrator_id;
				$data_bonus_withdrawal['ewallet_withdrawal_status'] = 'success';
				$data_bonus_withdrawal['ewallet_withdrawal_status_datetime'] = $datetime;
				$data_bonus_withdrawal['ewallet_withdrawal_subtotal'] = $withdrawal->ewallet_withdrawal_value;
				$data_bonus_withdrawal['ewallet_withdrawal_adm_charge_value'] = CONFIG_WITHDRAWAL_ADM_CHARGE_TYPE == "percent" ? CONFIG_WITHDRAWAL_ADM_CHARGE_PERCENT / 100 * $withdrawal->ewallet_withdrawal_value : CONFIG_WITHDRAWAL_ADM_CHARGE_VALUE;
				$data_bonus_withdrawal['ewallet_withdrawal_nett'] = $withdrawal->ewallet_withdrawal_value - $data_bonus_withdrawal['ewallet_withdrawal_adm_charge_value'];

				$this->db->table('sys_ewallet_withdrawal')->update($data_bonus_withdrawal, ['ewallet_withdrawal_id' => $bonus_withdrawal_id]);

				if ($this->db->affectedRows() <= 0) {
					$failed++;
				} else {
					$amount = ($withdrawal->ewallet_withdrawal_value - $data_bonus_withdrawal['ewallet_withdrawal_adm_charge_value']);

					$this->db->table("sys_stockist_transfer")
						->insert([
							"stockist_transfer_member_id" => $withdrawal->ewallet_withdrawal_member_id,
							"stockist_transfer_ewallet_withdrawal_id" => $bonus_withdrawal_id,
							"stockist_transfer_total" => $withdrawal->ewallet_withdrawal_value,
							"stockist_transfer_member_bank_id" => $withdrawal->member_bank_id,
							"stockist_transfer_member_bank_name" => $withdrawal->member_bank_name,
							"stockist_transfer_member_bank_account_name" => $withdrawal->member_bank_account_name,
							"stockist_transfer_member_bank_account_no" => $withdrawal->member_bank_account_no,
							"stockist_transfer_adm_charge_value" => $data_bonus_withdrawal['ewallet_withdrawal_adm_charge_value'],
							"stockist_transfer_date" => date("Y-m-d"),
							"stockist_transfer_datetime" => $datetime
						]);

					if ($this->db->affectedRows() <= 0) {
						throw new \Exception("Gagal menambah data stokis transfer.", 1);
					}

					$stockist_transfer_id = $this->db->insertID();

					$arr_insert_disbursement_detail = [
						'disbursement_detail_disbursement_id' => $disbursement_id,
						'disbursement_detail_internal_id' => $stockist_transfer_id,
						'disbursement_detail_amount' => $amount,
						'disbursement_detail_bank_code' => $withdrawal->member_bank_name,
						'disbursement_detail_bank_account_name' => $withdrawal->member_bank_account_name,
						'disbursement_detail_bank_account_number' => $withdrawal->member_bank_account_no,
						'disbursement_detail_description' => 'WITHDRAW SALDO STOKIS KIMSTELLA NETWORK',
					];

					$this->db->table("payment_disbursement_detail")->insert($arr_insert_disbursement_detail);

					if ($this->db->affectedRows() <= 0) {
						throw new \Exception("Gagal menambah detail disbursement", 1);
					}

					$disbursement_detail_id = $this->db->insertID();

					array_push($arr_disbursements, [
						'amount' => (int)$amount,
						'bank_code' => $this->db->table("ref_bank")->getWhere(["bank_id" => $withdrawal->member_bank_id])->getRow("bank_code"),
						'bank_account_name' => $withdrawal->member_bank_account_name,
						'bank_account_number' => $withdrawal->member_bank_account_no,
						'description' => 'WITHDRAW SALDO STOKIS KIMSTELLA NETWORK',
						'external_id' => (string) $disbursement_detail_id,
						'email_to' => [$this->db->table("sys_member")->getWhere(["member_id" => $withdrawal->ewallet_withdrawal_member_id])->getRow("member_email")],
					]);

					$disbursement_uploaded_amount += $amount;
					$disbursement_uploaded_count++;

					$success++;
				}
			}

			$dataArchive = [
				'success' => $success,
				'failed' => $failed
			];
			$message = 'Berhasil approve withdraw!';
			if ($success == 0) {
				$this->db->transRollback();
				$message = 'Gagal approve withdraw';
			} else {
				$disbursements_params = [
					'reference' => (string) $disbursement_id,
					'disbursements' => $arr_disbursements
				];
				$disbursement = $this->payment_service->createDisbursementBatch($disbursements_params);

				$this->db->table("payment_disbursement")->where('disbursement_id', $disbursement_id)->update([
					'disbursement_total_uploaded_count' => $disbursement_uploaded_count,
					'disbursement_total_uploaded_amount' => $disbursement_uploaded_amount,
					'disbursement_external_id' => $disbursement['id'],
					'disbursement_created' => $disbursement['created'],
					'disbursement_status' => $disbursement['status'],
					'disbursement_request_json' => json_encode($disbursements_params),
				]);

				if ($this->db->affectedRows() <= 0) {
					throw new \Exception("Gagal mengubah disbursement", 1);
				}

				$this->db->transCommit();
			}
			$this->createRespon(200, $message, $dataArchive);
		} catch (\Throwable $th) {
			$this->db->transRollback();
			$this->createRespon(400, $th->getMessage());
		}
	}
}
